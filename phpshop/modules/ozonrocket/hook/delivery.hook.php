<?php

function ozonRocketDeliveryHook($obj, $data) {
    // API
    include_once '../modules/ozonrocket/class/OzonRocketWidget.php';
    $OzonRocketWidget = new OzonRocketWidget();

    if((int) $OzonRocketWidget->options['delivery_id'] === 0){
        return;
    }

    if ((int) $data[1] === (int) $OzonRocketWidget->options['delivery_id']) {
        $data[0]['hook'] = 'OzonRocketWidgetStart();';
        $data[0]['success'] = 1;

        return $data[0];
    }
}

$addHandler = ['delivery' => 'ozonRocketDeliveryHook'];

