<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Подключение языков
$GetSystems=GetSystems();
$Admoption=unserialize($GetSystems['admoption']);
$Lang=$Admoption['lang'];

require("../language/".$Lang."/language.php");

// Проверка на вложенные товары
function chekDelProduct($delCatMass){
global $SysValue;
$temp = "id=0";
foreach ($delCatMass as $value)
    $temp .= " OR category=$value";
$sql="select id from ".$SysValue['base']['table_name2']." where $temp";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
    $mass[]=$row[id];
return $mass;
}

// Проверка на вложенные каталоги
function chekDelCatalogRevers($categoryID,$delCatMass){
global $SysValue;
$sql="select id from ".$SysValue['base']['table_name']." where parent_to='$categoryID'";
$result=mysql_query($sql);
while($row=mysql_fetch_array($result)){
    $delCatMass[]=$row[id];
    @chekDelCatalogRevers($row[id],&$delCatMass);
    }
return "";
}

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Редактирование Каталога</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<LINK href="../css/tab.winclassic.css" type=text/css rel=stylesheet>
<?
//Check user's Browser
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
	echo "<script language=JavaScript src='../editor3/scripts/editor.js'></script>";
else
	echo "<script language=JavaScript src='../editor3/scripts/moz/editor.js'></script>";
?>
<script language="JavaScript" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" src="../java/tabpane.js"></script>
<script type="text/javascript" language="JavaScript" src="../language/<?
echo $Lang;?>/language_windows.js"></script>
<script> 
DoResize(<? echo $GetSystems['width_icon']?>,650,630);
</script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>);preloader(0)">
<table id="loader">
<tr>
	<td valign="middle" align="center">
		<div id="loadmes" onclick="preloader(0)">
<table width="100%" height="100%">
<tr>
	<td id="loadimg"></td>
	<td ><b><?=$SysValue['Lang']['System']['loading']?></b><br><?=$SysValue['Lang']['System']['loading2']?></td>
</tr>
</table>
		</div>
</td>
</tr>
</table>

<SCRIPT language=JavaScript type=text/javascript>preloader(1);</SCRIPT>
<?
function Disp_cat($parent_to,$n)// вывод каталогов в выборе
{
global $table_name;
if($parent_to==1000003) $name="Временная папка";
  else{
$sql="select name from $table_name where id=$parent_to";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
$name=$row['name'];
}
return "$name => $n";
}

function Disp_sort_category($sort)// вывод сортировки
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name20']." where category=0 order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
    $name=$row['name'];
	$sel="";
	if(is_array($sort))
	foreach($sort as $k=>$v){
	if ($id == $v) $sel="selected";
	}
    @$dis.="
	<optgroup label=\"$name\">
	".Disp_sort($id,$sort)."
	</optgroup>
	";
	}
@$disp="
<select name=sort_new[] size=1 style=\"width: 580;height:260 \" multiple>
$dis
</select>
";
return @$disp;
}

function Disp_sort($n,$sort)// вывод сортировки
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name20']." where category=$n order by num";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
    $name=substr($row['name'],0,35);
	$sel="";
	if(is_array($sort))
	foreach($sort as $k=>$v){
	if ($id == $v) $sel="selected";
	}
    @$dis.="<option value=".$id." ".$sel.">".$name."</option>\n";
	}
return @$dis;
}


function Disp_servers($server_array)// вывод сортировки
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name31']." order by name";
$result=mysql_query($sql);
while($row = mysql_fetch_array($result))
    {
    $id=$row['id'];
    $name=$row['name'];
	$host=$row['host'];
	$sel="";
	$server = preg_split('/i/', $server_array, -1, PREG_SPLIT_NO_EMPTY);
	if(is_array($server))
	foreach($server as $v){
	if ($id == $v) $sel="selected";
	}
    @$dis.="<option value=".$id." ".$sel." >".$name." >> $host</option>\n";
	}
@$disp="
<select name=servers_new[] size=1 style=\"width: 580;height:260 \" multiple>
$dis
</select>
";
return @$disp;
}


