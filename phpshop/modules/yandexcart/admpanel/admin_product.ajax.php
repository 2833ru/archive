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
}

function yandexAddLabels($product) {
    global $PHPShopInterface;

    $memory = $PHPShopInterface->getProductTableFields();

    // Постфикс
    if (!empty($_GET['cat']))
        $postfix = '&cat=' . (int) $_GET['cat'];
    else
        $postfix = null;

    if ((int) $product['yml'] === 1 && (int) $memory['catalog.option']['label_yandex_market'] === 1)
        $PHPShopInterface->productTableRowLabels[] = '<a class="label label-success" title="' . __('Вывод в Яндекс.Маркете') . '" href="?path=catalog' . $postfix . '&where[yml]=1">' . __('Я') . '</a> ';
}

$addHandler = [
    'grid'   => 'yandexcartAddOption',
    'labels' => 'yandexAddLabels'
];