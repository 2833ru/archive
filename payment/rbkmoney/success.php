<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Success Function PayOnlineSystem   |
+-------------------------------------+
*/



if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

if(isset($_GET['rbk'])){
$order_metod="rbkmoney";
$my_crc = "NoN";
$crc = "NoN";
$success_function=false; // Выключаем функцию обновления статуса заказа
$d_hash1=base64_decode($_GET['inv']);
$mH=substr($d_hash1,2,strlen($d_hash1));
$mT=substr($mH,0,strlen($mH)-5);
$inv_id=0;
$message = "ваш заказ успешно оплачен";
/*
// Переход в личный кабинет
if(!empty($_SESSION['UsersId'])) 
  header("Location: ".$SysValue['dir']['dir']."/users/order.html?orderId=".$_POST['ouid']."#PphpshopOrder");
  else header("Location: ".$SysValue['dir']['dir']."/clients/?mail=".$_POST['mail']."&order=".$_POST['ouid']."");

*/
}

?>
