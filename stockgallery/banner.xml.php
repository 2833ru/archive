<? 
session_start();
$SysValue=parse_ini_file("../phpshop/inc/config.ini",1);
@mysql_connect ($SysValue['connect']['host'], $SysValue['connect']['user_db'],  $SysValue['connect']['pass_db']);
mysql_select_db($SysValue['connect']['dbase']);
@mysql_query("SET NAMES 'cp1251'");
header('Content-Type: text/xml; charset=utf-8');

// Отрезаем длинный символы
function SubstrName($str){
$num=50; // Кол-во максимальное в имени символов
$len=strlen($str);
if($len > $num)
  return substr($str, 0, $num)."...";
  else return $str;
}


function win_utf8 ($in_text){ 
$output=""; 
$other[1025]="Ё"; 
$other[1105]="ё"; 
$other[1028]="Є"; 
$other[1108]="є"; 
$other[1030]="I"; 
$other[1110]="i"; 
$other[1031]="Ї"; 
$other[1111]="ї"; 

for ($i=0; $i<strlen($in_text); $i++){ 
if (ord($in_text{$i})>191){ 
  $output.="&#".(ord($in_text{$i})+848).";"; 
} else { 
  if (array_search($in_text{$i}, $other)===false){ 
   $output.=$in_text{$i}; 
  } else { 
   $output.="&#".array_search($in_text{$i}, $other).";"; 
  } 
} 
} 
return $output; 
} 


// Подключаем модули
include("../phpshop/inc/engine.inc.php");            // Модуль движка
include("../phpshop/inc/cache.inc.php");


// Подключаем кеш
$LoadItems=CacheReturnBase("cart");



$XML = ('<?xml version="1.0" encoding="UTF-8"?>
<dat>
	<itemsSum>6</itemsSum>
	<moveto>left</moveto>
	<defaultSpeed>2</defaultSpeed>
	<acceleration>40</acceleration>
	<itemsInterval>10</itemsInterval> 
	<scalePrirost>1.5</scalePrirost>
	<backgroundImage>/stockgallery/src.jpg</backgroundImage>
	<titleBlockAlpha>40</titleBlockAlpha>
	<blur>4</blur>
	<currency>'.win_utf8($LoadItems['Valuta'][$LoadItems['System']['dengi']]['code']).'</currency>
	<items>');
$sql="select * from ".$SysValue['base']['table_name2']." where spec='1' and  enabled='1' and parent_enabled='0' and sklad!='1'  order by  RAND() LIMIT 0, 6";
$result=mysql_query($sql);
$num=mysql_num_rows($result);

if($num<6){
$sql="select * from ".$SysValue['base']['table_name2']." where enabled='1' and parent_enabled='0' and sklad!='1' order by  RAND() LIMIT 0, 6";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
}



while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
	$name=win_utf8(SubstrName($row['name']));
	$baseinputvaluta=$row['baseinputvaluta'];
	$price=$row['price'];
	
	$defvaluta=$LoadItems['System']['dengi'];
	
	if($baseinputvaluta>0)
	if ($baseinputvaluta!==$defvaluta) {//Если присланная валюта отличается от базовой
		$vkurs=$LoadItems['Valuta'][$baseinputvaluta]['kurs'];
		$price=$price/$vkurs; //Приводим цену в базовую валюту
	}

	
	
	$price=($price+(($price*$LoadItems['System']['percent'])/100));
	
	$formatPrice = unserialize($LoadItems['System']['admoption']);
    $format=$formatPrice['price_znak'];
	$price=round($price,$format);
	
	
	// Если цены показывать только после аторизации
    $admoption=unserialize($LoadItems['System']['admoption']);
    if($admoption['user_price_activate']==1 and !$_SESSION['UsersId'])
    $price="***";

	
	$pic_small=$row['pic_small'];
	
    if(empty($pic_small))
    $pic_small="images/shop/no_photo.gif";
	
	
   $XML.= '<item price="'.$price.'" image="'.$pic_small.'" url="/shop/UID_'.$id.'.html">'.$name.'</item>';}

if($num==0)  $XML.= '<item price="" image="images/shop/no_photo.gif" url="/">'.win_utf8("Добавьте товары в базу").'</item>';


$XML.= ('


    </items>
</dat>');



echo $XML?>