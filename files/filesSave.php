<?
// Парсируем установочный файл
$SysValue=parse_ini_file("../phpshop/inc/config.ini",1);
  while(list($section,$array)=each($SysValue))
                while(list($key,$value)=each($array))
$SysValue['other'][chr(73).chr(110).chr(105).ucfirst(strtolower($section)).ucfirst(strtolower($key))]=$value;


if(isset($_GET['F'])){

$code=str_replace("$",$SysValue['my']['digital_pass2'],$F);
$code=str_replace("!",$SysValue['my']['digital_pass1'],$code);
$code=base64_decode($code);
$code=unserialize($code);


$files=$code['files'];
$time=$code['time'];


$file_1="../".$files;
$_Name=pathinfo($file_1);

if(in_array($_Name['extension'],array('php','ini','html','tpl'))) exit('<h3>Запрещенный формат...</h3>');

if($time>date("U")){
if(file_exists($file_1)){
    header("Content-Description: File Transfer"); 
    header('Content-Type: application/force-download'); 
    header('Content-Disposition: attachment; filename='.$_Name['basename']); 
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
}} else exit("<h3>Ссылка просрочена...</h3>");

?>