<?php

function yandexcartAddCaptions()
{
    global $PHPShopInterface;

    $memory = $PHPShopInterface->getProductTableFields();

    if(isset($memory['catalog.option']['price_yandex_dbs'])) {
        $PHPShopInterface->productTableCaption[] = ["Я.Маркет DBS", "15%", ['view' => (int) $memory['catalog.option']['price_yandex_dbs']]];
    }
    if(isset($memory['catalog.option']['price_sbermarket'])) {
        $PHPShopInterface->productTableCaption[] = ["СберМаркет", "15%", ['view' => (int) $memory['catalog.option']['price_sbermarket']]];
    }
}

$addHandler = [
    'getTableCaption' => 'yandexcartAddCaptions'
];
