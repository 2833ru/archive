<?php

function payment_mod_yandexmoney_hook($obj,$value) {
    
    // Настройки модуля
    include_once(dirname(__FILE__).'/mod_option.hook.php');
    $option = new PHPShopYandexmoneyArray();

    $value[]=array($option->getParam('title'),10002,false);
    $obj->set('orderOplata',PHPShopText::select('order_metod',$value,250));;
    return true;
}


$addHandler=array
        (
        'payment'=>'payment_mod_yandexmoney_hook'
);
?>