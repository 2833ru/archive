<?php

function yandexcartAddOption()
{
    global $PHPShopInterface;

    $memory = $PHPShopInterface->getProductTableFields();

    $PHPShopInterface->_CODE .= __('Яндекс.Маркет') . '<br>';
    $PHPShopInterface->_CODE .= $PHPShopInterface->setCheckbox('price_yandex_dbs', 1, 'Цена Яндекс.Маркет DBS', $memory['catalog.option']['price_yandex_dbs']);
    $PHPShopInterface->_CODE .= $PHPShopInterface->setCheckbox('label_yandex_market', 1, 'Лейбл Яндекс.Маркет', $memory['catalog.option']['label_yandex_market']);
}

$addHandler = [
    'actionOption' => 'yandexcartAddOption'
];
