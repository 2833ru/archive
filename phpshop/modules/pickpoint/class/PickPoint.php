<?php

/**
 * API version: 1.8.14.3
 * API docs: https://pickpoint.ru/sales/api/
 */
class PickPoint
{
    const PICKPOINT_API_URL = 'https://e-solution.pickpoint.ru/api/';
    const LOGIN_URL = 'login';
    const CALCULATE_COST_URL = 'calctariff';
    const CREATE_ORDER_URL = 'CreateShipment';

    var $options = [];

    public function __construct()
    {
        $orm = new PHPShopOrm('phpshop_modules_pickpoint_system');
        $this->options = $orm->select();
    }

    public function calculate($pvz)
    {
        if(empty($this->options['login']) || empty($this->options['password']) || empty($this->options['ikn'])) {
            throw new \Exception('Ошибка авторизации.');
        }

        $cartWeight = (new PHPShopCart())->getWeight() / 1000;

        $request = $this->request(self::CALCULATE_COST_URL, [
            'SessionId'   => $this->getSessionId(),
            'IKN'         => $this->options['ikn'],
            'FromCity'    => PHPShopString::win_utf8($this->options['city_from']),
            'FromRegion'  => PHPShopString::win_utf8($this->options['region_from']),
            'PTNumber'    => $pvz,
            'GettingType' => $this->options['type_reception'],
            'Weight'      => $cartWeight > 0 ? $cartWeight : $this->options['weight'] / 1000,
            'Length'      => $this->getMaxDimension('length'),
            'Depth'       => $this->getMaxDimension('height'),
            'Width'       => $this->getMaxDimension('width')
        ]);

        if(isset($request['ErrorMessage']) && !empty($request['ErrorMessage'])) {
            throw new \Exception('Ошибка расчета стоимости доставки.'); // use throw new \Exception($request['ErrorMessage']); for debug
        }

        $cost = 0;
        foreach ($request['Services'] as $service) {
            $cost += $service['Tariff'];
        }

        return $this->applyFee($cost);
    }

    public function createOrder($order)
    {
        $cart = unserialize($order['orders']);
        $pickpoint = unserialize($order['pickpoint_data']);

        if($pickpoint['sent']) {
            return;
        }

        if(empty($order['fio']))
            $name = $cart['Person']['name_person'];
        else
            $name = $order['fio'];

        $phone = trim(str_replace(['(', ')', '-', '+', '&#43;', ' '], '', $order['tel']));
        // Проверка на первую 7 или 8
        $first_d = substr($phone, 0, 1);
        if ($first_d != 8 and $first_d != 7)
            $phone = '7' . $phone;

        $request = $this->request(self::CREATE_ORDER_URL, [
            'SessionId' => $this->getSessionId(),
            'Sendings' => [[
                'EDTN' => $order['uid'],
                'IKN' => $this->options['ikn'],
                'TittleRus' => PHPShopString::win_utf8(sprintf('Заказ №%s', $order['uid'])),
                'Invoice' => [
                    'SenderCode' => $order['uid'],
                    'Description' => PHPShopString::win_utf8(sprintf('Заказ №%s', $order['uid'])),
                    'RecipientName' => PHPShopString::win_utf8($name),
                    'PostamatNumber' => $pickpoint['pvz_id'],
                    'MobilePhone' => $phone,
                    'Email' => $cart['Person']['mail'],
                    'PostageType' => $this->options['type_service'],
                    'GettingType' => $this->options['type_reception'],
                    'PayType' => 1,
                    'Sum' => number_format((float) $order['sum'], 2, '.', ''),
                    'PrepaymentSum' => (int) $order['paid'] === 1 ? number_format((float) $order['sum'], 2, '.', '') : 0,
                ]
            ]]
        ]);

        if(isset($request['CreatedSendings'][0]['EDTN'])) {
            $orm = new PHPShopOrm('phpshop_orders');
            $pickpoint['sent'] = true;
            $orm->update([
                'pickpoint_data_new' => serialize($pickpoint),
                'tracking_new'       => $request['CreatedSendings'][0]['InvoiceNumber']
            ], ['id' => "='" . $order['id'] . "'"]);
        }
    }

