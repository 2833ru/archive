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
	<title>Создание Нового Отзыва</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<LINK href="../css/dateselector.css" type=text/css rel=stylesheet>
<?
//Check user's Browser
if(strpos($_SERVER["HTTP_USER_AGENT"],"MSIE"))
	echo "<script language=JavaScript src='../editor3/scripts/editor.js'></script>";
else
	echo "<script language=JavaScript src='../editor3/scripts/moz/editor.js'></script>";
?>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<SCRIPT language=JavaScript src="../java/popup_lib.js"></SCRIPT>
<SCRIPT language=JavaScript src="../java/dateselector.js"></SCRIPT>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,630,580);
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
<form name="product_edit" method=post onsubmit="Save()">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Создание Нового Отзыва за</span> <?=date("d-m-Y")?></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_account_properties_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0" width="100%">
<tr valign="top">
	<td width="130">
	<FIELDSET id=fldLayout style="height: 8em;">
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Д</u>ата</span> </LEGEND>
<div style="padding:10">
<input type="text"  name="data_new" size="8" value="<?=date("d-m-Y")?>" class=s>
<IMG onclick="popUpCalendar(this, product_edit.data_new, 'dd-mm-yyyy');" height=18 hspace=3 src="../icon/date.gif" width=16 border=0 align="absmiddle">
</div>
</FIELDSET>
	</td>
	<td align="left" width="500">
	<FIELDSET id=fldLayout style="height: 8em;">
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>О</u>тправитель</span></LEGEND>
<div style="padding:10">
<table>
<tr>
	<td width="40"><span name=txtLang id=txtLang>Имя</span>:</td>
	<td><input type="text" name="name_new" style="width: 400; " value="<?=$name?>"></td>
</tr>
<tr>
	<td>E-mail:</td>
	<td><input type="text" name="mail_new" style="width: 400; " value="<?=$mail?>"></td>
</tr>
</table>
</div>
</FIELDSET>
	</td>
	
</tr>
<tr>
	<td colspan="3">
	<FIELDSET id=fldLayout>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Т</u>ема</span> </LEGEND>
<div style="padding:10">
<textarea name="tema_new" class=s style="width:100%; height:30"></textarea>
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	<td colspan="3">
	<FIELDSET id=fldLayout>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>О</u>тзыв</span> </LEGEND>
<div style="padding:10">
<textarea name="otsiv_new" class=s style="width:100%; height:30"></textarea>
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	<td colspan="3">
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>О</u>твет</span></LEGEND>
<div style="padding:10">
<?
$systems=GetSystems();
$MyStyle=chr(47)."phpshop".chr(47)."templates".chr(47).$systems['skin'].chr(47).$SysValue['css']['default'];
$option=unserialize($systems['admoption']);
if($option['editor_enabled']  == 1){
echo'
<pre id="idTemporary" name="idTemporary" style="display:none">
'.$otvet.'
</pre>
	<script>
		var oEdit1 = new InnovaEditor("oEdit1");
	oEdit1.cmdAssetManager="modalDialogShow(\'/phpshop/admpanel/editor3/assetmanager/assetmanager.php\',640,500)";
		oEdit1.width="97%";
		oEdit1.height=150;
		oEdit1.btnStyles=true;
	    oEdit1.css="'.$MyStyle.'";
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
	<input type="hidden" name="EditorContent" id="EditorContent">
</div>
	';}
	else{
echo '
<textarea name="EditorContent" id="EditorContent" style="width:100%;height:150px">'.$otvet.'</textarea>
';
}
	
	?>
</div>
</FIELDSET>
	</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
    <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('gbook')">Справка</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
    <input type="submit" name="editID" value="OK" class=but>
	<input type="reset" name="btnLang" name="delID" value="Сбросить" class=but>
	<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?
if(isset($editID) and !empty($tema_new))// Запись редактирования
{
if(CheckedRules($UserStatus["gbook"],2) == 1){
 if($otsiv_new !="") $flag_new = 1;
 
 $data_new = GetUnicTime($data_new);
 
$sql="INSERT INTO ".$SysValue['base']['table_name7']." VALUES ('','$data_new','$name_new','$mail_new','$tema_new','$otsiv_new','$EditorContent','$flag_new')";
$result=mysql_query($sql)or @die("".mysql_error()."");
echo"
	  <script>
DoReloadMainWindow('gbook');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



