<?php

class YandexMarketRest {

    // ID Приложения
    protected $oauth_client_id = '5b5057ed29784d83a5ba85c7c2cae9b9';

    function __construct() {
        $this->option();
        $this->oauth_token = $this->option['token'];
        $this->campaignId = $this->option['campaign'];
    }

    /**
     * Настройки модуля
     */
    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexcart']['yandexcart_system']);
        $this->option = $PHPShopOrm->select();
    }

    /**
     * Запрос REST
     * @param int $orderId ИД заказа
     * @param array $data данные
     * @param string $rout роутер [order | region]
     * @return string
     */
    function request($orderId, $data, $rout='orders') {

        $data_string = json_encode($data);

        $ch = curl_init();
        
        switch($rout){
            
            // Статус заказа
            case "orders":
                $rout_path="/orders/" . $orderId . "/status.json";
                $method="PUT";
               break;
           
           // Регион
            case "region":
                $rout_path="/region.json";
                $method="GET";
                break;
        }
        
        curl_setopt($ch, CURLOPT_URL, "https://api.partner.market.yandex.ru/v2/campaigns/" . $this->campaignId .$rout_path."?oauth_token=" . $this->oauth_token . "&oauth_client_id=" . $this->oauth_client_id);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));

        $output = curl_exec($ch);

        curl_close($ch);


        $response = explode("\r\n\r\n", $output);
        $responsecontent = $response[1];

        return $responsecontent;
    }

    function setOrderStatus($orderId, $request) {
        $data['order'] = $request;
        return $this->request($orderId, $data);
    }

    // Лог
    function log($OrderId, $data) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexcart']['yandexcart_log']);



        $log = array(
            'message_new' => serialize(json_decode($data, true)),
            'order_id_new' => $OrderId,
            'date_new' => time(),
            'path_new' => '/order/status/update'
        );

        $PHPShopOrm->insert($log);
    }

    // Заказ Яндекса?
    function isYandex($order) {
        if (strstr($order, 'yandex')) {
            $this->yandexOrderId = intval(str_replace('yandex', '', $order));
            return true;
        }
        else
            return false;
    }

}

?>