<?
function RSSchanels()// Вывод 
{
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name38']." order by id";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$link=$row['link'];
	$day_num=$row['day_num'];
	$news_num = $row['news_num'];
	if ($row['start_date'] == 0) {
		$start_date = '';
	}
	else $start_date = date( "d-m-Y",$row['start_date']);
	
	if ($row['end_date'] == 0) {
		$end_date = '';
	}
	else $end_date = date( "d-m-Y",$row['end_date']);
	
	if(($row['enabled'])=="1"){$checked="<img src=img/icon-activate.gif  width=\"16\" height=\"16\" alt=\"В наличии\">";}else{$checked="<img src=img/icon-deactivate.gif  width=\"16\" height=\"16\" alt=\"Отсутствует\">";};

	@$display.='
	<tr onmouseover="show_on(\'r'.$id.'\')" id="r'.$id.'" onmouseout="show_out(\'r'.$id.'\')" class=row >
	
    <td align=center id=Nws class=Nws onmouseover="show_on(\'r'.$id.'\')" onmouseout="show_out(\'r'.$id.'\')" onclick="miniWin(\'rssgraber/adm_chanelsID.php?id='.$id.'\',400,480)">
    '.$checked.'
    </td>
    <td id=Nws class=Nws onmouseover="show_on(\'r'.$id.'\')" onmouseout="show_out(\'r'.$id.'\')" onclick="miniWin(\'rssgraber/adm_chanelsID.php?id='.$id.'\',400,480)">
	'.$link.'
	</td>
	<td id=Nws class=Nws onmouseover="show_on(\'r'.$id.'\')" onmouseout="show_out(\'r'.$id.'\')" onclick="miniWin(\'rssgraber/adm_chanelsID.php?id='.$id.'\',400,480)">
	'.$day_num.'
	</td>
	<td id=Nws class=Nws onmouseover="show_on(\'r'.$id.'\')" onmouseout="show_out(\'r'.$id.'\')" onclick="miniWin(\'rssgraber/adm_chanelsID.php?id='.$id.'\',400,480)">
	'.$news_num.'
	</td>
	<td id=Nws class=Nws onmouseover="show_on(\'r'.$id.'\')" onmouseout="show_out(\'r'.$id.'\')" onclick="miniWin(\'rssgraber/adm_chanelsID.php?id='.$id.'\',400,480)">
	'.$start_date.'
	</td>
	<td id=Nws class=Nws onmouseover="show_on(\'r'.$id.'\')" onmouseout="show_out(\'r'.$id.'\')" onclick="miniWin(\'rssgraber/adm_chanelsID.php?id='.$id.'\',400,480)">
	'.$end_date.'
	</td>
	<td class=forma style=\"padding:1px\" align=\"center\">
	<input type=checkbox name="c'.$id.'" value="'.$id.'">
	</td>
    </tr>
	';
	@$i++;
	}
if($i>20)$razmer="height:600;";
	return "
	
<div  id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 

<table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
	<form name=\"form_flag\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"50\" id=pane align=center><img  src=\"icon/blank.gif\"  width=\"1\" height=\"1\" border=\"0\" onLoad=\"starter('chanels');\" align=left><span name=txtLang id=txtLang>Статус</span></td>
	<td id=pane align=center><span name=txtLang id=txtLang>Адрес ленты</span></td>
	<td width=\"100\" id=pane align=center><span name=txtLang id=txtLang>Заборов в день</span></td>
    <td width=\"100\" id=pane align=center><span name=txtLang id=txtLang></span>Кол-во  новостей</td>
    <td width=\"100\" id=pane align=center><span name=txtLang id=txtLang>Дата начала</span></td>
    <td width=\"100\" id=pane align=center><span name=txtLang id=txtLang>Дата конца</span></td>
    <td width=\"25\" id=pane align=center style=\"padding:0px\"><input type=checkbox value=1 name=DoAll onclick=\"SelectAllBox(this,form_flag)\"></td>
</tr>

	".$display."

    </table>
    </form>
	</td>
</tr>
    </table>


<div align=\"right\" style=\"padding:10;width:100%\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('rssgraber/adm_chanels_new.php',400, 270); return false;\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая прозиция</span>
</BUTTON></div>
</div>
	".'
<div class=cMenu id=cMenuNws> 
	<TABLE style="width:260px;"  border="0" cellspacing="0" cellpadding="0">
	<TR><TD id="txtLang" STYLE="background: #C0D2EC;"><B>Действия</B></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNewsrss1>Удалить</A></TD></TR>
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNewsrss2>Включить</A></TD></TR>	
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNewsrss3>Выключить</A></TD></TR>	
	<TR><TD id="txtLang" STYLE="background: #fff"><A name="tarurl" id=nameNewsrss4>Привязать к единой дате</A></TD></TR>		
	</TABLE>
</div>
';


}
?>
