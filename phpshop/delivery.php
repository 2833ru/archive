<?php

session_start();
$_classPath="./";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");

// Подключаем библиотеку поддержки.
require_once "./lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

// Подключаем библиотеку доставки
require_once "./inc/delivery.inc.php";

/**
 * Вывод стоимости доставки
 * @package PHPShopAjaxElementsDepricated
 * @param int $deliveryID ИД доставки
 * @param float $sum сумма
 * @param float $weight вес
 * @return float
 */
function GetDeliveryPrice($deliveryID,$sum,$weight=0) {
    global $SysValue;
    
    if(!empty($deliveryID)) {
        $sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID' and enabled='1'";
        $result=mysql_query($sql);
        $num=mysql_numrows($result);
        $row = mysql_fetch_array($result);
        
        if($num == 0) {
            $sql="select * from ".$SysValue['base']['table_name30']." where flag='1' and enabled='1'";
            $result=mysql_query($sql);
            $row = mysql_fetch_array($result);
        }
    }
    else {
        $sql="select * from ".$SysValue['base']['table_name30']." where flag='1' and enabled='1'";
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);
    }
    
    if($row['price_null_enabled'] == 1 and $sum>=$row['price_null']) {
        return 0;
    } else {
        if ($row['taxa']>0) {
            $addweight=$weight-500;
            if ($addweight<0) {
                $addweight=0;
                $at='';
            } else {
                $at='';
                //$at='Вес: '.$weight.' гр. Превышение: '.$addweight.' гр. Множитель:'.ceil($addweight/500).' = ';
            }
            $addweight=ceil($addweight/500)*$row['taxa'];
            $endprice=$row['price']+$addweight;
            return $at.$endprice;
        } else {
            return $row['price'];
        }
    }
}


$GetDeliveryPrice=GetDeliveryPrice($_REQUEST['xid'],$_REQUEST['sum'],$_REQUEST['wsum']);
$totalsumma=$GetDeliveryPrice+$_REQUEST['sum'];
$dellist=GetDelivery($_REQUEST['xid']);

// Результат
$_RESULT = array(
        'delivery' => $GetDeliveryPrice,
        'dellist'=> $dellist,
        'total' => $totalsumma
); 
?>