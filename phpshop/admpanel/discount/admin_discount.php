<?
function Discount()// Вывод скидок
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name23']." order by discount";
$result=mysql_query($sql);

while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$discount=$row['discount'];
	$sum=$row['sum'];
	$enabled=$row['enabled'];
	if(($enabled)=="1"){$checked="<img src=img/icon-activate.gif  >";}else{$checked="<img src=img/icon-deactivate.gif>";};
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('discount/adm_discountID.php?id=$id',400,270,event)\">
<td align=\"center\">$checked</td>
<td class=forma>
	$sum ".GetIsoValuta()."
	</td>
	<td class=forma>
	$discount%
	</td>
    </tr>
	";
	@$i++;
	}
if($i>30)$razmer="height:600;";
	return"
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"50\" id=pane align=>+/-</td>
	<td width=\"45%\" id=pane align=><span name=txtLang id=txtLang>Сумма</span></td>
	<td width=\"45%\" id=pane align=><span name=txtLang id=txtLang>Скидка</span></td>
</tr>
	".$display."
    </table>
</table>

<div align=\"right\" style=\"width:50%;padding:10\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('discount/adm_discount_new.php',400,270)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>
	";
}
?>
