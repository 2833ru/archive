<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Генерации                   |
+-------------------------------------+
*/



// Отрезаем до точки
function mySubstr($str,$a){
if(empty($str)) return $str;
$str = htmlspecialchars(strip_tags($str));
for ($i = 1; $i <= $a; $i++) {
	if($str{$i} == ".") $T=$i;
}
if($T<1) return substr($str, 0, $a)."...";
  else return substr($str, 0, $T+1);
}


// Выборка колонки цен для статуса пользователя
function GetUsersStatusPrice($n){
global $SysValue;
$sql="select price from ".$SysValue['base']['table_name28']." where id=$n";
$result=mysql_query($sql);
$row = mysql_fetch_array(@$result);
return $row['price'];
}

// Выборка из базы нужной колонки цены
function ReturnTruePriceUser($n,$price)
{
global $SysValue,$LoadItems,$_SESSION;
if(session_is_registered('UsersStatus')){
$GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
$pole="price".$GetUsersStatusPrice;
$sql="select $pole from ".$SysValue['base']['table_name2']." where id='$n'";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
if(!empty($row[$pole])) return $row[$pole];
  else return $price;
} else return $price;
}


// Характеристики
function Sorts($sql=false)
 {
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name21'].$sql;
$result=mysql_query($sql) or  die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
$Sorts='';
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$page=$row['page'];
	$array=array(
	"id"=>$id,
	"page"=>$page,
	"name"=>$name
	);
	$Sorts[$id]=$array;
	}
@$SysValue['sql']['num']++;
return $Sorts;
 }
 
// Каталоги Характеристик
function CatalogSorts()
 {
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name20'];
@$result=mysql_query($sql);
$Sorts='';
while (@$row = mysql_fetch_array(@$result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$category=$row['category'];
	$filtr=$row['filtr'];
	$flag=$row['flag'];
	$goodoption=$row['goodoption'];
	$page=$row['page'];
	$array=array(
	"id"=>$id,
	"name"=>$name,
	"category"=>$category,
	"filtr"=>$filtr,
	"page"=>$page,
	"flag"=>$flag,
	"goodoption"=>$goodoption
	);
	$Sorts[$id]=$array;
	}
@$SysValue['sql']['num']++;
return $Sorts;
 }

// Каталог статей
function CatalogPage()
 {
global $SysValue;
$sql="select id,name,parent_to from ".$SysValue['base']['table_name29'];
$result=mysql_query($sql) or  die("".PHPSHOP_error(102,$SysValue['my']['error_tracer'])."");
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$parent_to=$row['parent_to'];
	$array=array(
	"id"=>$id,
	"name"=>$name,
	"parent_to"=>$parent_to
	);
	$Catalog[$id]=$array;
	}
@$SysValue['sql']['num']++;
return $Catalog;
 }

// Вывод каталогов страниц
function  CatalogPageKeys()
 {
global $SysValue;
$sql="select id,parent_to  from ".$SysValue['base']['table_name29']." order by num";
$result=mysql_query($sql);
while (@$row = mysql_fetch_array(@$result))
    {
	$id=$row['id'];
	$parent_to=$row['parent_to'];
	$Catalog[$id]=$parent_to;
	}
@$SysValue['sql']['num']++;
return $Catalog;
 }
 
// Вывод каталогов
function  CatalogKeys()
 {
global $SysValue;
$sql="select id,parent_to,num  from ".$SysValue['base']['table_name']." order by num";
$result=mysql_query($sql);
while (@$row = mysql_fetch_array(@$result))
    {
	$id=$row['id'];
	$parent_to=$row['parent_to'];
	$Catalog[$id]=$parent_to;
	}
@$SysValue['sql']['num']++;
return $Catalog;
 }
 
