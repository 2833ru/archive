<?php

/*
 * **** 
 * Модуль "СМАРТ-ФИЛЬТР" для PHPShop Enterprise 3.6
 * Copyright © WEB for ALL, 20010-2014 
 * @author "WEB for ALL" (www.web4.su) 
 * @version 1.0
 * ****
 */
/*
 * Функционал подсчета товаров в выборке на AJAX
 */
session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("user");
PHPShopObj::loadClass('order');
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("text");

// Подключаем библиотеку поддержки.
require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");

// Массив валют
$PHPShopValutaArray = new PHPShopValutaArray();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

$PHPShopModules = new PHPShopModules('../../');
$PHPShopModules->checkInstall('w4asmartfilter');

// получам данные формы фильтра по аналог: $_GET['key_N']=val_N
// data0 - key_1,key_2,...key_N 
// data1 - val_1,val_2,...val_N
// pref - префикс для имен полей характеристик в форме 

$data0 = $_REQUEST['data0'];
$data1 = $_REQUEST['data1'];
$pref = $_REQUEST['pref'];


if(strstr($data0,','))
$key_arr = explode(',', $data0);

if(strstr($data1,','))
$val_arr = explode(',', $data1);

if (is_array($key_arr))
    foreach ($key_arr as $key => $val) {
        $data[$val] = $val_arr[$key];
    }

if (is_array($data)) {
    $sql = "select count(id) as count from " . $GLOBALS['SysValue']['base']['table_name2'] . " where " . sql_sort(false, $data);
    $row = mysql_fetch_array(mysql_query($sql));
    $prod_num = $row['count'];
}

if (empty($prod_num))
    $prod_num = 0;

$_RESULT = array(
    "msg" => $data0,
    "item0" => $prod_num
);

function sql_sort($flag = false, $data) {

    $smart_arr = get_smart($data);
    $price_arr = get_price($data);

    if (is_array($smart_arr)) { // если есть GET-параметры характеристик 
        $ii = 0;
        foreach ($smart_arr as $key => $val) {

            $i = 0;
            foreach ($val as $k => $v) {

                if ($i == 0) {
                    $sort_or = "var_id_" . intval($key) . "=" . intval($k);
                } else {
                    $sort_or .= " OR var_id_" . intval($key) . "=" . intval($k);
                }

                $i++;
            }

            if ($ii == 0) {
                $sort = "($sort_or)";
            } else {
                $sort .= " AND ($sort_or)";
            }
            $sort_or = '';
            $ii++;
        }

        if (is_array($price_arr)) {

            $price = " AND price between " . intval($price_arr['from']) . " and " . intval($price_arr['till']);
        } else {
            $price = '';
        }

        if ($flag === false) {
            $sql = "id in(
					SELECT product_id
					FROM `phpshop_modules_w4asmartfilter`
					WHERE $sort $price
					GROUP BY product_id
					)";
        } else {
            $sql = "id in(
					SELECT product_id
					FROM `phpshop_modules_w4asmartfilter`
					WHERE $sort $price
					GROUP BY product_id
					)";
        }
    }
    else
        $sql = false;

    return $sql;
}

/**
 * возвращает массив цен ОТ и ДО для выборки из GET-параметров
 * $pice_arr=false - по дефолту (не участвует цена)
 * $pice_arr(from=>0,till=>1000) - от 0 до 1000
 * $pice_arr(from=>1000,till=>1000) - равен 1000
 * false - в GET-параметрах отсутствует значения цены или они некорректны
 */
function get_price($data) {

    // ключи относящиеся к ценам ps & pf (price start & price finish)
    $ps = $data['ps'];
    $pf = $data['pf'];

    if (empty($pf) or $pf < $ps or $pf <= 0) { // если введенные данные в поле цен некорректы либо отсутсвуют
        $price_arr = false;
    } else {
        if (empty($ps) or $ps < 0)
            $ps = 0;
        $price_arr = array('from' => $ps, 'till' => $pf);
    }

    return $price_arr;
}

/**
 * возвращает массив характеристик из GET-параметров
 * $smart_arr[k1][k2]
 * k1 - # поля в таблице smart-фильтра
 * k2 - ID значения характеристики
 * false - в GET-параметрах отсутствует значения характеристик
 */
function get_smart($data) {
    global $pref;
    // собираем массив характеристик фильтра из GET- параметров
    foreach ($data as $key => $val) {

        // если ключ содержит префикс относящийся к смарт-фильтру
        if (strpos($key, $pref) !== false) {
            $k1 = $val;
            $k2 = str_replace($pref, '', $key);

            $smart_arr[$k1][$k2] = 'true';
        }
    }
    if (is_array($smart_arr))
        return $smart_arr;
    else
        return false;
}

?>
