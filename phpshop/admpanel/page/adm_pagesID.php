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

function Disp_cat_pod($category)// вывод каталогов в выборе подкаталогов
{
global $SysValue;
$sql="select name from ".$SysValue['base']['table_name29']." where id='$category'";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
@$name=$row['name'];
return @$name." -> ";
}

function Disp_cat($category)// вывод каталогов в выборе
{
global $SysValue;
$sql="select name,parent_to from ".$SysValue['base']['table_name29']." where id=$category";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$num = mysql_num_rows($result);
if($num>0){
$name=$row['name'];
$parent_to=$row['parent_to'];
$dis=Disp_cat_pod($parent_to).$name;
}
elseif($category == 1000) $dis=$SysValue['Lang']['Category'][12];
elseif($category == 2000) $dis=$SysValue['Lang']['Category'][13];
return @$dis;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title>Редактирование Страниц</title>
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
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<? 
echo $Lang;?>/language_windows.js"></script>
<script type="text/javascript" src="../java/tabpane.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,650,600);
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
// Редактирование записей книги
	  $sql="select * from $table_name12 where id=$id";
      $result=mysql_query($sql);
	  $row = mysql_fetch_array($result);
	$id=$row['id'];
	$name=$row['name'];
	$num=$row['num'];
	$link=$row['link'];
	$title=$row['title'];
	$keywords=$row['keywords'];
	$description=$row['description'];
	$content=stripslashes($row['content']);
	$category=$row['category'];
	$dir=$row['dir'];
	$odnotip=$row['odnotip'];
	$secure_groups=$row['secure_groups'];
	if ($row['flag']==1)
	   {
	   $sel="checked";
	   }
	   else
	      {
		  $sel2="checked";
		  }
	 if ($row['enabled']==1) $sel3="checked";
	 if ($row['secure']==1)  $sel4="checked";
	  ?>

<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
	<td style="padding:10">
	<b><span name=txtLang id=txtLang>Редактирование Страницы</span> "<a target="_blank" href="http://<?=$SERVER_NAME.$SysValue['dir']['dir']."/page/".$link.".html";?>"  ><?=$SERVER_NAME.$SysValue['dir']['dir']."/page/".$link.".html";?></a>"</b><br>
	&nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
	</td>
	<td align="right">
	<img src="../img/i_website_tab[1].gif" border="0" hspace="10">
	</td>
</tr>
</table>
<form name="product_edit" method=post onsubmit="Save()">
<!-- begin tab pane -->
<div class="tab-pane" id="article-tab" style="margin-top:5px;height:250px">

<script type="text/javascript">
tabPane = new WebFXTabPane( document.getElementById( "article-tab" ), true );
</script>


<!-- begin intro page -->
<div class="tab-page" id="intro-page" style="height:420px">
<h2 class="tab"><span name=txtLang id=txtLang>Основное</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "intro-page" ) );
</script>


<table width="100%">
<tr>
	<td>
	<FIELDSET>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>К</u>аталог</span>:</LEGEND>
	<div style="padding:10">
	<?echo '
<input type=text id="myName"  style="width: 500" value="'.Disp_cat($category).'">
<input type="hidden" value="'.$category.'" name="category_new" id="myCat">
<BUTTON style="width: 3em; height: 2.2em; margin-left:5"  onclick="miniWinFull(\'adm_cat.php?category='.$category.'\',300,400,300,200);return false;"><img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>';
	?>
	</FIELDSET>
	</td>
	
</tr>
<tr>
	<td>
	<FIELDSET id=fldLayout>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Н</u>азвание</span></LEGEND>
<div style="padding:10">
<input type="text" style="width:100%;" value="<?=$name?>" name="name_new">
</div>
</FIELDSET>
	</td>
</tr>
<tr>
   <td valign="top">
   <table>
   <tr>
   <td><FIELDSET id=fldLayout>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>С</u>сылка</span></LEGEND>
<div style="padding:10">
/page/<input type="text" name="link_new" style="width:110" value="<?=$link?>">.html
</div>
</FIELDSET>
</td>
	<td>
	<FIELDSET id=fldLayout >
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>П</u>озиция</span>:</LEGEND>
<div style="padding:10">
<input type=text name=num_new  style="width:100%" value="<?=$num?>">
</div>
</FIELDSET>
	</td>

</TR>
<tr>
    <td align=left  colspan=2>
	
	<FIELDSET id=fldLayout >
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Р</u>екомендуемые товары для совместной продажи</span>:</LEGEND>
<div style="padding:10">
<textarea class=full name=odnotip_new style="height:50px"><?=$odnotip;?></textarea>
<table width="570">
<tr>
	<td><img src="../icon/icon_info.gif" alt="" width="16" height="16" border="0" align="absmiddle"> <span name=txtLang id=txtLang>Введите идентификаторы (ID) товаров через запятую без пробела</span> (100,101).</td>
	
</tr>
</table>



</div>
</FIELDSET>

	</td>
</tr>
   </table>
   </td>
   
	
</tr>
</table>


</div>

<div class="tab-page" id="content" style="height:420px">
<h2 class="tab"><span name=txtLang id=txtLang>Содержание</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "content" ) );
</script>
<table width="100%">
<tr>
	<td colspan="3" width="100%">
	<FIELDSET id=fldLayout>
<div style="padding:10">
<?
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
		oEdit1.height=360;
		oEdit1.btnStyles=true;
	    oEdit1.css="'.$MyStyle.'";
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
	<input type="hidden" name="EditorContent" id="EditorContent">
</div>
	';
	}
else{
echo '
<textarea name="EditorContent" id="EditorContent" style="width:100%;height:360px">'.$content.'</textarea>
';
}
	
	?>
