<?php

include_once dirname(__DIR__) . '/class/PickPoint.php';

function pickpointSend($data) {

    $order = unserialize($data['orders']);
    $PickPoint = new PickPoint();

    if((int) $order['Person']['dostavka_metod'] === $PickPoint->getPickpointDeliveryId()) {
        if ($data['statusi'] != $_POST['statusi_new'] or !empty($_POST['pickpoint_send_now'])) {
            if (((int) $_POST['statusi_new'] === $PickPoint->options['status']) or !empty($_POST['pickpoint_send_now'])) {
                $PickPoint->createOrder($data);
            }
        }
    }
}

function addPickpointTab($data) {
    global $PHPShopGUI;

    $PickPoint = new PickPoint();
    $order = unserialize($data['orders']);

    if((int) $order['Person']['dostavka_metod'] === $PickPoint->getPickpointDeliveryId()) {
        $PHPShopGUI->addJSFiles('../modules/pickpoint/admpanel/gui/script.gui.js');

        $pickpoint = unserialize($data['pickpoint_data']);

        if(!$pickpoint['sent']) {
            $Tab1 = $PHPShopGUI->setField('Статус оплаты',
                $PHPShopGUI->setCheckbox('pickpoint_payment_status', 1, 'Заказ оплачен', $data['paid']));
            $Tab1 .= $PHPShopGUI->setField('Синхронизация заказа', $PHPShopGUI->setCheckbox('pickpoint_send_now', 1, 'Отправить заказ в PickPoint сейчас', 0));
            $Tab1 .= $PHPShopGUI->setInput('hidden', 'pickpoint_order_id', $data['id']);
            $PHPShopGUI->addTab(["PickPoint", $Tab1, true]);
        }
    }
}

$addHandler = [
    'actionStart'  => 'addPickpointTab',
    'actionDelete' => false,
    'actionUpdate' => 'pickpointSend'
];
?>