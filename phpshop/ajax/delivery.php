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

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

// Функции для заказа
$PHPShopOrder = new PHPShopOrderFunction();

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
    global $SysValue;

    if (!empty($deliveryID)) {
        $sql = "select * from " . $SysValue['base']['table_name30'] . " where id='$deliveryID' and enabled='1'";
        $result = mysql_query($sql);
        $num = mysql_numrows($result);
        $row = mysql_fetch_array($result);

        if ($num == 0) {
            $sql = "select * from " . $SysValue['base']['table_name30'] . " where flag='1' and enabled='1'";
            $result = mysql_query($sql);
            $row = mysql_fetch_array($result);
        }
    } else {
        $sql = "select * from " . $SysValue['base']['table_name30'] . " where flag='1' and enabled='1'";
        $result = mysql_query($sql);
        $row = mysql_fetch_array($result);
    }

    if ($row['price_null_enabled'] == 1 and $sum >= $row['price_null']) {
        return 0;
    } else {
        if ($row['taxa'] > 0) {
            $addweight = $weight - 500;
            if ($addweight < 0) {
                $addweight = 0;
                $at = '';
            } else {
                $at = '';
                //$at='Вес: '.$weight.' гр. Превышение: '.$addweight.' гр. Множитель:'.ceil($addweight/500).' = ';
            }
            $addweight = ceil($addweight / 500) * $row['taxa'];
            $endprice = $row['price'] + $addweight;
            return $at . $endprice;
        } else {
            return $row['price'];
        }
    }
}

$GetDeliveryPrice = GetDeliveryPrice(intval($_REQUEST['xid']), $_REQUEST['sum'], $_REQUEST['wsum']);
$totalsumma = $PHPShopOrder->returnSumma($_REQUEST['sum'], $PHPShopOrder->ChekDiscount($_REQUEST['sum'])) + $GetDeliveryPrice;
$deliveryArr = delivery(false, intval($_REQUEST['xid']));
$dellist = $deliveryArr['dellist'];
$adresList = $deliveryArr['adresList'];



// Результат
$_RESULT = array(
    'delivery' => $GetDeliveryPrice,
    'dellist' => $dellist,
    'adresList' => $adresList,
    'total' => $totalsumma,
    'success' => 1
);

// Перехват модуля в начале функции
$hook = $PHPShopModules->setHookHandler('delivery', 'delivery', false, array($_RESULT, $_REQUEST['xid']));
if ($hook)
    $_RESULT = $hook;


if ($_REQUEST['type'] == 'json'){
    $_RESULT['dellist']=PHPShopString::win_utf8($_RESULT['dellist']);
    $_RESULT['adresList']=PHPShopString::win_utf8($_RESULT['adresList']);
    echo json_encode($_RESULT);
}
?>