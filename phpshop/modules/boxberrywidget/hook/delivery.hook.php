<?php

/**
 * Внедрение js функции
 *
 * param object $obj
 * param array $data
 */
function boxberrywidget_delivery_hook($obj, $data) {

    $_RESULT = $data[0];
    $xid = $data[1];

    // API
    include_once '../modules/boxberrywidget/class/BoxberryWidget.php';
    $BoxberryWidget = new BoxberryWidget();

    if (in_array($xid, @explode(",", $BoxberryWidget->option['delivery_id'])) or in_array($xid, @explode(",", $BoxberryWidget->option['express_delivery_id']))) {

        $hook['dellist'] = $_RESULT['dellist'];
        $hook['hook'] = 'boxberrywidgetStart();';
        $hook['delivery'] = $_RESULT['delivery'];
        $hook['total'] = $_RESULT['total'];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['success'] = 1;
    
    } else {
        $hook['dellist'] = $_RESULT['dellist'];
        $hook['hook'] = 'boxberrywidgetReset();';
        $hook['delivery'] = $_RESULT['delivery'];
        $hook['total'] = $_RESULT['total'];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['success'] = 1;
    }

    return $hook;
    
}

$addHandler = array
    (
    'delivery' => 'boxberrywidget_delivery_hook'
);
?>