// Выбор шкуры
function GetSkins($skin){
global $SysValue;
$dir="../../templates";
if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
		
		    if($skin == $file)
			$sel="selected";
			  else $sel="";
		
		    if($file!="." and $file!=".." and $file!="index.html")
            @$name.= "<option value=\"$file\" $sel>$file</option>";
        }
        closedir($dh);
    }
}
$disp="
<select name=\"skin_new\" style=\"height:200px;width:280px\" size=5 onchange=\"GetSkinIcon(this.value)\">
".@$name."
</select>
";
return @$disp;
}

// Выбор иконки шкуры
function GetSkinsIcon($skin){
global $SysValue;
$dir="../../templates";
$filename=$dir.'/'.$skin.'/icon/icon.gif';
if (file_exists($filename))
$disp='<img src="'.$filename.'" alt="'.$skin.'" width="150" height="120" border="1" id="icon">';
else $disp='<img src="../img/icon_non.gif"  width="150" height="120" border="1" id="icon">';
return @$disp;
}


$sql="select * from $table_name where id=$catalogID";
$result=mysql_query($sql);
@$row = mysql_fetch_array(@$result);
    $id=$row['id'];
    $name=$row['name'];
	$name_rambler=$row['name_rambler'];
	$num=$row['num'];
	$parent_to=$row['parent_to'];
	$yml=$row['yml'];
	$content=$row['content'];
	$num_row=$row['num_row'];
	$num_cow=$row['num_cow'];
	$sort=unserialize($row['sort']);
	if($yml=="1") $sel="checked";
	   else $sel2="checked";
	switch($num_row){
	case(1): $rowl="checked"; break;
	case(2): $row2="checked"; break;
	case(3): $row3="checked"; break;
	case(4): $row4="checked"; break;
	}
	$content=$row['content'];
	$servers=$row['servers'];
	$title=$row['title'];
	$title_enabled=$row['title_enabled'];
	$title_shablon=$row['title_shablon'];
	$descrip=$row['descrip'];
	$descrip_enabled=$row['descrip_enabled'];
	$descrip_shablon=$row['descrip_shablon'];
	$keywords=$row['keywords'];
	$keywords_enabled=$row['keywords_enabled'];
	$keywords_shablon=$row['keywords_shablon'];
	$order_by=$row['order_by'];
	$order_to=$row['order_to'];
	$secure_groups=$row['secure_groups'];
	
	if(empty($row['skin'])) $skin=$GetSystems['skin'];
	 else $skin=$row['skin'];
	
	if($title_enabled == 0) {
	   $t1="checked"; 
	   $t2_enabled="none";
	   $t3_enabled="none";
	   }elseif($title_enabled == 1) {
	   $t2="checked";
	   $t2_enabled="block";
	   $t3_enabled="none";
	   }
	   elseif($title_enabled == 2) {
	   $t3="checked";
	   $t3_enabled="block";
	   $t2_enabled="none";
	   }
	 
	 if($descrip_enabled == 0) {
	   $d1="checked"; 
	   $d2_enabled="none";
	   $d3_enabled="none";
	   }elseif($descrip_enabled == 1) {
	   $d2="checked";
	   $d2_enabled="block";
	   $d3_enabled="none";
	   }
	   elseif($descrip_enabled == 2) {
	   $d3="checked";
	   $d3_enabled="block";
	   $d2_enabled="none";
	   }
	   
	  if($keywords_enabled == 0) {
	   $k1="checked"; 
	   $k2_enabled="none";
	   $k3_enabled="none";
	   }elseif($keywords_enabled == 1) {
	   $k2="checked";
	   $k2_enabled="block";
	   $k3_enabled="none";
	   }
	   elseif($keywords_enabled == 2) {
	   $k3="checked";
	   $k3_enabled="block";
	   $k2_enabled="none";
	   }
	   
	   if($order_by == 1) {
	   $o1="selected"; 
	   $o2="";
	   $o3="";
	   }elseif($order_by == 2) {
	   $o1=""; 
	   $o2="selected";
	   $o3="";
	  }elseif($order_by == 3) {
	   $o1=""; 
	   $o2="";
	   $o3="selected";
	   }
	   else $o3="selected";
	   
	   if($order_to == 1) {
	   $ot1="selected"; 
	   $o2t="";
	   }elseif($order_to == 2) {
	   $ot1=""; 
	   $ot2="selected";
	  }
	   else $ot1="selected";
	   
   if($row['vid']=="1") $vid="checked";
   
   if($row['skin_enabled']==1)
	$f3="checked";
	
	echo ('
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Редактирование Каталога</span> "'.$name.'"</b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_filemanager_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<!-- begin tab pane -->
<div class="tab-pane" id="article-tab" style="margin-top:5px;">

<script type="text/javascript">
tabPane = new WebFXTabPane( document.getElementById( "article-tab" ), true );
</script>

<!-- begin intro page -->
<div class="tab-page" id="intro-page" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Основное</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "intro-page" ) );
</script>

