<?
require("../connect.php");
@mysql_connect ("$host", "$user_db", "$pass_db")or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase")or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// Языки
$GetSystems=GetSystems();
$option=unserialize($GetSystems['admoption']);
$Lang=$option['lang'];
require("../language/".$Lang."/language.php");
if(CheckedRules($UserStatus["option"],1) == 0) die ("Недостаточно прав!");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <title>Реквизиты</title>
<META http-equiv=Content-Type content="text/html; charset=windows-1251">
<LINK href="../css/texts.css" type=text/css rel=stylesheet>
<script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
<script type="text/javascript" language="JavaScript1.2" src="../language/<?=$Lang?>/language_windows.js"></script>
<script>
DoResize(<? echo $GetSystems['width_icon']?>,500,500);
</script>
</head>
<body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0" onload="DoCheckLang(location.pathname,<?=$SysValue['lang']['lang_enabled']?>);preloader(0)">
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
$sql="select * from $table_name3";
$result=mysql_query($sql);
$row = mysql_fetch_array($result);
$name=$row['name'];
  $company=stripslashes($row['company']);
  $tel=$row['tel'];
  $adminmail2=$row['adminmail2'];
  $bank=$row['bank'];
  $LoadBanc=unserialize($bank);
echo"
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" id=\"title\">
<tr bgcolor=\"#ffffff\">
  <td style=\"padding:10\">
  <b><span name=txtLang id=txtLang>Настройка Реквизитов Фирмы</span></b><br>
  &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Реквизиты учитываются при выставлении счета</span>.
  </td>
  <td align=\"right\">
  <img src=\"../img/i_mailbox_med[1].gif\" border=\"0\" hspace=\"10\">
  </td>
</tr>
</table>
<br>
<table >
<tr valign=\"top\">
<form action=\"$PHP_SELF\" method=\"post\" name=\"system_forma\">
<tr class=adm2>
    <td align=right>
   <span name=txtLang id=txtLang> Название магазина</span>:
    </td>
    <td align=left>
    <input type=text name=name_new size=60 value=\"$name\" title=\"$name\">
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
    <span name=txtLang id=txtLang>Владелец</span>:
    </td>
    <td align=left>
    <input type=text name=company_new size=60 value=\"$company\" title=\"$company\">
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
   <span name=txtLang id=txtLang> Телефоны</span>:
    </td>
    <td align=left>
    <input type=text name=tel_new size=60 value=\"$tel\">
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
   <span name=txtLang id=txtLang> Почта для заказов<br>через запятую</span>:
    </td>
    <td align=left>
     <input type=text name=adminmail2_new size=60 value=\"$adminmail2\">
    </td>
  </tr>
<tr class=adm2>
    <td align=right>
    <span name=txtLang id=txtLang>Наименование организации</span>:
    </td>
    <td align=left>
    <input type=text name=org_name size=60 value=\"".$LoadBanc['org_name']."\">
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
   <span name=txtLang id=txtLang> Юридический адрес</span>:
    </td>
    <td align=left>
    <input type=text name=org_ur_adres size=60 value=\"".$LoadBanc['org_ur_adres']."\">
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
    <span name=txtLang id=txtLang>Физический адрес</span>:
    </td>
    <td align=left>
    <input type=text name=org_adres size=60 value=\"".$LoadBanc['org_adres']."\">
    </td>
  </tr>
<tr class=adm2>
    <td align=right>
    <span name=txtLang id=txtLang>ИНН</span>:
    </td>
    <td align=left>
    <input type=text name=org_inn size=60 value=\"".$LoadBanc['org_inn']."\" >
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
   <span name=txtLang id=txtLang> КПП</span>:
    </td>
    <td align=left>
    <input type=text name=org_kpp size=60 value=\"".$LoadBanc['org_kpp']."\">
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
   <span name=txtLang id=txtLang> № Счета организации</span>:
    </td>
    <td align=left>
    <input type=text name=org_schet size=60 value=\"".$LoadBanc['org_schet']."\">
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
   <span name=txtLang id=txtLang> Наименование банка</span>:
    </td>
    <td align=left>
    <input type=text name=org_bank size=60 value=\"".$LoadBanc['org_bank']."\">
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
    <span name=txtLang id=txtLang>БИК</span>:
    </td>
    <td align=left>
  <input type=text name=org_bic size=60 value=\"".$LoadBanc['org_bic']."\">
    </td>
  </tr>
  <tr class=adm2>
    <td align=right>
    <span name=txtLang id=txtLang>№ Счета банка</span>:
    </td>
    <td align=left>
    <input type=text name=org_bank_schet size=60 value=\"".$LoadBanc['org_bank_schet']."\">
    </td>
  </tr>
</table>
<hr>
<table cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" height=\"50\" >
<tr>
     <td align=\"left\" style=\"padding:10\">
    <BUTTON class=\"help\" onclick=\"helpWinParent('recvizit')\">Справка</BUTTON>
  </td>
  <td align=\"right\" style=\"padding:10\">
<input type=submit value=ОК class=but name=optionsSAVE>
  <input type=submit name=btnLang value=Отмена class=but onClick=\"return onCancel();\">
  </td>
</tr>
</form>
</table>
";


if(isset($optionsSAVE))
{
if(CheckedRules($UserStatus["option"],1) == 1){
$LoadBancNew = array(
"org_name"=>str_replace("\"","&quot;",$org_name),
"org_ur_adres"=>$org_ur_adres,
"org_adres"=>$org_adres,
"org_inn"=>$org_inn,
"org_kpp"=>$org_kpp,
"org_schet"=>$org_schet,
"org_bank"=>str_replace("\"","&quot;",$org_bank),
"org_bic"=>$org_bic,
"org_bank_schet"=>$org_bank_schet
);

$LoadBancSer=serialize($LoadBancNew);
$sql="UPDATE $table_name3
SET
name='".addslashes($name_new)."',
company='".addslashes($company_new)."',
tel='$tel_new',
adminmail2='$adminmail2_new',
bank ='".$LoadBancSer."'";
$result=mysql_query($sql)or @die("".mysql_error()."");
$UpdateWrite=UpdateWrite();// Обновляем LastModified
echo"
   <script>
   CL();
   </script>
     ";
   }else $UserChek->BadUserFormaWindow();
   } 
?>


