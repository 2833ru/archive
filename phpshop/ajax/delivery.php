<?php

/**
 * Доставка
 * @package PHPShopAjaxElements
 */
session_start();
$_classPath = "../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("lang");
PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass("cart");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini",true,true);

// Мультибаза
$PHPShopBase->checkMultibase("../../");

// Функции для заказа
$PHPShopSystem = new PHPShopSystem();
$PHPShopOrder = new PHPShopOrderFunction();

$PHPShopLang = new PHPShopLang(array('locale'=>$_SESSION['lang'],'path'=>'shop'));

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Подключаем библиотеку поддержки.
if ($_REQUEST['type'] != 'json') {
    require_once $_classPath . "lib/Subsys/JsHttpRequest/Php.php";
    $JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
}

// Подключаем библиотеку доставки
require_once $_classPath . "core/order.core/delivery.php";

function GetDeliveryPrice($deliveryID, $sum, $weight = 0) {
    global $SysValue,$link_db;

    $PHPShopDelivery = new PHPShopDelivery();

    if (!empty($deliveryID)) {
        $sql = "select * from " . $SysValue['base']['delivery'] . " where id='$deliveryID' and enabled='1'";
        $result = mysqli_query($link_db,$sql);
        $num = mysqli_num_rows($result);
        $row = mysqli_fetch_array($result);

        if ($num == 0) {
            $sql = "select * from " . $SysValue['base']['delivery'] . " where flag='1' and enabled='1'";
            $result = mysqli_query($link_db,$sql);
            $row = mysqli_fetch_array($result);
        }
    } else {
        $sql = "select * from " . $SysValue['base']['delivery'] . " where flag='1' and enabled='1'";
        $result = mysqli_query($link_db,$sql);
        $row = mysqli_fetch_array($result);
    }

    if ($row['price_null_enabled'] == 1 and $sum >= $row['price_null']) {
        return 0;
    } else {
        if ($row['taxa'] > 0) {
            $addweight = $weight - $PHPShopDelivery->fee;
            if ($addweight < 0) {
                $addweight = 0;
                $at = '';
            } else {
                $at = '';
                //$at='Вес: '.$weight.' гр. Превышение: '.$addweight.' гр. Множитель:'.ceil($addweight/500).' = ';
            }
            $addweight = ceil($addweight / $PHPShopDelivery->fee) * $row['taxa'];
            $endprice = $row['price'] + $addweight;
            return $at . $endprice;
        } else {
            return $row['price'];
        }
    }
}

$GetDeliveryPrice = GetDeliveryPrice(intval($_REQUEST['xid']), $_REQUEST['sum'], floatval($_REQUEST['wsum']));
$GetDeliveryPrice = $GetDeliveryPrice*$PHPShopSystem->getDefaultValutaKurs(true);

$PHPShopCart = new PHPShopCart();
// Итого товары по акции
$totalsumma = (float) $PHPShopOrder->returnSumma($PHPShopCart->getSumPromo(true));

// Итого товары без акции
$totalsumma += (float) $PHPShopOrder->returnSumma($PHPShopCart->getSumWithoutPromo(true), $PHPShopOrder->ChekDiscount($_REQUEST['sum']), '', (float) $GetDeliveryPrice);

$deliveryArr = delivery(false, intval($_REQUEST['xid']),$_REQUEST['sum']);
$dellist = $deliveryArr['dellist'];
$adresList = $deliveryArr['adresList'];
$format = $PHPShopSystem->getSerilizeParam("admoption.price_znak");

// Результат
$_RESULT = array(
    'delivery' => number_format($GetDeliveryPrice, $format, '.', ' '),
    'dellist' => $dellist,
    'discount'=>$PHPShopOrder->ChekDiscount($_REQUEST['sum']),
    'adresList' => $adresList,
    'total' => number_format($totalsumma, $PHPShopOrder->format, '.', ' '),
    'wsum' => floatval($_REQUEST['wsum']),
    'success' => 1
);

// Перехват модуля в начале функции
$hook = $PHPShopModules->setHookHandler('delivery', 'delivery', false, array($_RESULT, $_REQUEST['xid']));
if(is_array($hook))
    $_RESULT = $hook;

if ($_REQUEST['type'] == 'json'){
    $_RESULT['dellist']=PHPShopString::win_utf8($_RESULT['dellist'],false);
    $_RESULT['adresList']=PHPShopString::win_utf8($_RESULT['adresList'],false);

    echo json_encode($_RESULT);
}
?>