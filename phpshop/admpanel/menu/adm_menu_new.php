<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Языки
$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);
$Lang=$option['lang'];
require("../language/".$Lang."/language.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Добавление Меню</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<?
//Check user's Browser
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
	echo "<script language=JavaScript src='../editor3/scripts/editor.js'></script>";
else
	echo "<script language=JavaScript src='../editor3/scripts/moz/editor.js'></script>";
?>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,630,610);
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
<form name="product_edit"  method=post onsubmit="Save()">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Создание Нового Текстового Блока</span></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_select_another_account_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<table class=mainpage4 cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<tr>
  <td colspan="2">
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Н</u>азвание Меню</span></LEGEND>
<div style="padding:10">
<table>
<tr>
<td>

<input type="text" name="name_new" value="<?=$name?>" size="50">

	</td>
	<td width="10"></td>
	<td>
	<span name=txtLang id=txtLang><u>П</u>озиция</span>: 
<select name=num_new size=1 class=s>
<option value="1" selected>1</option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>
<option value="5">5</option>
<option value="6">6</option>
<option value="7">7</option>
<option value="8">8</option>
<option value="9">9</option>
<option value="10">10</option>
</select>
	</td>
	<td width="10"></td>
	<td>
		<span name=txtLang id=txtLang><u>Р</u>асположение</span>: 
<select name=element_new size=1 class=s>
<option value="0" id=txtLang>Слева</option>
<option value="1" id=txtLang>Справа</option>
</select>
	</td>
</tr>
</table>
<input type="radio" name="flag_new" value="1" checked><span name=txtLang id=txtLang>Показывать меню</span>&nbsp;&nbsp;&nbsp;
<input type="radio" name="flag_new" value="0"><span name=txtLang id=txtLang>Скрыть меню</span>
</div>
</FIELDSET>
  </td>
</tr>
<tr>
  <td colspan="2">
  <FIELDSET >
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>П</u>ривязка к странице</span></LEGEND>
<div style="padding:10">
<input type="text" name="dir_new" style="width:100%"><br>
<span name=txtLang id=txtLang>* Пример: page/,news/. Можно указать несколько адресов через запятую.</span>
</FIELDSET>
  </td>
</tr>
<tr>
	<td colspan="3">
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang>Содержание</span></LEGEND>
<div style="padding:10">
<?
$systems=GetSystems();
$option=unserialize($systems['admoption']);
if($option['editor_enabled']  == 1){
$MyStyle=$SysValue['dir']['dir'].chr(47)."phpshop".chr(47)."templates".chr(47).$systems['skin'].chr(47).$SysValue['css']['default'];
echo'
<pre id="idTemporary" name="idTemporary" style="display:none">
'.@$content.'
</pre>
	<script>
		var oEdit1 = new InnovaEditor("oEdit1");
	oEdit1.cmdAssetManager="modalDialogShow(\''.$SysValue['dir']['dir'].'/phpshop/admpanel/editor3/assetmanager/assetmanager.php\',640,500)";
		oEdit1.width=600;
		oEdit1.height=200;
		oEdit1.btnStyles=true;
	    oEdit1.css="'.$MyStyle.'";
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
	<input type="hidden" name="EditorContent" id="EditorContent">
	';
	}
else{
echo '
<textarea name="EditorContent" id="EditorContent" style="width:100%;height:200px">'.$content.'</textarea>
';
}?>
</div>
</FIELDSET>
	</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
     <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('menu')">Справка</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
	<input type="submit" name="editID" value="OK" class=but>
	<input type="reset" name="btnLang" value="Сбросить" class=but>
	<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?
if(isset($editID) and @$name_new!="")// Запись редактирования
{
if(CheckedRules($UserStatus["page_menu"],2) == 1){
$sql="INSERT INTO $table_name14
VALUES ('','$name_new','".addslashes($EditorContent)."','$flag_new','$num_new','$dir_new','$element_new')";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
echo"
	  <script>
DoReloadMainWindow('page_menu');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