    public function getSessionId()
    {
        if(!empty($this->options['session_id']) && (int) $this->options['session_expire'] > time()) {
            return $this->options['session_id'];
        }

        $request = $this->request(self::LOGIN_URL, [
            'Login'    => $this->options['login'],
            'Password' => $this->options['password']
        ]);

        if(isset($request['ErrorMessage']) && !empty($request['ErrorMessage'])) {
            throw new \Exception('Ошибка создания сессии.'); // use throw new \Exception($request['ErrorMessage']); for debug
        }

        $expire = time() + 3600 * 23; // Сессия живет 24 часа. Сохраняем на час меньше.
        $orm = new PHPShopOrm('phpshop_modules_pickpoint_system');
        $orm->update(['session_id_new' => $request['SessionId'], 'session_expire_new' => $expire]);

        return $request['SessionId'];
    }

    public function getPickpointDeliveryId() {
        return (int) $this->options['delivery_id'];
    }

    public static function getStatusesVariants($current)
    {
        $statusesObj = new PHPShopOrderStatusArray();
        $statuses = $statusesObj->getArray();

        $result[] = [__('Новый заказ'), 0, $current];
        if (is_array($statuses)) {
            foreach ($statuses as $status) {
                $result[] = [$status['name'], $status['id'], $current];
            }
        }

        return $result;
    }

    public static function getDeliveryVariants($currentDelivery)
    {
        $PHPShopDeliveryArray = new PHPShopDeliveryArray(['is_folder' => "!='1'", 'enabled' => "='1'"]);

        $DeliveryArray = $PHPShopDeliveryArray->getArray();
        $deliveries =[
            [__('Не выбрано'), 0, $currentDelivery]
        ];
        if (is_array($DeliveryArray)) {
            foreach ($DeliveryArray as $delivery) {

                if (strpos($delivery['city'], '.')) {
                    $name = explode(".", $delivery['city']);
                    $delivery['city'] = $name[0];
                }

                $deliveries[] = [$delivery['city'], $delivery['id'], $currentDelivery];
            }
        }

        return $deliveries;
    }

    private function request($method, $params = [])
    {
        $ch = curl_init();
        $headers = [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($params))
        ];
        curl_setopt($ch, CURLOPT_URL, self::PICKPOINT_API_URL . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    private function getMaxDimension($field)
    {
        $maxValue = 0;
        $cart = new PHPShopCart();

        // Поиск максимального значения длины\ширины\высоты в корзине
        foreach ($cart->getArray() as $cartProduct) {
            $product = new PHPShopProduct((int) $cartProduct['id']);

            // Есть в товаре
            if(!empty($product->getParam($field)) && $product->getParam($field) > $maxValue) {
                $maxValue = $product->getParam($field);
            } elseif (\is_null($cartProduct['parent']) === false) {
                $product = new PHPShopProduct((int) $cartProduct['parent']);
                // Если товар подтип и у главного товара есть значение больше
                if(!empty($product->getParam($field)) && $product->getParam($field) > $maxValue) {
                    $maxValue = $product->getParam($field);
                }
            }
        }

        return $maxValue > 0 ? $maxValue : $this->options[$field];
    }

    private function applyFee($cost)
    {
        $format = (int) (new PHPShopSystem())->getSerilizeParam('admoption.price_znak');

        $fee = $this->options['fee'];

        if(empty($fee)) {
            return round($cost, $format);
        }

        if((int) $this->options['fee_type'] === 1) {
            return  round($cost + ($cost * $fee / 100), $format);
        }

        return round($cost + $fee, $format);
    }
}