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
@$License = parse_ini_file_true("../../license/" . $GetFile, 1);

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

if ($License['License']['Pro'] == 'Start') {
    $product_name = 'Basic';
} else {
    if ($License['License']['Pro'] == 'Enabled')
        $product_name = 'Pro 1C';
    else
        $product_name = 'Enterprise';
}

if (PHPShopSecurity::true_skin($_COOKIE['bootstrap_theme']))
    $theme = ' + ' . $_COOKIE['bootstrap_theme'] . '.css';
else
    $theme = null;


$version = null;
foreach (str_split($GLOBALS['SysValue']['upload']['version']) as $w)
    $version.=$w . '.';

if(empty($License['License']['DomenLocked']))
    $License['License']['DomenLocked']='-';

$Info = "Информация о программе
---------------------------------------------

Версия: PHPShop " . $product_name . "
Сборка: " . substr($version, 0, strlen($version)-1). "
Дизайн: " . $PHPShopSystem->getParam('skin') . " " . $theme . "
Установлено: " . $FileDate . "
Окончание лицензии: " . $LicenseUntil . "
Окончание поддержки: " . $TechPodUntil . "
Ограничение на домен: ".$License['License']['DomenLocked']."

---------------------------------------------

Copyright © PHPShop™, 2004-" . date("Y") . ". 
Все права защищены  ООО \"ПХПШОП\"";

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