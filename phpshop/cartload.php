<?

session_start();

require_once "./lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

// Получаем запрос.
$q = $_REQUEST['q'];
$xid = $_REQUEST['xid'];
$_num = $_REQUEST['num'];
$addname = $_REQUEST['addname'];
$cart = $_SESSION['cart'];


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
@mysql_query("SET NAMES cp1251");

$SysValue['nav']['nav'] = $NAV;


// Подключаем модули
include("./inc/engine.inc.php");            // Модуль движка
include("./inc/cache.inc.php");


// Подключаем кеш
$LoadItems=CacheReturnBase("cart");

function Chek($stroka)// проверка вводимых цифр
{
if (!ereg ("([0-9])", $stroka)) {$stroka="0";}
return abs($stroka);
}

function Chek2($stroka)// проверка вводимых цифр
{
if (!ereg ("([0-9])", $stroka)) {$stroka="0";}
return number_format(abs($stroka),"2",".","");
}


function CleanStr($str){
	  $str=str_replace("/","",$str);
	  $str=str_replace("\"","",$str);
	  $str=str_replace("'","",$str);
	  return $str;
}

$xid=Chek($xid);


$sql="select * from ".$SysValue['base']['table_name2']." where id=$xid";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$name=CleanStr($row['name']);
$name.=" ".$addname;
$price=$row['price'];
$baseinputvaluta=$row['baseinputvaluta'];
$defvaluta=$LoadItems['System']['dengi'];

//получаем исходную цену
if ($baseinputvaluta) { //Если прислали баз. валюту
	if ($baseinputvaluta!==$defvaluta) {//Если присланная валюта отличается от базовой
		$vkurs=$LoadItems['Valuta'][$baseinputvaluta]['kurs'];
		$price=$price/$vkurs; //Приводим цену в базовую валюту
	}
} //Если прислали баз. валюту
//получаем исходную цену
$price=($price+(($price*$LoadItems['System']['percent'])/100));
$uid=$row['uid'];
$id=$row['id'];
$user=$row['user'];
$weight=$row['weight'];



// Выборка из базы нужной колонки цены
	if(session_is_registered('UsersStatus')){
    $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
	  if($GetUsersStatusPrice>1){
	   $pole="price".$GetUsersStatusPrice;
	   $pricePersona=$row[$pole];

	//получаем исходную цену
	if ($baseinputvaluta) { //Если прислали баз. валюту
		if ($baseinputvaluta!==$defvaluta) {//Если присланная валюта отличается от базовой
			$vkurs=$LoadItems['Valuta'][$baseinputvaluta]['kurs'];
			$pricePersona=$pricePersona/$vkurs; //Приводим цену в базовую валюту
		}
	} //Если прислали баз. валюту
	//получаем исходную цену




	   if(!empty($pricePersona)) 
	     $price=($pricePersona+(($pricePersona*$LoadItems['System']['percent'])/100));
	   }
	}


//Принимаем что товар добавляется другой
$same=0;
if (isset($cart[$xid])) {
	if ($cart[$xid]['name']!==$name) { //Если такой товар есть и у него было другое имя, сказать что характеристики обновлены!
		$same=1;
	}
}


$num=@$cart[$xid]['num']+$_num;
$cart_new=array(
"id"=>"$id",
"name"=>"$name",
"price"=>$price,
"uid"=>"$uid",
"num"=>"$num",
"weight"=>$weight,
"user"=>$user
	);
$cart[$xid]=$cart_new;

// Подключаем корзину
if(is_array($cart)){
$_SESSION['cart']= $cart;
}


if(is_array($cart))// вывод корзины
foreach($cart as $j=>$v)
  {
 $price=$cart[$j]['price']*$cart[$j]['num'];
 @$sum+=$price_now;
 @$sum=number_format($sum,"2",".","");
 @$num+=$cart[$j]['num'];
 }

function ReturnNum($cart)
{
while (list($key, $value) = @each($cart)) @$num+=$cart[$key]['num'];
return @$num;
}

function ReturnSumma($sum){ // Поправки по курсу
global $LoadItems;
$formatPrice = unserialize($LoadItems['System']['admoption']);
$format=$formatPrice['price_znak'];
$kurs=GetKursOrder();
$sum*=$kurs;
return number_format($sum,$format,"."," ");
}

function GetKursOrder(){ // Курс
global $LoadItems;

if(isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
  else $valuta=$LoadItems['System']['dengi'];
  
return  $LoadItems['Valuta'][$valuta]['kurs'];
}


function ReturnSum($cart)
{
while (list($key, $value) = @each($cart)) @$sum+=$cart[$key]['price']*$cart[$key]['num'];
$sum=ReturnSumma($sum);
return @$sum;
}

function GetValuta(){ // Валюта
global $LoadItems,$_SESSION;

if(isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
  else $valuta=$LoadItems['System']['dengi'];

return  $LoadItems['Valuta'][$valuta]['code'];
}

// Формируем результат
$_RESULT = array(
  "num"   => ReturnNum($cart),
  "sum" => ReturnSum($cart)
); 
?>