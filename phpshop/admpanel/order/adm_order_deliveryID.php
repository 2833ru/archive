<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Подключение языков
$GetSystems=GetSystems();
$Admoption=unserialize($GetSystems['admoption']);
$Lang=$Admoption['lang'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Редактирование Доставки</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script type="text/javascript" language="JavaScript" 
  src="/phpshop/lib/JsHttpRequest/JsHttpRequest.js"></script>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>

<script type="text/javascript" language="JavaScript1.2" src="../language/<?
echo $Lang?>/language_windows.js">
</script>
<script> 
DoResize(<? echo $GetSystems['width_icon']?>,400,230);

function DoUpadateSum(price){
var sum=document.getElementById(price).value;
document.getElementById('sum').innerHTML="<h1>"+sum+"</h1>";
}
</script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0"  onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>)">
<?


function DelivSelList ($cid,$PID,$nPID=0,$lvl=0) {
global $SysValue;

$sql='select * from '.$SysValue['base']['table_name30'].' where PID='.$nPID.' order by city';
//$display=$sql;
$result=mysql_query($sql);
$lvl++;
while ($row = mysql_fetch_array($result))
    {
	$nid=$row['id'];
//	if ($nid==$cid) {continue;}

	$nPID=$row['PID'];
	$city=$row['city'];
	$spacer='';
	for ($ii=1;$ii<$lvl;$ii++) {
		$spacer.='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
	}
	if ($lvl>1) {$pointer='|&ndash;>&nbsp;';} else {$pointer='';}
        if ($nid==$cid) {$sel='selected';} else {$sel='';}
	@$display.='<option value="'.$nid.'" '.$sel.'>'.$spacer.$pointer.$city.'</option>';
        $display.=DelivSelList ($cid,$PID,$nid,$lvl);
	}

return $display;

} //Конец DelivList
//	echo DelivSelList ($id,$PID);

function GetAllDelivery($deliveryId){
global $SysValue;
	  $sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryId'";
      $result=mysql_query($sql);
	  $row = mysql_fetch_array($result);
	  $cid=$row['id'];
	  $cPID=$row['PID'];
	  $city=$row['city'];
	  $price=$row['price'];


 $sql="select * from ".$SysValue['base']['table_name30']." order by city";
 $result=mysql_query($sql);
 while($row = mysql_fetch_array($result)){
	  $id=$row['id'];
	  $city=$row['city'];
	  $price=$row['price'];
	  
	  if($deliveryId == $id) $sel="SELECTED";
	   else $sel="";
	  @$dis_hidden.="<input type=\"hidden\" id=\"$id\" value=\"$price\">\n";
      @$dis.="<option value=\"$id\" $sel>$city</option>\n";
	  }
return "
".$dis_hidden."
<select name=\"delivery\" id=\"delivery\" onchange=\"DoUpadateSum(this.value)\">
".DelivSelList ($deliveryId,$cPID)."
</select>";
}



// Редактирование записей 
	  $sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryId'";
      $result=mysql_query($sql);
	  $row = mysql_fetch_array($result);
	  $id=$row['id'];
	  $city=$row['city'];
	  $price=$row['price'];
	  ?>
<form name="product_edit"  method=post>
<table class=mainpage4 cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
	<td valign="top">
	<FIELDSET style="height:120px">
<LEGEND><span name=txtLang id=txtLang><u>Г</u>ород доставки</span></LEGEND>
<div style="padding:10">
<?=GetAllDelivery($deliveryId);?>
</div>
</FIELDSET>
	</td>
	<td>
	<FIELDSET style="height:120px">
<LEGEND><span name=txtLang id=txtLang><u>С</u>тоимость</span> ( <?=GetIsoValutaOrder()?>.)</LEGEND>
<div style="padding:10" align="center" id="sum">
<h1><?=$price?></h1>
</div>
</FIELDSET>
	</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
	<td align="right" style="padding:10">
	<input type="button" id=btnOk  name="editID" value="OK" class=but onclick="DoUpdateDeliveryFromOrder(document.getElementById('delivery').value,<?  echo $orderId; ?>)">
		<input type=submit id=btnCancel value=Отмена class=but onClick="return onCancel();">
	</td>
</tr>
</table>
</form>
