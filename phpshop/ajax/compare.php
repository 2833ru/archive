<?php

/**
 * Сравнение товаров
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("compare");
PHPShopObj::loadClass("lang");

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Подключаем библиотеку поддержки JsHttpRequest
if ($_REQUEST['type'] != 'json') {
    require_once $_classPath . "lib/Subsys/JsHttpRequest/Php.php";
    $JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
}

// Получаем запрос.
$xid = intval($_REQUEST['xid']);

//Получаем входящее количество товаров для сравнения
$compar = count($_SESSION['compare']);

$PHPShopLang = new PHPShopLang(array('locale' => $_SESSION['lang'], 'path' => 'shop'));

// Класс сравнения
$PHPShopCompare = new PHPShopCompare();

// Добавлем товар
$PHPShopCompare->add($xid);

if ($compar == $PHPShopCompare->getNum()) {
    $same = '1';
} else {
    $same = '0';
}

// Формируем результат
$_RESULT = array(
    "num" => $PHPShopCompare->getNum(),
    "same" => $same,
    "message" => $PHPShopCompare->getMessage(),
    "success"=>1
);


if ($_REQUEST['type'] == 'json'){
    $_RESULT['message']=PHPShopString::win_utf8($_RESULT['message']);
    echo json_encode($_RESULT);
}
?>