<?

// Парсируем установочный файл
$SysValue = parse_ini_file(dirname(__FILE__)."/../../phpshop/inc/config.ini",1);


// Калибровщик
function getSizer(){
$GetSystems=GetSystems(); //Считываем системные настройки

return '<BUTTON class="help"  id="sizeSaver" name="sizeSaver" onclick="savesize();return false;" style="display:none;width:200px">Сохранить новый размер</BUTTON>
<INPUT TYPE="HIDDEN" id="oldw">
<INPUT TYPE="HIDDEN" id="oldh">
<INPUT TYPE="HIDDEN" id="neww">
<INPUT TYPE="HIDDEN" id="newh">
<INPUT TYPE="HIDDEN" id="width_icon" value="'.$GetSystems['width_icon'].'">
<INPUT TYPE="HIDDEN" id="width_icon_new" value="'.$GetSystems['width_icon'].'">
<SCRIPT>
var oldW = (window.innerWidth)?window.innerWidth: ((document.all)?document.body.offsetWidth:null);
var oldH=(window.innerHeight)?window.innerHeight: ((document.all)?document.body.offsetHeight:null);
document.getElementById("oldw").value=oldW;
document.getElementById("oldh").value=oldH;

window.onresize=function(){
  var newW=(window.innerWidth)?window.innerWidth: ((document.all)?document.body.offsetWidth:null);
  var newH=(window.innerWidth)?window.innerHeight: ((document.all)?document.body.offsetHeight:null);
  document.getElementById("neww").value=newW;
  document.getElementById("newh").value=newH;
  document.getElementById("sizeSaver").style.display="block";
  document.getElementById("sizeSaver").value="Сохранить новый размер";
  document.getElementById("sizeSaver").disabled=false;

}
</SCRIPT>';
}


// Ресайз редактора
function DoResize($p,$w){
$mywin = $p/100;
return $w+$w*$mywin;
}


// Типы оплат
function TipPayment($payment){
$TIP=array(
	"message"=>"Сообщение",
	"bank"=>"Счет в банк",
	"sberbank"=>"Сбербанк",
	"robox"=>"Обменная касса Robox",
	"webmoney"=>"Webmoney",
	"interkassa"=>"Обменная касса Interkassa",
	"rbs"=>"Visa, Mastercard (RBS)",
	"z-payment"=>"Обменная касса Z-payment",
	"payonlinesystem"=>"Visa, Mastercard (PayOnlineSystem)"
	);

foreach($TIP as $k=>$v)
      if($k == $payment) return $v;
return "Оплата ".$payment;
}


$RegTo = $SysValue['license']['regto'];
$ProductName=$SysValue['license']['product_name'];
$ProductNameVersion=$SysValue['license']['product_name']." (сборка ".$SysValue['upload']['version'].")";


// Вывод валюты в выборе для загрузки товаров
function ChoiceValuta(){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name24']." WHERE enabled='1' order by num";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$vid=$row['id'];
	$vname=$row['name'];
	$vcode=$row['code'];
	$viso=$row['iso'];
	$vkurs=$row['kurs'];
	$venabled=$row['enabled'];
	if($vkurs == 1) $selected="selected"; else $selected="";
    @$dis.="<option value=".$vid." $selected>".$viso."</option>";
}
$disp='
<select id="tip_16">
'.$dis.'
</select>';
return $disp;
}




// Отрезаем до точки
function mySubstr($str,$a){
$T=$a;
for ($i = 1; $i <= $a; $i++) {
	if($str{$i} == ".") $T=$i;
}
return substr($str, 0, $T+1);
}


// Вывод доставки
function GetDeliveryPrice($deliveryID,$sum,$weight=0){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);

if($row['price_null_enabled'] == 1 and $sum>=$row['price_null']) {
	return 0;
} else {
	if ($row['taxa']>0) {
		$addweight=$weight-500;
		if ($addweight<0) {$addweight=0;}
		$addweight=ceil($addweight/500)*$row['taxa'];
		$endprice=$row['price']+$addweight;
		return $at.$endprice;
	} else {
		return $row['price'];
	}
}

}

// Вывод доставки
function GetDelivery($deliveryID,$name){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
return $row[$name];
}

function dataV($nowtime,$flag="true"){
$Months = array("01"=>"января","02"=>"февраля","03"=>"марта", 
 "04"=>"апреля","05"=>"мая","06"=>"июня", "07"=>"июля",
 "08"=>"августа","09"=>"сентября",  "10"=>"октября",
 "11"=>"ноября","12"=>"декабря");
 
$curDateM = date("m",$nowtime); 
if($flag=="true")
$t=date("d",$nowtime)." ".$Months[$curDateM]." ".date("Y",$nowtime)."г.".date("H:i ",$nowtime); 
elseif($flag=="shot") $t=date("d",$nowtime).".".$curDateM.".".date("Y",$nowtime)."г. ".date("H:i ",$nowtime); 
elseif($flag=="update") $t=date("d",$nowtime)."-".$curDateM."-".date("Y",$nowtime); 
else $t=date("d",$nowtime)." ".$Months[$curDateM]." ".date("Y",$nowtime)."г."; 
return $t;
}


