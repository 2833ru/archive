<?php

class OzonRocketWidget {

    const CREATE_ORDER_METHOD = '/principal-integration-api/v1/order';
    const API_TEST_URL = 'https://api-stg.ozonru.me';
    const API_URL = 'https://xapi.ozon.ru';
    const TOKEN_METOD = '/principal-auth-api/connect/token';
    const GET_DELIVERY_VARIANT = '/principal-integration-api/v1/delivery/variants/byids';

    private $token;

    public function __construct() {
        $PHPShopOrm = new PHPShopOrm('phpshop_modules_ozonrocket_system');

        /* Опции модуля */
        $this->options = $PHPShopOrm->select();
    }

    public function getCart($cart, $required = true) {
        $list = [];
        foreach ($cart as $cartItem) {
            $weight = (int) number_format($this->getDimension('weight', $cartItem['id'], $cartItem['parent']), 2, '.', '');
            $length = (int) $this->getDimension('length', $cartItem['id'], $cartItem['parent']) * 10;
            $width = (int) $this->getDimension('width', $cartItem['id'], $cartItem['parent']) * 10;
            $height = (int) $this->getDimension('height', $cartItem['id'], $cartItem['parent']) * 10;

            if ($required && ($weight === 0 || $length === 0 || $width === 0 || $height === 0)) {
                throw new \DomainException('Необходимо заполнить габариты у товаров в заказе или габариты по умолчанию в настройках модуля.');
            }

            $list[] = [
                'weight' => $weight,
                'length' => $length,
                'width' => $width,
                'height' => $height
            ];
        }

        return $list;
    }

    private function getDimension($field, $productId, $parent = null) {
        $product = new PHPShopProduct((int) $productId);

        // Если есть габариты в товаре
        if (!empty($product->getParam($field))) {
            return $product->getParam($field);
        }

        // Если подтип и есть габариты основного товара
        if (is_null($parent) === false) {
            $product = new PHPShopProduct((int) $parent);
            if (!empty($product->getParam($field))) {
                return $product->getParam($field);
            }
        }

        return (float) $this->options[$field];
    }

    public function log($message, $order_id, $status, $type, $status_code = 'succes') {
        $PHPShopOrm = new PHPShopOrm('phpshop_modules_ozonrocket_log');

        $log = array(
            'message_new' => serialize($message),
            'order_id_new' => $order_id,
            'status_new' => $status,
            'type_new' => $type,
            'date_new' => time(),
            'status_code_new' => $status_code
        );

        $PHPShopOrm->insert($log);
    }

