<?

function SortsGroup()// Вывод сортировки
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name20']." where category=0 order by name";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$description=$row['description'];
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('sort/adm_sortcategoryID.php?id=$id',500,500)\" title=\"".$description."\">
	<td class=\"forma\" style=\"padding-left: 10px\">
	$name
	</td>
    </tr>
	";
	@$i++;
	}
if($i>25)$razmer="height:600;";
	return "
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
	<td width=\"90%\" id=pane><span name=txtLang id=txtLang>Наименование</span></td>
</tr>
	".$display."
    </table>
</table>
<div align=\"right\" style=\"width:50%;padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('sort/adm_sortcategory_new.php',500,300)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>

	";
}

function Sorts()// Вывод сортировки
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name20']." where category!=0 order by name";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$description=$row['description'];
	if($row['filtr']==1){
	$fl="<img src=\"img/icon-duplicate-acl.gif\" alt=\"Фильтр\">";
	}else{
	$fl="";}
	
	if($row['goodoption']==1){
	$gl="<img src=\"img/icon-duplicate-banner.gif\" alt=\"Опция\">";
	}else{
	$gl="";}
	
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('sort/adm_sortID.php?id=$id',500,500)\" title=\"".$description."\">
    <td align=\"center\" class=\"forma\">$fl $gl</td>
	<td class=\"forma\">
	$name
	</td>
    </tr>
	";
	@$i++;
	}
if($i>25)$razmer="height:600;";
	return "
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\" width=\"50%\">
<tr>
	<td valign=\"top\" >
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\">
<tr>
    <td width=\"10%\" id=pane>+/-</td>
	<td width=\"90%\" id=pane><span name=txtLang id=txtLang>Наименование</span></td>
</tr>
	".$display."
    </table>
	</td>
	</tr>
</table>
<div align=\"right\" style=\"width:50%;padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('sort/adm_sort_new.php',500,350)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>

	";
}
?>
