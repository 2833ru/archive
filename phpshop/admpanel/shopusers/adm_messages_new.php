<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

// подключаем используемые классы
$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("mail");



require("../language/russian/language.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Создание сообщения пользователю</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">
        <form name="product_edit"  method=post>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
                <tr bgcolor="#ffffff">
                    <td style="padding:10">
                        <b><span name=txtLang id=txtLang>Создание Нового Сообщения Пользователю</span></b><br>

                    </td>
                    <td align="right">
                        <img src="../img/i_mail_forward_med[1].gif" border="0" hspace="10">
                    </td>
                </tr>
            </table>
            <br>
            <table class=mainpage4 cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
                <tr>
                    <td>

                        <FIELDSET>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>З</u>аголовок сообщения</span></LEGEND>
                            <div style="padding:10">
                                <input type=text name="Subject_new"  style="width: 100%" value="">
                                </FIELDSET>
                                </TD>
                                <TD>
                                    <FIELDSET>
                                        <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Д</u>ата сообщения</span></LEGEND>
                                        <div style="padding:10">
                                            <input type=HIDDEN name="DateTime_new" value="<?= date("Y-m-d H:i:s") ?>">
                                            <input readonly disabled type=text style="width: 100%" value="<?= date("Y-m-d H:i:s") ?>">
                                            </FIELDSET>

                                            </TD>
                                        </TR><TR>     <TD colspan=2>

                                            <FIELDSET>
                                                <LEGEND><span name=txtLang id=txtLang><u>Т</u>екст сообщения</span></LEGEND>
                                                <div style="padding:10">
                                                    <textarea cols="" rows="" name="Message_new" style="width:100%;height:100px;">
                                                    </textarea>
                                                </div>
                                            </FIELDSET>



                                        </td>
                                    </tr>


                                    </table>
                                    <hr>
                                    <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                                        <tr>
                                            <td align="left" style="padding:10">
                                                <BUTTON class="help" onclick="helpWinParent('shopusers_messages')">Справка</BUTTON></BUTTON>
                                            </td>
                                            <td align="right" style="padding:10">
                                                <input type="submit" name="editID" value="OK" class=but>
                                                <input type="reset" name="btnLang" class=but value="Сбросить">
                                                <input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
                                            </td>
                                        </tr>
                                    </table>
                                    </form>
                                    <?
                                    if (isset($UID) and !empty($Message_new)) {// Запись редактирования
                                        if (CheckedRules($UserStatus["delivery"], 2) == 1) {


                                            $sql = 'INSERT INTO ' . $SysValue['base']['table_name37'] . '
VALUES ("",0,' . $UID . ',' . $_SESSION['idPHPSHOP'] . ',\'' . $DateTime_new . '\',\'' . $Subject_new . '\',\'' . $Message_new . '\',"1")';
                                            $result = mysql_query($sql) or @die("" . mysql_error() . "");


                                            $sql = 'SELECT mail,name FROM ' . $SysValue['base']['table_name27'] . ' WHERE id=' . $UID . ' LIMIT 1';
                                            $result = mysql_query($sql) or @die("" . mysql_error() . "");
                                            $row = mysql_fetch_array($result);
                                            $mail = $row['mail'];
                                            $name = $row['name'];

                                            $GetSystems = GetSystems();

                                            PHPShopParser::set('adminMessage', TotalClean($Message_new, 2));
                                            PHPShopParser::set('user_name', $name);


//                                            $zag_adm = $GetSystems['name'] . " -  Сообщение от Администратора";
                                            $zag_adm = "Сообщение от Администратора";
                                            //отсылаем письмо
                                            $PHPShopMail = new PHPShopMail($mail, $GetSystems['adminmail2'], $zag_adm, '', true, true);
                                            $content_adm = PHPShopParser::file('../../lib/templates/users/mail_admin_message.tpl', true);
                                            if (!empty($content_adm)) {
                                                $PHPShopMail->sendMailNow($content_adm);
                                            }


                                            echo'
<script>
CLREL("right");
</script>
	   ';
//*/
                                        }
                                        else
                                            $UserChek->BadUserFormaWindow();
                                    }
                                    ?>



