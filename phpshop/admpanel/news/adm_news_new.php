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
	<title>Новая Новость</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<LINK href="../css/dateselector.css" type=text/css rel=stylesheet>
<LINK href="../css/tab.winclassic.css" type=text/css rel=stylesheet>

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
<script type="text/javascript" src="../java/tabpane.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_interface.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,650,650);
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
<form method=post name="product_edit" onsubmit="Save()">
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Создание Новости за</span> <?=date("d-m-Y")?></b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_balance_med[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<!-- begin tab pane -->
<div class="tab-pane" id="article-tab" style="margin-top:5px;height:250px">

<script type="text/javascript">
tabPane = new WebFXTabPane( document.getElementById( "article-tab" ), true );
</script>

<!-- begin intro page -->
<div class="tab-page" id="intro-page" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Основное</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "intro-page" ) );
</script>
<table width="100%">
<tr valign="top">
	<td width="140">
	<FIELDSET id=fldLayout style="height: 70;">
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Д</u>ата</span> </LEGEND>
<div style="padding:10">
<input type="text" name="data_new" size="8"  class=s value="<?=date("d-m-Y")?>">
<IMG onclick="popUpCalendar(this, product_edit.data_new, 'dd-mm-yyyy');" height=16 hspace=3 src="../icon/date.gif" width=16 border=0 align="absmiddle" style="cursor:pointer;">
</div>
</FIELDSET>
	</td>
	<td align="left">
	<FIELDSET id=fldLayout style="height: 70;">
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>З</u>аголовок новости</span></LEGEND>
<div style="padding:10">
<textarea  name="zag_new" class=s style="width:460; height:30"><?=$zag?></textarea>
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	<td colspan="2">
	<FIELDSET>
<LEGEND><span name=txtLang id=txtLang>Анонс</span></LEGEND>
<div style="padding:10">
<?
$systems=GetSystems();
$MyStyle=$SysValue['dir']['dir'].chr(47)."phpshop".chr(47)."templates".chr(47).$systems['skin'].chr(47).$SysValue['css']['default'];
if($option['editor_enabled']  == 1){
echo'
<pre id="idTemporary" name="idTemporary" style="display:none">
'.$kratko.'
</pre>
	<script>
		var oEdit1 = new InnovaEditor("oEdit1");
	oEdit1.cmdAssetManager="modalDialogShow(\'../../editor3/assetmanager/assetmanager.php\',640,500)";
		oEdit1.width=610;
		oEdit1.height=300;
		oEdit1.btnStyles=true;
	    oEdit1.css="'.$MyStyle.'";
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
	<input type="hidden" name="EditorContent" id="EditorContent">
</div>
	';}
	else{
echo '
<textarea name="EditorContent" id="EditorContent" style="width:100%;height:300px">'.$kratko.'</textarea>
';
}?>
</div>
</FIELDSET>
	</td>
</tr>

</table>
</div>
<!-- begin intro page -->
<div class="tab-page" id="podrob" style="height:450px">
<h2 class="tab"><span name=txtLang id=txtLang>Подробно</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "podrob" ) );
</script>
<table width="100%">
<tr>
	<td colspan="3">
	<FIELDSET id=fldLayout>
<div style="padding:10">
<?
if($option['editor_enabled']  == 1){
echo'
<pre id="idTemporary2" name="idTemporary2" style="display:none">
'.$podrob.'
</pre>
	<script>
		var oEdit2 = new InnovaEditor("oEdit2");
	oEdit2.cmdAssetManager="modalDialogShow(\''.$SysValue['dir']['dir'].'/phpshop/admpanel/editor3/assetmanager/assetmanager.php\',640,500)";
		oEdit2.width=610;
		oEdit2.height=395;
		oEdit2.btnStyles=true;
	    oEdit2.css="'.$MyStyle.'";
		oEdit2.RENDER(document.getElementById("idTemporary2").innerHTML);
	</script>
	<input type="hidden" name="EditorContent2" id="EditorContent2">
</div>
	';}
	else{
echo '
<textarea name="EditorContent2" id="EditorContent2" style="width:100%;height:395px">'.$podrob.'</textarea>
';
}?>
</div>
</FIELDSET>
	</td>
</tr>
</table>
</div>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
     <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('news')">Справка</BUTTON></BUTTON>
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
if(isset($editID) and isset($zag_new))// Запись редактирования
{
if(CheckedRules($UserStatus["news"],2) == 1){
$sql="INSERT INTO $table_name8 VALUES ('','$data_new','$zag_new','".addslashes($EditorContent)."','".addslashes($EditorContent2)."','".GetUnicTime($data_new)."')";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
echo"
	  <script>
DoReloadMainWindow('news');
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



