<?php

include_once dirname(__DIR__) . '/class/BoxberryWidget.php';

function boxberrywidgetSend($data) {

    $BoxberryWidget = new BoxberryWidget();
    $order = unserialize($data['orders']);

    if($BoxberryWidget->isBoxberryDeliveryMethod((int) $order['Person']['dostavka_metod'])) {
        if ((int) $_POST['statusi_new'] === (int) $BoxberryWidget->option['status'] or !empty($_POST['boxberry_send_now'])) {

            // Заказ уже отправлен
            if(empty($data['boxberry_pvz_id'])) {
                return;
            }

            $BoxberryWidget->isPvzDelivery((int) $order['Person']['dostavka_metod']) ? $vid = 1 : $vid = 2;
            $BoxberryWidget->setData($data, $vid, (int) $order['Person']['discount']);

            $result = $BoxberryWidget->request('ParselCreate');
            if($result) {
                $_POST['boxberry_pvz_id_new'] = '';
            }
        }
    }
}

function addBoxberryTab($data) {
    global $PHPShopGUI;

    $BoxberryWidget = new BoxberryWidget();
    $order = unserialize($data['orders']);

    if($BoxberryWidget->isBoxberryDeliveryMethod((int) $order['Person']['dostavka_metod'])) {
        $PHPShopOrm = new PHPShopOrm("phpshop_modules_boxberrywidget_log");

        $log = $PHPShopOrm->select(array('*'), array('order_id=' => $data['id'], 'status=' => '"Успешная передача заказа"'));

        if(empty($log)) {
            $PHPShopGUI->addJSFiles('../modules/boxberrywidget/admpanel/gui/boxberrywidget.gui.js');

            $Tab1 = $PHPShopGUI->setField('Статус оплаты',
                $PHPShopGUI->setCheckbox('boxberry_payment_status', 1, 'Заказ оплачен', $data['paid']));
            $Tab1 .= $PHPShopGUI->setField('Синхронизация заказа', $PHPShopGUI->setCheckbox('boxberry_send_now', 1, 'Отправить заказ в Boxberry сейчас', 0));
            $Tab1 .= $PHPShopGUI->setInput('hidden', 'boxberry_order_id', $data['id']);
            $PHPShopGUI->addTab(array("Boxberry", $Tab1, true));
        }

        // Обновление трекинга
        if(isset($data['tracking']) and empty($data['tracking']))
        {
            $tracking = $PHPShopOrm->select(array('tracking'), array('status_code=' => '"success"', 'order_id=' => $data['id']));

            if(!empty($tracking['tracking'])) {
                $PHPShopOrmOrder = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
                $PHPShopOrmOrder->update(array('tracking_new' => "$tracking[tracking]"), array('id=' => $data['id']));
            }
        }
    }
}

$addHandler = array(
    'actionStart' => 'addBoxberryTab',
    'actionDelete' => false,
    'actionUpdate' => 'boxberrywidgetSend'
);
?>