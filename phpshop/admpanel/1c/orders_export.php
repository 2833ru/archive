<?
$_classPath="../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("security");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
$PHPShopBase->chekAdmin();


if(isset($_GET['orderID'])){
$PHPShopOrder = new PHPShopOrderFunction($_GET['orderID']);

$sql="select * from ".$SysValue['base']['table_name1']." where id=".$_GET['orderID'];
$result=mysql_query($sql);
$num=0;
$csv1="Начало личных данных;;;;;;;;;;\n";
$csv2="Начало заказанных товаров;;;;;;;;;;\n";
$row = mysql_fetch_array($result);
    $id=$row['id'];
    $datas=$row['datas'];
	$uid=$row['uid'];
	$order=unserialize($row['orders']);
	$status=unserialize($row['status']);
	$num=$row['num'];
	$mail=$order['Person']['mail'];
	$name=$order['Person']['name_person'];
	$conpany=str_replace("&quot;","",$order['Person']['org_name']);
	$inn=$order['Person']['org_inn'];
	$tel=$order['Person']['tel_name'];
	$adres=str_replace("&quot;","",$order['Person']['adr_name']);
	$oplata=$PHPShopOrder->getOplataMetodName();
	$sum=$PHPShopOrder->returnSumma($order['Cart']['sum'],$order['Person']['discount']);
	$discount=$order['Person']['discount'];
	if($discount>0) $discountStr="- скидка $discount%";
	else $discountStr="";
	$csv1.="$uid;$datas;$mail;$name $discountStr;$conpany;$tel;$oplata;$sum;$discount;$inn;\n";

  if(is_array($order['Cart']['cart']))
  foreach($order['Cart']['cart'] as $val){
  $id=$val['id'];
  $uid=$val['uid'];
  $num=$val['num'];
  $sum=$PHPShopOrder->returnSumma($val['price']*$num,$order['Person']['discount']);
  
  // Нахождение кода валюты
  $valuta=$PHPShopOrder->getValutaIso($id);
  $csv2.="$id;$uid;$num;$sum;$valuta;;;;;;\n";
  }

  $csv=$csv1.$csv2;
  $file=$row['uid'].".csv";
  @$fp = fopen("../csv/".$file, "w+");
  if ($fp) {
  //stream_set_write_buffer($fp, 0);
  fputs($fp, $csv);
  fclose($fp);
  }
//sleep(1);
//exit("../csv/".$file);
header("Location: ../csv/".$file);
}

?>