    public function send($order) {

        $ozonrocketData = unserialize($order['ozonrocket_order_data']);
        $cart = unserialize($order['orders']);
        $discount = $cart['Person']['discount'];

        if (empty($order['fio'])) {
            $name = $cart['Person']['name_person'];
        } else {
            $name = $order['fio'];
        }

        if (empty($name)) {
            $PHPShopUser = new PHPShopUser($order['user']);
            $name = $PHPShopUser->getParam('name');
        }

        // Присвоение заказу статуса оплачено если ПВЗ не принимает наложенный платеж
        $pvzInfo = $this->request(self::GET_DELIVERY_VARIANT, ['ids' => [(int) $ozonrocketData['delivery_id']]]);
        if ($pvzInfo['data'][0]['cardPaymentAvailable'] === false && $pvzInfo['data'][0]['isCashForbidden'] === true) {
            $order['paid'] = 1;
        }

        if ((int) $order['paid'] === 1) {
            $type = 'FullPrepayment';
            $prepaymentAmount = $order['sum'];
            $recipientPaymentAmount = 0;
        } else {
            $type = 'Postpay';
            $prepaymentAmount = 0;
            $recipientPaymentAmount = $order['sum'];
        }
        $delivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
        $nds = $delivery->getParam('ofd_nds');

        /*
          if (empty($nds)) {
          $nds = 20;
          } */

        try {
            $packagesDimensions = $this->getCart($cart['Cart']['cart']);
        } catch (\DomainException $exception) {
            $this->log(
                    ['error' => $exception->getMessage()], $order['id'], __('Ошибка передачи заказа'), __('Передача заказа службе доставки Ozon Rocket'), 'error'
            );
            return;
        }

        $packages = [];
        $number = 1;

        // Общая упаковка
        if ($this->options['one_package'] == 1) {
            $packages[] = [
                'packageNumber' => (string) $number,
                'dimensions' => [
                    'weight' => (int) $this->options['weight'],
                    'length' => (int) $this->options['length']*10,
                    'width' => (int) $this->options['width']*10,
                    'height' => (int) $this->options['height']*10
            ]];
        }
        // Отдельная упаковка
        else {

            foreach ($packagesDimensions as $product) {
                $packages[] = [
                    'packageNumber' => (string) $number,
                    'dimensions' => $product
                ];
                $number++;
            }
        }

        $orderLines = [];
        $number = 1;
        foreach ($cart['Cart']['cart'] as $item) {

            if ($discount > 0 && empty($item['promo_price']))
                $price = $item['price'] - ($item['price'] * $discount / 100);
            else
                $price = $item['price'];


            $orderLines [] = [
                'articleNumber' => !empty($item['uid']) ? $item['uid'] : $item['id'],
                'name' => PHPShopString::win_utf8($item['name']),
                'sellingPrice' => (float) number_format($price, 0, '', ''),
                'estimatedPrice' => (float) number_format($item['total'], 0, '', ''),
                'quantity' => $item['num'],
                'vat' => [
                    'rate' => $nds,
                    'sum' => $item['total'] * ($nds / 100)
                ],
                'attributes' => ['isDangerous' => false],
                'resideInPackages' => [(string) $number]
            ];

            if ($this->options['one_package'] == 0)
                $number++;
        }

        $parameters = [
            'orderNumber' => $order['uid'],
            'buyer' => [
                'name' => PHPShopString::win_utf8($name),
                'phone' => str_replace(['(', ')', ' ', '+', '-', '&#43;'], '', $order['tel']),
                'type' => 'NaturalPerson'
            ],
            'recipient' => [
                'name' => PHPShopString::win_utf8($name),
                'phone' => str_replace(['(', ')', ' ', '+', '-', '&#43;'], '', $order['tel']),
                'type' => 'NaturalPerson'
            ],
            'firstMileTransfer' => [
                'type' => 'DropOff',
                'fromPlaceId' => $this->options['from_place_id']
            ],
            'payment' => [
                'type' => $type,
                'prepaymentAmount' => (int) $prepaymentAmount,
                'recipientPaymentAmount' => (int) $recipientPaymentAmount,
                'deliveryPrice' => (int) $cart['Cart']['dostavka'],
                'deliveryVat' => [
                    'rate' => $nds,
                    'sum' => $cart['Cart']['dostavka'] * ($nds / 100)
                ]
            ],
            'deliveryInformation' => [
                'deliveryVariantId' => $ozonrocketData['delivery_id'],
                'address' => PHPShopString::win_utf8($ozonrocketData['address']),
                'desiredDeliveryTimeInterval' => [
                    'from' => (new DateTimeImmutable())->modify('+1 days')->format('c'),
                    'to' => (new DateTimeImmutable())->modify('+7 days')->format('c')
                ]
            ],
            'packages' => $packages,
            'orderLines' => $orderLines,
            'allowPartialDelivery' => false,
            'orderAttributes' => [
                'contractorShortName' => PHPShopString::win_utf8((new PHPShopSystem())->getName())
            ]
        ];
        $result = $this->request(self::CREATE_ORDER_METHOD, $parameters);

        // Статус отправки заказа
        if (isset($result['errorCode']) or ! is_array($result))
            $status = __('Ошибка передачи заказа');
        else
            $status = __('Успешная передача заказа');

        $this->log(
                ['response' => $result, 'parameters' => $parameters], $order['id'], $status, __('Передача заказа службе доставки Ozon Rocket'), 'success'
        );
    }