function TotalClean($str,$flag)// чистка 
/*
  1 - проверяет корзину;
  2 - преобразует все в код html;
  3 - проверяет мыло;
  4 - проверяет ввод с формы
  5 - прверяет цифры
*/
{
 if($flag==1)// корзина
 {
   if (!ereg ("([0-9])", $str)) 
     {
     $str="0";
     }
     return abs($str);
   }
 elseif($flag==2)// убирает бяки
      {
	  return htmlspecialchars(stripslashes($str));
	  }
 elseif($flag==3)// обработка строки на бяки в мыле
      {
	 //проверка почты
	  if(!preg_match("/^([a-z0-9_\.-]+@[a-z0-9_\.\-]+\.[a-z0-9_-]{2,6})$/i",$str))
        {
        $str="";
        }
	   return $str;
	  }
 elseif($flag==4)// обработка строки на бяки
      {
	  if (preg_match("/[^(\w)|(\x7F-\xFF)|(\s)]/",$str)) 
        {
        $str="";
         }
       return  htmlspecialchars(stripslashes($str));
	  }
 elseif($flag==5)// проверка вводимых цифр
      {
	  if (preg_match("/[^(0-9)|(\-)|(\.]/",$str)) 
       {
       $str="0";
       }
       return $str;
	  }
}

// Чистим
function CleanStr($str){
	  $str=str_replace("/","|",$str);
	  $str=str_replace("\"","*",$str);
	  $str=str_replace("'","*",$str);
	  return htmlspecialchars(stripslashes($str));
}

function GetSystems()// вывод настроек
{
global $SysValue,$lang;


$sql="select * from ".$SysValue['base']['table_name3'];
$result=mysql_query($sql);
$option = mysql_fetch_array($result);

$my_lang=htmlspecialchars(stripslashes($lang));
if(file_exists("./language/".$my_lang."/language.php") or file_exists("../language/".$my_lang."/language.php")){
  $lang=$my_lang;
  session_register('lang');
  $array=unserialize($option['admoption']);
  $array['lang']=$my_lang;
  $option['admoption']=serialize($array);
}


return $option;
}

function UpdateWrite(){// Пишем изменение документа
global $SysValue;
$sql="UPDATE ".$SysValue['base']['table_name3']."
SET
updateU='".date("U")."'";
$result=mysql_query($sql);
}

function GetValutaValue($n){
global $SysValue;
$sql="select $n from ".$SysValue['base']['table_name3'];
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
return $row[$n];
}


function GetIsoValuta(){
global $SysValue;
$sql="select code from ".$SysValue['base']['table_name24']." where id=".GetValutaValue("dengi");
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
return $row['code'];
}




function GetIsoValutaOrder(){
global $SysValue;
$sql="select code from ".$SysValue['base']['table_name24']." where 
id=".GetValutaValue("kurs");
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
return $row['code'];
}

function GetKursOrder(){ // Курс
global $SysValue;
$sql="select kurs from ".$SysValue['base']['table_name24']." where 
id=".GetValutaValue("kurs");
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
return $row['kurs'];
}

function GetUnicTime($data){
$array=explode("-",$data);
return @mktime(12, 0, 0, $array[1], $array[0], $array[2]);
}

function GetUsersStatusForma($n){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name28']." order by discount";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$discount=$row['discount'];
	$sel="";
	if($n==$id) $sel="selected";
	@$dis.="<option value=".$id." ".$sel." >".$name." - ".$discount."%</option>\n";
	}
@$disp='
<select name=list size=1>
<option value="" id=txtLang>Все</option>
<option value=0 id=txtLang>Авторизованный пользователь</option>
'.$dis.'
</select>
<input type=button name="btnLang" value=Показать class=but3 onclick="DoReload(\'shopusers\',\'\',document.getElementById(\'list\').value)">
';
return @$disp;
}

function GetOrderStatusArray(){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name32'];
$result=mysql_query($sql);
while(@$row = mysql_fetch_array(@$result))
    {
	$array=array(
	"id"=>$row['id'],
	"name"=>$row['name'],
	"color"=>$row['color'],
	"sklad"=>$row['sklad_action']
	);
	$Status[$row['id']]=$array;
	}
return @$Status;
}

function GetOrderStatusApi($n){
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
<select name='list' id='list'>
<option value='all'>Все</option>
<option value='new'>Новый заказ</option>
".@$dis."
</select>";
return @$disp;
}

// Определяем переменные
$host=$SysValue['connect']['host'];             
$user_db=$SysValue['connect']['user_db'];       
$pass_db=$SysValue['connect']['pass_db'];       
$dbase=$SysValue['connect']['dbase'];           
$table_name=$SysValue['base']['table_name'];    
$table_name1=$SysValue['base']['table_name1'];  
$table_name2=$SysValue['base']['table_name2'];  
$table_name3=$SysValue['base']['table_name3'];  
$table_name5=$SysValue['base']['table_name5'];  
$table_name6=$SysValue['base']['table_name6'];  
$table_name8=$SysValue['base']['table_name8'];  
$table_name12=$SysValue['base']['table_name11'];
$table_name14=$SysValue['base']['table_name14'];
$table_name15=$SysValue['base']['table_name15'];
$table_name17=$SysValue['base']['table_name17'];
$table_name18=$SysValue['base']['table_name18'];
$table_name19=$SysValue['base']['table_name19'];
$table_name27=$SysValue['base']['table_name27'];
$table_name32=$SysValue['base']['table_name32'];




// Обновление
define("TIME_LIMIT", 600);
?>
