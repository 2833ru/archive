<?php
/**
 * Flash баннер витрины товаров
 * @package PHPShopElementsDepricated
 * @author PHPShop Software
 * @version 1.3
 */

// Библиотеки
include("../phpshop/class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("string");

// Подключение к БД
$PHPShopBase = new PHPShopBase("../phpshop/inc/config.ini");
header('Content-Type: text/xml; charset=utf-8');

// Отрезаем длинный символы
function SubstrName($str) {
    $num=50; // Кол-во максимальное в имени символов
    $len=strlen($str);
    if($len > $num)
        return substr($str, 0, $num)."...";
    else return $str;
}

// Подключаем модули
include("../phpshop/inc/engine.inc.php");
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
	<currency>'.PHPShopString::win_utf8($LoadItems['Valuta'][$LoadItems['System']['dengi']]['code']).'</currency>
	<items>');
$sql="select * from ".$SysValue['base']['table_name2']." where spec='1' and  enabled='1' and parent_enabled='0' and sklad!='1'  order by  RAND() LIMIT 0, 6";
$result=mysql_query($sql);
$num=mysql_num_rows($result);

if($num<6) {
    $sql="select * from ".$SysValue['base']['table_name2']." where enabled='1' and parent_enabled='0' and sklad!='1' order by  RAND() LIMIT 0, 6";
    $result=mysql_query($sql);
    $num=mysql_num_rows($result);
}

while($row = mysql_fetch_array($result)) {
    $id=$row['id'];
    $name=PHPShopString::win_utf8(SubstrName($row['name']));
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

    $XML.= '<item price="'.$price.'" image="'.$pic_small.'" url="/shop/UID_'.$id.'.html">'.$name.'</item>';
}

if($num==0)  $XML.= '<item price="" image="images/shop/no_photo.gif" url="/">'.PHPShopString::win_utf8("Добавьте товары в базу").'</item>';

$XML.= ('
    </items>
</dat>');

// Вывод
echo $XML

?>