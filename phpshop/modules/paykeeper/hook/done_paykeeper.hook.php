<?php

function send_to_order_paykeeper_hook($obj, $row, $rout) {
    if ($rout == 'END' and $row['order_metod'] == 10115) {

         include_once($_SERVER['DOCUMENT_ROOT'] . '/phpshop/modules/paykeeper/class/order.php');
         return true;
    }
}
$addHandler = array('send_to_order' => 'send_to_order_paykeeper_hook');
?>