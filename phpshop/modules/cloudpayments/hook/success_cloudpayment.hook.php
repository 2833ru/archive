<?php

function success_mod_cloudpayments_hook($obj, $value) {

    if ($_REQUEST["result"] == "success") {
        $obj->order_metod = 'modules" and id="10014';

        $obj->message();
        return true;
    } else {
        $obj->error();
    }
}

$addHandler = array('index' => 'success_mod_cloudpayments_hook');
?>