<?php

// Парсируем установочный файл
$SysValue = parse_ini_file("../../../inc/config.ini", 1);
while (list($section, $array) = each($SysValue))
    while (list($key, $value) = each($array))
        $SysValue['other'][chr(73) . chr(110) . chr(105) . ucfirst(strtolower($section)) . ucfirst(strtolower($key))] = $value;

// Подключение к БД
$link_db=mysqli_connect($SysValue['connect']['host'], $SysValue['connect']['user_db'], $SysValue['connect']['pass_db']);
mysqli_select_db($link_db,$SysValue['connect']['dbase']);

$sql = "select * from phpshop_modules_ddeliverywidget_system limit 1";
$result = mysqli_query($link_db,$sql);
$row = mysqli_fetch_array($result);
$key = $row['key']; 

//echo $key; die(0);

include_once('DDeliveryWidgetApi.php');

$widgetApi = new DDeliveryWidgetApi();

// Передайте здесь свой API-key
$widgetApi->setApiKey($key);

$widgetApi->setMethod($_SERVER['REQUEST_METHOD']);

if(!is_array($_REQUEST['data']))
$_REQUEST['data'] = array();
$widgetApi->setData($_REQUEST['data']);

header('Content-Type: text/html; charset=UTF-8');
echo $widgetApi->submit($_REQUEST['url']);