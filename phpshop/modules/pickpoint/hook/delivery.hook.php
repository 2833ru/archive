<?php

include_once dirname(__DIR__) . '/class/PickPoint.php';

/**
 * Õóê
 */
function pickpoin_delivery_hook($obj, $data) {

    $_RESULT = $data[0];
    $xid = $data[1];

    $PickPoint = new PickPoint();

    if ((int) $xid === (int) $PickPoint->getPickpointDeliveryId()) {
        $hook['dellist']=$_RESULT['dellist'];
        $hook['hook']='PickPoint.open(pickpoint_phpshop);';
        $hook['delivery'] = $_RESULT['delivery'];
        $hook['total'] = $_RESULT['total'];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['success'] = 1;

        return $hook;
    }
}

$addHandler = array('delivery' => 'pickpoin_delivery_hook');
?>
