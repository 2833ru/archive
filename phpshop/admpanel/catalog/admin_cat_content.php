<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");
include("admin_catalog.php");

$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);

// Подключение языков
$Lang=$option['lang'];
require("../language/".$Lang."/language.php");
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<LINK href="../css/contextmenu.css" type=text/css rel=stylesheet>
<script language="JavaScript" src="../java/contextmenu.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<? 
echo $Lang;?>/language_windows.js"></script>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../java/sorttable.js"></script>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
</head>
<body style="background: threedface; color: windowtext;" topmargin="0" rightmargin="3" leftmargin="3"  onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>);preloader(0)" >
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
if(isset($pid))
	{
	echo CategoryID($pid);
	}
elseif(!empty($words))
	{
	echo CategorySearch($words);
	}
?>
	
</body>
</html>