<?php

function success_mod_netpay_hook($obj, $value) {

    if (isset($_GET['from']) and ($_GET['from']=='netpay') {
        $obj->order_metod = 'modules" and id="10017';
        $obj->message();
        return true;
    }
}

$addHandler = array('index' => 'success_mod_netpay_hook');
?>
