<?php

class YandexDelivery
{
    const STATUS_ORDER_PREPARED = 'prepared';
    const STATUS_ORDER_CREATED  = 'created';

    const API_URL = 'https://api.delivery.yandex.ru';
    const CREATE_ORDER_URL = '/orders';

    /** @var array */
    public $options;

    public function __construct()
    {
        $PHPShopOrm = new PHPShopOrm('phpshop_modules_yandexdelivery_system');
        $this->options = $PHPShopOrm->select();
    }

    /**
     * @param $deliveryId
     * @return bool
     */
    public function isYandexDeliveryMethod($deliveryId)
    {
        return in_array($deliveryId, explode(",", $this->options['delivery_id']));
    }

    public function createOrder($order)
    {
        $yandexData = unserialize($order['yadelivery_order_data']);
        $cart = unserialize($order['orders']);

        if($yandexData['status'] === YandexDelivery::STATUS_ORDER_CREATED) {
            return;
        }

        if(empty($order['fio']))
            $fullName = $cart['Person']['name_person'];
        else
            $fullName = $order['fio'];
        $fullNameArr = explode(' ', $fullName);

        $items = $this->getCart($cart['Cart']['cart']);
        $weight = 0;
        foreach ($items as $product) {
            $weight += (float) $product['dimensions']['weight'] * $product['count'];
        }

        $parameters = [
            'senderId'     => $this->options['sender_id'],
            'externalId'   => $order['uid'],
            'deliveryType' => $yandexData['type'],
            'recipient' => [
                'firstName'  => isset($fullNameArr[1]) ? PHPShopString::win_utf8($fullNameArr[1]) : '',
                'middleName' => isset($fullNameArr[2]) ? PHPShopString::win_utf8($fullNameArr[2]) : '',
                'lastName'   => isset($fullNameArr[0]) ? PHPShopString::win_utf8($fullNameArr[0]) : '',
                'email'      => $cart['Person']['mail'],
                'address' => [
                    'country'    => !empty($order['country']) ? PHPShopString::win_utf8($order['country']) : PHPShopString::win_utf8('Российская Федерация'),
                    'region'     => !empty($order['state']) ? PHPShopString::win_utf8($order['state']) : '',
                    'locality'   => !empty($order['city']) ? PHPShopString::win_utf8($order['city']) : '',
                    'street'     => !empty($order['street']) ? PHPShopString::win_utf8($order['street']) : '',
                    'house'      => !empty($order['house']) ? PHPShopString::win_utf8($order['house']) : '',
                    'apartment'  => !empty($order['flat']) ? PHPShopString::win_utf8($order['flat']) : '',
                    'postalCode' => !empty($order['index']) ? (int) $order['index'] : ''
                ]
            ],
            'cost' => [
                'assessedValue' => (float) str_replace(' ', '', (float) $order['sum']) * $this->options['declared_percent'] / 100,
                'fullyPrepaid'  => (int) $order['paid'] === 1
            ],
            'contacts' => [
                [
                    'type'       => 'RECIPIENT',
                    'phone'      => str_replace(['(', ')', ' ', '+', '-', '&#43;'], '', $order['tel']),
                    'firstName'  => isset($fullNameArr[1]) ? PHPShopString::win_utf8($fullNameArr[1]) : '',
                    'middleName' => isset($fullNameArr[2]) ? PHPShopString::win_utf8($fullNameArr[2]) : '',
                    'lastName'   => isset($fullNameArr[0]) ? PHPShopString::win_utf8($fullNameArr[0]) : '',
                ]
            ],
            'deliveryOption' => [
                'tariffId'            => $yandexData['tariff_id'],
                'delivery'            => $cart['Cart']['dostavka'],
                'deliveryForCustomer' => $cart['Cart']['dostavka'],
                'partnerId'           => $yandexData['partner_id']
            ],
            'places' => [
                [
                    'externalId' => 1,
                    'dimensions' => [
                        'length' => $this->getMaxDimension($cart['Cart']['cart'], 'length'),
                        'width'  => $this->getMaxDimension($cart['Cart']['cart'], 'width'),
                        'height' => $this->getMaxDimension($cart['Cart']['cart'], 'height'),
                        'weight' => $weight
                    ],
                    'items' => $items
                ]
            ]
        ];
        if($yandexData['type'] === 'PICKUP') {
            $parameters['recipient']['pickupPointId'] = $yandexData['pvz_id'];
        }

        $result = $this->request(self::CREATE_ORDER_URL, $parameters);

        if(is_int($result)) {
            $this->log(
                ['response' => $result, 'parameters' => $parameters],
                $order['id'],
                'Успешная передача заказа',
                'Передача заказа в Яндекс.Доставку',
                'success'
            );
            $yandexData['status'] = self::STATUS_ORDER_CREATED;
            $yandexData['status_text'] = 'Отправлен';

            $orm = new PHPShopOrm('phpshop_orders');
            $orm->update(['yadelivery_order_data_new' => serialize($yandexData)], ['id' => sprintf('="%s"', $order['id'])]);
        } else {
            $this->log(
                ['response' => $result, 'parameters' => $parameters],
                $order['id'],
                'Ошибка передачи заказа',
                'Передача заказа в Яндекс.Доставку',
                'fail'
            );

            throw new \Exception('Ошибка передачи заказа. Детальное описание ошибки доступно в Журнале операций.');
        }
    }

