<?php

include_once dirname(__DIR__) . '/class/include.php';

function query_filter_elastic_hook($obj)
{
    if(count($obj->category_array) === 0) {
        $obj->category_array = [$obj->category];
    }

    $client = new ElasticClient();
    $size = (int) $obj->PHPShopCategory->getParam('num_row');
    if($size === 0) {
        $size = $obj->PHPShopSystem->getParam('num_row');
    }

    if (empty($obj->page)) {
        $from = 0;
    } else {
        $from = $size * ($obj->page - 1);
    }

    $filter = [
        'size' => $size,
        'from' => $from,
        'query' => [
            'bool' => [
                'filter' => [
                    [
                        'terms' => [
                            'categories' => $obj->category_array
                        ]
                    ],
                    [
                        'term' => [
                            'active' => true
                        ]
                    ],
                ],
            ]
        ]
    ];

    if (is_array($_REQUEST['v'])) {
        foreach ($_REQUEST['v'] as $attributeId => $values) {
            if (!is_array($values)) {
                $values = [$values];
            }

            $filter['query']['bool']['filter'][] = [
                'nested' => [
                    'path' => 'attributes',
                    'query' => [
                        'bool' => [
                            'must' => [
                                [
                                    'bool' => [
                                        'must' => [
                                            [
                                                'match' => [
                                                    'attributes.id' => $attributeId
                                                ]
                                            ],
                                            [
                                                'terms' => [
                                                    'attributes.values' => $values
                                                ]
                                            ],
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
    }

    $result = $client->searchByQuery($filter);






    $sort = null;

    $s = (int) $_REQUEST['s'];
    $f = (int) $_REQUEST['f'];
    $l = $_REQUEST['l'];

    // Сортировка по алфавиту ?l=a
    if(!empty($l)){
        $sort.= " and name LIKE '".strtoupper(substr(urldecode($l),0,1))."%' ";
    }

    // Направление сортировки из настроек каталога. Вторая часть логики в sort.class.php
    if (empty($f))
        switch ($obj->PHPShopCategory->getParam('order_to')) {
            case(1): $order_direction = "";
                $obj->set('productSortImg', 1);
                break;
            case(2): $order_direction = " desc";
                $obj->set('productSortImg', 2);
                break;
            default: $order_direction = "";
                $obj->set('productSortImg', 1);
                break;
        }


    // Сортировки из настроек каталога. Вторая часть логики в sort.class.php
    if (empty($s))
        switch ($obj->PHPShopCategory->getParam('order_by')) {
            case(1): $order = array('order' => 'name' . $order_direction);
                $obj->set('productSortA', 'sortActiv');
                break;
            case(2):
                // Сортировка по цене среди мультивалютных товаров
                if ($obj->multi_currency_search)
                    $order = array('order' => 'price_search' . $order_direction . ',' . $obj->PHPShopSystem->getPriceColumn() . $order_direction);
                else
                    $order = array('order' => $obj->PHPShopSystem->getPriceColumn() . $order_direction);

                $obj->set('productSortB', 'sortActiv');
                break;
            case(3): $order = array('order' => 'num' . $order_direction . ", items desc");
                $obj->set('productSortC', 'sortActiv');
                break;
            default: $order = array('order' => 'num' . $order_direction . ", items desc");
                $obj->set('productSortC', 'sortActiv');
                break;
        }

    // Сортировка принудительная пользователем
    if ($s or $f) {
        switch ($f) {
            case(1): $order_direction = "";

                break;
            case(2): $order_direction = " desc";
                break;
            default: $order_direction = "";
                break;
        }
        switch ($s) {
            case(1): $order = array('order' => 'name' . $order_direction);
                break;
            case(2):
                // Сортировка по цене среди мультивалютных товаров
                if ($obj->multi_currency_search)
                    $order = array('order' => 'price_search' . $order_direction . ',' . $obj->PHPShopSystem->getPriceColumn() . $order_direction);
                else
                    $order = array('order' => $obj->PHPShopSystem->getPriceColumn() . $order_direction);
                break;
            case(3): $order = array('order' => 'num' . $order_direction);
                break;
            case(4): $order = array('order' => 'discount ' . $order_direction);
                break;
            default: $order = array('order' => 'num, name' . $order_direction);
        }
    }

    // Преобзазуем массив уловия сортировки в строку
    foreach ($order as $key => $val)
        $string = $key . ' by ' . $val;

    // Поиск по цене
    if (PHPShopSecurity::true_param($_REQUEST['min'], $_REQUEST['max'])) {

        $priceOT = intval($_REQUEST['min']) - 1;
        $priceDO = intval($_REQUEST['max']) + 1;

        $percent = $obj->PHPShopSystem->getValue('percent');

        if (empty($priceDO))
            $priceDO = 1000000000;


        // Цена с учетом выбранной валюты
        $priceOT/=$obj->currency('kurs');
        $priceDO/=$obj->currency('kurs');

        // Сортировки по прайсу среди мультивалютных товаров
        if ($obj->multi_currency_search)
            $sort.= " and (price_search BETWEEN " . ($priceOT / (100 + $percent) * 100) . " AND " . ($priceDO / (100 + $percent) * 100) . ") ";
        else
            $sort.= " and (" . $obj->PHPShopSystem->getPriceColumn() ." BETWEEN " . ($priceOT / (100 + $percent) * 100) . " AND " . ($priceDO / (100 + $percent) * 100) . ") ";
    }

    return array('sql' => $catt . " and enabled='1' and parent_enabled='0' " . $sort . $string);
}

$addHandler = [
    '#query_filter' => 'query_filter_elastic_hook'
];