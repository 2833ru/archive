<?
function PHPSHOP_error($code,$flag)// Генерация ошибок и кодов ошибок
{
global $SysValue;
if(empty($flag)) return false;
else
return "
<HTML>
<head>
<title>PHPShop Software - Обнаружена ошибка $code</title>
<meta http-equiv=\"Content-Type\" content=\"text-html; charset=windows-1251\">
<style>
html,body{
height:100%;
margin:0px;
padding:0px
}
a { color:#1A79E5
}
</style>
<script>
function openhelp(){
window.open('http://www.phpshop.ru/help/Content/install/phpshop.html#6');
}
</script>
</head>
<body onload='setTimeout(\"openhelp()\",3000);'>
<table width=\"100%\" height=\"100%\">
<tr>
	<td align=\"center\">

<table  height=50 cellpadding=5 cellspacing=1 bgcolor=red>
<tr bgcolor=ffffff align=center>
	<td>
	<font color=#FF0000><strong><img src=\"".$SysValue['dir']['dir']."/phpshop/admpanel/img/error.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\"> PHPSHOP WARNING <img src=\"".$SysValue['dir']['dir']."/phpshop/admpanel/img/error.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\"></strong><br><br>
	<a href=\"http://www.phpshop.ru/help/Content/install/phpshop.html#6\" title=\"Нажмите для получения справки об ошибке\" target=\"_blank\"><img src=\"".$SysValue['dir']['dir']."/phpshop/admpanel/img/btn_help[1].gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=\"5\">Код ошибки = $code</a><br>
<font size=\"2\">нажмите для получения справки</font></font>
	</td>
</tr>
</table>
</td>
</tr>
</table>
</body>
</html>
";
}
?>
