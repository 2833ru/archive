<?php

function yandexcartAddOption($row)
{
    global $PHPShopInterface;

    $memory = $PHPShopInterface->getProductTableFields();

    $PHPShopInterface->productTableRow[] = [
        'name'     => $row['price_yandex_dbs'],
        'sort'     => 'price_yandex_dbs',
        'editable' => 'price_yandex_dbs_new',
        'view'     => (int) $memory['catalog.option']['price_yandex_dbs']
    ];

    $PHPShopInterface->productTableRow[] = [
        'name'     => $row['price_sbermarket'],
        'sort'     => 'price_sbermarket',
        'editable' => 'price_sbermarket_new',
        'view'     => (int) $memory['catalog.option']['price_sbermarket']
    ];
}

$addHandler = [
    'grid' => 'yandexcartAddOption'
];