<?php

/**
 * Cортировка товаров по бренду
 * @author PHPShop Software
 * @version 1.4
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @return mixed
 */
function query_filter($obj) {
    global $SysValue;

    if (!empty($_REQUEST['v']))
        $v = $_REQUEST['v'];
    else
        $v = null;

    $s = intval(@$_REQUEST['s']);
    $f = intval(@$_REQUEST['f']);

    if (!empty($_REQUEST['set']))
        $set = intval($_REQUEST['set']);
    else
        $set = 2;


    if (!empty($_REQUEST['p']))
        $p = intval($_REQUEST['p']);
    else
        $p = 1;

    $num_row = $obj->num_row;
    $num_ot = 0;
    $q = 0;
    $sort =  $sortQuery = null;


    // Сортировка по характеристикам
    if (is_array($v)) {
        $sort.= ' and (';
        foreach ($v as $key => $value) {

            // Обычный отбор []
            if (PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($value)) {
                $hash = $key . "-" . $value;
                $sort.=" vendor REGEXP 'i" . $hash . "i' or";
                $sortQuery .= "&v[$key]=$value";
            }
        }
        $sort = substr($sort, 0, strlen($sort) - 2);
        $sort.=")";
    }


    // Сортировка принудительная пользователем
    switch ($f) {
        case(1): $order_direction = "";
            break;
        case(2): $order_direction = " desc";
            break;
        default: $order_direction = " desc";
            break;
    }
    switch ($s) {
        case(1): $order = array('order' => 'name' . $order_direction);
            break;
        case(2): $order = array('order' => 'price' . $order_direction);
            break;
        case(3): $order = array('order' => 'num' . $order_direction);
            break;
        default: $order = array('order' => 'num, name' . $order_direction);
    }

    // Преобзазуем массив уловия сортировки в строку
    foreach ($order as $key => $val)
        $string = $key . ' by ' . $val;

    // Все страницы
    if ($p == "all") {
        $sql = "select * from " . $SysValue['base']['products'] . " where enabled='1' and parent_enabled='0' $sort  $string";
    }
    else
        while ($q < $p) {

            $sql = "select * from " . $SysValue['base']['products'] . " where enabled='1' and parent_enabled='0' $sort  $string LIMIT $num_ot, $num_row";
            $q++;
            $num_ot = $num_ot + $num_row;
        }

    $obj->selection_order = array(
        'sortQuery' => $sortQuery,
        'sortV' => $sort
    );

    // Возвращаем SQL запрос
    return $sql;
}

?>