<?
function GetCatName($cat){
global $SysValue;
$sql="select name from ".$SysValue['base']['table_name']." where id=$cat";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
return $row['name'];
}

function CheckPreSearch($words){
global $SysValue;
$sql="select id from ".$SysValue['base']['table_name26']." where name REGEXP 'i".$words."i'";
$result=mysql_query($sql);
$num = mysql_num_rows($result);
return $num;
}


function SearchJurnal($pole1,$pole2)// ����� �������
{
global $SysValue;

if(empty($pole1)) $pole1=date("U")-86400;
 else $pole1=GetUnicTime($pole1)-86400;
if(empty($pole2)) $pole2=date("U");
 else $pole2=GetUnicTime($pole2)+86400;
 
$sql="select * from ".$SysValue['base']['table_name18']." where datas<'$pole2' and datas>'$pole1' order by id desc ";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$name=$row['name'];
	$datas=$row['datas'];
	$num=$row['num'];
	$dir=$row['dir'];
	$cat=$row['cat'];
	$set=$row['set'];
	$pre=CheckPreSearch($name);
	if($pre==1){
	$fl="<img src=\"img/btn_refresh[1].gif\" border=\"0\" alt=\"��������������� ������\">";
	}else{
	$fl="";}
	@$display.="
	<tr id=\"r".$id."\" class=row>
   <td id=Nws class=Nws onmouseout=\"show_out('r".$id."')\" onmouseover=\"show_on('r".$id."')\" align=center>$fl</td>
	<td id=Nws class=Nws onmouseout=\"show_out('r".$id."')\" onmouseover=\"show_on('r".$id."')\" >
	<a href=\"".$SysValue['dir']['dir']."/search/?words=$name&cat=$cat&set=$set\" title=\"������� �� ������:\n/search/?words=$name&cat=$cat&set=$set\" target=\"_blank\">$name</a>
	</td>
	<td id=Nws class=Nws onmouseout=\"show_out('r".$id."')\" onmouseover=\"show_on('r".$id."')\" >
	".dataV($datas,"shot")."
	</td>
	<td id=Nws class=Nws onmouseout=\"show_out('r".$id."')\" onmouseover=\"show_on('r".$id."')\" >
	$num
	</td>
	<td id=Nws class=Nws onmouseout=\"show_out('r".$id."')\" onmouseover=\"show_on('r".$id."')\" >
	".GetCatName($cat)."
	</td>
	<td id=Nws class=Nws onmouseout=\"show_out('r".$id."')\" onmouseover=\"show_on('r".$id."')\" >
	$dir
	</td>
	<td class=forma>
	<input type=checkbox name='c".$id."' value=\"$id\">
	</td>
    </tr>
	";
	@$i++;
	}
if($i>20)$razmer="height:600;";
	$_Return="

	<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\" > 


<form name=\"form_flag\">
<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"5%\" id=pane align=center><img  src=\"icon/blank.gif\"  width=\"1\" height=\"1\" border=\"0\" onLoad=\"starter('search_jurnal');\" align=left>&plusmn;</td>
	<td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>������</span></td>
    <td width=\"15%\" id=pane align=center><span name=txtLang id=txtLang>����</span></td>
	<td width=\"10%\" id=pane align=center><span name=txtLang id=txtLang>�������</span></td>
	<td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>������ � ��������</span></td>
	<td width=\"30%\" id=pane align=center><span name=txtLang id=txtLang>������������</span></td>
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
	<TR><TD id="txtLang" STYLE="background: #C0D2EC;"><B>��������</B></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews15>�������� � ��������� ����</A></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNews16>������� �� �������</A></TD></TR>	
	</TABLE>
</div>';
return $_Return;
}
?>