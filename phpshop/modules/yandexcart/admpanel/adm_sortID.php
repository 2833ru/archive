<?php

function addYandexcartSort($data) {
    global $PHPShopGUI;

    $Tab3=$PHPShopGUI->setField(__('Яндекс'), $PHPShopGUI->setRadio('yandex_param_new', 1, __('Выключить'), $data['yandex_param'], false, 'text-warning') .
            $PHPShopGUI->setRadio('yandex_param_new', 2, __('Включить'), $data['yandex_param']),1,'Выгружать характеристику для Яндекс.Маркет');
    
    
    $Tab3.= $PHPShopGUI->setField("Единица измерения", $PHPShopGUI->setInputText(null, 'yandex_param_unit_new', $data['yandex_param_unit'], 100));

    $PHPShopGUI->addTab(array("Яндекс.Заказ", $Tab3, true));
}

$addHandler = array(
    'actionStart' => 'addYandexcartSort',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>