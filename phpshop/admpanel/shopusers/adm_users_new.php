<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");
require("../language/russian/language.php");


function GetUsersStatus($n) {
    global $SysValue;
    $sql = "select * from " . $SysValue['base']['table_name28'] . " order by discount";
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id = $row['id'];
        $name = $row['name'];
        $discount = $row['discount'];
        $sel = "";
        if ($n == $id)
            $sel = "selected";
        @$dis.="<option value=" . $id . " " . $sel . " >" . $name . " - " . $discount . "%</option>\n";
    }
    @$disp = "
<select name=status_new size=1>
<option value=0 id=txtLang>Авторизованный пользователь</option>
            $dis
</select>
            ";
    return @$disp;
}

// Авторизуем из корзины
if (isset($_GET['visitorID'])) {
    $sql = "select * from $table_name1 where id=" . intval($visitorID) . " limit 0, 1";
    $result = mysql_query($sql);
    $row = mysql_fetch_array($result);

    $id = $row['id'];
    $order = unserialize($row['orders']);

    $user = "user" . $_GET['visitorID'];
    $pass = substr(abs(crc32(rand(0, 100))), 0, 6);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Создание Нового Пользователя</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">
        <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
            <tr bgcolor="#ffffff">
                <td style="padding:10">
                    <b><span name=txtLang id=txtLang>Создание Нового Пользователя</span></b><br>

                </td>
                <td align="right">
                    <img src="../img/i_groups_med[1].gif" border="0" hspace="10">
                </td>
            </tr>
        </table>
        <form name="product_edit">
            <table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
                <tr>
                    <td colspan="2">
                        <FIELDSET id=fldLayout>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>С</u>татус</span></LEGEND>
                            <div style="padding:10">
                                <?= GetUsersStatus(@$status) ?>
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="enabled_new" value="1" checked><span name=txtLang id=txtLang>Вкл. (<input type="checkbox" name="sendActivationEmail" value="1"><span name=txtLang id=txtLang>Уведомить</span>)</span>&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="enabled_new" value="0" ><font color="#FF0000"><span name=txtLang id=txtLang>Заблокировать</span></font>
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <FIELDSET id=fldLayout style="width: 480; height: 8em;">
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Д</u>оступ</span></LEGEND>
                            <div style="padding:10px">
                                <table>
                                    <tr>
                                        <td>E-mail</td>
                                        <td width="10"></td>
                                        <td><input type="text" name="login_new"  style="width:250px;" value="<?= @$user; ?>"></td>
                                        <td rowspan="2" valign="top" style="padding-left:10">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Password</td>
                                        <td width="10"></td>
                                        <td><input type="text" name="password_new" style="width:250px;" value="<?= @$pass; ?>"></td>
                                    </tr>
                                </table>

                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <FIELDSET id=fldLayout>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Л</u>ичные данные</span></LEGEND>
                            <div style="padding:10px">
                                <table>
                                    <tr>
                                        <td><span name=txtLang id=txtLang>ФИО</span>:
                                        </td>
                                        <td><input type="text" name="name_new" style="width:350px" 
                                                   value="<?= @$order['Person']['name_person']; ?>"></td>
                                    </tr>
                                </table>

                            </div>
                        </FIELDSET>
                    </td>
                </tr>
            </table>
            <hr>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                <tr>
                    <td align="left" style="padding:10">
                        <BUTTON class="help" onclick="helpWinParent('shopusersID')">Справка</BUTTON></BUTTON>
                    </td>
                    <td align="right" style="padding:10">
                        <input type="submit" name="editID" value="OK" class=but>
                        <input type="hidden" name="orderID"  value="<?= @$visitorID; ?>">
                        <input type="reset" name="btnLang" name="delID" value="Сбросить" class=but>
                        <input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
                    </td>
                </tr>
            </table>
        </form>
        <?
        if (isset($editID) and !empty($login_new) and !empty($password_new)) {
            if (CheckedRules($UserStatus["shopusers"], 2) == 1) {
                $sql = "INSERT INTO " . $SysValue['base']['table_name27'] . "
VALUES ('','$login_new','" . base64_encode($password_new) . "','" . date("U") . "','$login_new','$name_new','','','','','$enabled_new','$status_new',
'','','','','')";
                $result = mysql_query($sql) or @die("Невозможно изменить запись");


// Обвновляем данные заказа
                $sql2 = "select id from " . $SysValue['base']['table_name27'] . " where login='$login_new' limit 0, 1";
                $result2 = mysql_query($sql2) or @die($sql2);
                $row = mysql_fetch_array($result2);
                $id_user = $row['id'];

                mysql_query("UPDATE " . $SysValue['base']['table_name1'] . " SET user=$id_user where id=$orderID");

                // отправляем пиьсьмо пользователю об его активации, если админ выбрал данную опцию
                if ($enabled_new AND $sendActivationEmail) {
                    // подключаем используемые классы
                    $_classPath = "../../";
                    include($_classPath . "class/obj.class.php");
                    PHPShopObj::loadClass("system");
                    PHPShopObj::loadClass("parser");
                    PHPShopObj::loadClass("mail");

                    $GetSystems = GetSystems();

                    PHPShopParser::set('user_name', $name_new);
                    PHPShopParser::set('login', $login_new);
                    PHPShopParser::set('password', $password_new);


//                                            $zag_adm = $GetSystems['name'] . " -  Сообщение от Администратора";
                    $zag_adm = "Ваш аккаунт был успешно Зарегистрирован и активирован Администратором";
                    //отсылаем письмо
                    $PHPShopMail = new PHPShopMail($login_new, $GetSystems['adminmail2'], $zag_adm, '', true, true);
                    $content_adm = PHPShopParser::file('../../lib/templates/users/mail_user_activation_by_admin_success.tpl', true);
                    if (!empty($content_adm)) {
                        $PHPShopMail->sendMailNow($content_adm);
                    }
                }


                echo"
<script>
DoReloadMainWindow('shopusers');
</script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        ?>