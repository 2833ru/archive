<?php

function yandexcartAddOption()
{
    global $PHPShopInterface;

    $memory = $PHPShopInterface->getProductTableFields();

    $PHPShopInterface->_CODE .= __('Яндекс.Маркет') . '<br>';
    $PHPShopInterface->_CODE .= $PHPShopInterface->setCheckbox('price_yandex_dbs', 1, 'Цена Яндекс.Маркет DBS', $memory['catalog.option']['price_yandex_dbs']);
    $PHPShopInterface->_CODE .= $PHPShopInterface->setCheckbox('price_sbermarket', 1, 'Цена СберМаркет', $memory['catalog.option']['price_sbermarket']);
}

$addHandler = [
    'actionOption' => 'yandexcartAddOption'
];
