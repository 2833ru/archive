<?php

function success_mod_Assistmoney_hook($obj, $value) {
    if ($_GET['payment_name'] == 'assist') {
        
        $return=array();
        $return['order_metod']='modules';
        $return['success_function']=false;// Включаем функцию обновления статуса заказа
        $return['crc'] = null;
        $return['my_crc'] = null;
        $return['inv_id'] = $_GET['ordernumber'];
        $return['out_summ'] = false;
        
        return $return;
    }
}

$addHandler = array
    (
    'index' => 'success_mod_Assistmoney_hook'
);

?>