<?
session_start();

// Парсируем установочный файл
$SysValue=parse_ini_file("./../../inc/config.ini",1);
  while(list($section,$array)=each($SysValue))
                while(list($key,$value)=each($array))
$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))]=$value;

// Подключаем базу MySQL
@mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db'])or 
@die("".PHPSHOP_error(101,$SysValue['my']['error_tracer'])."");
mysql_select_db($SysValue['connect']['dbase'])or 
@die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
@mysql_query("SET NAMES 'cp1251'");

// Подключаем модули
include("../../inc/engine.inc.php");            // Модуль движка
include("../../inc/order.inc.php");            // Модуль движка
include("../../inc/cache.inc.php");
include("../../inc/mail.inc.php");

// Подключаем кеш
$LoadItems=CacheReturnBase($sid);



function ReturnLogo(){
global $LoadItems;
return $LoadItems['System']['logo'];
}


// Подключаем реквизиты
$SysValue['bank']=unserialize($LoadItems['System']['bank']);
$pathTemplate=$SysValue['dir']['templates'].chr(47).$_SESSION['skin'];


if(count(@$cart)>0)// вывод корзины
  {
if(is_array($cart))
$n=1;
foreach($cart as $j=>$v)
  {
  $price_now=ReturnSummaNal($cart[$j]['price']*$cart[$j]['num'],0);
 $priceOrder=$cart[$j]['price']*$cart[$j]['num'];
 @$dis.="
  <tr class=tablerow>
		<td class=tablerow>".$n."</td>
		<td class=tablerow>".$cart[$j]['name']."</td>
		<td class=tablerow align=center>шт.&nbsp;</td>
		<td align=right class=tablerow>".$cart[$j]['num']."</td>
		<td align=right class=tablerow nowrap>".ReturnSummaNal($cart[$j]['price'],0)."</td>
		<td class=tableright>".$price_now."</td>
	</tr>
  ";
  @$sum+=$price_now;
  @$num+=$val['num'];
  $n++;
 }
  
 @$sum=number_format($sum,"2",".","");
 $ChekDiscount=ChekDiscount($sum);

 
 //$datas=dataV($datas,"false");

 }
?>
<head>
<title>Форма предварительного заказа</title>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=windows-1251">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1251">
<script>
function NoFoto(obj){
obj.height=1;
obj.width=1;
}
</script>
<style type="text/css">
body {text-decoration: none;font: normal 11px x-small/normal Verdana, Arial, Helvetica, sans-serif;text-transform: none}
TABLE {font: normal 11px Verdana, Arial, Helvetica, sans-serif;}
p {font: normal 11px Verdana, Arial, Helvetica, sans-serif;word-spacing: normal;white-space: normal;margin: 5px 5px 5px 5px;letter-spacing : normal;} 
TD {
	font: normal 11px Verdana, Arial, Helvetica, sans-serif;
	background: #FFFFFF;
}
H4 {
	font: Verdana, Arial, Helvetica, sans-serif;
	background: #FFFFFF;
}
.tablerow {
	border: 0px;
	border-top: 1px solid #000000;
	border-left: 1px solid #000000;
}
.tableright {
	border: 0px;
	border-top: 1px solid #000000;
	border-left: 1px solid #000000;
	border-right: 1px solid #000000;
	text-align: right;
}
</style>
<style media="print" type="text/css">
<!-- 
.nonprint {
	display: none;
}
 -->
</style>
</head>
<body onload="window.focus()" bgcolor="#FFFFFF" text="#000000" marginwidth=5 leftmargin=5 style="padding: 2px;">
<div align="right" class="nonprint"><a href="#" onclick="window.print();return false;" ><img border=0 align=absmiddle hspace=3 vspace=3 src="http://<?=$SERVER_NAME.$SysValue['dir']['dir']?>/phpshop/admpanel/img/action_print.gif">Распечатать</a> | <a href="#" onclick="document.execCommand('SaveAs');return false;">Сохранить на диск<img border=0 align=absmiddle hspace=3 vspace=3 src="http://<?=$SERVER_NAME.$SysValue['dir']['dir']?>/phpshop/admpanel/img/action_save.gif"></a><br><br></div>
<div align="center"><table align="center" width="100%">
<tr>
	<td align="center"><img onerror=NoFoto(this) src="<?echo ReturnLogo();?>" alt="" border="0"></td>
	<td align="center"><h4 align=center>Форма предварительного заказа &nbsp;от&nbsp;<?=date("d-m-y")?></h4></td>
</tr>
</table>
</div>

<table width=99% cellpadding=2 cellspacing=0 align=center>
	<tr class=tablerow>
		<td class=tablerow>№</td>
		<td width=50% class=tablerow>Наименование</td>
		<td class=tablerow>Единица измерения&nbsp;</td>
		<td class=tablerow>Количество</td>
		<td class=tablerow>Цена</td>
		<td class=tableright>Сумма</td>
	</tr>
	<?
  echo @$dis;
 $my_total=ReturnSumma($sum,$order['Person']['discount']);
  ?>
       <tr>
			<td colspan=5 align=right style="border-top: 1px solid #000000;border-left: 1px solid #000000;">Скидка:</td>
			<td class=tableright nowrap><b><?= $ChekDiscount[0]?>%</b></td>
		</tr>
		<tr>
			<td colspan=5 align=right style="border-top: 1px solid #000000;border-left: 1px solid #000000;">Итого:</td>
			<td class=tableright nowrap><b><?=ReturnSummaOrder($sum,$ChekDiscount[0])?></b></td>
		</tr>
	<?if($LoadItems['System']['nds_enabled']){?>
	<?}?>
	<tr><td colspan=6 style="border: 0px; border-top: 1px solid #000000;">&nbsp;</td></tr>
</table>
<p><b>Всего наименований <?=($n-1)?>, на сумму <?=(ReturnSumma($sum,$ChekDiscount[0]))." ".GetIsoValutaOrder()?>
<br />
<?
$iw=new inwords;  
$s=$iw->get(ReturnSumma($sum,$ChekDiscount[0])); 
$v=GetIsoValutaOrder();
if (eregi("руб", $v)) echo $s;
?>
</b></p>




</body>
</html>