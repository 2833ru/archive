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

    function request($orderId, $data) {

        $data_string = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.partner.market.yandex.ru/v2/campaigns/" . $this->campaignId . "/orders/" . $orderId . "/status.json?oauth_token=" . $this->oauth_token . "&oauth_client_id=" . $this->oauth_client_id);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");

        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HEADER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));

        $output = curl_exec($ch);

        curl_close($ch);


        $response = split("\r\n\r\n", $output);
        $header = $response[0];
        $responsecontent = $response[1];

        return $responsecontent;
    }

    function setOrderStatus($orderId, $request) {
        $data['order'] = $request;
        return $this->request($orderId, $data);
    }

    // Лог
    function log($OrderId,$data) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexcart']['yandexcart_log']);
        
        $log = array(
            'message_new' =>  json_decode($data, true),
            'order_id_new' => $OrderId,
            'date_new' => time(),
            'path_new' => __FILE__
        );

        $PHPShopOrm->insert($log);
    }

    // Заказ Яндекса?
    function isYandex($order) {
        if (strpos($order, 'yandex')) {
            $this->yandexOrderId = intval(str_replace('yandex', '', $order));
            return true;
        }
        else
            return false;
    }

}


function yandexcartCheckOrder($data) {

    if ($data['statusi'] != $_POST['statusi_new']){

        $YandexMarketRest = new YandexMarketRest();

        if ($YandexMarketRest->isYandex($_POST['dop_info_new'])) {

            $OrderId = $YandexMarketRest->yandexOrderId;

            switch ($_POST['statusi_new']) {

                // Передан в службу доставки
                case $YandexMarketRest->option['status_delivery']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "DELIVERY"));
                    $YandexMarketRest->log($OrderId,$result);

                    break;

                //  Подтвержден на доставку
                case $YandexMarketRest->option['status_processing']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "PROCESSING"));
                    $YandexMarketRest->log($OrderId,$result);

                    break;

                //  Анулирован
                case $YandexMarketRest->option['status_processing']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "CANCELLED","substatus" => "SHOP_FAILED"));
                    $YandexMarketRest->log($OrderId,$result);

                    break;
            }
        }
    }
}

$addHandler = array(
    'actionStart' => false,
    'actionDelete' => false,
    'actionUpdate' => 'yandexcartCheckOrder'
);
?>