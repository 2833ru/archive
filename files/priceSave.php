<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Генерации Прайса            |
+-------------------------------------+
*/

session_start();

// Парсируем установочный файл
$SysValue=parse_ini_file("../phpshop/inc/config.ini",1);
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
include("../phpshop/inc/engine.inc.php");  

$System=DispSystems();

// Пишем GZIP файлы
function gzcompressfile($source,$level=false){ 
   $dest=$source.'.gz'; 
   $mode='wb'.$level; 
   $error=false; 
   if($fp_out=gzopen($dest,$mode)){ 
       if($fp_in=fopen($source,'rb')){ 
           while(!feof($fp_in)) 
               gzwrite($fp_out,fread($fp_in,1024*512)); 
           fclose($fp_in); 
           } 
         else $error=true; 
       gzclose($fp_out); 
	   unlink($source);
	   rename($dest, $source.'.bz2');
       } 
     else $error=true; 
   if($error) return false; 
     else return $dest; 
   } 


$defvaluta=$System['dengi'];
$formatPrice = unserialize($System['admoption']);
$format=$formatPrice['price_znak'];



if(@$catId == "ALL") $str="";
 elseif(is_numeric(@$catId)) $str=" and (category='$catId' or dop_cat LIKE '%#$catId#%')";

$sql="select * from ".$SysValue['base']['table_name2']." where enabled='1'".@$str;
$result=mysql_query($sql);
$num=0;
$csv="Артикул;Имя;Цена\n";
while($row = mysql_fetch_array($result))
    {
    $name=$row['name'];
	$price=$row['price'];
	$price=($price+(($price*$System['percent'])/100));
	$uid=$row['uid'];
	$id=$row['id'];
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



//получаем исходную цену
if ($baseinputvaluta) { //Если прислали баз. валюту
	if ($baseinputvaluta!==$defvaluta) {//Если присланная валюта отличается от базовой
		$sql2="select kurs from ".$SysValue['base']['table_name24']." where id=".$baseinputvaluta;
		$result2=mysql_query($sql2);
	        $row2 = mysql_fetch_array($result2);
		$vkurs=$row2['kurs'];
		$price=$price/$vkurs; //Приводим цену в базовую валюту



		$price=number_format($price,$format,'.', ' ');
	}
} //Если прислали баз. валюту

// Если цены показывать только после аторизации
$admoption=unserialize($System['admoption']);
if($admoption['user_price_activate']==1 and !$_SESSION['UsersId']){
    $price="~";
}
	
	$csv.="$uid;$name;$price\n";
	}
	
 $file="base_".date("d_m_y_His").".csv";
  @$fp = fopen("price/".$file, "w+");
  if ($fp) {
  fputs($fp, $csv);
  fclose($fp);
  $sorce="price/".$file;
  }
  // Пишес  GZIP
  if(@$gzip == "true"){
  gzcompressfile($sorce);
  //header('Content-Type: application/force-download'); 
  //header('Content-Disposition: attachment; filename="'.$file.'"'); 
  header("Location: price/".$file.".bz2");
  //readfile("price/".$file.".bz2"); 
  }
  else {
  //header('Content-Type: application/force-download'); 
  //header('Content-Disposition: attachment; filename="'.$file.'"'); 
  header("Location: ".$sorce);
  //header('Content-Length: '.filesize($sorce));
  //readfile($sorce); 
  }
?>