    public function request($method, $params = []) {
        if (empty($this->token)) {
            $this->getToken();
        }
        $ch = curl_init();
        $header = [
            'authorization: Bearer ' . $this->token,
            'Content-Type: application/json'
        ];
        curl_setopt($ch, CURLOPT_URL, $this->getApi() . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    private function getToken() {
        $ch = curl_init();
        $header = [
            'Content-Type: application/x-www-form-urlencoded'
        ];
        curl_setopt($ch, CURLOPT_URL, $this->getApi() . self::TOKEN_METOD);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, 'grant_type=client_credentials&client_id=' . $this->options['client_id'] . '&client_secret=' . str_replace('&#43;', '+', $this->options['client_secret']));

        $result = curl_exec($ch);
        curl_close($ch);

        $token = json_decode($result, true);

        if (empty($token['access_token'])) {
            $this->log(
                    ['response' => $result, 'parameters' => ['client_id' => $this->options['client_id'], 'client_secret' => str_replace('&#43;', '+', $this->options['client_secret'])]], null, __('Ошибка авторизации'), __('Передача заказа службе доставки Ozon Rocket'), 'error');
        } else
            return $this->token = $token['access_token'];
    }

    public function getApi() {
        if ((int) $this->options['dev_mode'] === 1) {
            return self::API_TEST_URL;
        }

        return self::API_URL;
    }

    public function buildInfoTable($order) {
        global $PHPShopGUI;

        $PHPShopCart = new PHPShopCart();

        if (!empty($this->options['default_city'])) {
            $defaultCity = $this->options['default_city'];
        }

        $cart = $this->getCart($PHPShopCart->getArray(), false);

        $params = [
            'token' => $this->options['token'],
            'defaultcity' => PHPShopString::win_utf8($defaultCity),
            'hidepvz' => (bool) $this->options['hide_pvz'] ? 'true' : 'false',
            'hidepostamat' => (bool) $this->options['hide_postamat'] ? 'true' : 'false',
            'showdeliverytime' => (bool) $this->options['show_delivery_time'] ? 'true' : 'false',
            'fromplaceid' => $this->options['from_place_id'],
            'showdeliveryprice' => (bool) $this->options['show_delivery_price'] ? 'true' : 'false',
            'packages' => '=' . $cart
        ];

        $ozonRocket = unserialize($order['ozonrocket_order_data']);

        if (!is_array($ozonRocket)) {
            // Изменили способ доставки на Ozon Rocket
            PHPShopParser::set('ozonrocket_order_id', $order['id']);
            $template = dirname(__DIR__) . '/templates/order_error.tpl';
        } else {
            PHPShopParser::set('ozonrocket_order_id', $order['id']);
            PHPShopParser::set('ozonrocket_delivery_type', $ozonRocket['delivery_type']);
            PHPShopParser::set('ozonrocket_payment_status', $PHPShopGUI->setCheckbox("payment_status", 1, "Заказ оплачен", (int) $order['paid']));
            PHPShopParser::set('ozonrocket_delivery_info', $ozonRocket['address']);

            $template = dirname(__DIR__) . '/templates/order_info.tpl';
        }

        PHPShopParser::set('ozonrocket_params', http_build_query($params));
        PHPShopParser::set('ozonrocket_popup', ParseTemplateReturn(dirname(__DIR__) . '/templates/template.tpl', true), true);

        return ParseTemplateReturn($template, true);
    }

    public function getOrderById($orderId) {
        $orm = new PHPShopOrm('phpshop_orders');

        $order = $orm->getOne(array('*'), array('id' => "='" . (int) $orderId . "'"));
        if (!$order) {
            throw new \Exception('Заказ не найден');
        }

        return $order;
    }

    public function changeAddress($request) {
        $orm = new PHPShopOrm('phpshop_orders');
        $order = $this->getOrderById($request['orderId']);

        $cart = unserialize($order['orders']);
        $cart['Cart']['dostavka'] = (float) $request['cost'];
        $sum = $cart['Cart']['sum'] + $cart['Cart']['dostavka'];

        $ozonRocketData = serialize(array(
            'status' => $this->options['status'],
            'status_text' => __('Ожидает отправки в Ozon'),
            'address' => PHPShopString::utf8_win1251($_POST['address']),
            'delivery_id' => $_POST['delivery_id'],
            'delivery_type' => PHPShopString::utf8_win1251($_POST['delivery_type'])
        ));

        $orm->update(['ozonrocket_order_data_new' => $ozonRocketData, 'orders_new' => serialize($cart), 'sum_new' => $sum], ['id' => "='" . $order['id'] . "'"]);
    }

}
