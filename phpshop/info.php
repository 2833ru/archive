<?php
session_start();

/**
 * Информер
 * @package PHPShopAjaxElementsDepricated
 */

require_once "./lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

// Библиотеки
include("./class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("date");

// Подключение к БД
$PHPShopBase = new PHPShopBase("../phpshop/inc/config.ini");

// Системные настройки
$PHPShopSystem = new PHPShopSystem();


@$fp = fopen("../index.php", "r");
if($fp) {
    $fstat = fstat($fp);
    fclose($fp);
    $FileDate=PHPShopDate::dataV($fstat['mtime']);
}


// Выбор файла
function GetFile($dir) {
    global $SysValue;
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            $fstat = explode(".",$file);
            if($fstat[1] == "lic")
                return $SysValue['license']['dir'].chr(47).$file;
        }
        closedir($dh);
    }
}

// Срок действия тех. поддержки
$GetFile=GetFile("../license/");
@$License=parse_ini_file("../".$GetFile,1);

$TechPodUntilUnixTime = $License['License']['SupportExpires'];
if(is_numeric($TechPodUntilUnixTime))
    $TechPodUntil=PHPShopDate::dataV($TechPodUntilUnixTime);
else $TechPodUntil=" - ";

$LicenseUntilUnixTime = $License['License']['Expires'];
if(is_numeric($LicenseUntilUnixTime))
    $LicenseUntil=PHPShopDate::dataV($LicenseUntilUnixTime);
else  $LicenseUntil=" - ";


$Info="PHPShop System Info
---------------------------------------------

Версия: ".$PHPShopBase->getParam('license.product_name')."
Сборка: ".$PHPShopBase->getParam('upload.version')."
Дата изменения: ".$PHPShopBase->getParam('cache.last_modified')."
Дизайн: ".$PHPShopSystem->getParam('skin')."
GZIP: ".$PHPShopBase->getParam('my.gzip')."; Сжатие: ".$PHPShopBase->getParam('my.gzip_level')."
Установлено: ".$FileDate."
Окончание лицензии: ".$LicenseUntil."
Окончание поддержки: ".$TechPodUntil."

---------------------------------------------

Все права защищены. 2003-".date("Y")."
Copyright © www.phpshop.ru";

// Формируем результат прямо в виде PHP-массива!
$_RESULT = array(
        "info"     => $Info,
        'hello' => isset($_SESSION['hello'])? $_SESSION['hello'] : null
); 
?>