// Вывод каталогов
function  Catalog()
 {
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name'];
$result=mysql_query($sql);
while (@$row = mysql_fetch_array(@$result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$parent_to=$row['parent_to'];
	$num_row=$row['num_row'];
	$num_cow=$row['num_cow'];
	$sort=$row['sort'];
	$servers=$row['servers'];
	$title_enabled=$row['title_enabled'];
	$descrip_enabled=$row['descrip_enabled'];
	$keywords_enabled=$row['keywords_enabled'];
	$skin_enabled=$row['skin_enabled'];
	$skin=$row['skin'];
	@$array=array(
	"id"=>$id,
	"name"=>$name,
	"parent_to"=>$parent_to,
	"num_row"=>$num_row,
	"num_cow"=>$num_cow,
	"sort"=>$sort,
	"title_enabled"=>$title_enabled,
	"descrip_enabled"=>$descrip_enabled,
	"keywords_enabled"=>$keywords_enabled,
	"skin_enabled"=>$skin_enabled,
	"skin"=>$skin,
	"order_by"=>$row['order_by'],
	"order_to"=>$row['order_to'],
	"parent_len"=>$row['parent_len'],
	"vid"=>$row['vid'],
	"servers"=>$servers
	);
	$Catalog[$id]=$array;
	}
@$SysValue['sql']['num']++;
return $Catalog;
 }
 

// Вывод продуктов
function  Product($str="")
 {
global $SysValue,$LoadItems,$_SESSION;

if($str != "none"){

$System=DispSystems();
$sql="select id,uid,name,category,price,price_n,sklad,odnotip,vendor,title_enabled,datas,page,user,descrip_enabled,keywords_enabled,pic_small,pic_big,parent,baseinputvaluta  from ".$SysValue['base']['table_name2'].$str;
$result=mysql_query($sql);
while (@$row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$uid=$row['uid'];
	$name=$row['name'];
	$category=$row['category'];
	$price=$row['price'];
    $sklad=$row['sklad'];
	$priceNew=$row['price_n'];
	$price=($price+(($price*$System['percent'])/100));
	$title_enabled=$row['title_enabled'];
	$descrip_enabled=$row['descrip_enabled'];
	$keywords_enabled=$row['keywords_enabled'];
	$datas=$row['datas'];
	$page=explode(",",$row['page']);
	$user=$row['user'];
	$pic_small=$row['pic_small'];
	$pic_big=$row['pic_big'];
	
	
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
	
	// Если есть новая цена
	if($priceNew>0){
	$priceNew=($priceNew+(($priceNew*$System['percent'])/100));
	$priceNew=number_format($priceNew,"2",".","");
	//$priceNew=$priceNew." ".$System['dengi'];
	}
	
	// Проверка на нулевую цену
	if(!is_numeric($row['price']))
	$sklad = 1;
	
	$uid=$row['uid'];
	$odnotip=explode(",",$row['odnotip']);
	$parent=explode(",",$row['parent']);
	$vendor=$row['vendor'];
	$baseinputvaluta=$row['baseinputvaluta'];	
	
	$array=array(
	"category"=>$category,
	"id"=>$id,
    "name"=>$name,
	"price"=>$price,
    "priceNew"=>$priceNew,
    "priceSklad"=>$sklad,
	"odnotip"=>$odnotip,
	"parent"=>$parent,
	"vendor"=>$vendor,
    "uid"=>$uid,
	"pic_small"=>$pic_small,
	"pic_big"=>$pic_big,
	"title_enabled"=>$title_enabled,
	"descrip_enabled"=>$descrip_enabled,
	"keywords_enabled"=>$keywords_enabled,
	"datas"=>$datas,
	"page"=>$page,
	"baseinputvaluta"=>$baseinputvaluta,
	"user"=>$user
	);
	$Products[$id]=$array;
	}
@$SysValue['sql']['num']++;
return @$Products;
}
 }
 


 
// Вывод кол-ва
function NumFrom($from_base,$query) 
{
global $SysValue;
$sql="select COUNT('id') as count from ".$SysValue['base'][$from_base]." ".$query;
@$result=mysql_query(@$sql);
@$row = mysql_fetch_array(@$result);
@$num=$row['count'];
return @$num;
}

function DispSystems()// вывод настроек
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name3'];
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
if(is_array($row))
foreach($row as $k=>$v)
$array[$k]=$v;

// Валюта по умолчанию
//$array['dengi']=4;

@$SysValue['sql']['num']++;
return $array;
}

function DispValuta()// вывод валют
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name24']." where enabled='1' order by num";
$result=mysql_query($sql);
while (@$row = mysql_fetch_array($result))
    {
    $id=$row['id'];
	$name=$row['name'];
	$code=$row['code'];
	$iso=$row['iso'];
	$kurs=$row['kurs'];
	$array=array(
	"id"=>$id,
	"name"=>"$name",
	"code"=>"$code",
	"iso"=>"$iso",
	"kurs"=>"$kurs"
	);
	$Valuta[$id]=$array;
	}
@$SysValue['sql']['num']++;
return $Valuta;
}

function Open($page)// Проверка существования файла
{
global $SysValue;
$page=$page.".php";
$handle=@opendir($SysValue['my']['default_page_dir']) or die("".PHPSHOP_error(103,$SysValue['my']['error_tracer'])."");;
while ($file = readdir($handle))
    {
	if($file==$page)
	  {
		return $page;
		exit;
	   }
	 }
return "404";
}


