<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");
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
                        &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
                    </td>
                    <td align="right">
                        <img src="../img/i_mail_forward_med[1].gif" border="0" hspace="10">
                    </td>
                </tr>
            </table>
            <table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
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


                                            $codepage = "windows-1251";
                                            $header_adm = "MIME-Version: 1.0\n";
                                            $header_adm .= "From:   <" . $LoadItems['System']['adminmail2'] . ">\n";
                                            $header_adm .= "Content-Type: text/plain; charset=$codepage\n";
                                            $header_adm .= "X-Mailer: PHP/";
                                            $zag_adm = $LoadItems['System']['name'] . " -  Сообщение от Администратора";
                                            $content_adm = "
Доброго времени!
--------------------------------------------------------

Поступило сообщение администратора в интернет-магазине '" . $LoadItems['System']['name'] . "'
---------------------------------------------------------

" . TotalClean($Message_new, 2) . "

Дата/время: " . date("d-m-y H:i a") . "
---------------------------------------------------------

Вы всегда можете просмотреть ваши сообщения
он-лайн через 'Личный кабинет' -> 'Связь с менеджером' 
или по ссылке http://" . $SERVER_NAME . $SysValue['dir']['dir'] . "/users/message.html

Powered & Developed by www.PHPShop.ru";
                                            mail($mail, $zag_adm, $content_adm, $header_adm);



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



