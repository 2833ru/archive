<?php

/**
 * VK Bot
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
PHPShopObj::loadClass("lang");

$PHPShopBase = new PHPShopBase($_classPath . "phpshop/inc/config.ini", true, true);
$PHPShopLang = new PHPShopLang(array('locale' => $_SESSION['lang'], 'path' => 'shop'));

// �������� ������
$body = file_get_contents('php://input');
$chat = json_decode($body, true);

if (is_array($chat)) {

    $bot = new PHPShopVKBot();

    if ($chat['type'] == 'confirmation' and $chat['secret'] == $bot->secret) {
        echo $bot->confirmation;
    } else{
        $bot->init($chat);
        exit('ok'); 
    }
}
?>