function Vivod_mini_cart()// Вывод мини корзины
{
global $SysValue,$LoadItems;
$cart=@$_SESSION['cart'];
$compare=@$_SESSION['compare'];

if(count($cart)>0)
{
if(is_array($cart))
foreach($cart as $j=>$v)
  {
   @$sum+=$cart[$j]['price']*$cart[$j]['num'];
   @$sum_r=@$sum*$LoadItems['System']['kurs'];
   @$num+=$cart[$j]['num'];
  }
$SysValue['other']['orderEnabled']= "block";
}
 else
     {
	 $sum="--";
	 $num="--";
	 $SysValue['other']['orderEnabled']= "none";
	 }

if(count($compare)>0)
{
	if(is_array($compare)) {foreach($compare as $j=>$v) {@$numcompare=count($compare);}}
	$SysValue['other']['compareEnabled']= "block";
} else {
	 $numcompare="--";
	 $SysValue['other']['compareEnabled']= "none";
}
$SysValue['other']['numcompare']= $numcompare;


// Определяем переменые
$SysValue['other']['tovarNow']= $SysValue['lang']['cart_tovar_now'];
$SysValue['other']['num']= $num;
$SysValue['other']['sum']= GetPriceValuta($sum);
$SysValue['other']['summaNow']= $SysValue['lang']['cart_summa_now'];
$SysValue['other']['money']= GetValuta();
$SysValue['other']['orderNow']= $SysValue['lang']['cart_order_now'];

// Подключаем шаблон
@$disp=ParseTemplateReturn($SysValue['templates']['menu_cart']);
return $disp;
}



function Vivod_menu_left()// вывод левого меню
{
global $SysValue,$REQUEST_URI;
$sql="select * from ".$SysValue['base']['table_name14']." where flag='1' and element='0' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
{
 if(empty($row['dir'])){
  // Определяем переменые
  $SysValue['other']['leftMenuName']= $row['name'];
  $SysValue['other']['leftMenuContent']= stripslashes($row['content']);
  // Подключаем шаблон
  @$dis.=ParseTemplateReturn($SysValue['templates']['left_menu']);
   }else{
        $dirs= explode(",",$row['dir']);
        foreach($dirs as $dir)
          if(strpos($REQUEST_URI, $dir) or $REQUEST_URI==$dir){
            // Определяем переменые
            $SysValue['other']['leftMenuName']= $row['name'];  
            $SysValue['other']['leftMenuContent']= stripslashes($row['content']);
			// Подключаем шаблон
            @$dis.=ParseTemplateReturn($SysValue['templates']['left_menu']);
          }
        }

}

@$SysValue['sql']['num']++;
return @$dis;
}

