<?
function Menu()// Вывод новостей
{
global $table_name14,$PHP_SELF,$systems,$page;
$sql="select * from $table_name14 order by num";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$dir=$row['dir'];
	if($row['element']==0) $element="Слева";
	else $element="Справа";
	if($row['flag']==1){
	$fl="<img src=\"img/icon-activate.gif\" width=\"16\" height=\"14\" border=\"0\">";
	}else{
	$fl="<img src=\"img/icon-deactivate.gif\" width=\"16\" height=\"14\" border=\"0\">";}
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('menu/adm_menuID.php?id=$id',650,600)\">
    <td align=\"center\" class=forma>
	$fl
	</td>
	<td class=forma>
	$name
	</td>
	<td class=forma>$dir</td>
	<td class=forma>$element</td>
    </tr>
	";
	@$i++;
	}
if($i>20)$razmer="height:600;";
	$_Return="
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\"  class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"10%\" id=pane align=center>+/-</td>
	<td id=pane align=center><span name=txtLang id=txtLang>Название</span></td>
	<td id=pane align=center><span name=txtLang id=txtLang>Привязка</span></td>
	<td id=pane align=center><span name=txtLang id=txtLang>Расположение</span></td>
</tr>
	".$display."
    </table>
	</td>
</tr>
</table>
<div align=\"right\" style=\"padding:10;\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('menu/adm_menu_new.php',630,600)\">
<img src=\"icon/page_new.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>

	";
return $_Return;
}
?>
