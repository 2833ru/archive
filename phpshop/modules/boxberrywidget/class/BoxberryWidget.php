<?php

class BoxberryWidget {

    public function __construct() {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['boxberrywidget']['boxberrywidget_system']);

        /*
         * Опции модуля
         */
        $this->option = $PHPShopOrm->select();

        /*
         * Данные для передачи
         */
        $this->parameters = array();
    }

    public function request($method) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'http://api.boxberry.de/json.php');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, array(
            'token' => $this->option['token'],
            'method'=> $method,
            'sdata' => json_encode($this->parameters)
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $data = json_decode(curl_exec($ch),1);
        if($data['err'])
        {
            $this->log(
                array('error' => PHPShopString::utf8_win1251($data['err']), 'parameters' => $this->parameters),
                $this->parameters['order_id'],
                'Ошибка передачи заказа',
                'Передача заказа службе доставки Boxberry'
            );
        }
        else
        {
            $data['parameters'] = $this->parameters;
            $this->log(
                $data,
                $this->parameters['order_id'],
                'Успешная передача заказа',
                'Передача заказа службе доставки Boxberry'
            );
        }
    }

    public function setDataFromDoneHook($obj, $data, $postData) {

        if($obj->PHPShopCart->getWeight() < 5)
            $weight = 5;
        else
            $weight = $obj->PHPShopCart->getWeight();

        $this->parameters = array(
            'order_id'     => $data['ouid'],
            'price'        => $obj->get('total'),
            'payment_sum'  => $obj->get('total'),
            'delivery_sum' => $postData['boxberrySum'],
            'shop'         => array(
                'name'         => $postData['boxberry_pvz_id_new'],
                'name1'        => $this->option['pvz_id']
            ),
            'customer'     => array(
                'fio'          => PHPShopString::win_utf8($data['fio_new']),
                'phone'        => PHPShopString::win_utf8($data['tel_new']),
                'email'        => PHPShopString::win_utf8($data['mail']),
            ),
            'weights'      => array(
                'weight'       => $weight,
            )
        );

        if(!empty($data['org_inn_new'])) {
            $this->parameters['customer']['name']    = PHPShopString::win_utf8($data['org_name_new']);
            $this->parameters['customer']['address'] = PHPShopString::win_utf8($data['org_yur_adres_new']);
            $this->parameters['customer']['inn']     = $data['org_inn_new'];
            $this->parameters['customer']['kpp']     = PHPShopString::win_utf8($data['org_kpp_new']);
            $this->parameters['customer']['r_s']     = PHPShopString::win_utf8($data['org_ras_new']);
            $this->parameters['customer']['bank']    = PHPShopString::win_utf8($data['org_bank_new']);
            $this->parameters['customer']['kor_s']   = PHPShopString::win_utf8($data['org_kor_new']);
            $this->parameters['customer']['bik']     = PHPShopString::win_utf8($data['org_bik_new']);
        }
    }

    public function setDataFromOrderEdit($data) {
        $order = unserialize($data['orders']);

        if($order['Cart']['weight'] < 5)
            $weight = 5;
        else
            $weight = $order['Cart']['weight'];

        $this->parameters = array(
            'order_id'     => $data['uid'],
            'price'        => $data['sum'],
            'payment_sum'  => $data['sum'],
            'delivery_sum' => $order['Cart']['dostavka'],
            'shop'         => array(
                'name'         => $data['boxberry_pvz_id'],
                'name1'        => $this->option['pvz_id']
            ),
            'customer'     => array(
                'fio'          => PHPShopString::win_utf8($data['fio']),
                'phone'        => PHPShopString::win_utf8($data['tel']),
                'email'        => PHPShopString::win_utf8($order['Person']['mail']),
            ),
            'weights'      => array(
                'weight'       => $weight,
            )
        );

        if(!empty($data['org_inn'])) {
            $this->parameters['customer']['name']    = PHPShopString::win_utf8($data['org_name']);
            $this->parameters['customer']['address'] = PHPShopString::win_utf8($data['org_yur_adres']);
            $this->parameters['customer']['inn']     = $data['org_inn'];
            $this->parameters['customer']['kpp']     = PHPShopString::win_utf8($data['org_kpp']);
            $this->parameters['customer']['r_s']     = PHPShopString::win_utf8($data['org_ras']);
            $this->parameters['customer']['bank']    = PHPShopString::win_utf8($data['org_bank']);
            $this->parameters['customer']['kor_s']   = PHPShopString::win_utf8($data['org_kor']);
            $this->parameters['customer']['bik']     = PHPShopString::win_utf8($data['org_bik']);
        }

    }

    public function setProducts($cart = array(), $discount = 0)
    {
        global $PHPShopSystem;

        if ($PHPShopSystem->getParam('nds_enabled') == '')
            $nds = $nds_delivery = 0;
        else
            $nds = $nds_delivery = $PHPShopSystem->getParam('nds');

        if (count($cart) > 0) {
            foreach ($cart as $product) {

                if($discount > 0)
                    $price = $product['price']  - ($product['price']  * $discount  / 100);
                else
                    $price = $product['price'];

                if(empty($product['ed_izm']))
                    $ed_izm = 'шт.';
                else
                    $ed_izm = $product['ed_izm'];

                $this->parameters['items'][] = array(
                    'id'       => $product['id'],
                    'name'     => PHPShopString::win_utf8($product['name']),
                    'UnitName' => PHPShopString::win_utf8($ed_izm),
                    'nds'      => $nds,
                    'price'    => floatval($price),
                    'quantity' => $product['num']
                );
            }
        }
    }

    /**
     * Запись лога
     * @param array $message содержание запроса в ту или иную сторону
     * @param string $order_id номер заказа
     * @param string $status статус отправки
     * @param string $type request
     */
    public function log($message, $order_id, $status, $type) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['boxberrywidget']['boxberrywidget_log']);
        $id = explode("-", $order_id);

        $log = array(
            'message_new' => serialize($message),
            'order_id_new' => $id[0],
            'status_new' => $status,
            'type_new' => $type,
            'date_new' => time()
        );
        $PHPShopOrm->insert($log);
    }
}
