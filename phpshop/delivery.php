<?php
session_start();
// Подключаем библиотеку поддержки.
//require_once "./lib/config.php";
require_once "./lib/Subsys/JsHttpRequest/Php.php";
require_once "./inc/delivery.inc.php";
// Создаем главный объект библиотеки.
// Указываем кодировку страницы (обязательно!).
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

// Парсируем установочный файл
$SysValue=parse_ini_file("./inc/config.ini",1);
  while(list($section,$array)=each($SysValue))
                while(list($key,$value)=each($array))
$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))]=$value;

// Подключаем базу MySQL
@mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db'])or 
@die("".PHPSHOP_error(101,$SysValue['my']['error_tracer'])."");
mysql_select_db($SysValue['connect']['dbase'])or 
@die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
mysql_query( 'set names cp1251' );


// Вывод стоимости доставки
function GetDeliveryPrice($deliveryID,$sum,$weight=0){
global $SysValue;

if(!empty($deliveryID)){
$sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID' and enabled='1'";
$result=mysql_query($sql);
$num=mysql_numrows($result);
$row = mysql_fetch_array($result);

if($num == 0){
$sql="select * from ".$SysValue['base']['table_name30']." where flag='1' and enabled='1'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
}}

else
{
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
			$addweight=0; $at='';
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

$_RESULT = array(
  'delivery' => $GetDeliveryPrice,
  'dellist'=> $dellist,
  'total' => $totalsumma
); 
?>