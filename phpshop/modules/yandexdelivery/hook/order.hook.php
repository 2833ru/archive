<?php

include_once dirname(__DIR__) . '/class/include.php';

function order_yandexdelivery_hook($obj, $row, $rout) {
    if ($rout === 'MIDDLE') {

        $YandexDelivery = new YandexDelivery();
        $PHPShopCart = new PHPShopCart();
        $cart = $YandexDelivery->getCart($PHPShopCart->getArray());

        PHPShopParser::set('yandexdelivery_order_uid', $obj->order_num);
        PHPShopParser::set('yandexdelivery_api_key', $YandexDelivery->options['api_key']);
        PHPShopParser::set('yandexdelivery_sender_id', $YandexDelivery->options['sender_id']);
        PHPShopParser::set('yandexdelivery_warehouse_id', $YandexDelivery->options['warehouse_id']);
        PHPShopParser::set('yandexdelivery_cart', json_encode($cart));

        $obj->set('order_action_add', ParseTemplateReturn($GLOBALS['SysValue']['templates']['yandexdelivery']['yandexdelivery_template'], true) , true);
    }
}

$addHandler = ['order' => 'order_yandexdelivery_hook'];