<?php

function marketplacesAddCaptions()
{
    global $PHPShopInterface;

    $memory = $PHPShopInterface->getProductTableFields();

    if(isset($memory['catalog.option']['price_google'])) {
        $PHPShopInterface->productTableCaption[] = ["G.Merchant", "15%", ['view' => (int) $memory['catalog.option']['price_google']]];
    }
    if(isset($memory['catalog.option']['price_sbermarket'])) {
        $PHPShopInterface->productTableCaption[] = ["ÑáåðÌàðêåò", "15%", ['view' => (int) $memory['catalog.option']['price_sbermarket']]];
    }
    if(isset($memory['catalog.option']['price_cdek'])) {
        $PHPShopInterface->productTableCaption[] = ["ÑÄÝÊ.ÌÀÐÊÅÒ", "15%", ['view' => (int) $memory['catalog.option']['price_cdek']]];
    }
    if(isset($memory['catalog.option']['price_aliexpress'])) {
        $PHPShopInterface->productTableCaption[] = ["AliExpress", "15%", ['view' => (int) $memory['catalog.option']['price_aliexpress']]];
    }
}

$addHandler = [
    'getTableCaption' => 'marketplacesAddCaptions'
];
