<?php

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
    global $_classPath;
    
    if ($data['statusi'] != $_POST['statusi_new']) {
        
        // Rest Yandex
        include_once($_classPath.'modules/yandexcart/class/yandexmarket.class.php');

        $YandexMarketRest = new YandexMarketRest();

        if ($YandexMarketRest->isYandex($data['dop_info'])) {

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
                    
                    // Если не менялся статус на PROCESSING -> DELIVERY
                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "DELIVERY"));
                    $YandexMarketRest->log($OrderId, $result);
                    

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "DELIVERED"));
                    $YandexMarketRest->log($OrderId, $result);

                    // Сообщение покупателю о заказе
                    //yandexcart_sendmail($data);

                    break;

                //  Отменен  SHOP_FAILED
                case $YandexMarketRest->option['status_cancelled']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "CANCELLED", "substatus" => "SHOP_FAILED"));
                    $YandexMarketRest->log($OrderId, $result);

                    break;

                //  Отменен  USER_CHANGED_MIND
                case $YandexMarketRest->option['status_cancelled_ucm']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "CANCELLED", "substatus" => "USER_CHANGED_MIND"));
                    $YandexMarketRest->log($OrderId, $result);

                    break;

                //  Отменен  USER_REFUSED_DELIVERY
                case $YandexMarketRest->option['status_cancelled_urd']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "CANCELLED", "substatus" => "USER_REFUSED_DELIVERY"));
                    $YandexMarketRest->log($OrderId, $result);

                    break;

                //  Отменен  USER_REFUSED_PRODUCT
                case $YandexMarketRest->option['status_cancelled_urp']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "CANCELLED", "substatus" => "USER_REFUSED_PRODUCT"));
                    $YandexMarketRest->log($OrderId, $result);

                    break;

                //  Отменен  USER_REFUSED_QUALITY
                case $YandexMarketRest->option['status_cancelled_urq']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "CANCELLED", "substatus" => "USER_REFUSED_QUALITY"));
                    $YandexMarketRest->log($OrderId, $result);

                    break;

                //  Отменен  USER_UNREACHABLE
                case $YandexMarketRest->option['status_cancelled_uu']:

                    $result = $YandexMarketRest->setOrderStatus($OrderId, array("status" => "CANCELLED", "substatus" => "USER_UNREACHABLE"));
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