<?php

session_start();

$_classPath = "../../../";
include_once($_classPath . "class/obj.class.php");
include_once($_classPath . "modules/pickpoint/class/PickPoint.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass('modules');
PHPShopObj::loadClass('orm');
PHPShopObj::loadClass('system');
PHPShopObj::loadClass('security');
PHPShopObj::loadClass('order');
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("user");
PHPShopObj::loadClass("lang");

// Массив валют
$PHPShopValutaArray = new PHPShopValutaArray();
// Системные настройки
$PHPShopSystem = new PHPShopSystem();
$PHPShopLang = new PHPShopLang(['locale'=>$_SESSION['lang'],'path'=>'shop']);
$PickPoint = new PickPoint();

if(isset($_REQUEST['operation']) && strlen($_REQUEST['operation']) > 2) {
    $result = [];
    try {
        switch ($_REQUEST['operation']) {
            case 'calculate':
                $result['cost'] = $PickPoint->calculate($_REQUEST['pvz']);
                break;
        }

        $result['success'] = true;
    } catch (\Exception $exception) {
        $result = ['success' => false, 'error' => PHPShopString::win_utf8($exception->getMessage())];
    }
} else {
    $result = ['success' => false, 'error' => PHPShopString::win_utf8('Не найден параметр operation')];
}

echo (json_encode($result)); exit;
