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
  <title>Редактирование Каталога Страниц</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
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
function Disp_cat($parent_to,$n)// вывод каталогов в выборе
{
global $SysValue;
$sql="select name from ".$SysValue['base']['table_name29']." where id=$parent_to";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$name=$row['name'];
return "$name => $n";
}

function Disp()// вывод формы
{
global $catalogID,$SysValue;
$sql="select * from ".$SysValue['base']['table_name29']." where id='$catalogID'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
    $id=$row['id'];
    $name=$row['name'];
  $num=$row['num'];
  $parent_to=$row['parent_to'];
  $content=$row['content'];

  echo ('
<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Редактирование Каталога Страниц</span> "'.$name.'"</b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="../img/i_filemanager_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<br>
<table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
<form action="'.$PHP_SELF.'" name="product_edit"  method=post enctype="multipart/form-data">
<tr valign="top">
  <td>
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Н</u>азвание</span>:</LEGEND>
  <div style="padding:10">
    <INPUT type=text style="width: 450; height: 2.0em; " name=name_new value="'.$name.'"></FIELDSET>
  </td>
</tr>
<tr>
  <td>
  <FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>К</u>аталог</span>:</LEGEND>
  <div style="padding:10">
  <input type=text id="myName"  style="width: 400" value="'.Disp_cat($parent_to,$name).'">
  <input type="hidden" value="'.$parent_to.'" name="parent_to_new" id="myCat">

<input type="button" value="Выбрать..." onclick="miniWinFull(\'adm_cat.php?category='.$id.'\',300,400,300,200);">
</FIELDSET>
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
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
   <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent(\'page_site_catalog\')">Справка</BUTTON>
	</td>
  <td align="right" style="padding:10">
<input type=hidden name=productID value='.$id.'>
<input type="submit"  name="productSAVE" value="OK" class=but>
<input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis();">
<input type="hidden" class=but  name="productDELETE" id="productDELETE">
<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
  </td>
</tr>
</table>
  ');
}



if((isset($productSAVE)) and $name_new!="")// запись в базу
{
if(CheckedRules($UserStatus["page_site"],1) == 1){
$sql="UPDATE ".$SysValue['base']['table_name29']."
SET
name='$name_new',
parent_to='$parent_to_new',
content='$EditorContent',
num='$num_new'
where id='$productID'";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
echo"
<script language=\"JavaScript1.2\">
CLREL('left');
</script>
     ";
}else $UserChek->BadUserFormaWindow();
}

if(@$productDELETE=="doIT")// Удаление записи
{
if(CheckedRules($UserStatus["page_site"],1) == 1){
  $sql="delete from ".$SysValue['base']['table_name29']."
    where id='$productID'";
    $result=mysql_query($sql)or @die("Невозможно удалить запись");
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
