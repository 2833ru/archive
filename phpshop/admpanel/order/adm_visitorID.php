<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

function Visitor_info()// вывод покупателей
{
global $table_name1,$visitorID;
$sql="select * from $table_name1 where id='$visitorID'";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
    $datas=$row['datas'];
	$uid=$row['uid'];
	$status=$row['status'];
	}
$ar=array($uid,$status);
return $ar;
}

function ReturnSumma($sum,$disc){
$kurs=GetKursOrder();
$sum*=$kurs;
$sum=$sum-($sum*$disc/100);
return number_format($sum,"2",".","");

}

// Подключение языков
$GetSystems=GetSystems();
$Admoption=unserialize($GetSystems['admoption']);
$Lang=$Admoption['lang'];
require("../language/".$Lang."/language.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Редактирование Заказа
</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<meta http-equiv="MSThemeCompatible" content="Yes">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<LINK href="../css/tab.winclassic.css" type=text/css rel=stylesheet>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script type="text/javascript" language="JavaScript" 
  src="/phpshop/lib/JsHttpRequest/JsHttpRequest.js"></script>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" src="../java/tabpane.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script> 
DoResize(<? echo $GetSystems['width_icon']?>,650,550);
</script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>);preloader(0)">
<table id="loader">
<tr>
	<td valign="middle" align="center">
		<div id="loadmes" onclick="preloader(0)">
<table width="100%" height="100%">
<tr>
	<td id="loadimg"></td>
	<td ><b><?=$SysValue['Lang']['System']['loading']?></b><br><?=$SysValue['Lang']['System']['loading2']?></td>
</tr>
</table>
		</div>
</td>
</tr>
</table>

<SCRIPT language=JavaScript type=text/javascript>preloader(1);</SCRIPT>
<?

function dataV2($nowtime){
$Months = array("01"=>"января","02"=>"февраля","03"=>"марта", 
 "04"=>"апреля","05"=>"мая","06"=>"июня", "07"=>"июля",
 "08"=>"августа","09"=>"сентября",  "10"=>"октября",
 "11"=>"ноября","12"=>"декабря");
$curDateM = date("m",$nowtime); 
$t=date("d",$nowtime)."-".$curDateM."-".date("Y",$nowtime).""; 
return $t;
}

function OplataMetod($tip,$datas){ 
$sql="select * from ".$GLOBALS['SysValue']['base']['table_name48']." where enabled='1' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result)){
    $path=$row['path'];
	$name=$row['name'];
	$id=$row['id'];
	
	if($tip == $id){ 
	  $sel="selected";
	  
	  if(preg_match("/(webmoney|robox|interkassa|z-payment)/i",$path))
	    $test_p='
<input type="button" value="Платежи" onclick="DoReloadMainWindow(\'order_payment\',\''.$datas.'\',\''.date("d-m-Y",$datas).'\');">';
	  
	  }
	  else $sel="";
	
	@$dis.='<option value="'.$id.'" '.$sel.'>'.$name.'</option>';
	
     }



$disp='
<table>
<tr>
	<td>
	<select name="order_metod_new">
'.@$dis.'
</select>
	</td>
	<td>'.$test_p.'</td>
</tr>
</table>';

return $disp;
}


// Разбор корзины
function UpdateSklad($CART,$productID){
global $SysValue,$id;

$cart=$CART['cart'];

  if(sizeof($cart)!=0)
  foreach(@$cart as $val)
    if($val['id'] == $productID)
      return 1;
return 0;
}



