<?


function News($pole1,$pole2,$pid)// Вывод новостей
{
global $SysValue,$PHP_SELF,$systems,$page;

if(empty($pole1)) $pole1=date("U")-86400;
 else $pole1=GetUnicTime($pole1)-86400;
if(empty($pole2)) $pole2=date("U");
 else $pole2=GetUnicTime($pole2)+86400;

if($pid=="all") $sql="select * from ".$SysValue['base']['table_name8']." order by id";
  else $sql="select * from ".$SysValue['base']['table_name8']." where datau<'$pole2' and datau>'$pole1' order by id desc";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$data=$row['datas'];
	$zag=$row['zag'];
	$kratko=strip_tags($row['kratko']);
	if (@$imgname)
	 {
	 $imgchek="ok";
	 }
	 else
	    {
		$imgchek="<strong>no</strong>";
		}
	@$display.="
	<tr id=\"r".$id."\" class=row>
	<td align=\"center\" id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('news/adm_newsID.php?id=$id',650,650)\">
	$data
	</td>
	<td  id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('news/adm_newsID.php?id=$id',650,650)\">
	$zag
	</td>
	<td  id=Nws class=Nws onmouseover=\"show_on('r".$id."')\" onmouseout=\"show_out('r".$id."')\" onclick=\"miniWin('news/adm_newsID.php?id=$id',650,650)\">
	".substr($kratko,0,150)."...
	</td>
	<td><input type=checkbox name='c".$id."' value=\"$id\"></td>
    </tr>
	";
	@$i++;
	}
if($i>30)$razmer="height:600;";
	$_Return="
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<form name=\"form_flag\">
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
	<td width=\"10%\" id=pane align=center><img  src=\"icon/blank.gif\"  width=\"1\" height=\"1\" border=\"0\" onLoad=\"starter('news');\" align=left><span name=txtLang id=txtLang>Дата</span></td>
	<td width=\"45%\" id=pane align=center><span name=txtLang id=txtLang>Заголовок</span></td>
	<td width=\"45%\" id=pane align=center><span name=txtLang id=txtLang>Краткая информация</span></td>
      <td width=\"25\" id=pane align=center style=\"padding:1px\"><input type=checkbox value=1 name=DoAll onclick=\"SelectAllBox(this,form_flag)\"></td>
</tr>
	".$display."
    </table>
</table>
</form>
</div>
	".'
<div class=cMenu id=cMenuNws> 
	<TABLE style="width:260px;"  border="0" cellspacing="0" cellpadding="0">
	<TR><TD id="txtLang" STYLE="background: #C0D2EC;"><B>Действия</B></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews46>Удалить из базы</A></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews47>Разослать пользователям</A></TD></TR>	
	</TABLE>
</div>

';
return $_Return;
}

function Ras_data()// Вывод списка рассылки
{
global $table_name8;
$sql="select distinct datas from $table_name8 order by id desc LIMIT 0, 10";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$data=$row['datas'];
	@$disp.="<option value='$data'>$data";
	}
return @$disp;
}

?>
