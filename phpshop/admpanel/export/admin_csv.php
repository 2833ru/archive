<?php
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);


class ReadCsv {
   var $CsvContent;
   var $ReadCsvRow;
   var $TableName;
   var $Sklad_status;
   var $Debug;
   var $TotalUpdate=0;
   
   
   function ReadCsvRow(){
   $this->ReadCsvRow = split("\n",$this->CsvContent);
   array_shift($this->ReadCsvRow);
   array_pop($this->ReadCsvRow);
   }
   
   function PrintThis($name,$string){
	  echo"<br><u><em>".$name."</em></u><pre>";
      print_r($string);
      echo"</pre><br>";
	  }
   
   function CsvToArray(){
       while (list($key, $val) = each($this->ReadCsvRow)) {
       $array1=split(";",$val);
            $OutArray[$array1[0]]=$this->CleanStr($array1);
       }
   return $OutArray;
   }
   
   
   function ReadCsv($CsvContent,$table_name2,$sklad_status){
   $this->CsvContent = $CsvContent;
   $this->TableName = $table_name2;
   $this->Sklad_status = $sklad_status;
   $this->ReadCsvRow();
   }
   
   function DoUpdatebase1(){
    $CsvToArray = $this->CsvToArray();
	foreach ($CsvToArray as $v)
	@$return.=$this->PrintResultDo($v);
	return $return;
   }
   
   function DoUpdatebase2(){
    $CsvToArray = $this->CsvToArray();
	foreach ($CsvToArray as $v)
	$this->UpdateBase($v);
   }

   function Zero($a){
   if($a!=0) return 1;
    else return 0;
   }
   
   
   function DoCheckBid($bid,$cbid){
   
   if(!empty($bid)) $yml_bid_enabled=1;
    else $yml_bid_enabled=0;
	
   if(!empty($cbid)) $yml_cbid_enabled=1;
    else $yml_cbid_enabled=0;

   $yml_bid_array=array(
"bid_enabled"=>$yml_bid_enabled,
"bid"=>$bid,
"cbid_enabled"=>$yml_cbid_enabled,
"cbid"=>$cbid
);
   return serialize($yml_bid_array);
   }
   
   function CleanStr($str){
   $a= str_replace("\"", "", $str);
   return str_replace("'", "", $a);
   }
   
   
   function UpdateBase($CsvToArray){

   
   

$Default=0; // Флаг, не трогаем наличие
// Склад
switch($this->Sklad_status){
  
       case(3):
	   if($CsvToArray[10]<1) {
	      $sklad=1;
		  $enabled=1;
		  }
	     else {
		 $sklad=0;
		 $enabled=1;
		 }
	   break;
	   
	   case(2):
	   if($CsvToArray[10]<1) {
	     $enabled=0;
		 $sklad=0;
		 }
	     else {
		 $enabled=1;
		 $sklad=0;
		 }
	   break;
	   
	   default: 
	   $Default=1; // Флаг, не трогаем наличие
	   break;
  }
   
$sql="UPDATE ".$this->TableName." SET ";

if(!empty($CsvToArray[2]))
$sql.="name='".trim($CsvToArray[2])."',";

if(!empty($CsvToArray[1]))
$sql.="uid='".trim($CsvToArray[1])."',";

if(!empty($CsvToArray[3]))
$sql.="price='".trim($CsvToArray[3])."',";

if(!empty($CsvToArray[10]) and $Default==0)
$sql.="sklad='".$sklad."',";

if(!empty($CsvToArray[4]))
$sql.="price2='".trim($CsvToArray[4])."',";

if(!empty($CsvToArray[5]))
$sql.="price3='".trim($CsvToArray[5])."',";

if(!empty($CsvToArray[6]))
$sql.="price4='".trim($CsvToArray[6])."',";

if(!empty($CsvToArray[7]))
$sql.="price5='".trim($CsvToArray[7])."',";

if(!empty($CsvToArray[8]))
$sql.="newtip='".trim($CsvToArray[8])."',";

if(!empty($CsvToArray[9]))
$sql.="spec='".trim($CsvToArray[9])."',";

if(isset($CsvToArray[10]))
$sql.="items='".trim($CsvToArray[10])."',";

if(!empty($CsvToArray[11]))
$sql.="weight='".trim($CsvToArray[11])."',";

if(!empty($CsvToArray[12]))
$sql.="num='".trim($CsvToArray[13])."',";

if($Default==0)
$sql.="enabled='".$enabled."',";

// Заглушка
$sql.="user='".$_SESSION['idPHPSHOP']."' ";

$sql.="where id='".$CsvToArray[0]."'";

// Отладка
//$this->Debug=$sql;
   $result=mysql_query($sql);
   $this->TotalUpdate++;
   }

function PrintResultDo($CsvToArray){
static $n;
	$disp="
		<tr class='row' style=\"padding:3\" onmouseover=\"show_on('r".$CsvToArray[0]."')\" id=\"r".$CsvToArray[0]."\" onmouseout=\"show_out('r".$CsvToArray[0]."')\">
     <td align=center>".(@$n+1)."</td>
	 <td align=center>".$CsvToArray[0]."</td>
	 <td >".$CsvToArray[1]."</td>
	 <td >".$CsvToArray[2]."</td>
	 <td >".$CsvToArray[3]."</td>
	 <td >".$CsvToArray[4]."</td>
	 <td >".$CsvToArray[5]."</td>
	 <td >".$CsvToArray[6]."</td>
	 <td >".$CsvToArray[7]."</td>
	 <td align=center>".Ors($CsvToArray[8])."</td>
	 <td align=center>".Ors($CsvToArray[9])."</td>
	 <td >".$CsvToArray[10]."</td>
	 <td >".$CsvToArray[11]."</td>
	 <td >".$CsvToArray[12]."</td>
	</tr>
	";
	$n++;
	return $disp;
   }
}

