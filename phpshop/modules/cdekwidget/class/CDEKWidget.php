<?php

include_once 'phpshop/class/xml.class.php';

class CDEKWidget {

    protected static $registerOrderUrl = 'https://integration.cdek.ru/new_orders.php';

    public function __construct() {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['cdekwidget']['cdekwidget_system']);

        /*
         * Опции модуля
         */
        $this->option = $PHPShopOrm->select();

        /*
         * Данные для передачи
         */
        $this->parameters = array();

        /*
         * Номер заказа
         */
        $this->orderId = false;
    }

    public function Request() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$registerOrderUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'xml_request' => $this->parameters
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = xml2array(curl_exec($ch), false, false);

        if($data['Order'][0]['@attributes']['ErrorCode'])
        {
            $this->log(
                array('error' => PHPShopString::utf8_win1251($data['Order'][0]['@attributes']['Msg']), 'parameters' => $this->parameters),
                $this->orderId,
                'Ошибка передачи заказа',
                'Передача заказа службе доставки СДЭК',
                'error'
            );
        }
        else
        {
            if(isset($data['Order'][0]['@attributes']['DispatchNumber']))
                $tracking = $data['Order'][0]['@attributes']['DispatchNumber'];

            $data['parameters'] = $this->parameters;
            $data['Order'][1]['@attributes']['Msg'] = PHPShopString::utf8_win1251($data['Order'][1]['@attributes']['Msg']);
            $this->log(
                $data,
                $this->orderId,
                'Успешная передача заказа',
                'Передача заказа службе доставки СДЭК',
                'success',
                $tracking
            );
        }
    }

    public function setDataFromDoneHook($obj, $data) {

        $cdek_data = unserialize($data['cdek_order_data_new']);

        $cartWeight = $obj->PHPShopCart->getWeight();

        if(!empty($cartWeight) and $cartWeight > 0)
            $weight = $cartWeight;
        else
            $weight = $this->option['weight'];

        if(empty($data['fio_new']))
            $name = $data['name_new'];
        else
            $name = $data['fio_new'];

        $data['cdek_order_data'] = $data['cdek_order_data_new'];
        $data['street'] = $data['street_new'];
        $data['house'] = $data['house_new'];
        $data['flat'] = $data['flat_new'];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<deliveryrequest account="' . $this->option['account'] . '" date="' . date('Y-m-d H:i:s') . '" number="1" ordercount="1" secure="' .  md5(date('Y-m-d H:i:s') . '&' . $this->option['password']) . '">' . "\n";
        $xml .= '<order  number="' .  $data['ouid'] . '" sendcitycode="' . $this->option['city_from_code'] . '" reccitycode="' . $cdek_data['city_id'] .'" sendcitypostcode="' . $this->option['index_from'] . '" reccitypostcode="' .  $data['index_new'] . '" tarifftypecode="' . $cdek_data['tariff'] .'" phone="' . $data['tel_new'] .'" recipientemail="' . $data['mail_new'] .'" recipientname="' . PHPShopString::win_utf8($name) . '">'. "\n";
        $xml .= $this->setAddress($data) . "\n";
        $xml .= '<package number="' .  $data['ouid'] . '" barcode="' .  $data['ouid'] . '" sizea="' . $this->option['length'] . '" sizeb="' . $this->option['width'] . '" sizec="' . $this->option['height'] . '" weight="' .  $weight .'">'. "\n";
        $xml .= $this->setProducts($obj->PHPShopCart->getArray(), $obj->discount) . "\n";
        $xml .= '</package>' . "\n";
        $xml .= '</order>' . "\n";
        $xml .=  '</deliveryrequest>';

        $this->parameters = $xml;
        $this->orderId = $data['ouid'];
    }

    public function setDataFromOrderEdit($data) {
        $order = unserialize($data['orders']);
        $cdek_data = unserialize($data['cdek_order_data']);

        if(empty($order['Cart']['weight']))
            $weight = $this->option['weight'];
        else
            $weight = $order['Cart']['weight'];
        if(empty($data['fio']))
            $name = $order['Person']['name_person'];
        else
            $name = $data['fio'];

        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<deliveryrequest account="' . $this->option['account'] . '" date="' . date('Y-m-d H:i:s') . '" number="1" ordercount="1" secure="' .  md5(date('Y-m-d H:i:s') . '&' . $this->option['password']) . '">' . "\n";
        $xml .= '<order  number="' .  $data['uid'] . '" sendcitycode="' . $this->option['city_from_code'] . '" reccitycode="' . $cdek_data['city_id'] .'" sendcitypostcode="' . $this->option['index_from'] . '" reccitypostcode="' .  $data['index'] . '" tarifftypecode="' . $cdek_data['tariff'] .'" phone="' . $data['tel'] .'" recipientemail="' . $order['Person']['mail'] .'" recipientname="' . PHPShopString::win_utf8($name) . '">'. "\n";
        $xml .= $this->setAddress($data) . "\n";
        $xml .= '<package number="' .  $data['uid'] . '" barcode="' .  $data['uid'] . '" sizea="' . $this->option['length'] . '" sizeb="' . $this->option['width'] . '" sizec="' . $this->option['height'] . '" weight="' .  $weight .'">'. "\n";
        $xml .= $this->setProducts($order['Cart']['cart'], $order['Person']['discount']) . "\n";
        $xml .= '</package>' . "\n";
        $xml .= '</order>' . "\n";
        $xml .=  '</deliveryrequest>';

        $this->parameters = $xml;
        $this->orderId = $data['uid'];
    }

    public function setAddress($data)
    {
        $cdek_data = unserialize($data['cdek_order_data']);

        if($cdek_data['type'] == 'courier') {
            $street = str_replace('ул.', '', $data['street']);
            $house = str_replace('д.', '', $data['house']);
            $flat = str_replace('кв.', '', $data['flat']);

            $xml = '<Address  street="' .  PHPShopString::win_utf8($street) . '" house="' . PHPShopString::win_utf8($house) . '" flat="' . PHPShopString::win_utf8($flat) .'"/>';
        } else {
            $xml = '<Address  pvzcode="' . $cdek_data['cdek_pvz_id'] . '"/>';
        }

        return $xml;
    }

    public function setProducts($cart = array(), $discount = 0)
    {
        $xml = '';
        if (count($cart) > 0) {
            foreach ($cart as $product) {

                if($discount > 0)
                    $price = $product['price']  - ($product['price']  * $discount  / 100);
                else
                    $price = $product['price'];

                if(empty($product['weight']))
                    $weight = 1;
                else
                    $weight = $product['weight'];
                $xml .= '<item amount="' . $product['num'] . '" warekey="' . $product['id'] .'" cost="' . $price .'" payment="' . $price .'" weight="' . $weight .'" comment="' . PHPShopString::win_utf8($product['name']) . '"/>';
            }
        }

        return $xml;
    }

    /**
     * Запись лога
     * @param array $message содержание запроса в ту или иную сторону
     * @param string $order_id номер заказа
     * @param string $status статус отправки
     * @param string $type request
     */
    public function log($message, $order_id, $status, $type, $status_code = 'succes', $traking = '') {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['cdekwidget']['cdekwidget_log']);
        $id = explode("-", $order_id);

        $log = array(
            'message_new'     => serialize($message),
            'order_id_new'    => $id[0],
            'status_new'      => $status,
            'type_new'        => $type,
            'date_new'        => time(),
            'status_code_new' => $status_code,
            'tracking_new'    => $traking
        );
        $PHPShopOrm->insert($log);
    }

}
