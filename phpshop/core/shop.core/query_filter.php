<?php

/**
 * Cортировка товаров
 * @author PHPShop Software
 * @version 1.6
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @return mixed
 */
function query_filter($obj) {

    $sort = null;

    if(count($obj->category_array) === 0) {
        $obj->category_array = array($obj->category);
    }

    $dop_cats = '';
    foreach ($obj->category_array as $category) {
        $dop_cats .= ' OR dop_cat LIKE \'%#' . $category . '#%\' ';
    }
    $categories_str = implode("','", $obj->category_array);

    $catt = "(category IN ('$categories_str') " . $dop_cats . " ) ";

    $v = @$_REQUEST['v'];
    $s = intval($_REQUEST['s']);
    $f = intval($_REQUEST['f']);
    $l = @$_REQUEST['l'];

    if ($obj->PHPShopNav->isPageAll())
        $p = PHPShopSecurity::TotalClean($p, 1);

    // Кол-во товаров на странице
    $num_row = $obj->PHPShopCategory->getParam('num_row');

    if (!empty($num_row))
        $num_row = $num_row;
    else // если 0 делаем по формуле кол-во колонок * 2 строки.
        $num_row = (6 - $obj->cell) * $obj->cell;

    // Сортировка по характеристикам
    if (is_array($v)) {
        foreach ($v as $key => $value) {

            // Множественный отбор [][]
            if (is_array($value)) {
                $sort.=" and (";
                foreach ($value as $v) {
                    if (PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($v)) {
                        $hash = $key . "-" . $v;
                        $sort.=" vendor REGEXP 'i" . $hash . "i' or";
                    }
                }
                $sort = substr($sort, 0, strlen($sort) - 2);
                $sort.=")";
            }
            // Обычный отбор []
            elseif (PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($value)) {
                $hash = $key . "-" . $value;
                $sort.=" and vendor REGEXP 'i" . $hash . "i' ";
            }
        }
    }
    
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
?>