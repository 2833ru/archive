<?php

// Подключаем библиотеку поддержки.
//require_once "./lib/config.php";
require_once "./lib/Subsys/JsHttpRequest/Php.php";
// Создаем главный объект библиотеки.
// Указываем кодировку страницы (обязательно!).
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
@mysql_query("SET NAMES 'cp1251'");




// Вывод картинки для подробного описания
function getFotoIconPodrobno($n,$f){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name35']." where parent=$n order by num";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
while(@$row = mysql_fetch_array(@$result))
    {
	$name=$row['name'];
	$name_s=str_replace(".","s.",$name);
        $name_b=str_replace(".","_big.",$name);
	$id=$row['id'];
	$info=$row['info'];
	$FotoArray[]=array(
	"id"=>$id,
	"name"=>$name,
	"name_s"=>$name_s,
        "name_b"=>$name_b,
	"info"=>$info
	);
}
	
$dBig='<div align="center" id="IMGloader" style="padding-bottom: 10px">
<a class=highslide onclick="return hs.expand(this)" href="'.$FotoArray[$f]["name_b"].'" target=_blank getParams="null">
 <img lowsrc="'.$FotoArray[$f-1]["name"].'" src="'.$FotoArray[$f]["name"].'" border="1" class="imgOn" alt="'.$info.'"  align="middle"></a><br>'.$FotoArray[$f]["info"].'
</div>';
	
	
	foreach($FotoArray as $k=>$v){

	$fL=$f-1;
	$fR=$f+1;
	
if($f==0){
  $fL=0;
  $f=1;
  $fR=2;
  }
  
if($fR == $num){
  
  $fRComSatrt="<!-- ";
  $fRComEnd=" -->";
  }

  
@$disp='
<tr>
  <td align="center">
  '.($f).'<br>
  <a href="javascript:fotoload('.$n.','.$fL.');"><img src="'.$FotoArray[$fL]["name_s"].'" alt="'.$FotoArray[$fL]["info"].'" border="1" class="imgOff" onmouseover="ButOn(this)" onmouseout="ButOff(this)"><br>
&laquo; Назад</a>
  </td>
  <td align="center">
  '.($f+1).'<br>
    <a href="javascript:fotoload('.$n.','.$f.');"><img src="'.$FotoArray[$f]["name_s"].'" alt="'.$FotoArray[$f]["info"].'" border="1" class="imgOn"></a><br>&nbsp;
  </td>
  <td align="center">'.@$fRComSatrt.'
  '.($f+2).'<br>
    <a href="javascript:fotoload('.$n.','.$fR.');"><img src="'.$FotoArray[$fR]["name_s"].'" alt="'.$FotoArray[$fR]["info"].'" border="1" class="imgOff" onmouseover="ButOn(this)" onmouseout="ButOff(this)"><br>
Вперед &raquo;</a>'.$fRComEnd.'
  </td>
</tr>
';
}
	
$d=$dBig;
if($num>1) $d.='
<table class="foto">
'.$disp.'
</table>
';
return $d;
}


$getFotoIconPodrobno=getFotoIconPodrobno($_REQUEST['xid'],$_REQUEST['fid']);

$_RESULT = array(
  'foto' => $getFotoIconPodrobno
); 
?>