<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<form name="product_edit"  method="post" onsubmit="Save()">
<tr valign="top">
	<td>
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Н</u>азвание</span>:</LEGEND>
	<div style="padding:10">
		<INPUT type=text class="full" name=name_new value="'.$name.'"></FIELDSET>
	</td>
</tr>
<tr>
	<td>
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>К</u>аталог</span>:</LEGEND>
	<div style="padding:10">
	<input type=text id="myName"  style="width: 500" value="'.Disp_cat($parent_to,$name).'">
	<input type="hidden" value="'.$parent_to.'" name="parent_to_new" id="myCat">
<BUTTON style="width: 3em; height: 2.2em; margin-left:5"  onclick="miniWinFull(\'adm_cat.php?category='.$id.'\',300,400);return false;"><img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>
	</FIELDSET>
	</td>
</tr> ');
	
if($_GET['tip'] == "main"){
	echo('
	<tr>
	<td >
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>П</u>ереход</span></LEGEND>
	<div style="padding:12">
		<input type="checkbox" name="vid_new" value="1" '.@$vid.'> <span name=txtLang id=txtLang>Выводить подкаталоги списком в основном окне</span>
</FIELDSET>
	</td>
	</tr>');
	}

echo ('<tr>
	<td>
	<table cellspacing="0" cellpadding="0">
<tr>
	<td>
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>С</u>ортировка</span></LEGEND>
	<div style="padding:11">
		№ <INPUT type=text style="width: 5em; height: 2.0em; " name=num_new  value="'.$num.'"></FIELDSET>
	</td>
	<td width="10"></td>
	');

	echo('
	<td >
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Т</u>оваров в длину</span></LEGEND>
	<div style="padding:12">
		<input type="radio" name="num_row_new" value="1" '.$rowl.'>1&nbsp;&nbsp;&nbsp;
		<input type="radio" name="num_row_new" value="2" '.$row2.'>2&nbsp;&nbsp;&nbsp;
		<input type="radio" name="num_row_new" value="3" '.$row3.'>3&nbsp;&nbsp;&nbsp; 
		<input type="radio" name="num_row_new" value="4" '.$row4.'>4&nbsp;&nbsp;&nbsp; 
</FIELDSET>
	</td>
	<td width="10"></td>
	<td >
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Т</u>оваров на странице</span></LEGEND>
	<div style="padding:12">
		<input type="text" name="num_cow_new" style="width:50" value="'.$num_cow.'"> шт.
</FIELDSET>

	</td>
	<td width="10"></td>
	<td>
	 <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>С</u>ортировка товаров</span></LEGEND>
	<div style="padding:12">
	  <select name="order_by_new">
			<option value="1" '.$o1.'>по имени</option>
			<option value="2" '.$o2.'>по цене</option>
			<option value="3" '.$o3.'>по популярности</option>
     </select>
	 &nbsp;
	  <select name="order_to_new">
			<option value="1" '.$ot1.'>возрастанию</option>
			<option value="2" '.$ot2.'>убыванию</option>
     </select>
		
</FIELDSET>
	</td>
		');


	echo('
</tr>
</table>
	</td>
</tr>
</table>
</div>
<!-- begin intro page -->
<div class="tab-page" id="content" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Описание</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "content" ) );
</script>
<table width="100%">

<tr>
	<td colspan=3 width="100%">
	<FIELDSET id=fldLayout>
<div style="padding:10">
	');

$systems=GetSystems();
$option=unserialize($systems['admoption']);
if($option['editor_enabled']  == 1){
$MyStyle=$SysValue['dir']['dir'].chr(47)."phpshop".chr(47)."templates".chr(47).$systems['skin'].chr(47).$SysValue['css']['default'];
echo'
<pre id="idTemporary" name="idTemporary" style="display:none">
'.$content.'
</pre>
	<script>
		var oEdit1 = new InnovaEditor("oEdit1");
	oEdit1.cmdAssetManager="modalDialogShow(\''.$SysValue['dir']['dir'].'/phpshop/admpanel/editor3/assetmanager/assetmanager.php\',640,500)";
		oEdit1.width=600;
		oEdit1.height=380;
		oEdit1.btnStyles=true;
	    oEdit1.css="'.$MyStyle.'";
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
	<input type="hidden" name="EditorContent" id="EditorContent">';
	}
else{
echo '
<textarea name="EditorContent" id="EditorContent" style="width:100%;height:380px">'.$content.'</textarea>
';
}
echo('
</div>
</FIELDSET>
	</td>
</tr>

</table>
</div>');
if($_GET['tip'] != "main"){
echo('
<!-- begin intro page -->
<div class="tab-page" id="sort" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Характеристики</span></h2>
<table>
<tr>
	<td>
	<FIELDSET>
	<div style="padding:10">
	'.Disp_sort_category($sort).'
	</FIELDSET>
	</td>
</tr>
</table>
<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "sort" ) );
</script>
</div>');
}
echo ('
<!-- begin intro page -->
<div class="tab-page" id="Rambler" style="height:440px">
<h2 class="tab">YML</h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "Rambler" ) );
</script>
<table width="100%">
<tr>
   <td>
   <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang onmouseover="GetNumName(this)"><u>В</u>ыгрузка</span></LEGEND>
	<div style="padding:12">
	<table width="80%" cellpadding="0" cellspacing="0">
<tr>
	<td><input type="radio" name="yml_new" value="1" '.$sel.'><span name=txtLang id=txtLang>Да</span>&nbsp;&nbsp;&nbsp;
		<input type="radio" name="yml_new" value="0" '.$sel2.'><font color="#FF0000"><span name=txtLang id=txtLang>Нет</span></font></td>
	<td align="right"> 
	<button style="width: 12em; height: 2.2em; margin-left:5"  onclick="window.open(\'/yml/yandex.php\')">
<img src="../img/interface_browser.gif" border="0" align="absmiddle" hspace=5>
	Yandex XML</button>
	<button style="width: 12em; height: 2.2em; margin-left:5"  onclick="window.open(\'/yml/rambler.php\')">
<img src="../img/interface_browser.gif" border="0" align="absmiddle" hspace=5>
	Rambler XML</button>
</td>
</tr>
</table>

		
   
</FIELDSET>
   </td>
</tr>
<tr>
	<td>
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Н</u>азвание для Рамблер-Покупки</span>:</LEGEND>
	<div style="padding:10">
		<INPUT type=text class="full" name=name_rambler_new value="'.$name_rambler.'"></FIELDSET>
	</td>
</tr>
<tr>
	<td>
	<FIELDSET>
	<div style="padding:10">
		<img src="../icon/icon_info.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="3"><span name=txtLang id=txtLang>При заполнении поля "категория товарного предложения" следует использовать
названия из списка категорий площадки Pokupki.rambler.ru</span>
(<a href="http://pokupki.rambler.ru/?T=1151403901&action=show_all_cats" target="_blank">http://pokupki.rambler.ru/?T=1151403901&action=show_all_cats</a>)
	</td>
</tr>
</table>
</div>
<!-- begin intro page -->
<div class="tab-page" id="Servers" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Сервера</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "Servers" ) );
</script>
'.Disp_servers($servers).'
</div>
<div class="tab-page" id="twer" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Заголовки</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "twer" ) );
</script>
<table width="100%">
<tr>
  <td width="100%">
  <FIELDSET id=fldLayout >
