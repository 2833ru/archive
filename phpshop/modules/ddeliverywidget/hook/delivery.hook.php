<?php

/**
 * Õóê
 */
function ddeliverywidget_delivery_hook($obj, $data) {

    $_RESULT = $data[0];
    $xid = $data[1];

    // API
    include_once '../modules/ddeliverywidget/class/ddeliverywidget.class.php';
    $ddeliverywidget = new ddeliverywidget();
    $option = $ddeliverywidget->option();

    if (in_array($xid, @explode(",", $option['delivery_id']))) {

        $hook['dellist'] = $_RESULT['dellist'];
        $hook['hook'] = 'ddeliverywidgetStart();';
        $hook['delivery'] = $_RESULT['delivery'];
        $hook['total'] = $_RESULT['total'];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['success'] = 1;
    
    } else {
        $hook['dellist'] = $_RESULT['dellist'];
        $hook['hook'] = 'ddeliverywidgetReset();';
        $hook['delivery'] = $_RESULT['delivery'];
        $hook['total'] = $_RESULT['total'];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['success'] = 1;
    }
    return $hook;
    
}

$addHandler = array
    (
    'delivery' => 'ddeliverywidget_delivery_hook'
);
?>
