<?php
@extract($_REQUEST);
session_start();

require_once "./lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

// Парсируем установочный файл
$SysValue=parse_ini_file("./inc/config.ini",1);
  while(list($section,$array)=each($SysValue))
                while(list($key,$value)=each($array))
$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))]=$value;

// Подключаем базу MySQL
@mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db'])or 
@die("".PHPSHOP_error(101,$SysValue['my']['error_tracer'])."");
mysql_select_db($SysValue['connect']['dbase'])or 
@die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
@mysql_query("SET NAMES 'cp1251'");


// Подключаем модули
include("./inc/engine.inc.php");            // Модуль движка
include("./inc/cache.inc.php");
include("./inc/sort.inc.php");

// Подключаем кеш
$LoadItems=CacheReturnBase("cart");

$category = $_REQUEST['category'];


$interfaces=DispCatSort($category);


$_RESULT = array(
  'sort' => $interfaces
); 
?>