<?php

/**
 * Промоакции
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("user");

// Подключаем библиотеку поддержки JsHttpRequest
if($_REQUEST['type'] != 'json'){
require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
}
else{
    $_REQUEST['messages']=PHPShopString::utf8_win1251($_REQUEST['messages']);
}

$success = 1;

// Функции для заказа
$PHPShopOrder = new PHPShopOrderFunction();
// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");
// Системные настройки
$PHPShopSystem = new PHPShopSystem();


if($_REQUEST['messages']==1) {
    $_SESSION['messagesNoView'] = 1;
}

// Результат
$_RESULT = array(
    'success' => 1
);


// JSON 
if($_REQUEST['type'] == 'json') {
    //$_RESULT['pointBalance']=PHPShopString::win_utf8($_RESULT['pointBalance']);
}
    echo json_encode($_RESULT);
?>