function Vivod_forma_valuta()// вывод формы валюты
{
global $SysValue,$LoadItems,$_SESSION;

if(isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
  else $valuta=$LoadItems['System']['dengi'];
  
if(is_array($LoadItems['Valuta']))
foreach($LoadItems['Valuta'] as $k=>$v){
  if($valuta == $LoadItems['Valuta'][$k]['id']) $sel="selected";
    else $sel="";
@$name.= '<option value="'.$LoadItems['Valuta'][$k]['id'].'" '.$sel.' >'.$LoadItems['Valuta'][$k]['name'].'</option>';
}
// Определяем переменые
$SysValue['other']['leftMenuName']= "Валюта";
$SysValue['other']['leftMenuContent']="
<form name=ValutaForm method=post>
<select name=\"valuta\" onchange=\"ChangeValuta()\">
".@$name."
</select>
</form>
";

// Подключаем шаблон
if(!ParseTemplateReturn($SysValue['templates']['valuta_forma']))
@$dis.=ParseTemplateReturn($SysValue['templates']['left_menu']);
else @$dis.=ParseTemplateReturn($SysValue['templates']['valuta_forma']);

return @$dis;
}


function Vivod_menu_right()// вывод левого меню
{
global $SysValue,$REQUEST_URI;
$sql="select * from ".$SysValue['base']['table_name14']." where flag='1' and element='1' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
{
if(empty($row['dir'])){
// Определяем переменые
$SysValue['other']['leftMenuName']= $row['name'];
$SysValue['other']['leftMenuContent']= stripslashes($row['content']);
// Подключаем шаблон
if(!ParseTemplateReturn($SysValue['templates']['right_menu']))
@$dis.=ParseTemplateReturn($SysValue['templates']['left_menu']);
else @$dis.=ParseTemplateReturn($SysValue['templates']['right_menu']);
}
else{
$dirs= explode(",",$row['dir']);
foreach($dirs as $dir)
if(strpos($REQUEST_URI, $dir) or $REQUEST_URI==$dir){
// Определяем переменые
$SysValue['other']['leftMenuName']= $row['name'];
$SysValue['other']['leftMenuContent']= stripslashes($row['content']);
// Подключаем шаблон
if(!ParseTemplateReturn($SysValue['templates']['right_menu']))
@$dis.=ParseTemplateReturn($SysValue['templates']['left_menu']);
else @$dis.=ParseTemplateReturn($SysValue['templates']['right_menu']);
}}
}
@$SysValue['sql']['num']++;
return @$dis;
}


function Vivod_menu_top()// вывод главного меню
{
global $SysValue,$_SESSION;

// Страницы только для аторизованных
if(isset($_SESSION['UsersId'])) $sort=" ";
 else $sort=" and secure !='1' ";
$sql="select * from ".$SysValue['base']['table_name11']." where category='1000' and enabled='1' $sort order by num";

$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
{
// Определяем переменые
$SysValue['other']['topMenuName']= $row['name'];
$SysValue['other']['topMenuLink']= $row['link'];

// Подключаем шаблон
@$dis.=ParseTemplateReturn($SysValue['templates']['top_menu']);
}
@$SysValue['sql']['num']++;
return @$dis;
}


function Skin_select($skin)// вывод смены диза
{
global $SysValue,$LoadItems;

$Options=unserialize($LoadItems['System']['admoption']);

if($Options['user_skin'] == 1){
$dir=$SysValue['dir']['templates'].chr(47);
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
		
		    if($skin == $file)
			$sel="selected";
			  else $sel="";
		
		    if($file!="." and $file!=".." and $file!="index.html")
            @$name.= "<option value=\"$file\" $sel>Шаблон $file</option>";
        }
        closedir($dh);
    }
}
$SysValue['other']['leftMenuContent']="

<div style=\"padding:10px\"><form name=SkinForm method=post>
<select name=\"skin\" onchange=\"ChangeSkin()\">
".@$name."
</select>
</form>
</div>

";

// Определяем переменые
$SysValue['other']['leftMenuName']= "Сменить дизайн";

// Подключаем шаблон
@$dis=ParseTemplateReturn($SysValue['templates']['left_menu']);

return @$dis;
}}



// Вывод содержания страниц
function Open_from_base($name,$str="none")
{
global $SysValue;
$name=TotalClean($name,2);
if($str=="none") $sql="select * from ".$SysValue['base']['table_name11']." where link='$name'";
else $sql="select * from ".$SysValue['base']['table_name11']." $str";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
@$SysValue['sql']['num']++;
$names=$row['name'];
$content=stripslashes($row['content']);
$link=$row['link'];
$ar=array($names,$content,$link);
return $ar;
}


function Vivod_cat_all_num($n)// выбор кол-ва товаров из данного подкатолога
{
global $SysValue;
$n=TotalClean($n,1);
$sql="select id from ".$SysValue['base']['table_name2']." where category=$n and enabled=1";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
return $num;
}


// Парсер для изображений
function ImgParser($img){
$array=split("\"",$img);
while (list($key, $value) = each($array))
    //if (preg_match("/\//",$value))
  if (preg_match("/Image/",$value))
    return $array[$key];
return "images/shop/no_photo.gif";
}   

// Привязанные страницы
function Vivod_page_products($productID)
{
global $SysValue,$REQUEST_URI;
$sql="select * from ".$SysValue['base']['table_name1']." where flag='1' and element='1' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
{
if(empty($row['dir'])){
// Определяем переменые
$SysValue['other']['leftMenuName']= $row['name'];
$SysValue['other']['leftMenuContent']= $row['content'];
// Подключаем шаблон
if(!ParseTemplateReturn($SysValue['templates']['right_menu']))
@$dis.=ParseTemplateReturn($SysValue['templates']['left_menu']);
else @$dis.=ParseTemplateReturn($SysValue['templates']['right_menu']);
}
else{
$dirs= explode(",",$row['dir']);
foreach($dirs as $dir)
if(strpos($REQUEST_URI, $dir)){
// Определяем переменые
$SysValue['other']['leftMenuName']= $row['name'];
$SysValue['other']['leftMenuContent']= $row['content'];
// Подключаем шаблон
if(!ParseTemplateReturn($SysValue['templates']['right_menu']))
@$dis.=ParseTemplateReturn($SysValue['templates']['left_menu']);
else @$dis.=ParseTemplateReturn($SysValue['templates']['right_menu']);
}}


}
return @$dis;
}