<LEGEND id=lgdLayout><u>T</u>itle:</LEGEND>
<div style="padding:10;width: 100%">
<input type="radio" value="0" name="title_enabled_new" onclick="document.getElementById(\'titleForma\').style.display=\'none\';document.getElementById(\'titleShablon\').style.display=\'none\'" '.$t1.'> <span name=txtLang id=txtLang>Автоматическая генерация</span>&nbsp;&nbsp;&nbsp;
<input type="radio" value="2" name="title_enabled_new" onclick="document.getElementById(\'titleShablon\').style.display=\'block\';document.getElementById(\'titleForma\').style.display=\'none\'" '.$t3.'> <span name=txtLang id=txtLang>Мой шаблон</span> &nbsp;&nbsp;&nbsp;
<input type="radio" value="1" name="title_enabled_new"  onclick="document.getElementById(\'titleForma\').style.display=\'block\';document.getElementById(\'titleShablon\').style.display=\'none\'" '.$t2.'> <span name=txtLang id=txtLang>Ручная настройка</span><br>
<div id="titleShablon" style="display:'.$t3_enabled.'">
<textarea style="width: 100%; height: 5em;" name="title_shablon_new" id="Shablon">'.$title_shablon.'</textarea>
<input name="btnLang" type="button" value="Каталог" onclick="ShablonAdd(\'@Catalog@\',\'Shablon\')" class="buttonSh">
<input name="btnLang" type="button" value="Подкаталог" onclick="ShablonAdd(\'@Podcatalog@\',\'Shablon\')" class="buttonSh">
<input type="button" name="btnLang"  value="Общий" onclick="ShablonAdd(\'@System@\',\'Shablon\')" class="buttonSh">
<input type="button" value="," onclick="ShablonAdd(\',\',\'Shablon\')" class="buttonSh">
<input type="button" value="-" onclick="ShablonAdd(\'-\',\'Shablon\')" class="buttonSh">
<input type="button" value="/" onclick="ShablonAdd(\'/\',\'Shablon\')" class="buttonSh">
<input type="button" name="btnLang" value="Пробел" onclick="ShablonAdd(\' \',\'Shablon\')" class="buttonSh">
<input type="button" name="btnLang" value="Ввести слово" onclick="ShablonPromt(\'Shablon\')" class="buttonSh">
<input type="button" name="btnLang" value="Сбросить" onclick="ShablonDell(\'Shablon\')" class="buttonSh">
</div>
<div id="titleForma" style="display:'.$t2_enabled.'">
<textarea style="width: 100%; height: 5em;" name="title_new">'.$title.'</textarea>
</div>
</div>
</FIELDSET>
  </td>
