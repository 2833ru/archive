<?php
$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Заказы
$order = $PHPShopBase->getNumRows('orders', 'where statusi=0');

// Сообщения
$message = $PHPShopBase->getNumRows('table_name37', "where enabled='0'");

// Комментарии
$comment = $PHPShopBase->getNumRows('table_name36', "where enabled != '1'");

require_once $_classPath."lib/JsHttpRequest/JsHttpRequest.php";
$JsHttpRequest = new JsHttpRequest("windows-1251");


$_RESULT = array(
    "order" => $order,
    "message" => $message,
    "comment" => $comment
);
?>