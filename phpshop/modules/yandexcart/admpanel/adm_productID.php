<?php

function addYandexcartCPA($data) {
    global $PHPShopGUI;

    $PHPShopGUI->addJSFiles('../modules/yandexcart/admpanel/gui/yandexcart.gui.js');

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexcart']['yandexcart_system']);
    $options = $PHPShopOrm->select();

    $Tab3 = '';
    if($options['model'] === 'DBS') {
        $Tab3 .=  $PHPShopGUI->setField('CPA модель',
            $PHPShopGUI->setRadio('cpa_new', 1,'Включить', $data['cpa']) .
            $PHPShopGUI->setRadio('cpa_new', 0,'Выключить', $data['cpa']) .
            $PHPShopGUI->setRadio('cpa_new', 2,'Не использовать CPA', $data['cpa'], false, 'text-muted')
        );
    }

    // Валюты
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $valuta_def_name = $val['code'];
            }
        }

    if((int) $data['yml'] === 1) {
        $Tab3 .= $PHPShopGUI->setField('Цена Яндекс.Маркет DBS', $PHPShopGUI->setInputText(null, 'price_yandex_dbs_new', $data['price_yandex_dbs'], 150, $valuta_def_name), 2);
    }

    $Tab3 .= $PHPShopGUI->setField("Модель", $PHPShopGUI->setInputText(null, 'model_new', $data['model'], 300), 1, 'Тег model');
    $Tab3 .= $PHPShopGUI->setField('Гарантия', $PHPShopGUI->setRadio('manufacturer_warranty_new', 1, 'Включить', $data['manufacturer_warranty']) . $PHPShopGUI->setRadio('manufacturer_warranty_new', 2, 'Выключить', $data['manufacturer_warranty'], false, 'text-muted'), 1, 'Тег manufacturer_warranty');

    $Tab3 .= $PHPShopGUI->setField("Имя производителя", $PHPShopGUI->setInputText(null, 'vendor_name_new', $data['vendor_name'], 300), 1, 'Тег vendor');

    $Tab3 .= $PHPShopGUI->setField("Код производителя", $PHPShopGUI->setInputText(null, 'vendor_code_new', $data['vendor_code'], 300), 1, 'Тег vendorCode');

    $Tab3 .= $PHPShopGUI->setField("Компания производитель, адрес и рег. номер (если есть)", $PHPShopGUI->setInputText(null, 'manufacturer_new', $data['manufacturer'], 300), 1, 'Тег manufacturer');
    
    $Tab3 .= $PHPShopGUI->setField("Штрихкод", $PHPShopGUI->setInputText(null, 'barcode_new', $data['barcode'], 300), 1, 'Тег barcode');

    $Tab3 .= $PHPShopGUI->setField("Комментарий", $PHPShopGUI->setInputText(null, 'sales_notes_new', $data['sales_notes'], 300), 1, 'Тег sales_notes');

    $Tab3 .= $PHPShopGUI->setField("Страна производства", $PHPShopGUI->setInputText(null, 'country_of_origin_new', $data['country_of_origin'], 300), 1, 'Тег country_of_origin');

    $Tab3 .= $PHPShopGUI->setField("Идентификатор товара на Яндексе", $PHPShopGUI->setInputText(null, 'market_sku_new', $data['market_sku'], 300), 1, 'Тег market-sku для модели FBS, можно получить в личном кабинете Яндекс.Маркета');

    $Tab3 .= $PHPShopGUI->setField('Товар для взрослых', $PHPShopGUI->setRadio('adult_new', 1, 'Включить', $data['adult']) . $PHPShopGUI->setRadio('adult_new', 2, 'Выключить', $data['adult'], false, 'text-muted'), 1, 'Тег adult');

    $condition[] = array(__('Новый товар'), 1, $data['yandex_condition']);
    $condition[] = array(__('Как новый'), 2, $data['yandex_condition']);
    $condition[] = array(__('Подержанный'), 3, $data['yandex_condition']);

    $Tab3 .= $PHPShopGUI->setField('Состояние товара', $PHPShopGUI->setSelect('yandex_condition_new', $condition,300), 1, 'Тег condition');
    
    $Tab3 .= $PHPShopGUI->setField('Причина уценки', $PHPShopGUI->setTextarea('yandex_condition_reason_new', $data['yandex_condition_reason']), 1, 'Тег reason');

    $Tab3 .= $PHPShopGUI->setField('Курьерская доставка', $PHPShopGUI->setRadio('delivery_new', 1, 'Включить', $data['delivery']) . $PHPShopGUI->setRadio('delivery_new', 2, 'Выключить', $data['delivery'], false, 'text-muted'), 1, 'Тег delivery');

    $Tab3 .= $PHPShopGUI->setField('Самовывоз', $PHPShopGUI->setRadio('pickup_new', 1, 'Включить', $data['pickup']) . $PHPShopGUI->setRadio('pickup_new', 2, 'Выключить', $data['pickup'], false, 'text-muted'), 1, 'Тег pickup');

    $Tab3 .= $PHPShopGUI->setField('Покупка в розничном магазине', $PHPShopGUI->setRadio('store_new', 1, 'Включить', $data['store']) . $PHPShopGUI->setRadio('store_new', 2, 'Выключить', $data['store'], false, 'text-muted'), 1, 'Тег store');

    $Tab3 .= $PHPShopGUI->setField("Минимальное количество", $PHPShopGUI->setInputText(null, 'yandex_min_quantity_new', $data['yandex_min_quantity'], 100), 1, ' Минимальное количество товара в одном заказе');

    $Tab3 .= $PHPShopGUI->setField("Минимальный шаг", $PHPShopGUI->setInputText(null, 'yandex_step_quantity_new', $data['yandex_step_quantity'], 100), 1, ' Количество товара, добавляемое к минимальному');

    $PHPShopGUI->addTab(array("Яндекс", $Tab3, true));
}

function addYandexCartOptions($data) {
    global $PHPShopGUI;

    $PHPShopGUI->field_col = 3;

    $Tab = $PHPShopGUI->setField("Штрихкод", $PHPShopGUI->setInputText(null, 'barcode_new', $data['barcode'], 300), 1, 'Тег barcode');
    $Tab .= $PHPShopGUI->setField("Код производителя", $PHPShopGUI->setInputText(null, 'vendor_code_new', $data['vendor_code'], 300), 1, 'Тег vendorCode');

    $PHPShopGUI->addTab(["Яндекс", $Tab, true]);
}

$addHandler = array(
    'actionStart' => 'addYandexcartCPA',
    'actionDelete' => false,
    'actionUpdate' => false,
    'actionOptionEdit' => 'addYandexCartOptions'
);
?>