<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

function ReturnSumma($sum,$disc){
$kurs=GetKursOrder();
$sum*=$kurs;
$sum=$sum-($sum*$disc/100);
return number_format($sum,0,".","");
}

// Подключение языков
$GetSystems=GetSystems();
$Admoption=unserialize($GetSystems['admoption']);
$Lang=$Admoption['lang'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Редактирование Заказа
</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<meta http-equiv="MSThemeCompatible" content="Yes">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script type="text/javascript" language="JavaScript" 
  src="/phpshop/lib/JsHttpRequest/JsHttpRequest.js"></script>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script>
function DoUpadateSum(){
var num=document.getElementById('num').value;
var price=document.getElementById('price').value;
document.getElementById('sum').innerHTML="<h1>"+(num*price)+"</h1>";
}
</script>
</head>
<body style="overflow:hidden;margin:0;" onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>)">
<?

function GetImg($productID){
global $table_name2;
$sql="select pic_small from $table_name2 where id='$productID'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
if(empty($row['pic_small'])) return "images/shop/no_photo.gif";
 else return $row['pic_small'];
}



$sql="select * from $table_name1 where id='$orderId'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);

    $id=$row['id'];
    $datas=$row['datas'];
	$uid=$row['uid'];
	$order=unserialize($row['orders']);
	$status=unserialize($row['status']);

	$xid = base64_decode(base64_decode($xid));
	echo $xid;
echo "

<form method=\"post\" id=\"product_edit\">
<table width=\"100%\">
<tr>
	<td>
	<FIELDSET >
	<LEGEND><span name=txtLang id=txtLang><u>Н</u>аименование</span> #$xid</LEGEND>
	<div style=\"padding:10\" align=\"left\">
	
	   <input type=\"text\" name=name_new style=\"width: 100%;\" value=\"".$order['Cart']['cart'][$xid]['name']."\">
</div>
	</FIELDSET>
	</td>
</tr>
</table>
<table  width=\"100%\">
<tr>
	<td valign=\"top\">
	<FIELDSET >
	<LEGEND><span name=txtLang id=txtLang><u>И</u>зображение</span></LEGEND>
	<div style=\"padding:10\" align=\"left\">
	<img src=\"".GetImg($xid)."\" border=\"0\">
	</div>
	</FIELDSET>
	</td>
	<td valign=top>
	<FIELDSET >
	<LEGEND><span name=txtLang id=txtLang><u>К</u>ол-во</span></LEGEND>
	<div style=\"padding:10\" align=\"left\">
	<input type=\"text\" name=num_new style=\"width: 70px;\" value=\"".$order['Cart']['cart'][$xid]['num']."\" id=\"num\" onchange=\"DoUpadateSum()\">
	</div>
	</FIELDSET>
	<FIELDSET >
	<LEGEND><span name=txtLang id=txtLang><u>Ц</u>ена (".GetIsoValutaOrder().".)</span></LEGEND>
	<div style=\"padding:10\" align=\"left\">
	<input type=\"text\" name=price_new style=\"width: 70px;\" value=\"".ReturnSumma($order['Cart']['cart'][$xid]['price'],0)."\" id=\"price\" onchange=\"DoUpadateSum()\"> 
	</div>
	</FIELDSET>
	</td>
	<td valign=top width=\"100%\">
	<FIELDSET >
	<LEGEND><span name=txtLang id=txtLang><u>С</u>умма</span> (".GetIsoValutaOrder().".)</LEGEND>
	<div style=\"padding:10\" align=\"center\" id=\"sum\">
	<h1>".(number_format(ReturnSumma($order['Cart']['cart'][$xid]['price']*$order['Cart']['cart'][$xid]['num'],0),0,""," "))."</h1>
	</div>
	</FIELDSET>
	</td>
</tr>
</table>
<hr>
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" >
<tr>
	<td align=\"right\" style=\"padding:10\">
<input type=button value=\"ОК\"  id=btnOk  style=\"width: 7em; height: 2.2em;\" onClick=\"DoUpdateFromOrder('".base64_encode(base64_encode($xid))."',$orderId,this.form.name_new.value,this.form.num_new.value,this.form.price_new.value);\">
<input type=\"button\" class=but id=btnRemove value=\"Удалить\" onClick=\"DoDelFromOrder('".base64_encode(base64_encode($xid))."', $orderId);\">
	<input type=submit id=btnCancel value=Отмена class=but onClick=\"return onCancel();\">

	</td>
</tr>
</table>
</form>
</body>
</html>";
