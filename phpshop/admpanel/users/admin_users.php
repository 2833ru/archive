<?
function UsersJurnalBlack()// Вывод журнала
{
global $SysValue;
include("../geoip/geoip.inc");
$gi = geoip_open("../geoip/GeoIP.dat",GEOIP_STANDARD);
$sql="select * from ".$SysValue['base']['table_name22']." order by id desc";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$ip=$row['ip'];
	$datas=$row['datas'];
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('users/adm_jurnal_listlID.php?id=$id',645,500)\">
<td class=forma>
	$datas
	</td>
	<td class=forma>
	$ip
	</td>
	<td class=forma>
	".geoip_country_name_by_addr($gi, $ip)." (".geoip_country_code_by_addr($gi, $ip).")
	</td>
    </tr>
	";
	@$i++;
	}
if($i>20)$razmer="height:600;";
	$_Return="
<div align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"50%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"30%\" id=pane align=center><span name=txtLang id=txtLang>Дата</span></td>
	<td width=\"30%\" id=pane align=center>IP</td>
	<td width=\"30%\" id=pane align=center><span name=txtLang id=txtLang>Страна</span></td>
</tr>
	".$display."
    </table>
	</td>
</tr>
    </table>
</div>
	";
return $_Return;
}

function ChekBlacklist($n){
global $SysValue;
$sql="select * from ".$SysValue['base']['table_name22']." where ip='$n'";
$result=mysql_query($sql);
$num=mysql_num_rows($result);
return $num;
}


function UsersJurnal($pole1,$pole2)// Вывод журнала
{
global $AdmUsers,$SysValue;

if(empty($pole1)) $pole1=date("U")-86400;
 else $pole1=GetUnicTime($pole1)-86400;
if(empty($pole2)) $pole2=date("U");
 else $pole2=GetUnicTime($pole2)+86400;
 
include("../geoip/geoip.inc");
$gi = geoip_open("../geoip/GeoIP.dat",GEOIP_STANDARD);
$sql="select * from ".$SysValue['base']['table_name10']." where datas<'$pole2' and datas>'$pole1' order by id desc";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$user=$row['user'];
	$datas=$row['datas'];
	$ip=$row['ip'];
	if(($row['flag'])=="0"){$checked="<img src=img/icon-activate.gif  width=\"16\" height=\"16\" alt=\"Авторизаван\">";}else{$checked="<img src=img/icon-deactivate.gif  width=\"16\" height=\"16\" alt=\"Не авторизован\">";};
    $black=ChekBlacklist($row['ip']);
    if($black>0) $blackchecked="<img src=img/i_spam_filter_med_small.gif  width=\"15\" height=\"15\" alt=\"Черный список\">"; else $blackchecked="";
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row onclick=\"miniWin('users/adm_jurnalID.php?id=$id',400,270)\">
<td align=center class=forma>$checked  $blackchecked
	</td>
	<td class=forma>
	$user
	</td>
	<td class=forma>
	".dataV($datas)."
	</td>
	<td class=forma>
	$ip
	</td>
	<td class=forma>".geoip_country_name_by_addr($gi, $ip)." (".geoip_country_code_by_addr($gi, $ip).")</td>
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
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
    <td width=\"10%\" id=pane align=center><span name=txtLang id=txtLang>Вход</span></td>
	<td width=\"30%\" id=pane align=center><span name=txtLang id=txtLang>Пользователь</span></td>
    <td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>Дата</span></td>
	<td width=\"20%\" id=pane align=center>IP</td>
	<td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>Страна</span></td>
</tr>
	".$display."
    </table>
	</td>
</tr>
    </table>
</div>
	";
return $_Return;
}

function GetLastEnter($n){
global $SysValue;
$sql="select datas from ".$SysValue['base']['table_name10']." where user='$n' order by id desc LIMIT 1";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$num = mysql_numrows($result);
if($num==0) return "-";
$datas=$row['datas'];
$data=dataV($datas,"shot");
return $data;
}

function Users()// Вывод новостей
{
global $table_name19,$PHP_SELF,$systems,$page,$AdmUsers;
$sql="select * from $table_name19 order by status";
$result=mysql_query($sql);
while ($row = mysql_fetch_array($result))
    {
	$id=$row['id'];
	$login=$row['login'];
	$status=$row['status'];
	if(($row['enabled'])=="1"){$checked="<img src=img/icon-activate.gif  width=\"16\" height=\"16\" alt=\"В наличии\">";}else{$checked="<img src=img/icon-deactivate.gif  width=\"16\" height=\"16\" alt=\"Отсутствует\">";};
	@$display.="
	<tr onmouseover=\"show_on('r".$id."')\" id=\"r".$id."\" onmouseout=\"show_out('r".$id."')\" class=row  onclick=\"miniWin('users/adm_userID.php?id=$id',500,500)\">
    <td style=\"padding:3\" align=center id=Nws class=Nws>
	".$checked."
	</td>
	<td id=Nws class=Nws>
	".$row['mail']."
	</td>
	<td id=Nws class=Nws>
	$login
	</td>
	<td id=Nws class=Nws>
	".GetLastEnter($login)."
	</td>
    </tr>
	";
	@$i++;
	}
if($i>20)$razmer="height:600;";
	$_Return="
<div id=interfacesWin name=interfacesWin align=\"left\" style=\"width:100%;".@$razmer.";overflow:auto\"> 
<table width=\"70%\"  cellpadding=\"0\" cellspacing=\"0\" style=\"border: 1px;
	border-style: inset;\">
<tr>
	<td valign=\"top\">
<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" bgcolor=\"#808080\" class=\"sortable\" id=\"sort\">
<tr>
<td width=\"10%\" id=pane align=center><span name=txtLang id=txtLang>Статус</span></td>
    <td width=\"20%\" id=pane align=center>E-mail</td>
	<td width=\"25%\" id=pane align=center><span name=txtLang id=txtLang>Имя</span></td>
	<td width=\"20%\" id=pane align=center><span name=txtLang id=txtLang>Вход</span></td>
</tr>
	".$display."
    </table>
	</td>
</tr>
    </table>

<div align=\"right\" style=\"padding:10;width:70%\"><BUTTON style=\"width: 15em; height: 2.2em; margin-left:5\"  onclick=\"miniWin('users/adm_users_new.php',500,500)\">
<img src=\"icon/page_add.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">
<span name=txtLang id=txtLang>Новая позиция</span>
</BUTTON></div>
</div>
	";
return $_Return;
}
?>
