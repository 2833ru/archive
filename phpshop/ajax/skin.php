<?php

/**
 * Настройка шаблона из внешней части
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../";


// Подключаем библиотеку поддержки JsHttpRequest
if ($_REQUEST['type'] != 'json') {
    require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";
    $JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
}

include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");

// Проверка прав админа
if (!empty($_SESSION['logPHPSHOP']) and PHPShopSecurity::true_skin($_COOKIE[$_REQUEST['template'] . '_theme'])) {

    $PHPShopSystem = new PHPShopSystem();

    if ($GLOBALS['SysValue']['template_theme']['demo'] != 'true') {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);
        $admoption = unserialize($PHPShopSystem->getParam('admoption'));

        if (PHPShopSecurity::true_skin($_COOKIE[$_REQUEST['template'] . '_theme2'])) {
            $admoption[$_REQUEST['template'] . '_theme2'] = $_COOKIE[$_REQUEST['template'] . '_theme2'];
            $status .= ",<br> " . $_COOKIE[$_REQUEST['template'] . '_theme2'] . '.css';
        }

        if (PHPShopSecurity::true_skin($_COOKIE[$_REQUEST['template'] . '_theme3'])) {
            $admoption[$_REQUEST['template'] . '_theme3'] = $_COOKIE[$_REQUEST['template'] . '_theme3'];
            $status .= ",<br> " . $_COOKIE[$_REQUEST['template'] . '_theme3'] . '.css';
        }

        $admoption[$_REQUEST['template'] . '_theme'] = $_COOKIE[$_REQUEST['template'] . '_theme'];
        $admoption[$_REQUEST['template'] . '_fluid_theme'] = $_COOKIE[$_REQUEST['template'] . '_theme'];
        $update['admoption_new'] = serialize($admoption);
        $PHPShopOrm->update($update);

        $_RESULT = array(
            "status" => "Шаблон изменен на " . $_COOKIE[$_REQUEST['template'] . '_theme'] . '.css' . $status,
            "success" => 1
        );

        if ($_REQUEST['type'] == 'json') {
            $_RESULT['status'] = PHPShopString::win_utf8($_RESULT['status']);
            echo json_encode($_RESULT);
        }
    }
}
?>