</tr>
<tr>
  <td width="100%">
  <FIELDSET id=fldLayout >
<LEGEND id=lgdLayout><u>D</u>escription:</LEGEND>
<div style="padding:10;width: 100%">
<input type="radio" value="0" name="descrip_enabled_new" onclick="document.getElementById(\'titleFormaD\').style.display=\'none\';document.getElementById(\'titleShablonD\').style.display=\'none\'" '.$d1.'> <span name=txtLang id=txtLang>Автоматическая генерация</span>&nbsp;&nbsp;&nbsp;
<input type="radio" value="2" name="descrip_enabled_new" onclick="document.getElementById(\'titleShablonD\').style.display=\'block\';document.getElementById(\'titleFormaD\').style.display=\'none\'" '.$d3.'> <span name=txtLang id=txtLang>Мой шаблон</span>&nbsp;&nbsp;&nbsp;
<input type="radio" value="1" name="descrip_enabled_new"  onclick="document.getElementById(\'titleFormaD\').style.display=\'block\';document.getElementById(\'titleShablonD\').style.display=\'none\'" '.$d2.'> <span name=txtLang id=txtLang>Ручная настройка</span><br>
<div id="titleShablonD" style="display:'.$d3_enabled.'">
<textarea style="width: 100%; height: 5em;" name="descrip_shablon_new" id="ShablonD">'.$descrip_shablon.'</textarea>
<input type="button" name="btnLang" value="Каталог" onclick="ShablonAdd(\'@Catalog@\',\'ShablonD\')" class="buttonSh">
<input type="button"  name="btnLang" value="Подкаталог" onclick="ShablonAdd(\'@Podcatalog@\',\'ShablonD\')" class="buttonSh">
<input type="button" name="btnLang" value="Общий" onclick="ShablonAdd(\'@System@\',\'ShablonD\')" class="buttonSh">
<input type="button" value="," onclick="ShablonAdd(\',\',\'ShablonD\')" class="buttonSh">
<input type="button" value="-" onclick="ShablonAdd(\'-\',\'ShablonD\')" class="buttonSh">
<input type="button" value="/" onclick="ShablonAdd(\'/\',\'ShablonD\')" class="buttonSh">
<input type="button" name="btnLang" value="Пробел" onclick="ShablonAdd(\' \',\'ShablonD\')" class="buttonSh">
<input type="button" name="btnLang" value="Ввести слово" onclick="ShablonPromt(\'ShablonD\')" class="buttonSh">
<input type="button" name="btnLang" value="Сбросить" onclick="ShablonDell(\'ShablonD\')" class="buttonSh">
</div>
<div id="titleFormaD" style="display:'.$d2_enabled.'">
<textarea style="width: 100%; height: 5em;" name="descrip_new">'.$descrip.'</textarea>
</div>
</div>
</FIELDSET>
  </td>
