<?php

/**
 * Telegram Bot
 * @package PHPShopRest
 * @author PHPShop Software
 * @version 1.0
 */
$_classPath = '../';
include($_classPath . 'phpshop/class/obj.class.php');
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("bot");
PHPShopObj::loadClass("string");

$PHPShopBase = new PHPShopBase($_classPath . "phpshop/inc/config.ini", true, true);

// ¬ход€щие данные
$body = file_get_contents('php://input');
$chat = json_decode($body, true);

if (is_array($chat)) {
    $bot = new PHPShopTelegramBot();
    $bot->init($chat['message']);
    exit('ok');
}
?>