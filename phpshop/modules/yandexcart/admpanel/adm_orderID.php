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

/**
 * Шаблон вывода таблицы корзины
 */
function yandexcart_mailcartforma($val, $option) {

    if (PHPShopProductFunction::true_parent($val['parent']))
        $val['uid'] = null;

    $dis = $val['uid'] . "  " . $val['name'] . " (" . $val['num'] . " " . $val['ed_izm'] . " * " . $val['price'] . ") -- " . ($val['price'] * $val['num']) . " " . $option['currency'] . " <br>
";
    return $dis;
}

function yandexcart_sendmail($data) {
    global $PHPShopSystem, $PHPShopBase, $PHPShopOrder;

    PHPShopObj::loadClass('parser');

    $PHPShopOrder = new PHPShopOrderFunction($data['id'], $data);


    $order = unserialize($data['orders']);

    // Доставка
    $PHPShopCart = new PHPShopCart($order['Cart']['cart']);
    $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);
    $currency = $PHPShopSystem->getDefaultValutaCode();
    PHPShopParser::set('cart', $PHPShopCart->display('yandexcart_mailcartforma', array('currency' => $currency)));

    $sum = $PHPShopCart->getSum(false);
    $delivery = $PHPShopDelivery->getPrice($sum, $PHPShopCart->getWeight());
    $total = $sum + $delivery;

    PHPShopParser::set('sum', $sum);
    PHPShopParser::set('currency', $currency);
    PHPShopParser::set('discount', $order['Person']['discount']);
    PHPShopParser::set('deliveryPrice', $delivery);
    PHPShopParser::set('total', $total);
    PHPShopParser::set('shop_name', $PHPShopSystem->getName());
    PHPShopParser::set('ouid', $data['uid']);
    PHPShopParser::set('date', date("d-m-y"));
    PHPShopParser::set('deliveryCity', $PHPShopDelivery->getCity());
    PHPShopParser::set('mail', $order['Person']['mail']);
    PHPShopParser::set('payment', $PHPShopOrder->getOplataMetodName());
    PHPShopParser::set('user_name', $data['fio']);
    PHPShopParser::set('dop_info', $data['dop_info']);

    // формируем список данных полей доставки.
    PHPShopParser::set('adresList', $PHPShopDelivery->getAdresListFromOrderData($_POST, "\n"));


    // Заголовок письма покупателю
    $title = $PHPShopBase->getParam('lang.mail_title_user_start') . $_POST['ouid'] . $PHPShopBase->getParam('lang.mail_title_user_end');


    // Отсылаем письмо покупателю
    $PHPShopMail = new PHPShopMail($order['Person']['mail'], $PHPShopSystem->getParam('adminmail2'), $title, '', true, true);
    $content = PHPShopParser::file('../lib/templates/order/usermail.tpl', true);

    // Замены
    $content = str_replace('Наши менеджеры свяжутся с Вами по координатам, оставленным в форме заказа.', 'Ваш заказ подтвержден, ожидайте звонка курьера, для согласования времени доставки.', $content);

    $PHPShopMail->sendMailNow($content);
}

function yandexcartCheckOrder($data) {


    if ($data['statusi'] != $_POST['statusi_new']) {

        $YandexMarketRest = new YandexMarketRest();


        if ($YandexMarketRest->isYandex($_POST['dop_info_new'])) {

            $OrderId = $YandexMarketRest->yandexOrderId;

            switch ($_POST['statusi_new']) {

                // Передан в службу доставки
                case $YandexMarketRest->option['status_delivery']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "DELIVERY"));
                    $YandexMarketRest->log($OrderId, $result);


                    break;

                //  Подтвержден на доставку
                case $YandexMarketRest->option['status_processing']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "PROCESSING"));
                    $YandexMarketRest->log($OrderId, $result);

                    // Сообщение покупателю о заказе
                    yandexcart_sendmail($data);

                    break;

                //  Доставлен
                case $YandexMarketRest->option['status_delivered']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "DELIVERED"));
                    $YandexMarketRest->log($OrderId, $result);

                    // Сообщение покупателю о заказе
                    yandexcart_sendmail($data);

                    break;

                //  Анулирован
                case $YandexMarketRest->option['status_cancelled']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "CANCELLED", "substatus" => "SHOP_FAILED"));
                    $YandexMarketRest->log($OrderId, $result);

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