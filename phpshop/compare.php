<?php
session_start();

/**
 * Сравнение товаров
 * @package PHPShopAjaxElementsDepricated
 */

$_classPath="./";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("compare");

// Подключаем библиотеку поддержки.
require_once "./lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

// Получаем запрос.
$q = $_REQUEST['q'];
$xid = $_REQUEST['xid'];
$_num = $_REQUEST['num'];

//Получаем входящее количество товаров для сравнения
$compar=count($_SESSION['compare']);

// Класс сравнения
$PHPShopCompare = new PHPShopCompare();

// Добавлем товар
$PHPShopCompare->add($xid);

if ($compar==$PHPShopCompare->getNum()) {$same='1';} else {$same='0';}

// Формируем результат
$_RESULT = array(
  "q"     => $q,
  "num"   => $PHPShopCompare->getNum(),
  "same"   => $same
); 
?>