<?php

function shoplogisticswidgetSend($data) {
    global $_classPath;

    if ($data['statusi'] != $_POST['statusi_new'] or !empty($_POST['shoplogistics_send_now'])) {

        include_once($_classPath . 'modules/shoplogisticswidget/class/ShopLogistics.php');
        $ShopLogistics = new ShopLogistics();
        if(!empty($data['shoplogistics_order_data'])) {
            if ($_POST['statusi_new'] == $ShopLogistics->option['status'] or !empty($_POST['shoplogistics_send_now'])) {

                $ShopLogistics->setDataFromOrderEdit($data);
                $ShopLogistics->Request();
                $_POST['shoplogistics_order_data_new'] = '';
            }
        }
    }
}

function addShoplogisticswidgetTab($data) {
    global $PHPShopGUI, $_classPath, $PHPShopModules;

    include_once($_classPath . 'modules/shoplogisticswidget/class/ShopLogistics.php');
    $ShopLogistics = new ShopLogistics();

    $order = unserialize($data['orders']);

    if(in_array($order['Person']['dostavka_metod'], explode(",", $ShopLogistics->option['delivery_id']))) {
        if(!empty($data['shoplogistics_order_data'])) {
            $Tab1 = $PHPShopGUI->setField(__('Синхронизация заказа'), $PHPShopGUI->setCheckbox('shoplogistics_send_now', 1, 'Отправить заказ в Shop-Logistics сейчас', 0));
            $PHPShopGUI->addTab(array("Shop-Logistics", $Tab1, true));
        }

        // Обновление трекинга
        if(isset($data['tracking']) and empty($data['tracking']))
        {
            $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.shoplogisticswidget.shoplogisticswidget_log"));
            $log = $PHPShopOrm->select(array('message'), array('status_code=' => '"success"', 'order_id=' => $data['id']));

            if(!empty($log['message'])) {
                $message = unserialize($log['message']);

                $info = $ShopLogistics->getOrderInfo($message['deliveries']['delivery']['code']);

                if(is_array($info['deliveries']['delivery']['tracking_number'])) {
                    $tracking = array_pop($info['deliveries']['delivery']['tracking_number']);
                    if(!empty($tracking)) {
                        $PHPShopOrmOrder = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
                        $PHPShopOrmOrder->update(array('tracking_new' => "$tracking"), array('id=' => $data['id']));
                    }
                }
            }
        }
    }
}

$addHandler = array(
    'actionStart'  => 'addShoplogisticswidgetTab',
    'actionDelete' => false,
    'actionUpdate' => 'shoplogisticswidgetSend'
);
?>