</tr>
<tr>
  <td width="100%">
  <FIELDSET id=fldLayout >
<LEGEND id=lgdLayout><u>K</u>eywords:</LEGEND>
<div style="padding:10;width: 100%">
<input type="radio" value="0" name="keywords_enabled_new" onclick="document.getElementById(\'titleFormaK\').style.display=\'none\';document.getElementById(\'titleShablonK\').style.display=\'none\'" '.$k1.'> <span name=txtLang id=txtLang>Автоматическая генерация</span>&nbsp;&nbsp;&nbsp;
<input type="radio" value="2" name="keywords_enabled_new" onclick="document.getElementById(\'titleShablonK\').style.display=\'block\';document.getElementById(\'titleFormaK\').style.display=\'none\'" '.$k3.'> <span name=txtLang id=txtLang>Мой шаблон</span> &nbsp;&nbsp;&nbsp;
<input type="radio" value="1" name="keywords_enabled_new"  onclick="document.getElementById(\'titleFormaK\').style.display=\'block\';document.getElementById(\'titleShablonK\').style.display=\'none\'" '.$k2.'> <span name=txtLang id=txtLang>Ручная настройка</span><br>
<div id="titleShablonK" style="display:'.$k3_enabled.'">
<textarea style="width: 100%; height: 5em;" name="keywords_shablon_new" id="ShablonK">'.$keywords_shablon.'</textarea>
<input type="button" name="btnLang" value="Каталог" onclick="ShablonAdd(\'@Catalog@\',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Подкаталог" onclick="ShablonAdd(\'@Podcatalog@\',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Общий" onclick="ShablonAdd(\'@System@\',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Автопобор" onclick="ShablonAdd(\'@Generator@\',\'ShablonK\')" class="buttonSh">
<input type="button" value="," onclick="ShablonAdd(\',\',\'ShablonK\')" class="buttonSh">
<input type="button" value="-" onclick="ShablonAdd(\'-\',\'ShablonK\')" class="buttonSh">
<input type="button" value="/" onclick="ShablonAdd(\'/\',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Пробел" onclick="ShablonAdd(\' \',\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Слово" onclick="ShablonPromt(\'ShablonK\')" class="buttonSh">
<input type="button" name="btnLang" value="Сбросить" onclick="ShablonDell(\'ShablonK\')" class="buttonSh">
</div>
<div id="titleFormaK" style="display:'.$k2_enabled.'">
<textarea style="width: 100%; height: 5em;" name="keywords_new">'.$keywords.'</textarea>
</div>
</div>
</FIELDSET>
  </td>
