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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Создание Нового Каталога</title>
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
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
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

function Disp_sort($sort)// вывод сортировки
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name20']." order by name";
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
    @$dis.="<option value=".$id." ".$sel." >".$name."</option>\n";
	}
@$disp="
<select name=sort_new[] size=1 style=\"width: 580;height:260 \" multiple>
$dis
</select>
";
return @$disp;
}
$t1=$d1=$k1="checked"; 

	echo ('
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Создание Нового Каталога</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу.</span>
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
<h2 class="tab"><span name=txtLang id=txtLang>Основное</span></span></h2>

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
	<input type=text id="myName"  style="width: 500" value="">
	<input type="hidden" value="'.$parent_to.'" name="parent_to_new" id="myCat">
<BUTTON style="width: 3em; height: 2.2em; margin-left:5"  onclick="miniWinFull(\'adm_cat.php?category='.$parent_to.'\',300,400);return false;"><img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>
	</FIELDSET>
	
</td>
</tr>
<tr>
	<td>
	<table cellspacing="0" cellpadding="0">
<tr>
	<td>
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>С</u>ортировка</span></LEGEND>
	<div style="padding:11">
		№ <INPUT type=text style="width: 5em; height: 2.0em; " name=num_new></FIELDSET>
	</td>
	
	<td width="10"></td>
	<td >
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Т</u>оваров в длину</span></LEGEND>
	<div style="padding:12">
		<input type="radio" name="num_row_new" value="1" >1&nbsp;&nbsp;&nbsp;
		<input type="radio" name="num_row_new" value="2" checked>2&nbsp;&nbsp;&nbsp;
		<input type="radio" name="num_row_new" value="3" >3&nbsp;&nbsp;&nbsp;
		<input type="radio" name="num_row_new" value="4" >4&nbsp;&nbsp;&nbsp;
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
			<option value="3" '.$o3.' selected>по популярности</option>
     </select>
	 &nbsp;
	  <select name="order_to_new">
			<option value="1" '.$ot1.'>возрастанию</option>
			<option value="2" '.$ot2.' selected>убыванию</option>
     </select>
		
</FIELDSET>
	</td>
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
</div>
<!-- begin intro page -->
<div class="tab-page" id="sort" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Характеристики</span></h2>
<table width="100%">
<tr>
	<td>
	<FIELDSET>
	<div style="padding:10">
	'.Disp_sort($sort).'
	</FIELDSET>
	</td>
</tr>
</table>
<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "sort" ) );
</script>
</div>
<!-- begin intro page -->
<div class="tab-page" id="Rambler" style="height:450px">
<h2 class="tab">YML</h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "Rambler" ) );
</script>
<table width="100%">
<tr>
   <td>
   <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang onmouseover="GetNumName(this)" ><u>В</u>ыгрузка</span></LEGEND>
	<div style="padding:12">
	<table width="80%" cellpadding="0" cellspacing="0">
<tr>
	<td><input type="radio" name="yml_new" value="1" '.$sel.' checked><span name=txtLang id=txtLang>Да</span>&nbsp;&nbsp;&nbsp;
		<input type="radio" name="yml_new" value="0" '.$sel2.'><font color="#FF0000"><span name=txtLang id=txtLang> Нет</span> </font></td>
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
<div id="titleShablon" style="display:none">
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
<div id="titleForma" style="display:none">
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
<div id="titleShablonD" style="display:none">
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
<div id="titleFormaD" style="display:none">
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
<div id="titleShablonK" style="display:none">
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
<div id="titleFormaK" style="display:none">
<textarea style="width: 100%; height: 5em;" name="keywords_new">'.$keywords.'</textarea>
</div>
</div>
</FIELDSET>
  </td>
</tr>
</table>
</div>
<div class="tab-page" id="skin" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Дизайн</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "skin" ) );
</script>
<table >
	<tr class=adm2>
	  <td align=left>
	  '.GetSkins($GetSystems['skin']).'
	  </td>
	  <td style="padding-left:5px" valign=top>
	  <FIELDSET >
	  <LEGEND ><u>С</u>криншот</LEGEND>
	  <div align="center" style="padding:10px">'.GetSkinsIcon($GetSystems['skin']).'</div>
	  </FIELDSET>
	  <br>
	  <input type="checkbox" value="1" name="skin_enabled_new">  <span name=txtLang id=txtLang>Использовать дизайн</span>
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
<input type="submit"  name="productSAVE" value="OK" class=but>
<input type="reset" class=but name="btnLang" value="Сбросить">
<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
<input type="hidden" value="true" name="reload">
	</td>
</tr>
</table>
</form>
	');

if((isset($productSAVE)) and $name_new!="")// запись в базу
{
if(CheckedRules($UserStatus["cat_prod"],2) == 1){

$sq_new='';
if(CheckedRules($UserStatus["cat_prod"],5) == 1){
	if(is_array($seq))
	foreach ($seq as $crid =>$value) {
		$sq_new.='i'.$crid.'-'.$value.'i';
		@$counter++;
		if ($value) {$selected++;}
		if (isset($seq['9999'])) {$sq_new=''; break;}
	}
	if ((!$selected) || ($counter==$selected)) {$sq_new='';}
} //Проверка прав


$sql="INSERT INTO $table_name
VALUES ('','".CleanStr(trim($name_new))."','$num_new','$parent_to_new','$yml_new','$num_row_new','$num_cow_new','".serialize($sort_new)."','$EditorContent',0,'$name_rambler_new','','$title_new','$title_enabled_new','$title_shablon_new','$descrip_new','$descrip_enabled_new','$descrip_shablon_new','$keywords_new','$keywords_enabled_new','$keywords_shablon_new','$skin_new','$skin_enabled_new','$order_by_new','$order_to_new','$sq_new')";
$result=mysql_query($sql);
if($reload=="true")
echo"
	  <script>
CLREL('left');
</script>
	   ";
else
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
