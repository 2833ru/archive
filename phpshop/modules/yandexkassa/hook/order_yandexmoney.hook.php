<?php

function payment_mod_yandexkassa_hook($obj) {
    
    // Настройки модуля
    include_once(dirname(__FILE__).'/mod_option.hook.php');
    $option = new PHPShopYandexkassaArray();
    $obj->value[10004] =array($option->getParam('title'),10004,false);
}


$addHandler=array
        (
        'payment'=>'payment_mod_yandexkassa_hook'
);
?>