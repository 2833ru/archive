<?
session_start();

// Парсируем установочный файл
$SysValue=parse_ini_file("../../inc/config.ini",1);
  while(list($section,$array)=each($SysValue))
                while(list($key,$value)=each($array))
$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))]=$value;

// Подключаем базу MySQL
@mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db'])or 
@die("".PHPSHOP_error(101,$SysValue['my']['error_tracer'])."");
mysql_select_db($SysValue['connect']['dbase'])or 
@die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
@mysql_query("SET NAMES cp1251");

// Подключаем модули
include("../../inc/engine.inc.php");            // Модуль движка
include("../../inc/mail.inc.php");
include("../../inc/cache.inc.php");             // Модуль кеша
include("../../inc/order.inc.php");             // Модуль кеша

// Подключаем кеш
$LoadItems=CacheReturnBase($sid);


function Vivod_product_price($n)// вывод товаров для прайса
{
global $SysValue,$LoadItems;
$n=TotalClean($n,1);
$sql="select id,name,price,price2,price3,price4,price5,baseinputvaluta from ".$SysValue['base']['table_name2']." where (category='$n' or dop_cat LIKE '%#$n#%') and enabled='1' order by name";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
{
$id=$row['id'];
$uid=$row['uid'];
$name=$row['name'];
$price=$row['price'];
$price=($price+(($price*$LoadItems['System']['percent'])/100));

$baseinputvaluta=$row['baseinputvaluta']; 
// Выборка из базы нужной колонки цены
	if(session_is_registered('UsersStatus')){
    $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
	  if($GetUsersStatusPrice>1){
	   $pole="price".$GetUsersStatusPrice;
	   $pricePersona=$row[$pole];
	   if(!empty($pricePersona)) 
	     $price=($pricePersona+(($pricePersona*$System['percent'])/100));
	   }
	}


	
// Если цены показывать только после аторизации
$admoption=unserialize($LoadItems['System']['admoption']);
if($admoption['user_price_activate']==1 and !$_SESSION['UsersId']){
    $price="~";
	$valuta="";
}else{
	$price=GetPriceValuta($price,"",$baseinputvaluta);
//    $price=GetPriceValuta($price);
	$valuta=GetValuta();
     }


	
@$disp.="
<tr bgcolor=\"#ffffff\">
	<td>
	".$name."
	</td>
	<td width=\"150\" align=\"center\">
	".$price." ".$valuta."
	</td>
	
</tr>";
}
@$SysValue['sql']['num']++;
return @$disp;
}


function Vivod_price($dir="null")// вывод каталогов для карта сайта
{
global $SysValue,$LoadItems;

// Если задан каталог
if($dir!="null"){

  if(!$LoadItems['Catalog'][$dir]['name']) return 404;

$parent=$LoadItems['Catalog'][$dir]['parent_to'];

 @$dis.="
	   <tr valign=\"top\">
	     <td colspan=4>
	      <b title=\"".$dir."\">".$LoadItems['Catalog'][$parent]['name']." / ".$LoadItems['Catalog'][$dir]['name']."</b>
	     </td>
	   </tr>";
	   @$dis.=Vivod_product_price($dir);

   

}
else{

   foreach($LoadItems['CatalogKeys'] as $cat=>$val){

       $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$cat);
	   if(count($podcatalog_id)==0){
	   @$dis.="
	   <tr valign=\"top\">
	     <td colspan=4>
	      <b title=\"".$cat."\">".$LoadItems['Catalog'][$val]['name']." / ".$LoadItems['Catalog'][$cat]['name']."</b>
	     </td>
	   </tr>";
	   @$dis.=Vivod_product_price($cat);
	   }
}
}

$dis="
    <table cellpadding=2 cellspacing=1 width=\"98%\" align=\"center\" border=1>
	$dis
	</table>
";

echo @$dis;
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title><?=$LoadItems['System']['name']?> / Прайс / Печатная форма</title>
<META http-equiv="Content-Type" content="text-html; charset=windows-1251">
<style>
BODY {
	FONT-FAMILY: tahoma,verdana,arial,sans-serif
	color:000000;
	font-size: 11px;
}
td {
	font-size: 11px;
	font-family:Tahoma;
	color:#000000;
}
a {
   font-size: 11px;
   font-family:Tahoma;
   color:#000000;
   text-decoration: none;
}
a:hover {
   font-size: 11px;
   font-family:Tahoma;
   color:#000000;
   text-decoration: underline;
}

.bor {
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
<body>
<div style="padding-left:10"><h3>Прайс-лист Интернет магазина "<?=$LoadItems['System']['name']?>"</h3></div>
<TABLE cellpadding="0" cellspacing="0" width="100%" class="style5">
								<TR>
									<TD>
										<TABLE width="100%">
											<TR>
												<TD class="black" style="padding:10" width="50%">	
												 Дата: <b><?=date("d-m-y")?></b>
												
								
												</TD>
												<td align="center" width="50%">
												<input type="submit" value="Распечатать" onclick="window.print();return false;" class="nonprint">&nbsp;&nbsp;&nbsp;<input type="submit" value="Сохранить на диск" onclick="document.execCommand('SaveAs');return false;" class="nonprint">
												</td>
											</TR>
										</TABLE>
									</TD>
								</TR>
</TABLE>
<? 
if(@$catId == "ALL" or empty($catId))
 echo Vivod_price("null");
elseif(!empty($catId)) echo Vivod_price($catId);

?>
<TABLE cellpadding="0" cellspacing="0" width="100%" class="style5">
								<TR>
									<TD>
										<TABLE width="100%">
											<TR>
												<TD class="black" style="padding:10" width="50%">	
												Дата: <b><?=date("d-m-y")?></b>
												
								
												</TD>
												<td align="center" width="50%">
												<input type="submit" value="Распечатать" onclick="window.print();return false;" class="nonprint">&nbsp;&nbsp;&nbsp;<input type="submit" value="Сохранить на диск" onclick="document.execCommand('SaveAs');return false;" class="nonprint">
												</td>
											</TR>
										</TABLE>
									</TD>
								</TR>
</TABLE>
</body>
</html>	