<?php

function delivery_promotions_hook($obj, $data) {

    $_RESULT = $data[0];

    if ($_SESSION['freedelivery'] == 0) {
        $hook['total'] = $_RESULT['total'];
        $hook['delivery'] = 0;
        $hook['dellist'] = $_RESULT['dellist'];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['success'] = 1;

        // Совместимость с модулем ddeliverywidget
        if (empty($GLOBALS['SysValue']['base']['ddeliverywidget']['ddeliverywidget_system'])) {
            return $hook;
        }
    } elseif (!empty($_SESSION['totalsummainput'])) {

        $hook['total'] = $_SESSION['totalsummainput'] + $_RESULT['delivery'];
        $hook['delivery'] = $_RESULT['delivery'];
        $hook['dellist'] = $_RESULT['dellist'];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['success'] = 1;

        // Совместимость с модулем ddeliverywidget
        if (empty($GLOBALS['SysValue']['base']['ddeliverywidget']['ddeliverywidget_system'])) {
            return $hook;
        }
    }
}

$addHandler = array(
    'delivery' => 'delivery_promotions_hook'
);
?>