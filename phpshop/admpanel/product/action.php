<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Подключаем библиотеку поддержки.
require_once "../../lib/Subsys/JsHttpRequest/Php.php";

$JsHttpRequest =& new Subsys_JsHttpRequest_Php("windows-1251");


// Характеристики
function Sorts()
 {
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name21'];
$result=mysql_query($sql);
$Sorts='';
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$page=$row['page'];
	$array=array(
	"id"=>$id,
	"page"=>$page,
	"name"=>$name
	);
	$Sorts[$id]=$array;
	}
return $Sorts;
 }

 
// Каталоги Характеристик
function CatalogSorts()
 {
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name20'];
@$result=mysql_query($sql);
$Sorts='';
while (@$row = mysql_fetch_array(@$result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$category=$row['category'];
	$filtr=$row['filtr'];
	$flag=$row['flag'];
	$goodoption=$row['goodoption'];
	$page=$row['page'];
	$array=array(
	"id"=>$id,
	"name"=>$name,
	"category"=>$category,
	"filtr"=>$filtr,
	"page"=>$page,
	"flag"=>$flag,
	"goodoption"=>$goodoption
	);
	$Sorts[$id]=$array;
	}
@$SysValue['sql']['num']++;
return $Sorts;
 }
 
function SortDisp($vendor_array){
global $SysValue;
$Sort=Sorts();
$CatalogSort=CatalogSorts();
$vendor_array=unserialize($vendor_array);
foreach($vendor_array as $key=>$val)
       foreach($val as $v)
       @$dis.='
	   <tr class=row3 onclick="miniWin(\'./sort/adm_sortID.php?id='.$key.'\',500,500)" onmouseover="show_on(\'r'.$key.'\')" id="r'.$key.'" onmouseout="show_out(\'r'.$key.'\')">
	      <td class=Nws >'.$CatalogSort[$key]['name'].'</td>
		  <td class=Nws >'.$Sort[$v]['name'].'</td>
	   </tr>
	   ';

$disp="<table cellpadding=0 bgcolor=808080 border=0 cellspacing=1 width=100%>
	   <tr>
	      <td id=pane>Характеристика</td>
		  <td id=pane>Значение</td>
	   </tr>
$dis
</table>
";
return $disp;
}


switch($do){

      case("info"):
	  $sql="select * from  ".$SysValue['base']['table_name2']." where id=".$_REQUEST['xid'];
      $result=mysql_query(@$sql);
	  $num=mysql_numrows($result);
      @$row = @mysql_fetch_array(@$result);
      $id=$row['id'];
	  $uid=$row['uid'];
	  $spec=$row['spec'];
	  $newtip=$row['newtip'];
	  $parent=$row['parent'];
	  $parent_enabled=$row['parent_enabled'];
	if(($row['enabled'])=="1"){$checked="<td width=100 align=center><img src=./img/icon-activate.gif name=imgLang  alt=\"В наличии\" align=\"absmiddle\"> В наличии</td><td width=1></td>";}
else{$checked="<td width=100><img src=./img/icon-deactivate.gif name=imgLang  alt=\"Отсутствует\" align=\"absmiddle\"> Отсутствует</td><td width=1></td>";};
   

    if(($row['spec'])=="1"){$checked.="<td width=100 align=center><img name=imgLang src=./img/icon-duplicate-acl.gif align=\"absmiddle\" alt=\"Спецпредложение\"> Спец</td><td width=1></td>";}
      else $checked.="<td  width=100></td><td width=1></td>";

    if(($row['yml'])=="1"){$checked.="<td  width=100 align=center><img name=imgLang src=./img/icon-duplicate-banner.gif align=\"absmiddle\"  alt=\"YML прайс\"> Маркет</td><td width=1></td>";}
       else $checked.="<td  width=100></td><td width=1></td>";

    if(($row['newtip'])=="1") $checked.="<td  width=100 align=center><img name=imgLang src=./img/icon-move-banner.gif  align=\"absmiddle\" alt=\"Новинка\"> Новинка</td><td width=1></td>";
       else $checked.="<td width=100></td><td width=1></td>";

    if(($row['sklad'])=="1") $checked.="<td width=100 align=center><img name=imgLang src=./icon/cart_error.gif align=\"absmiddle\"  alt=\"Уведомление, под заказ\"> Под заказ</td><td width=1></td>";
      else $checked.="<td  width=100></td><td width=1></td>";

    if($parent_enabled==1) $checked.="<td  width=100 align=center><img name=imgLang src=./icon/plugin.gif align=\"absmiddle\"   alt=\"Подтип товара\"> Подтип товара</td><td width=1></td>";
      else $checked.="<td  width=100></td><td width=1></td>";

	  
	  
	  $interfaces='
	  <table width="100%"  cellpadding="0" cellspacing="1" style="border: 1px;border-style: inset;">
    <tr class=row3>
	'.$checked.'
	   </tr>
      </table>
	  ';
	  break;

      case("prev"):
	  $pic_small="";
	  $sql="select * from  ".$SysValue['base']['table_name2']." where id=".$_REQUEST['xid'];
      $result=mysql_query(@$sql);
	  $num=mysql_numrows($result);
      @$row = @mysql_fetch_array(@$result);
      $id=$row['id'];
	  $pic_small=$row['pic_small'];
	  $vendor_array=$row['vendor_array'];
	  $description=stripslashes($row['description']);
	  
      if($num>0){
	  
	  $interfaces='
      <table cellpadding="0" cellspacing="1"  border="0">
	  <tr>
      <td valign="top" align="center" width="150" style="cursor: pointer;" bgcolor="#ffffff">
	  ';
	  if(!empty($pic_small))
	  $interfaces.='
	  <table width="100%"  cellpadding="0" cellspacing="0" style="border: 1px;border-style: inset;">
<tr>
	<td valign="top">
	  <a href="javascript:miniWin(\'./product/adm_productID.php?productID='.$id.'\',650,630)"><iframe src="'.$pic_small.'" width="130" height="150" scrolling="No" frameborder="0"></iframe></a> </td>
	   </tr>
      </table>';
	  
	  $interfaces.='
	  </td>
	  <td valign="top" width="40%" bgcolor="#ffffff">
	  <div style="height:150;overflow:auto">
	 '.SortDisp($vendor_array).'
	  </div>
	  </td>
	  <td valign="top" width="40%">
	  <table cellpadding=0 bgcolor=808080 border=0 cellspacing=1 width=100%>
	   <tr>
	      <td id=pane>Описание</td>
	   </tr>
      <tr class=row3>
	      <td class=Nws>
		  <div style="height:120;overflow:auto">
		  '.$description.'
		  </div>
		  </td>
	   </tr>
      </table>
	  </td>
      </tr>
     </table>
	 ';
	  }else $interfaces="";
      break;

      case("del"):
	  $sql="delete from ".$SysValue['base']['table_name35']." where id=".$_REQUEST['xid'];
      mysql_query($sql);
	  
	  // Удаляем изображение с сервера
	  $name=$_REQUEST['img'];
	  $s_name=str_replace(".","s.",$name);
	  unlink($DOCUMENT_ROOT.$name);
	  unlink($DOCUMENT_ROOT.$s_name);
	  
	  $sql="select * from ".$SysValue['base']['table_name35']." where parent=".$_REQUEST['uid']." order by num desc";
      $result=mysql_query($sql);
	  
$i=1;
while($row = mysql_fetch_array($result))
    {
    $name=$row['name'];
    $id=$row['id'];
    @$dis.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('adm_galeryID.php?n=$id',650,500)\">
	  <td align=center>$i</td>
	   <td>$name</td>
	</tr>
	";
	$i++;
	}

$interfaces='
<table cellpadding="0" cellspacing="1"  border="0" bgcolor="#808080" width="100%">
<tr>
    <td width="20" id=pane align=center>№</td>
	<td id=pane align=center>Размещение</td>
</tr>
    '.$dis.'
    </table>
';
  break;


      case("update"):

$sql="select * from ".$SysValue['base']['table_name35']." where parent=".$_REQUEST['xid']." order by num desc";
$result=mysql_query($sql);
$i=1;
while($row = mysql_fetch_array($result))
    {
    $name=$row['name'];
    $id=$row['id'];
    @$dis.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('adm_galeryID.php?n=$id',650,500)\">
	  <td align=center>$i</td>
	   <td>$name</td>
	</tr>
	";
	$i++;
	}

$interfaces='
<table cellpadding="0" cellspacing="1"  border="0" bgcolor="#808080" width="100%">
<tr>
    <td width="20" id=pane align=center>№</td>
	<td id=pane align=center>Размещение</td>
</tr>
    '.$dis.'
    </table>
';
  break;
  
  
     case("num"):

mysql_query("update ".$SysValue['base']['table_name35']." set num='".$_REQUEST['num']."', info='".$_REQUEST['info']."' where id=".$_REQUEST['xid']);

$sql="select * from ".$SysValue['base']['table_name35']." where parent=".$_REQUEST['uid']." order by num desc";

$result=mysql_query($sql);
$i=1;
while($row = mysql_fetch_array($result))
    {
    $name=$row['name'];
    $id=$row['id'];
    @$dis.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('adm_galeryID.php?n=$id',650,500)\">
	  <td align=center>$i</td>
	   <td>$name</td>
	</tr>
	";
	$i++;
	}

$interfaces='
<table cellpadding="0" cellspacing="1"  border="0" bgcolor="#808080" width="100%">
<tr>
    <td width="20" id=pane align=center>№</td>
	<td id=pane align=center>Размещение</td>
</tr>
    '.$dis.'
    </table>
';
  break;
  
 }


 

$_RESULT = array(
  "interfaces"=> @$interfaces
  ); 

?>