function Ors($n){
   if($n==0) return "<font color=FF0000>нет</font>";
   elseif($n==1) return "да";
   else return "non";
   }

// Проверка расширения файла
function getExt($sFileName)//ffilter
{
    $sTmp=$sFileName;
    while($sTmp!="") {
        $sTmp=strstr($sTmp,".");
        if($sTmp!="") {
            $sTmp=substr($sTmp,1);
            $sExt=$sTmp;
        }
    }
    $pos=stristr($sFileName, "php");
    $pos2=stristr($sFileName, "phtm");
    if($pos === false and $pos2 === false) return strtolower($sExt);
}

// Load JsHttpRequest backend.
require_once "../../lib/JsHttpRequest/JsHttpRequest.php";
// Create main library object. You MUST specify page encoding!
$JsHttpRequest =& new JsHttpRequest("windows-1251");
// Store resulting data in $_RESULT array (will appear in req.responseJs).


// Расширение
$_FILES['file']['ext']=getExt($_FILES['file']['name']);

if($_REQUEST['page']=="predload" and $_FILES['file']['ext']=="csv"){

// Загружаем
if(move_uploaded_file(@$_FILES['file']['tmp_name'], "../csv/".@$_FILES['file']['name']))
@$fp = fopen("../csv/".@$_FILES['file']['name'], "r");

if ($fp) {
  $fstat = fstat($fp);
  $CsvContent=fread($fp,$fstat['size']);
  fclose($fp);

  $ReadCsv = new ReadCsv($CsvContent,$table_name2,$option['sklad_status']);
  
$interface.='
<div id=interfacesWin name=interfacesWin align="left" style="width:100%;height:580;overflow:auto"> 
<TABLE style="border: 1px;border-style: inset;" cellSpacing=0 cellPadding=0 width="100%"><TBODY>
<TR>
<TD vAlign=top>
<table width="100%" cellpadding="0" cellspacing="1" bgcolor="#808080">
<tr>
    <td id="pane" width="50">№</td>
	<td id="pane">ID</td>
	<td id="pane">Art#</td>
	<td id="pane"><span name=txtLangs id=txtLangs>Наименование</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 1</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 2</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 3</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 4</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Цена 5</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Нов.</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Спец.</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Склад</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Вес</span></td>
	<td id="pane"><span name=txtLangs id=txtLangs>Сорт.</span></td>
</tr>'.$ReadCsv->DoUpdatebase1();
 
 
@$interface.='
</table>
</TD></TR></TBODY></TABLE>
</div>
<div align="center" style="padding-top:20">
<button style="WIDTH: 17em; HEIGHT: 2.3em" onclick="DoReload(\'csv\')">
<img src="img/icon-setup2.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">
<span name=txtLangs id=txtLangs>Выбрать другой файл</span></button>
&nbsp;&nbsp;
<button style="WIDTH: 17em; HEIGHT: 2.3em" onclick="DoLoad(null,\'load\',\''.$_FILES['file']['name'].'\',\'csv\')">
<img src="img/icon-setup.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">
<span name=txtLangs id=txtLangs2>Принять изменения</span></button>
</div>
    ';
  }

}
elseif($_REQUEST['page']=="load"){

@$fp = fopen("../csv/".@$_REQUEST['name'], "r");
if ($fp) {
  $fstat = fstat($fp);
  $CsvContent=fread($fp,$fstat['size']);
  fclose($fp);
$ReadCsv = new ReadCsv($CsvContent,$table_name2,$option['sklad_status']);
$Done2 = $ReadCsv->DoUpdatebase2();

@$interface.='
<TABLE cellSpacing=0 cellPadding=0 width="50%" align="center">
<TR>

<TD vAlign=top style="padding-top:25">
<div align="center"><h4><span name=txtLang2 id=txtLang2>Загрузка прайс-листа Excel выполнена</span>!</h4></div>
<FIELDSET id=fldLayout style="width: 60em; height: 8em;">
<table style="border: 1px;border-style: inset;background-color: White;" cellpadding="10" width="100%">
<tr>
	<td width="50%" ><h4><span name=txtLang2 id=txtLang2>Отчет:</span></h4>
<ol>
	<li>Обновлено позиций: '. $ReadCsv->TotalUpdate.'
</ol></td>
</tr>
</table>
</FIELDSET>
</TD></TR></TABLE>
'.$ReadCsv->Debug.'
    ';
  }
} else @$interface.=$disp='
	  <table width="100%" height="100%" style="Z-INDEX:2;">
<tr>
	<td valign="middle" align="center">
		<div style="width:400px;height:100px;BACKGROUND: #C0D2EC;padding:10px;border: solid;border-width: 1px; border-color:#4D88C8;FILTER: alpha(opacity=80);" align="left">
<table width="100%" height="100%">
<tr>
	<td width="35" vAlign=center ><IMG 
            hspace=0 src="img/i_support_med[1].gif" align="absmiddle" 
            border=0 ></td>
	<td ><b>Внимание, выберите файл с расширением *.csv.
	Повторите  <a href="javascript:DoReload(\'csv\')" style="color:red">загрузку файла</a>.</b></td>
</tr>
</table>

		</div>
</td>
</tr>
</table>
	  
	  ';



$_RESULT = array(
  "name"   => @$_FILES['file']['name'],
  "content"=> @$interface,
  "size"   => @filesize($_FILES['file']['tmp_name']),
); 
?>

