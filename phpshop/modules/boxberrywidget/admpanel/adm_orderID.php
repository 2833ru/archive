<?php

function boxberrywidgetSend($data) {
    global $_classPath;

    if ($data['statusi'] != $_POST['statusi_new'] or !empty($_POST['boxberry_send_now'])) {

        include_once($_classPath . 'modules/boxberrywidget/class/BoxberryWidget.php');
        $BoxberryWidget = new BoxberryWidget();
        $order = unserialize($data['orders']);

        if(!empty($data['boxberry_pvz_id'])) {
            if ($_POST['statusi_new'] == $BoxberryWidget->option['status'] or !empty($_POST['boxberry_send_now'])) {

                $BoxberryWidget->setDataFromOrderEdit($data);
                $BoxberryWidget->setProducts($order['Cart']['cart'], $order['Person']['discount']);

                if(in_array($order['Person']['dostavka_metod'], @explode(",", $BoxberryWidget->option['delivery_id'])))
                    $BoxberryWidget->parameters['vid'] = 1;
                else {
                    if(!empty($data['street']))
                        $street = ', ' . $data['street'];
                    else
                        $street = '';
                    if(!empty($data['house']))
                        $house = ', ' . $data['house'];
                    else
                        $house = '';
                    if(!empty($data['flat']))
                        $flat = ', ' . $data['flat'];
                    else
                        $flat = '';
                    $BoxberryWidget->parameters['vid'] = 2;
                    $BoxberryWidget->parameters['kurdost'] = array(
                        'index'    => $data['index'],
                        'citi'     => PHPShopString::win_utf8($data['city']),
                        'addressp' => PHPShopString::win_utf8($data['index'] . ', ' . $data['city'] . ', ' . $street . $house . $flat)
                    );
                }

                $BoxberryWidget->request('ParselCreate');

                $_POST['boxberry_pvz_id_new'] = '';
            }
        }
    }
}

function addBoxberryTab($data) {
    global $PHPShopGUI;

    if(!empty($data['boxberry_pvz_id'])) {
        $Tab1 = $PHPShopGUI->setField(__('Синхронизация заказа'), $PHPShopGUI->setCheckbox('boxberry_send_now', 1, 'Отправить заказ в Boxberry сейчас', 0));
        $PHPShopGUI->addTab(array("Boxberry", $Tab1, true));
    }
}

$addHandler = array(
    'actionStart' => 'addBoxberryTab',
    'actionDelete' => false,
    'actionUpdate' => 'boxberrywidgetSend'
);
?>