</tr>
</table>
</div>
');

if(CheckedRules($UserStatus["cat_prod"],5) == 1){ //Если есть права на редактирование доступа к папке
echo '
<div class="tab-page" id="security" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Безопасность</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "security" ) );
</script>
';
?>

<table width="100%">
<tr>
<td width="100%">

<SCRIPT>
function enable_div2() {
if (document.getElementById('allusers').checked) {
	document.getElementById('regsel').disabled=true;
} else {
	document.getElementById('regsel').disabled=false;
}
}

</SCRIPT>
	<FIELDSET id=fldLayout >
<div style="padding:10">
<span name=txtLang id=txtLang>Каталог могут редактировать:</span><BR>
<?
$sql='select * from '.$SysValue['base']['table_name19'].' WHERE enabled="1"';
$result=mysql_query($sql);
$num = mysql_num_rows($result);
if ($num) { ?>

<DIV id="allreg">
&nbsp;&nbsp;&nbsp;
<input type="HIDDEN" name="9999" value="0">
<?
if (strlen($secure_groups)) {$che='';} else {$che='checked';}

?>
<input type="checkbox" onClick="enable_div2()" id="allusers" name="seq[9999]" <?=$che?> value="1">
<span name=txtLang id=txtLang>Все, у кого есть права на ред. каталогов (снимите отметку, чтобы выбрать определенных пользователей)</span><BR>

<DIV <?if (!(strlen($secure_groups))) echo "disabled";?> id="regsel" style="overflow-y:auto; height:280px;">

<?
	while ($row = mysql_fetch_array($result)) {
		if (strlen($secure_groups)) {
			$string='i'.$row['id'].'-1i';
			if (strpos($secure_groups,$string) !==false) {$che='checked';} else {$che='';}
		} else {$che='';}

		if ($row['id']==$_SESSION['idPHPSHOP']) {
			$che='checked';
			$amddis='disabled';
			$admval='1';
			$admname='<B>Это вы!</B> ';
		} else {
			$amddis='';
			$admval='0';
			$admname='';
		}



		echo '&nbsp;&nbsp;&nbsp;
			<input type="HIDDEN" name="seq['.$row['id'].']" value="'.$admval.'">
			<input type="checkbox" name="seq['.$row['id'].']" '.$che.' '.$amddis.' value="1">'.$admname.$row['name'].' (login:'.$row['login'].',e-mail:'.$row['mail'].')<BR>';
	}
?>
</DIV>
</DIV>
<?
} //Конец если есть статусы
?>
</div>
</FIELDSET>


</td>
</tr>
</table>
</div>

<?
echo '
</div>

';
} //Если есть права на редактирование доступа к папке

