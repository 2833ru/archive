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
  <title>Новый Каталог Страниц</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?
echo $Lang;?>/language_windows.js"></script>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,600,570);
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

function Disp()// вывод формы
{
global $catalogID,$PHP_SELF,$table_name;
  echo ('
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Создание Нового Каталога Страниц</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_filemanager_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<form  name="product_edit"  method=post enctype="multipart/form-data">
<tr valign="top">
  <td>
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Н</u>азвание</span>:</LEGEND>
  <div style="padding:10">
    <INPUT type=text style="width: 450; height: 2.0em; " name=name_new ></FIELDSET>
  </td>
</tr>
<tr>
  <td>
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Т</u>екст описания</span>:</LEGEND>
<div style="padding:10">
<textarea name="EditorContent" id="EditorContent" style="width:100%;height:160px">'.$content.'</textarea>
</div>
  </FIELDSET>
  </td>
</tr>

<tr>
  <td>
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>К</u>аталог</span>:</LEGEND>
  <div style="padding:10">
  <input type=text id="myName"  style="width: 400" value="">
  <input type="hidden" value="0" name="parent_to_new" id="myCat">
<BUTTON style="width: 3em; height: 2.2em; margin-left:5"  onclick="miniWinFull(\'adm_cat.php?category=\',300,400,300,200)"><img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>
  </FIELDSET>
  </td>
</tr>
<tr>
  <td>
  <table cellspacing="0" cellpadding="0">
<tr>
  <td>
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>П</u>озиция сверху</span>:</LEGEND>
  <div style="padding:11">
    <INPUT type=text style="width: 7em; height: 2.0em; " name=num_new  value="'.$num.'"></FIELDSET>
  </td>
</tr>
</table>
  </td>
</tr>
</table>
</div>
</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
   <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent(\'page_site_catalog\')">Справка</BUTTON>
	</td>
  <td align="right" style="padding:10">
  <input type="submit"  name="productSAVE" value="OK" class=but>
<input type="reset" name="btnLang" class=but  value="Сбросить">
<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
  </td>
</tr>
</table>
  ');
}


if((isset($productSAVE)) and $name_new!="")// запись в базу
{
if(CheckedRules($UserStatus["page_site"],2) == 1){
$sql="INSERT INTO ".$SysValue['base']['table_name29']."
VALUES ('','$name_new','$num_new','$parent_to_new','$EditorContent')";
$result=mysql_query($sql)or @die("".mysql_error()."");
echo"
    <script language=\"JavaScript1.2\">
CLREL('left');
</script>
     ";
}else $UserChek->BadUserFormaWindow();
}
else
   {
   Disp();
   }

?>
</body>
</html>
