<?php

include_once 'phpshop/class/xml.class.php';

class ShopLogistics {

    private static $API_Url = '';

    public function __construct() {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shoplogisticswidget']['shoplogisticswidget_system']);

        /*
         * Опции модуля
         */
        $this->option = $PHPShopOrm->select();

        /*
         * URL API
         */
        if($this->option['dev_mode'] == 1)
            self::$API_Url = 'https://test.client-shop-logistics.ru/index.php?route=deliveries/api';
        else
            self::$API_Url = 'https://client-shop-logistics.ru/index.php?route=deliveries/api';

        /*
         * Данные для передачи
         */
        $this->parameters = '';

        /*
         * Номер заказа
         */
        $this->orderId = false;
    }

    public function Request() {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::$API_Url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'xml' => base64_encode($this->parameters)
        ));
        curl_setopt($curl, CURLOPT_USERAGENT, 'Opera 10.00');
        $data = xml2array(curl_exec($curl), false, false);
        curl_close($curl);

        if(!empty($data['deliveries']['delivery']['errors']))
        {
            foreach ($data['deliveries']['delivery']['errors'] as $key => $error)
                $data['deliveries']['delivery']['errors'][$key] = PHPShopString::utf8_win1251($error);

            $this->log(
                array('error' => $data['deliveries']['delivery']['errors'][$key], 'parameters' => $this->parameters),
                $this->orderId,
                'Ошибка передачи заказа',
                'Передача заказа службе доставки Shop-Logistics',
                'error'
            );
        }
        else
        {
            $data['parameters'] = $this->parameters;
            $this->log(
                $data,
                $this->orderId,
                'Успешная передача заказа',
                'Передача заказа службе доставки Shop-Logistics',
                'success'
            );
        }
    }

    public function setDataFromDoneHook($obj, $data) {

        if(empty($data['fio_new']))
            $name = $data['name_new'];
        else
            $name = $data['fio_new'];

        if(!empty($data['shoplogistics_pvz_id'])) {
            $pickup_place = '<pickup_place>' . $data['shoplogistics_pvz_id'] . '</pickup_place>' . "\n";
            $address = '<address>' .  PHPShopString::win_utf8($data['shoplogistics_pvz_adress']) . '</address>' . "\n";
        } else {
            $pickup_place = '';
            if(!empty($data['house_new']))
                $house = ', ' . $data['house_new'];
            else
                $house = '';
            if(!empty($data['flat_new']))
                $flat = ', ' . $data['flat_new'];
            else
                $flat = '';
            $address =  '<address>' .  PHPShopString::win_utf8($data['street_new'] . $house . $flat) . '</address>' . "\n";
        }

        if(!empty($data['index_new']))
            $index = '<address_index>' . $data['index_new'] . '</address_index>' . "\n";
        else
            $index = '';

        $dateObj = new DateTime();
        if(strpos($data['shoplogistics_delivery_date'], '-') !== false) {
            $date = explode('-', $data['shoplogistics_delivery_date']);
            $max_date = $date[1];
        }
        else
            $max_date = $data['shoplogistics_delivery_date'];

        $dateObj->modify('+' . $max_date . ' day');

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<request>' . "\n";
        $xml .= '<function>add_delivery</function>' . "\n";
        $xml .= '<api_id>'. $this->option['api_id'] . '</api_id>' . "\n";
        $xml .= '<deliveries>' . "\n";
        $xml .= '<delivery>' . "\n";
        $xml .= '<delivery_date>' . $dateObj->format('Y-m-d') . '</delivery_date>' . "\n";
        $xml .= '<time_from>10:00</time_from>' . "\n";
        $xml .= '<time_to>18:00</time_to>' . "\n";
        $xml .= '<date_transfer_to_store>' . date("Y-m-d") . '</date_transfer_to_store>' . "\n";
        $xml .= '<to_city>' .  PHPShopString::win_utf8($data['city_new']) . '</to_city>' . "\n";
        $xml .= '<order_id>' . $data['ouid'] . '</order_id>' . "\n";
        $xml .= $index;
        $xml .= $address;
        $xml .= '<contact_person>' .  PHPShopString::win_utf8($name) . '</contact_person>' . "\n";
        $xml .= '<phone>' . $data['tel_new'] . '</phone>' . "\n";
        $xml .= '<phone_sms>' . $data['tel_new'] . '</phone_sms>' . "\n";
        $xml .= '<price>' . $obj->total . '</price>' . "\n";
        $xml .= '<site_name>' . $_SERVER['SERVER_NAME'] . '</site_name>' . "\n";
        $xml .= $pickup_place;
        $xml .= '<customer_email>' . $data['mail_new'] . '</customer_email>' . "\n";
        $xml .= '<products>' . $this->setProducts($obj->PHPShopCart->getArray(), $obj->discount) . '</products>' . "\n";
        $xml .= '</delivery>' . "\n";
        $xml .= '</deliveries>' . "\n";
        $xml .= '</request>' . "\n";

        $this->parameters = $xml;
        $this->orderId = $data['ouid'];
    }

    public function setDataFromOrderEdit($data) {
        $order = unserialize($data['orders']);
        $shoplogisticsOrderData = unserialize($data['shoplogistics_order_data']);

        if(empty($data['fio']))
            $name = $order['Person']['name_person'];
        else
            $name = $data['fio'];

        if(!empty($shoplogisticsOrderData['pvz_id'])) {
            $pickup_place = '<pickup_place>' . $shoplogisticsOrderData['pvz_id'] . '</pickup_place>' . "\n";
            $address = '<address>' .  PHPShopString::win_utf8($shoplogisticsOrderData['pvz_address']) . '</address>' . "\n";
        } else {
            $pickup_place = '';
            if(!empty($data['house']))
                $house = ', ' . $data['house'];
            else
                $house = '';
            if(!empty($data['flat']))
                $flat = ', ' . $data['flat'];
            else
                $flat = '';
            $address =  '<address>' .  PHPShopString::win_utf8($data['street'] . $house . $flat) . '</address>' . "\n";
        }

        if(!empty($data['index']))
            $index = '<address_index>' . $data['index'] . '</address_index>' . "\n";
        else
            $index = '';

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<request>' . "\n";
        $xml .= '<function>add_delivery</function>' . "\n";
        $xml .= '<api_id>'. $this->option['api_id'] . '</api_id>' . "\n";
        $xml .= '<deliveries>' . "\n";
        $xml .= '<delivery>' . "\n";
        $xml .= '<delivery_date>' . $shoplogisticsOrderData['delivery_date'] . '</delivery_date>' . "\n";
        $xml .= '<time_from>10:00</time_from>' . "\n";
        $xml .= '<time_to>18:00</time_to>' . "\n";
        $xml .= '<date_transfer_to_store>' . date("Y-m-d") . '</date_transfer_to_store>' . "\n";
        $xml .= '<to_city>' .  PHPShopString::win_utf8($data['city']) . '</to_city>' . "\n";
        $xml .= '<order_id>' . $data['uid'] . '</order_id>' . "\n";
        $xml .= $index;
        $xml .= $address;
        $xml .= '<contact_person>' .  PHPShopString::win_utf8($name) . '</contact_person>' . "\n";
        $xml .= '<phone>' . $data['tel'] . '</phone>' . "\n";
        $xml .= '<phone_sms>' . $data['tel'] . '</phone_sms>' . "\n";
        $xml .= '<price>' . $data['sum'] . '</price>' . "\n";
        $xml .= '<site_name>' . $_SERVER['SERVER_NAME'] . '</site_name>' . "\n";
        $xml .= $pickup_place;
        $xml .= '<customer_email>' . $order['Person']['mail'] . '</customer_email>' . "\n";
        $xml .= '<products>' . $this->setProducts($order['Cart']['cart'], $order['Person']['discount']) . '</products>' . "\n";
        $xml .= '</delivery>' . "\n";
        $xml .= '</deliveries>' . "\n";
        $xml .= '</request>' . "\n";

        $this->parameters = $xml;
        $this->orderId = $data['uid'];
    }

    public function setProducts($cart = array(), $discount = 0)
    {
        $xml = '';
        if (count($cart) > 0) {
            global $PHPShopSystem;

            if ($PHPShopSystem->getParam('nds_enabled') == '')
                $nds = 0;
            else
                $nds = $PHPShopSystem->getParam('nds');
            foreach ($cart as $product) {

                if($discount > 0)
                    $price = $product['price']  - ($product['price']  * $discount  / 100);
                else
                    $price = $product['price'];
                if(!empty($product['uid']))
                    $uid = $product['uid'];
                else
                    $uid = $product['id'];

                $xml .= '<product>' . "\n";
                $xml .= '<articul>' . $uid . '</articul>' . "\n";
                $xml .= '<name>' . PHPShopString::win_utf8($product['name']) . '</name>' . "\n";
                $xml .= '<quantity>' . $product['num'] . '</quantity>' . "\n";
                $xml .= '<item_price>' . $price . '</item_price>' . "\n";
                $xml .= '<nds>' . $nds . '</nds>' . "\n";
                $xml .= '</product>' . "\n";
            }
        }

        return $xml;
    }

    public function getOrderInfo($code)
    {
        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<request>' . "\n";
        $xml .= '<function>get_deliveries</function>' . "\n";
        $xml .= '<api_id>'. $this->option['api_id'] . '</api_id>' . "\n";
        $xml .= '<code>'. $code . '</code>' . "\n";
        $xml .= '</request>' . "\n";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::$API_Url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
            'xml' => base64_encode($xml)
        ));
        curl_setopt($curl, CURLOPT_USERAGENT, 'Opera 10.00');
        $data = xml2array(curl_exec($curl), false, false);
        curl_close($curl);

        return $data;
    }

    /**
     * Запись лога
     * @param array $message содержание запроса в ту или иную сторону
     * @param string $order_id номер заказа
     * @param string $status статус отправки
     * @param string $type request
     */
    public function log($message, $order_id, $status, $type, $status_code = 'succes') {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shoplogisticswidget']['shoplogisticswidget_log']);
        $id = explode("-", $order_id);

        $log = array(
            'message_new' => serialize($message),
            'order_id_new' => $id[0],
            'status_new' => $status,
            'type_new' => $type,
            'date_new' => time(),
            'status_code_new' => $status_code,
            'tracking_new'    => ''
        );
        $PHPShopOrm->insert($log);
    }
}