echo ('
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
    <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent(\'catalogID\')">Справка</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
<input type=hidden name=id value='.$id.'>
<input type=hidden  name=catalogID value='.$id.'>
<input type="submit"  name="productSAVE" value="OK" class=but>
<input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis(\'Вниманние! Удаление категории! Данная операция приведёт к удалению всех подкаталогов и товаров удаляемой категории!\');">
<input type="hidden" class=but  name="productDELETE" id="productDELETE">
<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	');

if((isset($productSAVE)) and $name_new!="")// запись в базу
{

if (strlen($secure_groups)) {
	$ider=trim($_SESSION['idPHPSHOP']);
	$string='i'.$ider.'-1i';
	if (strpos($secure_groups,$string) ===false) {$UserChek->BadUserFormaWindow();}
}

//		if ($row['id']==$_SESSION['idPHPSHOP']) {


if(CheckedRules($UserStatus["cat_prod"],5) == 1){
if(is_array($seq))
foreach ($seq as $crid =>$value) {
	$sq_new.='i'.$crid.'-'.$value.'i';
	@$counter++;
	if ($value) {$selected++;}
	if (isset($seq['9999'])) {$sq_new=''; break;}
}
if ((!$selected) || ($counter==$selected)) {$sq_new='';}
$sql="UPDATE $table_name SET secure_groups='$sq_new' where id='$id'";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
} //Проверка прав

if(CheckedRules($UserStatus["cat_prod"],1) == 1){

if(is_array($servers_new))
foreach($servers_new as $v)
@$servers_array.="i".$v."i";

$sql="UPDATE $table_name
SET
name='".CleanStr(trim($name_new))."',
num='$num_new',
parent_to='$parent_to_new',
yml='$yml_new',
num_row='$num_row_new',
num_cow='$num_cow_new',
sort='".serialize($sort_new)."',
content='$EditorContent',
vid='$vid_new', 
name_rambler='$name_rambler_new',
servers='$servers_array',
title='$title_new',
title_enabled='$title_enabled_new',
title_shablon='$title_shablon_new',
descrip='$descrip_new',
descrip_enabled='$descrip_enabled_new',
descrip_shablon='$descrip_shablon_new',
keywords='$keywords_new',
keywords_enabled='$keywords_enabled_new',
keywords_shablon='$keywords_shablon_new',
skin_enabled='$skin_enabled_new',
order_by='$order_by_new',
order_to='$order_to_new'  
where id='$id'";
$result=mysql_query($sql)or @die("Невозможно изменить запись");

unset($skin);

echo"
<script language=\"JavaScript1.2\">
CLREL('left');
</script>";
	   
}else $UserChek->BadUserFormaWindow();
}
 
if(@$productDELETE=="doIT")// Удаление записи
{
if(CheckedRules($UserStatus["cat_prod"],4) == 1){

    
    // проверяем есть вложенные подкаталоги (любая вложенность), полчучаем массив ID удаляемых категорий. Который изначально содержит уже 1 эелемент равный удаляемому каталогу.
    $delCatMass[]=$id;  // массив ID всех подкаталогов удаляемого каталога до любого уровня включая ID удаляемого каталога
    @chekDelCatalogRevers($id,&$delCatMass);
    
    // удаляем каталог и все его подкаталоги всех уровней
    $delCatID = "id = 0";
    foreach ($delCatMass as $value)
        $delCatID.=" OR id = $value";
    
    $sql="delete from $table_name
    where $delCatID";
    $result=mysql_query($sql);
    $chekDelCatalog=true;
        
    // Получаем ID всех товаров каталоги или его подкаталогов в виде массива, используя массив $delCatMass
    $delProdMass = chekDelProduct($delCatMass);
    
    // удаляем все вложенные товары удаляемого каталога и его подкаталогов.
    $delProdID = "id=0";
    $delImageID = "id=0"; // формируем условия для удаления изображений
    foreach ($delProdMass as $value){
        $delProdID .= " OR id=$value";
        $delImageID .= " OR parent=$value";
    }
    
    $sql="delete from $table_name2
    where $delProdID";
    $result=mysql_query($sql);
    $chekDelProduct=true;
    
    // Удаляем изображения  из БД для удалённых товаров.    
    $sql="delete from ".$SysValue['base']['table_name35']."
    where $delImageID";
    $result=mysql_query($sql);
    
    unset($skin);
	
	if($chekDelProduct == true and $chekDelCatalog==true)
    echo"
<script language=\"JavaScript1.2\">
CLREL('left');
</script>
	   ";
	  
}else $UserChek->BadUserFormaWindow();
}

  
?>
</body>
</html>