    public function getCart($cart)
    {
        $list = [];
        foreach ($cart as $cartItem) {
            $list[] = [
                'externalId'    => !empty($cartItem['uid']) ? PHPShopString::win_utf8($cartItem['uid']) : $cartItem['id'],
                'name'          => PHPShopString::win_utf8($cartItem['name']),
                'count'         => (int) $cartItem['num'],
                'price'         => number_format($cartItem['price'], 2, '.', ''),
                'assessedValue' => (float) str_replace(' ', '', (float) $cartItem['price']) * $this->options['declared_percent'] / 100,
                'tax'        => $this->getTax(),
                'dimensions' => [
                    'weight' => number_format($this->getDimension('weight', $cartItem['id'], $cartItem['parent']) / 1000, 2, '.', ''),
                    'length' => $this->getDimension('length', $cartItem['id'], $cartItem['parent']),
                    'width'  => $this->getDimension('width', $cartItem['id'], $cartItem['parent']),
                    'height' => $this->getDimension('height', $cartItem['id'], $cartItem['parent'])
                ]
            ];
        }

        return $list;
    }

    public static function getDeliveryVariants($current)
    {
        $deliveriesObj = new PHPShopDeliveryArray(['is_folder' => "!='1'", 'enabled' => "='1'"]);

        $deliveries = $deliveriesObj->getArray();
        $result = [];
        if (is_array($deliveries)) {
            foreach ($deliveries as $delivery) {

                if (strpos($delivery['city'], '.')) {
                    $name = explode(".", $delivery['city']);
                    $delivery['city'] = $name[0];
                }

                if (in_array($delivery['id'], @explode(",", $current)))
                    $delivery_id = $delivery['id'];
                else
                    $delivery_id = null;

                $result[] = [$delivery['city'], $delivery['id'], $delivery_id];
            }
        }

        return $result;
    }

    public static function getDeliveryStatuses($current)
    {
        $statusesObj = new PHPShopOrderStatusArray();
        $statuses = $statusesObj->getArray();

        $result = [['Новый заказ', 0, $current]];
        foreach ($statuses as $status) {
            $result[] = [$status['name'], $status['id'], $current];
        }

        return $result;
    }

    public function buildOrderTab($order)
    {
        global $PHPShopGUI;

        $yandex = unserialize($order['yadelivery_order_data']);
        $disabledSettings = '';
        if($yandex['status'] === self::STATUS_ORDER_CREATED) {
            PHPShopParser::set('yadelivery_hide_actions', 'display: none;');
            $disabledSettings = 'disabled="disabled"';
        }

        $orderInfo =
            PHPShopText::tr(
                __('Статус заказа'),
                '<span class="yandex-status">' . __($yandex['status_text']) . '</span>'
            ) .
            PHPShopText::tr(
                __('Адрес доставки с виджета'),
                '<span>' . $yandex['delivery_info'] . '</span>'
            ) .
            PHPShopText::tr(
                __('Статус оплаты'),
                $PHPShopGUI->setCheckbox("yandex_payment_status", 1, 'Заказ оплачен', (int) $order['paid'], $disabledSettings)
            );

        PHPShopParser::set('yadelivery_order_info', PHPShopText::table($orderInfo, 3, 1, 'left', '100%', false, 0, 'yadelivery-table', 'list table table-striped table-bordered'));
        PHPShopParser::set('yadelivery_order_id', $order['id']);

        return ParseTemplateReturn(dirname(__DIR__) . '/templates/order.tpl', true);
    }

    private function getTax()
    {
        $system = new PHPShopSystem();

        if((int) $system->getParam('nds_enabled') === 1) {
            return 'VAT_' . $system->getParam('nds');
        }

        return 'NO_VAT';
    }

    private function getDimension($field, $productId, $parent = null)
    {
        $product = new PHPShopProduct((int) $productId);

        // Если есть габариты в товаре
        if(!empty($product->getParam($field))) {
            return $product->getParam($field);
        }

        // Если подтип и есть габариты основного товара
        if(\is_null($parent) === false) {
            $product = new PHPShopProduct((int) $parent);
            if(!empty($product->getParam($field))) {
                return $product->getParam($field);
            }
        }

        return $this->options[$field];
    }

    private function getMaxDimension($cart, $side)
    {
        $maxDimension = 0;
        foreach ($cart as $cartItem) {
            $productDimension = $this->getDimension($side, $cartItem['id'], $cartItem['parent']);
            if($productDimension > $maxDimension) {
                $maxDimension = $productDimension;
            }
        }

        return $maxDimension;
    }

    private function request($method, $parameters = [])
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::API_URL . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: OAuth ' . $this->options['token'],
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($parameters))
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($parameters));

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    private function log($message, $order_id, $status, $type, $status_code = 'succes') {

        $PHPShopOrm = new PHPShopOrm('phpshop_modules_yandexdelivery_log');

        $log = [
            'message_new'     => serialize($message),
            'order_id_new'    => $order_id,
            'status_new'      => $status,
            'type_new'        => $type,
            'date_new'        => time(),
            'status_code_new' => $status_code
        ];

        $PHPShopOrm->insert($log);
    }
}