// Вывод городов доставки
require_once "delivery.inc.php";


// Вывод стоимости доставки
function GetDeliveryPrice($deliveryID,$sum,$weight=0){
global $SysValue;

$deliveryID=TotalClean($deliveryID,1);
if(!empty($deliveryID)){
	$sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID' and enabled='1'";
	$result=mysql_query($sql);
	$num=mysql_numrows($result);
	$row = mysql_fetch_array($result);
	if($num == 0){
		$sql="select * from ".$SysValue['base']['table_name30']." where flag='1' and enabled='1'";
		$result=mysql_query($sql);
		$row = mysql_fetch_array($result);
	}
} else {
	$sql="select * from ".$SysValue['base']['table_name30']." where flag='1' and enabled='1'";
	$result=mysql_query($sql);
	$row = mysql_fetch_array($result);
}

@$SysValue['sql']['num']++;

if($row['price_null_enabled'] == 1 and $sum>=$row['price_null']) {
	return 0;
} else {
	if ($row['taxa']>0) {
		$addweight=$weight-500;
		if ($addweight<0) {
			$addweight=0; 
			$at='';
		} else {
			$at='';
//			$at='Вес: '.$weight.' гр. Превышение: '.$addweight.' гр. Множитель:'.ceil($addweight/500).' = ';
		}
		$addweight=ceil($addweight/500)*$row['taxa'];
		$endprice=$row['price']+$addweight;
		return $at.$endprice;
	} else {
		return $row['price'];
	}
}
}//endfunct

// Вывод доставки
function GetDeliveryBase($deliveryID,$name){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name30']." where id='$deliveryID'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
return $row[$name];
}

// Вывод методов оплаты
function GetOplataMetod(){
global $SysValue;

$sql="select * from ".$SysValue['base']['table_name48']." where enabled='1' order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result)){
     $name=$row['name'];
	 $id=$row['id'];
	 @$dis.='<option value="'.$id.'" >'.$name.'</option>';
	 }

$disp='<select name="order_metod">
'.@$dis.'
</select>';
return $disp;
}

// Вывод страниц списком
function GetSelectPage(){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name11']." order by name";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result)){
@$dis.='<OPTION value="'.$row['link'].'.html">'.$row['name'];
}
return @$dis;
}

// Promo - преобразования
function NameToLatin($str){
$str=strtolower($str);
$str=str_replace("/", "", $str);
$str=str_replace("\\", "", $str);
$str=str_replace("(", "", $str);
$str=str_replace(")", "", $str);
$str=str_replace(":", "", $str);
$str=str_replace("-", "", $str);
$str=str_replace(" ", "", $str);

$_Array=array(
"а"=>"a",
"б"=>"b",
"в"=>"v",
"г"=>"g",
"д"=>"d",
"е"=>"e",
"ё"=>"e",
"ж"=>"gh",
"з"=>"z",
"и"=>"i",
"й"=>"i",
"к"=>"k",
"л"=>"l",
"м"=>"m",
"н"=>"n",
"о"=>"o",
"п"=>"p",
"р"=>"r",
"с"=>"s",
"т"=>"t",
"у"=>"u",
"ф"=>"f",
"х"=>"h",
"ц"=>"c",
"ч"=>"ch",
"ш"=>"sh",
"щ"=>"sh",
"ъ"=>"i",
"ы"=>"yi",
"ь"=>"i",
"э"=>"a",
"ю"=>"u",
"я"=>"ya",
"А"=>"a",
"Б"=>"b",
"В"=>"v",
"Г"=>"g",
"Д"=>"d",
"Ё"=>"e",
"Ж"=>"gh",
"З"=>"z",
"И"=>"i",
"Й"=>"i",
"К"=>"k",
"Л"=>"l",
"М"=>"m",
"Н"=>"n",
"О"=>"o",
"П"=>"п",
"Р"=>"r",
"С"=>"s",
"Т"=>"t",
"У"=>"u",
"Ф"=>"f",
"Х"=>"h",
"Ц"=>"c",
"Ч"=>"ch",
"Ш"=>"sh",
"Щ"=>"sh",
"Э"=>"a",
"Ю"=>"u",
"Я"=>"ya",
"."=>"_",
"$"=>"i",
"%"=>"i",
"&"=>"and"
);

$chars = preg_split('//', $str, -1, PREG_SPLIT_NO_EMPTY);

foreach($chars as $val)
if(empty($_Array[$val])) @$new_str.=$val;

return @$new_str;
}

?>