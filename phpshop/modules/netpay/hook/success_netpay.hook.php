<?php

function success_mod_nextpay_hook($obj, $value) {

    if ($_GET['from'] == 'netpay') {
        $obj->order_metod = 'modules" and id="10017';
        $obj->message();
        return true;
    }
}

$addHandler = array('index' => 'success_mod_nextpay_hook');
?>