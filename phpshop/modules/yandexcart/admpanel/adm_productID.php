<?php

function addYandexcartCPA($data) {
    global $PHPShopGUI;

    $PHPShopGUI->addJSFiles('../modules/yandexcart/admpanel/gui/yandexcart.gui.js');

    $Tab3 = $PHPShopGUI->setField("Ставка Fee", $PHPShopGUI->setInputText(null, 'fee_new', $data['fee'], 100), 1, '1% комиссии соответствует значению 100');

    $Tab3.=$PHPShopGUI->setField(__('CPA модель'), $PHPShopGUI->setRadio('cpa_new', 1, __('Включить'), $data['cpa']) . $PHPShopGUI->setRadio('cpa_new', 2, __('Выключить'), $data['cpa'], false, 'text-muted'));

    $Tab3.=$PHPShopGUI->setField(__('Гарантия производителя'), $PHPShopGUI->setRadio('manufacturer_warranty_new', 1, __('Включить'), $data['manufacturer_warranty']) . $PHPShopGUI->setRadio('manufacturer_warranty_new', 2, __('Выключить'), $data['manufacturer_warranty'], false, 'text-muted'), 1, 'Тег <manufacturer_warranty>');

    $Tab3.= $PHPShopGUI->setField("Имя производителя", $PHPShopGUI->setInputText(null, 'vendor_name_new', $data['vendor_name'], 300), 1, 'Тег <vendor>');

    $Tab3.= $PHPShopGUI->setField("Код производителя", $PHPShopGUI->setInputText(null, 'vendor_code_new', $data['vendor_code'], 300), 1, 'Тег <vendorCode>');

    $Tab3.= $PHPShopGUI->setField("Комментарий", $PHPShopGUI->setInputText(null, 'sales_notes_new', $data['sales_notes'], 300), 1, 'Тег <sales_notes>');

    $Tab3.= $PHPShopGUI->setField("Страна производства", $PHPShopGUI->setInputText(null, 'country_of_origin_new', $data['country_of_origin'], 300), 1, 'Тег <country_of_origin>');

    $Tab3.=$PHPShopGUI->setField(__('Товар для взрослых'), $PHPShopGUI->setRadio('adult_new', 1, __('Включить'), $data['adult']) . $PHPShopGUI->setRadio('adult_new', 2, __('Выключить'), $data['adult'], false, 'text-muted'), 1, 'Тег <adult>');

    $Tab3.=$PHPShopGUI->setField(__('Курьерская доставка'), $PHPShopGUI->setRadio('delivery_new', 1, __('Включить'), $data['delivery']) . $PHPShopGUI->setRadio('delivery_new', 2, __('Выключить'), $data['delivery'], false, 'text-muted'), 1, 'Тег <delivery>');

    $Tab3.=$PHPShopGUI->setField(__('Самовывоз'), $PHPShopGUI->setRadio('pickup_new', 1, __('Включить'), $data['pickup']) . $PHPShopGUI->setRadio('pickup_new', 2, __('Выключить'), $data['pickup'], false, 'text-muted'), 1, 'Тег <pickup>');

    $Tab3.=$PHPShopGUI->setField(__('Покупка в розничном магазине'), $PHPShopGUI->setRadio('store_new', 1, __('Включить'), $data['store']) . $PHPShopGUI->setRadio('store_new', 2, __('Выключить'), $data['store'], false, 'text-muted'), 1, 'Тег <store>');

    $Tab3.= $PHPShopGUI->setField("Минимальное количество", $PHPShopGUI->setInputText(null, 'yandex_min_quantity_new', $data['yandex_min_quantity'], 100), 1, ' Минимальное количество товара в одном заказе');

    $Tab3.= $PHPShopGUI->setField("Минимальный шаг", $PHPShopGUI->setInputText(null, 'yandex_step_quantity_new', $data['yandex_step_quantity'], 100), 1, ' Количество товара, добавляемое к минимальному');


    $PHPShopGUI->addTab(array("Яндекс.Заказ", $Tab3, true));
}

$addHandler = array(
    'actionStart' => 'addYandexcartCPA',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>