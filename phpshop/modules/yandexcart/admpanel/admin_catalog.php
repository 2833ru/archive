<?php

function yandexcartAddCaptions()
{
    global $PHPShopInterface;

    $memory = $PHPShopInterface->getProductTableFields();

    if(isset($memory['catalog.option']['price_yandex_dbs'])) {
        $PHPShopInterface->productTableCaption[] = ["�.������ DBS", "15%", ['view' => (int) $memory['catalog.option']['price_yandex_dbs']]];
    }
    if(isset($memory['catalog.option']['price_sbermarket'])) {
        $PHPShopInterface->productTableCaption[] = ["����������", "15%", ['view' => (int) $memory['catalog.option']['price_sbermarket']]];
    }
}

$addHandler = [
    'getTableCaption' => 'yandexcartAddCaptions'
];
