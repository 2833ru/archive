<?php

/**
 * Корзина
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../";
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
PHPShopObj::loadClass("lang");


// Подключаем библиотеку поддержки JsHttpRequest
if($_REQUEST['type'] != 'json'){
require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
}
else{
    $_REQUEST['addname']=PHPShopString::utf8_win1251($_REQUEST['addname']);
}

// Массив валют
$PHPShopValutaArray = new PHPShopValutaArray();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

$PHPShopLang = new PHPShopLang(array('locale'=>$_SESSION['lang'],'path'=>'shop'));

// Корзина
$PHPShopCart = new PHPShopCart();

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Добавлем товар
if (PHPShopSecurity::true_param($_REQUEST['xid'], $_REQUEST['num'])) {
    $productData = $PHPShopCart->add($_REQUEST['xid'], $_REQUEST['num'], $_REQUEST['xxid']);
}

// Дата обновления корзины
setcookie("cart_update_time", time(), 0, "/", $_SERVER['SERVER_NAME'], 0);

// Формируем результат
$_RESULT = array(
    "num" => $PHPShopCart->getNum(),
    "sum" => $PHPShopCart->getSum(),
    "message" => $PHPShopCart->getMessage(),
    "success" => 1
);

// Перехват модуля в начале функции
$hook = $PHPShopModules->setHookHandler('cartload', 'cartload', false, array($_RESULT, $_REQUEST['xid']));
if(is_array($hook))
    $_RESULT = $hook;

// JSON 
if($_REQUEST['type'] == 'json'){
    $_RESULT['message']=PHPShopString::win_utf8($_RESULT['message']);
    echo json_encode($_RESULT);
}
?>