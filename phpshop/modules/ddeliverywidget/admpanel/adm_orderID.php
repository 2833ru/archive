<?php

function ddeliverywidgetSend($data) {
    global $_classPath;

    if ($data['statusi'] != $_POST['statusi_new'] or !empty($_POST['ddelivery_send_now'])) {

        // Rest 
        include_once($_classPath . 'modules/ddeliverywidget/class/ddeliverywidget.class.php');
        $ddeliverywidget = new ddeliverywidget();
        $option = $ddeliverywidget->option();


        if ($_POST['statusi_new'] == $option['status'] or !empty($_POST['ddelivery_send_now'])) {
            $apiKey = $option['key'];
            $sessionId = $data['ddelivery_token'];
            $helper = new DDeliveryHelper($apiKey, false);

            $order = unserialize($data['orders']);

            $params = array(
                'session' => $sessionId,
                'to_name' => PHPShopString::win_utf8($_POST['fio_new']),
                'to_phone' => '+7' . str_replace(array('(', ')', ' ', '+', '-'), '', $_POST['tel_new']),
                'shop_refnum' => $data['uid'],
                'to_email' => $order['Person']['mail'],
                //'payment_price' =>$obj->total,
                'comment' => PHPShopString::win_utf8($_POST['dop_info']),
                'to_flat' => PHPShopString::win_utf8($_POST['flat_new']),
                'to_street' => PHPShopString::win_utf8($_POST['street_new']),
                'to_house' => PHPShopString::win_utf8($_POST['house_new']),
                    //'payment_variant' => PHPShopString::win_utf8($obj->get('payment'))
            );

            $result=$helper->sendOrder($sessionId, $params);
            if($result['success'] == 1)
                $_POST['ddelivery_token_new']=0;
        }
    }
}

function addDdeliveryTab($data) {
    global $PHPShopGUI;
    if (!empty($data['ddelivery_token'])) {
        $Tab1 = $PHPShopGUI->setField(__('Синхронизация заказа'), $PHPShopGUI->setCheckbox('ddelivery_send_now', 1, 'Отправить заказ в DDelivery.ru сейчас', 0));
        $PHPShopGUI->addTab(array("Ddelivery", $Tab1, true));
    }
}

$addHandler = array(
    'actionStart' => 'addDdeliveryTab',
    'actionDelete' => false,
    'actionUpdate' => 'ddeliverywidgetSend'
);
?>