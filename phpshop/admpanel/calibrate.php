<?
require("./connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("./enter_to_admin.php");

// Подключение языков
$GetSystems=GetSystems();
$Admoption=unserialize($GetSystems['admoption']);
$Lang=$Admoption['lang'];
$systems=GetSystems();
require("./language/".$Lang."/language.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
  <title>Калибровка окон</title>
<META http-equiv=Content-Type content="text/html; charset=<?=$SysValue['Lang']['System']['charset']?>">
<meta http-equiv="MSThemeCompatible" content="Yes">
<LINK href="./css/texts.css" type=text/css rel=stylesheet>
<LINK href="./css/tab.winclassic.css" type=text/css rel=stylesheet>
<SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
<script language="JavaScript" src="./java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" src="./java/tabpane.js"></script>
<script type="text/javascript" language="JavaScript" src="./language/<? 
echo $Lang;?>/language_windows.js"></script>
<script> 
DoResize(<? echo $GetSystems['width_icon']?>,700,630);
</script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">
<SCRIPT language=JavaScript type=text/javascript>preloader(1);</SCRIPT>

<table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
<tr bgcolor="#ffffff">
  <td style="padding:10">
  <b><span name=txtLang id=txtLang>Окно калибровки</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
  </td>
  <td align="right">
  <img src="./img/i_actionlog_med[1].gif" border="0" hspace="10">
  </td>
</tr>
</table>
<DIV style="height:478px; padding:10px;font-size:15px">
Увеличивайте размер окна, так чтобы была видна кнопка ОК.<BR>
Если после кнопки ОК осталось слишком много пространства - уменьшите размер окна.<BR>
После этого нажмите кнопку "сохранить размер" и закройте это окно!

<p>
<?

if(CheckedRules($UserStatus["option"],1) == 1){
echo getSizer();
}else $UserChek->BadUserFormaWindow();

?>
        </p>

</DIV>
<hr>
<table cellpadding="0" cellspacing="0" width="700px">
<tr>
    <td align="left" style="padding:10;"></td>
      <td align="right" style="padding:10">
      <input type=button onclick="self.close();" id="calOk" value="ОК" style="width: 7em; height: 2.2em; ">
  </TD>

</tr>
</table>
</form>

</body>
</html>