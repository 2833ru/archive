<?
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


include("../phpshop/inc/mail.inc.php");


function getFile1c($n){
global $SysValue;

$n=TotalClean($n,5);
$datas=TotalClean($_GET['datas'],5);

if(empty($_SESSION['UsersId']))
$sql="select id from ".$SysValue['base']['table_name1']." where id=$n and user=0 and datas=".$datas;
else 
$sql="select id from ".$SysValue['base']['table_name1']." where user=".$_SESSION['UsersId']."  and id=$n";


$result=mysql_query($sql);
@$num = mysql_num_rows(@$result);
if($num!=0){
$sql="select * from ".$SysValue['base']['table_name9']." where uid=$n order by id desc limit 1";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$cid=$row['cid'];
$datas=$row['datas'];


$order_t=date("m",$datas)."-".date("Y",$datas);

if(!empty($cid))
$file=$order_t."/".$cid;
$array=array($file,$cid);
return $array;
}


}


if(isset($orderId)){
echo $content;

switch($tip){
     case("doc"):
	 $f_r=".doc";
	 break;
	 
	 case("xls"):
	 $f_r=".xls";
	 break;
	 
	 case("htm"):
	 $f_r=".htm";
	 break;
	 
	 default:
	 $f_r=".htm";
	 break;
}

switch($list){
     case("accounts"):
	 $f_l="/accounts/";
	 break;
	 
	 case("invoice"):
	 $f_l="/invoice/";
	 break;
	 
	 default:
	 $f_l="/accounts/";
	 break;
}


$_Name=getFile1c($orderId);
$_Path = "../1cManager";
$file_1=$_Path.$f_l.$_Name[0].$f_r;

    if(file_exists($file_1)){
    header("Content-Description: File Transfer"); 
    header('Content-Type: application/force-download'); 
    header('Content-Disposition: attachment; filename='.$_Name[1].$f_r); 
    header("Content-Transfer-Encoding: binary"); 
    header('Content-Length: '.filesize($file_1));
    readfile($file_1); 
	}
	else {
	     header("Location: /error/");
         exit;
	     }

}
else {
header("Location: /error/");
exit;
}

?>