<?php
session_start();

// Библиотеки
include("../../class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("text");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("orm");

// Подключение к БД
$PHPShopBase = new PHPShopBase("../../inc/config.ini");
$PHPShopSystem = new PHPShopSystem();
include("../../inc/order.inc.php");             // Модуль кеша
include("../../inc/engine.inc.php");            // Модуль движка
include("../../inc/cache.inc.php");             // Модуль кеша

// Подключаем кеш
$LoadItems=CacheReturnBase($sid);

function printProduct($cat) {
    $disp=null;

    $PHPShopOrm = new PHPShopOrm();
    $result=$PHPShopOrm->query("select id,name,price,baseinputvaluta from ".$GLOBALS['SysValue']['base']['table_name2']." where (category='$cat' or dop_cat LIKE '%#$cat#%') and enabled='1' order by name");
    while($row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $uid=$row['uid'];
        $name=$row['name'];
        $price=$row['price'];
        $price=($price+(($price*$LoadItems['System']['percent'])/100));
        $baseinputvaluta=$row['baseinputvaluta'];

        // Выборка из базы нужной колонки цены
        if(session_is_registered('UsersStatus')) {
            $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
            if($GetUsersStatusPrice>1) {
                $pole="price".$GetUsersStatusPrice;
                $pricePersona=$row[$pole];
                if(!empty($pricePersona))
                    $price=($pricePersona+(($pricePersona*$System['percent'])/100));
            }
        }


        // Если цены показывать только после аторизации
        $admoption=unserialize($LoadItems['System']['admoption']);
        if($admoption['user_price_activate']==1 and !$_SESSION['UsersId']) {
            $price="~";
            $valuta="";
        }else {
            $price=GetPriceValuta($price,"",$baseinputvaluta);
            //    $price=GetPriceValuta($price);
            $valuta=GetValuta();
        }

        $disp.=PHPShopText::tr($name,$price." ".$valuta);
    }
    return $disp;
}

// Вывод каталогов
function printCatalog($dir=false) {
    global $SysValue,$LoadItems;
    $dis=null;

    // Если задан каталог
    if(!empty($dir)) {
        if(!$LoadItems['Catalog'][$dir]['name']) return 404;

        $parent=$LoadItems['Catalog'][$dir]['parent_to'];
        $dis.=PHPShopText::tr(PHPShopText::b($LoadItems['Catalog'][$parent]['name']." / ".$LoadItems['Catalog'][$dir]['name']),'');
        $dis.=printProduct($dir);

    }
    else {

        foreach($LoadItems['CatalogKeys'] as $cat=>$val) {

            $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$cat);
            if(count($podcatalog_id)==0) {
                $dis.=PHPShopText::tr(PHPShopText::b($LoadItems['Catalog'][$val]['name']." / ".$LoadItems['Catalog'][$cat]['name']),'');
                $dis.=printProduct($cat);
            }
        }
    }
    return $dis;
}



if($_GET['catId'] == "ALL" or empty($_GET['catId'])) $cat=null;
else $cat=$_GET['catId'];

PHPShopParser::set('name',$PHPShopSystem->getName());
PHPShopParser::set('price',printCatalog($cat));
PHPShopParser::set('date',date("d-m-y"));
PHPShopParser::file('../../lib/templates/print/price.tpl');
?>