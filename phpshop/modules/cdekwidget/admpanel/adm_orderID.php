<?php

function cdekwidgetSend($data) {
    global $_classPath;

    if ($data['statusi'] != $_POST['statusi_new'] or !empty($_POST['cdek_send_now'])) {

        include_once($_classPath . 'modules/cdekwidget/class/CDEKWidget.php');
        $CDEKWidget = new CDEKWidget();
        $order = unserialize($data['orders']);

        if(!empty($data['cdek_order_data'])) {
            if ($_POST['statusi_new'] == $CDEKWidget->option['status'] or !empty($_POST['cdek_send_now'])) {

                $CDEKWidget->setDataFromOrderEdit($data);
                $CDEKWidget->Request();
                $_POST['cdek_order_data_new'] = '';
            }
        }
    }
}

function addCdekTab($data) {
    global $PHPShopGUI;

    if(!empty($data['cdek_order_data'])) {
        $Tab1 = $PHPShopGUI->setField(__('Синхронизация заказа'), $PHPShopGUI->setCheckbox('cdek_send_now', 1, 'Отправить заказ в СДЭК сейчас', 0));
        $PHPShopGUI->addTab(array("СДЭК", $Tab1, true));
    }
}

$addHandler = array(
    'actionStart'  => 'addCdekTab',
    'actionDelete' => false,
    'actionUpdate' => 'cdekwidgetSend'
);
?>