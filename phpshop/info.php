<?php
session_start();
require_once "./lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");

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

function DispSystems()// вывод настроек
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name3'];
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
foreach($row as $k=>$v)
$array[$k]=$v;
return $array;
}
$DispSystems=DispSystems();

function dataV($nowtime){
$Months = array("01"=>"января","02"=>"февраля","03"=>"марта", 
 "04"=>"апреля","05"=>"мая","06"=>"июня", "07"=>"июля",
 "08"=>"августа","09"=>"сентября",  "10"=>"октября",
 "11"=>"ноября","12"=>"декабря");
$curDateM = date("m",$nowtime); 
$t=date("d",$nowtime).".".$curDateM.".".date("Y",$nowtime); 
return $t;
}

@$fp = fopen("../index.php", "r");
if($fp)
{
$fstat = fstat($fp);
fclose($fp);
$FileDate=dataV($fstat['mtime']);
}


// Выбор файла
function GetFile($dir){
global $SysValue;
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
		$fstat = explode(".",$file);
		if($fstat[1] == "lic")
		  return $SysValue['license']['dir'].chr(47).$file;
        }
        closedir($dh);
    }
}

// Срок действия тех. поддержки
$GetFile=GetFile("../license/");
@$License=parse_ini_file("../".$GetFile,1);

$TechPodUntilUnixTime = $License['License']['SupportExpires'];
if(is_numeric($TechPodUntilUnixTime))
$TechPodUntil=dataV($TechPodUntilUnixTime);
  else $TechPodUntil=" - ";

$LicenseUntilUnixTime = $License['License']['Expires'];
if(is_numeric($LicenseUntilUnixTime))
$LicenseUntil=dataV($LicenseUntilUnixTime);
  else  $LicenseUntil=" - ";


if (function_exists('memory_get_usage')) {
$mem = memory_get_usage();
$_MEM=round($mem/1024,2)." Kb";
}else @$_MEM="неизвестно";


$Info="PHPShop System Info
---------------------------------------------

Версия: ".$SysValue['license']['product_name']."
Сборка: ".$SysValue['upload']['version']."
Память: ".@$_MEM." 
Кэширование: ".$SysValue['cache']['cache_mod']."
Дата изменения: ".$SysValue['cache']['last_modified']."
Дизайн: ".$DispSystems['skin']."
GZIP: ".$SysValue['my']['gzip']."; Сжатие: ".$SysValue['my']['gzip_level']."
Установлено: ".$FileDate."
Окончание лицензии: ".$LicenseUntil."
Окончание поддержки: ".$TechPodUntil."
GEOIP: ".$SysValue['geoip']['geoip']."; Zone: ".$SysValue['geoip']['geoip_zone']."

---------------------------------------------

Все права защищены. 2003-".date("Y")."
Copyright © www.phpshop.ru";

// Формируем результат прямо в виде PHP-массива!
$_RESULT = array(
  "info"     => $Info,
  'hello' => isset($_SESSION['hello'])? $_SESSION['hello'] : null
); 
?>