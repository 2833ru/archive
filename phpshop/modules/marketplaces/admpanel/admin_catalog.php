<?php

function marketplacesAddCaptions()
{
    global $PHPShopInterface;

    $memory = $PHPShopInterface->getProductTableFields();

    if(isset($memory['catalog.option']['price_google'])) {
        $PHPShopInterface->productTableCaption[] = ["G.Merchant", "15%", ['view' => (int) $memory['catalog.option']['price_google']]];
    }
    if(isset($memory['catalog.option']['price_sbermarket'])) {
        $PHPShopInterface->productTableCaption[] = ["СберМаркет", "15%", ['view' => (int) $memory['catalog.option']['price_sbermarket']]];
    }
    if(isset($memory['catalog.option']['price_aliexpress'])) {
        $PHPShopInterface->productTableCaption[] = ["AliExpress", "15%", ['view' => (int) $memory['catalog.option']['price_aliexpress']]];
    }
    if(isset($memory['catalog.option']['price_cdek'])) {
        $PHPShopInterface->productTableCaption[] = ["Я.Маркет", "15%", ['view' => (int) $memory['catalog.option']['price_cdek']]];
    }
}

$addHandler = [
    'getTableCaption' => 'marketplacesAddCaptions'
];
