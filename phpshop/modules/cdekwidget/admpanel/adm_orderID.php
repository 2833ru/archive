<?php

function cdekwidgetSend($data) {
    global $_classPath;

    if ($data['statusi'] != $_POST['statusi_new'] or !empty($_POST['cdek_send_now'])) {

        include_once($_classPath . 'modules/cdekwidget/class/CDEKWidget.php');
        $CDEKWidget = new CDEKWidget();

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
    global $PHPShopGUI, $PHPShopModules, $_classPath;

    include_once($_classPath . 'modules/cdekwidget/class/CDEKWidget.php');
    $CDEKWidget = new CDEKWidget();

    $order = unserialize($data['orders']);

    if(in_array($order['Person']['dostavka_metod'], explode(",", $CDEKWidget->option['delivery_id']))) {
        if(!empty($data['cdek_order_data'])) {
            $Tab1 = $PHPShopGUI->setField(__('Синхронизация заказа'), $PHPShopGUI->setCheckbox('cdek_send_now', 1, 'Отправить заказ в СДЭК сейчас', 0));
            $PHPShopGUI->addTab(array("СДЭК", $Tab1, true));
        }

        // Обновление трекинга
        if(isset($data['tracking']) and empty($data['tracking']))
        {
            $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cdekwidget.cdekwidget_log"));
            $tracking = $PHPShopOrm->select(array('tracking'), array('status_code=' => '"success"', 'order_id=' => $data['id']));

            if(!empty($tracking['tracking'])) {
                $PHPShopOrmOrder = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
                $PHPShopOrmOrder->update(array('tracking_new' => "$tracking[tracking]"), array('id=' => $data['id']));
            }
        }    
    }
}

$addHandler = array(
    'actionStart'  => 'addCdekTab',
    'actionDelete' => false,
    'actionUpdate' => 'cdekwidgetSend'
);
?>