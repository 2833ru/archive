<?php

/**
 * Cортировка товаров
 * @author PHPShop Software
 * @version 1.3
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
    $sortV = null;
    $sort = null;

    // Сортировка по характеристикам
    if (empty($_POST['v']))
        @$v = $SysValue['nav']['query']['v'];
    if (is_array($v))
        foreach ($v as $key => $value) {
            if (!empty($value)) {
                $hash = $key . "-" . $value;
                $sortV.=" and vendor REGEXP 'i" . $hash . "i' ";
                $sortQuery .= "&v[$key]=$value";
            }
        }

    // Сортировка принудительная пользователем
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
        case(2): $order = array('order' => 'price' . $order_direction);
            break;
        case(3): $order = array('order' => 'num' . $order_direction);
            break;
        default: $order = array('order' => 'num' . $order_direction);
    }

    // Преобзазуем массив уловия сортировки в строку
    foreach ($order as $key => $val)
        $string = $key . ' by ' . $val;

    // Все страницы
    if ($p == "all") {
        $sql = "select * from " . $SysValue['base']['table_name2'] . " where enabled='1' $sortV  $string and parent_enabled='0' $string";
    }
    else
        while ($q < $p) {

            $sql = "select * from " . $SysValue['base']['table_name2'] . " where enabled='1' and parent_enabled='0' $sortV  $string LIMIT $num_ot, $num_row";
            $q++;
            $num_ot = $num_ot + $num_row;
        }

    $obj->selection_order = array(
        'sortQuery' => $sortQuery,
        'sortV' => $sortV
    );

    // Возвращаем SQL запрос
    return $sql;
}

?>