</FIELDSET>
	</td>
</tr>
</table>
</div>
<!-- begin intro page -->
<div class="tab-page" id="promo" style="height:420px">
<h2 class="tab"><span name=txtLang id=txtLang>Заголовки</span></h2>
<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "promo" ) );
</script>
<table width="100%">
<tr>
	 <td  valign="top">
	<FIELDSET id=fldLayout>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>З</u>аголовок</span> (Title)</LEGEND>
<div style="padding:10">
<textarea  name="title_new" style="width:100%; height:70px"><?=$title?></textarea>
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	<td valign="top">
	<FIELDSET id=fldLayout>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>О</u>писание</span> (Description)</LEGEND>
<div style="padding:10">
<textarea  name="description_new" style="width:100%; height:70px"><?=$description?></textarea>
</div>
</FIELDSET>
	</td>
</tr>
<tr>
	 <td  valign="top">
	<FIELDSET id=fldLayout>
<LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>К</u>лючевые слова</span> (Keywords)</LEGEND>
<div style="padding:10">
<textarea  name="keywords_new" style="width:100%; height:70px"><?=$keywords?></textarea>
</div>
</FIELDSET>
	</td>
</tr>
</table>


</div>
<div class="tab-page" id="security" style="height:420px">
<h2 class="tab"><span name=txtLang id=txtLang>Безопасность</span></h2>

<script type="text/javascript">
tabPane.addTabPage( document.getElementById( "security" ) );
</script>
<table width="100%">
<tr>
<td width="100%">

<SCRIPT>
function enable_div1() {
if (document.getElementById('secure_new').checked) {
	document.getElementById('allreg').disabled=false;
	document.getElementById('allusers').checked=true;
} else {
	document.getElementById('allreg').disabled=true;
}
}


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
<input type="checkbox" name="enabled_new" value="1" <?=$sel3?>> <span name=txtLang id=txtLang>Включить</span>
<BR>
<input type="checkbox" id="secure_new" name="secure_new" onClick="enable_div1()" value="1" <?=$sel4?>> <span name=txtLang id=txtLang>Показывать только зарегистрированным пользователям</span><BR>
<?
$sql='select id,name from '.$SysValue['base']['table_name28'].' WHERE enabled="1"';
$result=mysql_query($sql);
$num = mysql_num_rows($result);
if ($num) { ?>

<DIV <? if ($sel4!=="checked") echo "disabled";?>  id="allreg">
<span name=txtLang id=txtLang>Из зарегистрировавшихся показывать:</span><BR>
&nbsp;&nbsp;&nbsp;
<input type="HIDDEN" name="9999" value="0">
<?
if (strlen($secure_groups)) {$che='';} else {$che='checked';}

?>
<input type="checkbox" onClick="enable_div2()" id="allusers" name="seq[9999]" <?=$che?> value="1"><span name=txtLang id=txtLang>Всем пользователям (снимите отметку, чтобы выбрать определенные группы)</span><BR>

<DIV <?if (!(strlen($secure_groups))) echo "disabled";?> id="regsel" style="overflow-y:auto; height:280px;">
<BR>
<?
	while ($row = mysql_fetch_array($result)) {
		if (strlen($secure_groups)) {
			$string='i'.$row['id'].'-1i';
			if (strpos($secure_groups,$string) !==false) {$che='checked';} else {$che='';}
		} else {$che='';}
		echo '&nbsp;&nbsp;&nbsp;
			<input type="HIDDEN" name="seq['.$row['id'].']" value="0">
			<input type="checkbox" name="seq['.$row['id'].']" '.$che.' value="1">'.$row['name'].'<BR>';
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
<hr>
<table cellpadding="0" cellspacing="0" width="100%" height="50" >
<tr>
    <td align="left" style="padding:10">
    <BUTTON class="help" onclick="helpWinParent('page_site_catalog')">Справка</BUTTON></BUTTON>
	</td>
	<td align="right" style="padding:10">
	<input type="hidden" name="id" value="<?=$id?>">
	<input type="submit" name="editID" value="OK" class=but>
	<input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis();">
    <input type="hidden" class=but  name="productDELETE" id="productDELETE">
	<input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
</form>
	  <?

if(isset($editID))// Запись редактирования
{
if(CheckedRules($UserStatus["page_site"],1) == 1){

if(is_array($seq))
foreach ($seq as $crid =>$value) {
	$sq_new.='i'.$crid.'-'.$value.'i';
	if (isset($seq['9999'])) {$sq_new=''; break;}
}



$sql="UPDATE $table_name12
SET
name='$name_new',
link='$link_new',
category='$category_new',
keywords='$keywords_new',
description='$description_new',
content='".addslashes($EditorContent)."',
flag='$flag_new',
num='$num_new',
datas='".date("U")."',
odnotip='$odnotip_new',
title='$title_new',
enabled='$enabled_new',
secure='$secure_new',
secure_groups='$sq_new' where id='$id'";

//echo $s;
//print_r($seq);

//echo $sql;
///*
$result=mysql_query($sql)or @die("Невозможно изменить запись".mysql_error());
echo"
	  <script>
CLREL(\"right\");
window.close(); 
</script>
	   ";
//*/
}else $UserChek->BadUserFormaWindow();
}
if(@$productDELETE=="doIT")// Удаление
{
if(CheckedRules($UserStatus["page_site"],1) == 1){
$sql="delete from $table_name12
where id='$id'";
$result=mysql_query($sql)or @die("Невозможно изменить запись");
echo"
	  <script>
CLREL(\"right\");
window.close(); 
</script>
	   ";
}else $UserChek->BadUserFormaWindow();
}
?>