// Разбор корзины
function ViewCart($CART,$PERSON){
global $SysValue,$id;
$cart=$CART['cart'];
$kurs=$CART['kurs'];
$n=1;
  if(sizeof($cart)!=0)
  if(is_array($cart))
  foreach(@$cart as $val){
  $disCart.="
<tr class=row3 onmouseover=\"show_on('r".$val['id']."')\" id=\"r".$val['id']."\" onmouseout=\"show_out('r".$val['id']."')\" onclick=\"miniWin('adm_order_productID.php?orderId=$id&xid=".$val['id']."',400,300,event)\">
 <td style=\"padding:3\">$n</td> 
  <td style=\"padding:3\">".$val['uid']."</td>
  <td style=\"padding:3\">".$val['name']."</td>
  <td style=\"padding:3\">".$val['num']."</td>
  <td style=\"padding:3\">".ReturnSumma($val['price']*$val['num'],0)."</td>
  
</tr>
";

$n++;
@$num+=$val['num'];
@$sum+=$val['price']*$val['num'];
//Определение и суммирование веса
 $goodid=$val['id'];
 $goodnum=$val['num'];
 $wsql='select weight from '.$SysValue['base']['table_name2'].' where id=\''.$goodid.'\'';
 $wresult=mysql_query($wsql);
 $wrow=mysql_fetch_array($wresult);
 $cweight=$wrow['weight']*$goodnum;
 if (!$cweight) {$zeroweight=1;} //Один из товаров имеет нулевой вес!
 $weight+=$cweight;


}

//Обнуляем вес товаров, если хотя бы один товар был без веса
if ($zeroweight) {$weight=0;}


$GetDeliveryPrice=GetDeliveryPrice($PERSON['dostavka_metod'],$sum,$weight);
 $disCart.="
<tr class=row3 onclick=\"miniWin('adm_order_deliveryID.php?deliveryId=".GetDelivery($PERSON['dostavka_metod'],"id")."&orderId=".$id."',400,270,event)\" onmouseover=\"show_on('r".$n."')\" id=\"r".$n."\" onmouseout=\"show_out('r".$n."')\">
  <td style=\"padding:3\">$n</td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\">Доставка - ".GetDelivery($PERSON['dostavka_metod'],"city")."</td>
  <td style=\"padding:3\">1</td>
  <td style=\"padding:3\">".$GetDeliveryPrice."</td>
  
</tr>
";
$n++;
while($n<11){
 $disCart.="
 <tr bgcolor=\"ffffff\">
  <td style=\"padding:3\" height=\"20\">$n</td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
</tr>
 ";
 $n++;
 }
$disCart.="
<tr bgcolor=\"#C0D2EC\">
  <td style=\"padding:3\" colspan=\"3\" id=pane align=center><span name=txtLang id=txtLang>Итого с учетом скидки</span> ".$PERSON['discount']."%</td>
  <td style=\"padding:3\"><b>".($num+1)."</b> <span name=txtLang id=txtLang>шт.</span></td>
  <td style=\"padding:3\" colspan=\"2\" align=\"center\"><b>".(ReturnSumma($sum,$PERSON['discount'])+$GetDeliveryPrice)."</b> ".GetIsoValutaOrder()."</td>
</tr>
";
 
return $disCart;
}

$GetSystems=GetSystems();


function GetOrderStatus($n){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name32'];
$result=mysql_query($sql);
while(@$row = mysql_fetch_array(@$result))
    {
	if($n==$row['id'])  $sel2="selected";
	 else $sel2="";
	@$dis.="<option value='".$row['id']."' $sel2>".$row['name']."</option>";
	}
$disp="
<select name=statusi_new size=1 class=s onchange=\"StatusChek(this.value)\">
<option value='0'>Новый заказ</option>
".@$dis."
</select>";
return @$disp;
}


if(@$visitorUID) $sql="select * from $table_name1 where uid='$visitorUID'";
else $sql="select * from $table_name1 where id='$visitorID'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);

    $id=$row['id'];
    $datas=$row['datas'];
	$uid=$row['uid'];
	$user=$row['user'];
	$order=unserialize($row['orders']);
	$status=unserialize($row['status']);
	$statusi=$row['statusi'];
	
   if($status['status']=="Новый заказ") {$forma="disabled"; $sel1="selected"; }
   elseif($status['status']=="Выполняется") {$forma=""; $sel2="selected"; }
   elseif($status['status']=="Доставляется") {$forma=""; $sel3="selected"; }
   elseif($status['status']=="Выполнен") {$forma=""; $sel4="selected"; }
   elseif($status['status']=="Аннулирован") {$forma=""; $sel5="selected"; }
   if(!empty($order['Person']['dos_ot'])) $DosTime=" с ".$order['Person']['dos_ot']." по ".$order['Person']['dos_do']." ч.";
   else $DosTime="";
   
   if(empty($status['time'])) $status['time']=dataV($datas);
   
