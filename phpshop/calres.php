<?php
session_start();

/**
 * Календарь
 * @package PHPShopAjaxElementsDepricated
 */

$_classPath="./";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");

// Подключаем библиотеку поддержки.
require_once "./lib/Subsys/JsHttpRequest/Php.php";
require_once "./lib/parser/parser.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

// Подключаем модули
include("./inc/engine.inc.php");  
include("./inc/calendar.inc.php");

// Подключаем функцию
$calres=calendar($_REQUEST['year'],$_REQUEST['month']);

$_RESULT = array(
        'calres' => $calres,
); 
?>