<?php

include_once dirname(__DIR__) . '/class/include.php';

function yadeliverySend($data) {

    $order = unserialize($data['orders']);
    $YandexDelivery = new YandexDelivery();

    if($YandexDelivery->isYandexDeliveryMethod((int) $order['Person']['dostavka_metod'])) {
        if ((int) $data['statusi'] !== (int) $_POST['statusi_new']) {
            if ((int) $_POST['statusi_new'] === (int) $YandexDelivery->options['status']) {
                $YandexDelivery->createOrder($data);
            }
        }
    }
}

function addYadeliveryTab($data) {
    global $PHPShopGUI;

    $YandexDelivery = new YandexDelivery();
    $order = unserialize($data['orders']);

    if($YandexDelivery->isYandexDeliveryMethod((int) $order['Person']['dostavka_metod'])) {

       $PHPShopGUI->addJSFiles('../modules/yandexdelivery/admpanel/gui/script.gui.js');
       $Tab = $YandexDelivery->buildOrderTab($data);

       $PHPShopGUI->addTab(["Яндекс.Доставка", $Tab]);
    }
}

$addHandler = array(
    'actionStart'  => 'addYadeliveryTab',
    'actionDelete' => false,
    'actionUpdate' => 'yadeliverySend'
);
?>