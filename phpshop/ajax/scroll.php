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
PHPShopObj::loadClass("nav");
PHPShopObj::loadClass("parser");

function ParseTemplateReturn($path){
    return PHPShopParser::file($path, true);
}

$PHPShopValutaArray = new PHPShopValutaArray();
$PHPShopSystem = new PHPShopSystem();
$PHPShopNav = new PHPShopNav();

include_once("../core/shop.core.php");
$PHPShopCore = new PHPShopShop();
$PHPShopCore->loadActions();

?>