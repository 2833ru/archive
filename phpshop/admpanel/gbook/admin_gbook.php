<?
function Gbook($pole1,$pole2)// Вывод новостей
{
global $PHP_SELF,$systems,$SysValue;

if(empty($pole1)) $pole1=date("U")-86400;
 else $pole1=GetUnicTime($pole1)-86400;
if(empty($pole2)) $pole2=date("U");
 else $pole2=GetUnicTime($pole2)+86400;

$sql="select * from ".$SysValue['base']['table_name7']." where datas<'$pole2' and datas>'$pole1' order by id desc";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$datas=$row['datas'];
	$tema=$row['tema'];
	$otsiv=$row['otsiv'];
	$otvet=$row['otvet'];
	$flag=$row['flag'];
	$name=$row['name'];
	if(@$flag!=0)
	 {
	 $imgchek="<img src=\"img/icon-activate.gif\" border=\"0\">";
	 }
	 else
	    {
		$imgchek="<img src=\"img/icon-deactivate.gif\" border=\"0\">";
		}
	@$display.="
	<tr id=\"r".$id."\" class=row>
<td align=\"center\" id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('gbook/adm_gbookID.php?id=$id',630,580)\">
	$imgchek
	</td>
	<td align=\"center\" id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('gbook/adm_gbookID.php?id=$id',630,580)\">
	".dataV($datas,"shot")."
	</td>
	<td id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('gbook/adm_gbookID.php?id=$id',630,580)\">
	$name
	</td>
	<td id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('gbook/adm_gbookID.php?id=$id',630,580)\">
	".substr($tema,0,250)."...
	</td>
	<td><input type=checkbox name='c".$id."' value=\"$id\"></td>
    </tr>
	";
	@$i++;
	}
	if($i>15)$razmer="height:600;";
	$_Return="
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<form name=\"form_flag\">
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"7%\" id=pane align=center>+/-</td>
	<td width=\"12%\" id=pane align=center><img  src=\"icon/blank.gif\"  width=\"1\" height=\"1\" border=\"0\" onLoad=\"starter('gbook');\" align=left><span name=txtLang id=txtLang>Дата</span></td>
	<td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>Автор</span></td>
	<td width=\"60%\" id=pane align=center><span name=txtLang id=txtLang>Заголовок</span></td>
	      <td width=\"25\" id=pane align=center style=\"padding:1px\"><input type=checkbox value=1 name=DoAll onclick=\"SelectAllBox(this,form_flag)\"></td>
</tr>
	".$display."
    </table>
	</td>
</tr>
</table>
</form>
</div>
	".'
<div class=cMenu id=cMenuNws> 
	<TABLE style="width:260px;"  border="0" cellspacing="0" cellpadding="0">
	<TR><TD id="txtLang" STYLE="background: #C0D2EC;"><B>Действия</B></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews48>Отключить вывод</A></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews50>Включить вывод</A></TD></TR>	
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews49>Удалить из базы</A></TD></TR>		
	</TABLE>
</div>

';
	
return $_Return;
}
?>
