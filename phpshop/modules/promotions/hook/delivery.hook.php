<?php

function delivery_promotions_hook($obj, $data) {

    $_RESULT = $data[0];

    if ($_SESSION['freedelivery'] == 0) {
        $hook['total'] = $_RESULT['total'] - $_RESULT['delivery'];
        $hook['delivery'] = 0;
        $hook['dellist'] = $_RESULT['dellist'];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['success'] = 1;

        // Совместимость с модулем ddelivery
        if (empty($GLOBALS['SysValue']['base']['ddelivery']['ddelivery_system'])) {
            return $hook;
        }
    }
}

$addHandler = array(
    'delivery' => 'delivery_promotions_hook'
);
?>