<?php

session_start();

/**
 * Информер
 * @package PHPShopAjaxElements
 */
$_classPath = "../";

// Подключаем библиотеку поддержки JsHttpRequest
if ($_REQUEST['type'] != 'json') {
    require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";
    $JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
}


// Библиотеки
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("security");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

@$fp = fopen("../../index.php", "r");
if ($fp) {
    $fstat = fstat($fp);
    fclose($fp);
    $FileDate = PHPShopDate::dataV($fstat['mtime']);
}

// Выбор файла
function GetFile($dir) {
    global $SysValue;
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            $fstat = explode(".", $file);
            if ($fstat[1] == "lic")
                return $file;
        }
        closedir($dh);
    }
}

// Срок действия тех. поддержки
$GetFile = GetFile("../../license/");
@$License = parse_ini_file("../../license/" . $GetFile, 1);

$TechPodUntilUnixTime = $License['License']['SupportExpires'];
if (is_numeric($TechPodUntilUnixTime))
    $TechPodUntil = PHPShopDate::dataV($TechPodUntilUnixTime);
else
    $TechPodUntil = " - ";

$LicenseUntilUnixTime = $License['License']['Expires'];
if (is_numeric($LicenseUntilUnixTime))
    $LicenseUntil = PHPShopDate::dataV($LicenseUntilUnixTime);
else
    $LicenseUntil = " - ";

if ($License['License']['Pro'] == 'Enabled')
    $Pro = 'Pro';
else
    $Pro = null;

if (PHPShopSecurity::true_skin($_COOKIE['bootstrap_theme']))
    $theme = ' + ' . $_COOKIE['bootstrap_theme'] . '.css';
else
    $theme = null;

$Info = "PHPShop System Info
---------------------------------------------

Версия: " . $PHPShopBase->getParam('license.product_name') . " " . $Pro . "
Сборка: " . $PHPShopBase->getParam('upload.version') . "
Дата изменения: " . $PHPShopBase->getParam('cache.last_modified') . "
Дизайн: " . $PHPShopSystem->getParam('skin') . " " . $theme . "
GZIP: " . $PHPShopBase->getParam('my.gzip') . "; Сжатие: " . $PHPShopBase->getParam('my.gzip_level') . "
Установлено: " . $FileDate . "
Окончание лицензии: " . $LicenseUntil . "
Окончание поддержки: " . $TechPodUntil . "

---------------------------------------------

Все права защищены. 2004-" . date("Y") . "
Copyright © www.phpshop.ru";

// Формируем результат прямо в виде PHP-массива!
$_RESULT = array(
    "info" => $Info,
    'success' => 1
);

// JSON 
if ($_REQUEST['type'] == 'json') {
    $_RESULT['info'] = PHPShopString::win_utf8($_RESULT['info']);
    echo json_encode($_RESULT);
}
?>