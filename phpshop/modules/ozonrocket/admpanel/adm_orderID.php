<?php

function ozonRocketSend($data) {
    global $_classPath;

    include_once($_classPath . 'modules/ozonrocket/class/OzonRocketWidget.php');
    $OzonRocketWidget = new OzonRocketWidget();
    $order = unserialize($data['orders']);

    if((int) $order['Person']['dostavka_metod'] === (int) $OzonRocketWidget->options['delivery_id']) {
        if ($data['statusi'] != $_POST['statusi_new']) {
            if ($_POST['statusi_new'] == $OzonRocketWidget->options['status']) {
                $OzonRocketWidget->send($data);
            }
        }
    }
}

function addOzonRocketTab($data) {
    global $PHPShopGUI, $_classPath;

    include_once($_classPath . 'modules/ozonrocket/class/OzonRocketWidget.php');
    $OzonRocketWidget = new OzonRocketWidget();

    $order = unserialize($data['orders']);
    if((int) $order['Person']['dostavka_metod'] === (int) $OzonRocketWidget->options['delivery_id']) {
        $PHPShopGUI->addJSFiles('../modules/ozonrocket/admpanel/gui/script.gui.js', '../modules/ozonrocket/js/ozonrocket.js');

        $Tab1 = $OzonRocketWidget->buildInfoTable($data);
        $PHPShopGUI->addTab(array("OZON Rocket", $Tab1, false, 107));
    }
}

$addHandler = array(
    'actionStart'  => 'addOzonRocketTab',
    'actionDelete' => false,
    'actionUpdate' => 'ozonRocketSend'
);