echo"
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" id=\"title\">
<tr bgcolor=\"#ffffff\">
	<td style=\"padding:10\">
	<b><span name=txtLang id=txtLang>Заказ</span> № $uid / ".dataV($datas)."</b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Состояние заказа</span>.
	</td>
	<td align=\"right\">
	<img src=\"../img/i_commercemanager_med[1].gif\" border=\"0\" hspace=\"10\">
	</td>
</tr>
</table>
<form method=post name=product_edit>
<!-- begin tab pane -->
<div class=\"tab-pane\" id=\"article-tab\" style=\"margin-top:5px;height:250px\">

<script type=\"text/javascript\">
tabPane = new WebFXTabPane( document.getElementById( \"article-tab\" ), true );
</script>

<!-- begin intro page -->
<div class=\"tab-page\" id=\"intro-page\" style=\"height:350px\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Основное</span></h2>

<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"intro-page\" ) );
</script>
<table cellpadding=\"2\" cellspacing=\"0\" border=\"0\">
<tr valign=\"top\">
    <td>
	<FIELDSET  style=\"width: 200; height: 6em;\">
	<LEGEND ><span name=txtLang id=txtLang><u>С</u>остояние заказа</span></LEGEND>
	<div style=\"padding:10\" align=\"left\">
	<form method=\"post\">
	".GetOrderStatus($statusi)."
	<div style=\"border: 1px;border-style: inset;margin-top:5px\" >
	<img src=\"../img/icon_clock.gif\"  border=\"0\" align=\"absmiddle\" hspace=\"5\">
	".@$status['time']."
	</div>
	</div>
	
	</FIELDSET>
	</td>
	<td>
	<FIELDSET  style=\"height: 7em;\">
	<LEGEND><span name=txtLang id=txtLang><u>Д</u>ополнительная информация по заказу</span></LEGEND>
	<div style=\"padding:10\" align=\"left\">
	<textarea style=\"width: 390; height: 43;\" name=maneger_new $forma id=status_forma>".@$status['maneger']."</textarea>
	</div>
	</FIELDSET>
	</td>
</tr>
<tr valign=\"top\">
    <td colspan=2>

<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
	<tr>
	<td valign=\"top\">
	<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" >
<tr>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Адрес доставка</span></td>
	<td id=pane align=center colspan=3><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Покупатель</span></td>
</tr>
<tr bgcolor=\"ffffff\">
  <td>
  
  
  <textarea name=\"adr_name\" style=\"width: 100%; height: 50;\">".$order['Person']['adr_name']."</textarea>

  </td>
  <td colspan=3>
  <textarea name=\"name_person\" style=\"width: 100%; height: 35;\">".$order['Person']['name_person']."</textarea><br>
&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Время доставки от</span> <input type=\"text\" name=dos_ot style=\"width: 50px;\" value=\"".$order['Person']['dos_ot']."\"> 
&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>до</span> <input type=\"text\" name=dos_do style=\"width: 50px;\" value=\"".$order['Person']['dos_do']."\">
</td>
</tr>
<tr>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Телефон</span></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Оплата</span></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Компания</span></td>
</tr>
<tr bgcolor=\"ffffff\">
  <td>
  <input type=\"text\" name=tel_code style=\"width: 50px;\" value=\"".$order['Person']['tel_code']."\"><input type=\"text\" name=tel_name style=\"width: 100px;\" value=\"".$order['Person']['tel_name']."\">
</td>
  <td style=\"padding:3\" width=200>".OplataMetod($order['Person']['order_metod'],$datas)."</td>
  <td>
  <textarea name=\"org_name\" style=\"width: 100%; height: 30;\">".$order['Person']['org_name']."</textarea></td>
</tr>
</table>
	</td>
</tr>
</table>
</td>
</tr>
</table>

</td>
</tr>
<tr>
  <td>
  <br>
  
  <FIELDSET >
	<LEGEND><span name=txtLang id=txtLang><u>Д</u>окументы</span></LEGEND>
	<div style=\"padding:5\" align=\"center\">
	
	";
	if($user>0)
echo"<button style=\"width: 14em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('../shopusers/adm_userID.php?id=".$order['Person']['user_id']."',500,580)\"> <img src=\"../img/icon_user.gif\"  border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Пользователь</span></button>";
   else echo"<button style=\"width: 14em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('../shopusers/adm_users_new.php?visitorID=".$id."',500,580)\"> <img src=\"../img/icon_user.gif\"  border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Авторизовать</span></button>";


echo"

  <button style=\"width: 14em; height: 2.2em; margin-left:5\"  onclick=\"DoPrint('forms/forma.html?orderID=".$id."')\">
<img src=\"../img/action_print.gif\" border=\"0\" align=\"absmiddle\" hspace=5>
	<span name=txtLang id=txtLang>Бланк заказа</span></button>
	";
	if($Lang == "russian")
	echo "
	 <button style=\"width: 14em; height: 2.2em; margin-left:5\"  onclick=\"DoPrint('forms/forma2.php?orderID=".$id."')\">
<img src=\"../img/action_print.gif\" border=\"0\" align=\"absmiddle\" hspace=5>
	Товарный чек</button>
	<button style=\"width: 11em; height: 2.2em; margin-left:5\"  onclick=\"DoPrint('forms/forma3.php?orderID=".$id."')\">
<img src=\"../img/action_print.gif\" border=\"0\" align=\"absmiddle\" hspace=5>
	Гарантия</button>
	<button style=\"width: 14em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('forms/forma4.php?orderID=".$id."',800,800)\">
<img src=\"../img/action_print.gif\" border=\"0\" align=\"absmiddle\" hspace=5>
	Счет-Фактура</button>
	<BUTTON style=\"width: 11em; height: 2.2em; margin-left:5\"  onclick=\"DoPrint('../../../phpshop/forms/1/forma.html?orderId=".$id."&tip=2&datas=".$datas."')\"> <img src=\"../img/interface_browser.gif\"  border=\"0\" align=\"absmiddle\" hspace=\"5\">Счет в банк</BUTTON>
<BUTTON style=\"width: 11em; height: 2.2em; margin-left:5\"  onclick=\"DoPrint('../../../phpshop/forms/2/forma.html?orderId=".$id."&tip=2&datas=".$datas."')\"> <img src=\"../img/interface_dialog.gif\"  border=\"0\" align=\"absmiddle\" hspace=\"5\">Сбербанк</BUTTON>
";
echo "
<BUTTON style=\"width: 11em; height: 2.2em; margin-left:5\"  onclick=\"GetMailTo('".$order['Person']['mail']."','Re: ".$GetSystems['name']." - Заказ №".$uid."');return false;\"> <img src=\"../img/icon_email.gif\"  border=\"0\" align=\"absmiddle\" hspace=\"5\">E-mail</BUTTON>
	";
	if($SysValue['pro']['enabled'] == "true" and $Lang == "russian"){
	echo"
	<button style=\"width: 11em; height: 2.2em; margin-left:5\"  onclick=\"window.open('../1c/orders_export.php?orderID=".$id."')\">
<img src=\"../img/icon_package_get.gif\" border=\"0\" align=\"absmiddle\" hspace=5>
	Импорт в 1С</button>
	";}
	echo"
	<button style=\"width: 14em; height: 2.2em; margin-left:5\"  onclick=\"window.location.replace('adm_visitor_new.php?orderAdd=".$id."'); return false;\">
<img src=\"../img/page_attachment.gif\" border=\"0\" align=\"absmiddle\" hspace=5>
	<span name=txtLang id=txtLang>Новый заказ</span></button>
	</div>
	</FIELDSET>
  </td>

</tr>
</table>
</div>

<div class=\"tab-page\" id=\"cart\" style=\"height:350px\">
<h2 class=\"tab\"><span name=txtLang id=txtLang>Корзина</span></h2>
<script type=\"text/javascript\">
tabPane.addTabPage( document.getElementById( \"cart\" ) );
</script>
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style:inset;\" >
	<tr>
	<td valign=\"top\">
		<div align=\"left\" style=\"width:100%;height:255;overflow:auto\"> 
	<div id=interfaces>
	<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\">
<tr>
	<td id=pane align=center width=\"10\"><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Артикул</span></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Наименование</span></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Кол-во</span></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Сумма</span> ".GetIsoValutaOrder()."</td>
</tr>
".ViewCart($order['Cart'],$order['Person'])."
</table>
</div>

	</td>
</tr>
</table>

<table width=\"100%\" cellpadding=0 cellspacing=0>
<tr>
	<td>
	<FIELDSET style=\"padding-top:5px\">
	<LEGEND><span name=txtLang id=txtLang><u>Д</u>обавить в заказ</span></LEGEND>
	<div style=\"padding:10px\">

<span name=txtLang id=txtLang>ID товара</span>: <input type=\"text\" id=\"new_product_id\"> <input type=\"button\" id=btnAdd class=but value=\"Добавить\" onClick=\"DoAddProductFromOrder(document.getElementById('new_product_id').value,".$id.")\">
</div>
</FIELDSET >
	</td>
	<td>
	<FIELDSET style=\"padding-top:5px\">
	<LEGEND><span name=txtLang id=txtLang><u>С</u>кидка</span></LEGEND>
	<div style=\"padding:10px\">
% <input type=\"text\" id=\"new_discount\" value=\"".$order['Person']['discount']."\"> <input type=\"button\" id=btnChange class=but value=\"Изменить\" onClick=\"DoUpdateDiscountFromOrder(document.getElementById('new_discount').value,".$id.")\">
</div>
</FIELDSET >
	</td>
</tr>
</table>



</div>
<hr>



<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" >
<tr>
   <td align=\"left\" style=\"padding:10\" width=50>
    <BUTTON class=\"help\" onclick=\"helpWinParent('ordersID')\">Справка</BUTTON>
	</td>
                <td align=\"left\" style=\"padding:10\" width=\"200\">".getSizer()."</td>
	<td align=\"right\" style=\"padding:10\" >
    <input type=submit id=btnOk  value=ОК class=but name=productSAVE>
	<input type=\"button\"  id=btnRemove class=but value=\"Удалить\" onClick=\"PromptThis();\">
	<input type=submit id=btnCancel value=Отмена class=but onClick=\"return onCancel();\">
	<input type=hidden name=id value=".$id.">
	<input type=hidden name=old_status value=".$statusi.">
	<input type=hidden name=visitorID value=".$id.">
	<input type=hidden name=pole1 value='".date("d-m-Y",$pole1+86400)."'>
	<input type=hidden name=pole2 value='".date("d-m-Y",$pole2-86400)."'>
	 <input type=\"hidden\" class=but  name=\"productDELETE\" id=\"productDELETE\">
	</td>
</tr>
</table>
</form>
	";

function MyStripSlashes($str){
return str_replace("\"","*",$str);
}


if(isset($productSAVE))
{
if(CheckedRules($UserStatus["visitor"],1) == 1){


$cart2=$order['Cart']['cart'];
foreach(@$cart2 as $val){

//Определение и суммирование веса
 $goodid=$val['id'];
 $goodnum=$val['num'];
 $wsql='select weight from '.$SysValue['base']['table_name2'].' where id=\''.$goodid.'\'';
 $wresult=mysql_query($wsql);
 $wrow=mysql_fetch_array($wresult);
 $cweight=$wrow['weight']*$goodnum;
 if (!$cweight) {$zeroweight=1;} //Один из товаров имеет нулевой вес!
 $weight+=$cweight;


}

//Обнуляем вес товаров, если хотя бы один товар был без веса
if ($zeroweight) {$weight=0;}


$order['Person']['order_metod'] = $_POST['order_metod_new'];
$order['Person']['name_person']=MyStripSlashes($_POST['name_person']);
$order['Person']['adr_name']=MyStripSlashes($_POST['adr_name']);
$order['Person']['dos_ot']=MyStripSlashes($_POST['dos_ot']);
$order['Person']['dos_do']=MyStripSlashes($_POST['dos_do']);
$order['Person']['tel_code']=MyStripSlashes($_POST['tel_code']);
$order['Person']['tel_name']=MyStripSlashes($_POST['tel_name']);
$order['Person']['org_name']=MyStripSlashes($_POST['org_name']);
$order['Cart']['weight']=$weight;


foreach($order['Cart']['cart'] as $val)
      @$num+=$val['num'];
	  
	  
$order['Cart']['num']=$num;


$Status=array(
"maneger"=>$maneger_new,
"time"=>date("d-m-y H:i a")
);
	 $sql="UPDATE $table_name1
     SET
	 orders='".serialize($order)."',
     status='".serialize($Status)."',
	 statusi='".$statusi_new."' 
     where id='$id'";
     $result=mysql_query($sql)or @die("".mysql_error()."");
	 
	 // Списываем со склада
	 $GetOrderStatusArray=GetOrderStatusArray();
	 $cart=$order['Cart']['cart'];
	 // Если новый статус Аннулирован, а был статус не Новый заказ, то мы не списываем, а добавляем обратно
	 if ($old_status != 0 &&  $statusi_new == 1)
	 {
		if(sizeof($cart)!=0)
			foreach(@$cart as $val){
				$sql="select items from $table_name2 where id='".$val['id']."'";
				$result=mysql_query($sql)or @die("".mysql_error()."");
				$row = mysql_fetch_array($result);
				$items=$row['items'];
				$items_update=$items+$val['num'];
				$sklad_update = "";
				if ($items_update > 0)
					$sklad_update = " ,sklad='0' ";
				$sql="UPDATE $table_name2
				SET
				items='$items_update' ".$sklad_update."
				where id='".$val['id']."'";
				$result=mysql_query($sql)or @die("".mysql_error()."");
			}
	 }
	 else if($GetOrderStatusArray[$statusi_new]['sklad'] == 1 and $GetOrderStatusArray[$old_status]['sklad'] != 1){
	   if(sizeof($cart)!=0)
	     foreach(@$cart as $val){
		 $sql="select items from $table_name2 where id='".$val['id']."'";
		 $result=mysql_query($sql)or @die("".mysql_error()."");
		 $row = mysql_fetch_array($result);
		 $items=$row['items'];
         $items_update=$items-$val['num'];
		 $sklad_update = "";
		 if ($items_update == 0)
			$sklad_update = " ,sklad='1' ";
		 $sql="UPDATE $table_name2
         SET
         items='$items_update' ".$sklad_update."
         where id='".$val['id']."'";
		 $result=mysql_query($sql)or @die("".mysql_error()."");
		 }
	 }
 echo '
 <table width="100%" height="100%">
<tr>
	<td valign="middle" align="center">
	<FIELDSET style="width: 300px">
<div style="padding:30">
	<input type="submit" value="Закрыть окно" onclick="self.close()">
</div>
</FIELDSET>
	</td>
</tr>
</table>
 ';
 echo"
<script>
DoReloadMainWindow('orders','$pole1','$pole2');
</script>";
}else $UserChek->BadUserFormaWindow();
}
if(@$productDELETE=="doIT")// Удаление записи
{
if(CheckedRules($UserStatus["visitor"],2) == 1){
	$sql="delete from $table_name1
    where id='$id'";
    $result=mysql_query($sql)or @die("Невозможно удалить запись");
	echo '
 <table width="100%" height="100%">
<tr>
	<td valign="middle" align="center">
	<FIELDSET style="width: 300px">
<div style="padding:30">
	<input type="submit" value="Закрыть окно" onclick="self.close()">
</div>
</FIELDSET>
	</td>
</tr>
</table>
 ';
	 echo"
<script>
DoReloadMainWindow('orders','$pole1','$pole2');
</script>";
}else $UserChek->BadUserFormaWindow();
} 
?>
</body>
</html>
