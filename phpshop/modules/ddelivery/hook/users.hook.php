<?php

include_once( $_SERVER['DOCUMENT_ROOT'] . '/phpshop/modules/ddelivery/class/application/bootstrap.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/phpshop/modules/ddelivery/class/mrozk/IntegratorShop.php' );

function userdeleveryforma_ddelivery_hook($option, $val) {

    $formaContent = $GLOBALS['SysValue']['other']['formaContent'];
    $IntegratorShop = new IntegratorShop();
    $order_id = $_GET['order_info'];

    try {
        $ddeliveryUI = new \DDelivery\DDeliveryUI($IntegratorShop, true);
        $order = $ddeliveryUI->getOrderByCmsID($order_id);
        if ($order) {
            $clientPrice = $ddeliveryUI->getOrderClientDeliveryPrice($order);

            $dis = PHPShopText::tr(__('Доставка') . ' - ' . $val['name'], 1, $clientPrice . ' ' . $option['currency']);
            return array('tr' => $dis, 'name' => $val['name'], 'adres' => $adres);
        }
    } catch (\DDelivery\DDeliveryException $e) {
        $ddeliveryUI->logMessage($e);
    }
}

$addHandler = array
    (
    'userdeleveryforma' => 'userdeleveryforma_ddelivery_hook',
);