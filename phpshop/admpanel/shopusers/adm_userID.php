<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");
require("../language/russian/language.php");

// Формируем таблицу адресов
function dispAdr($id, $mass) {

    if (is_array($mass))
        foreach ($mass['list'] as $adrId => $data) {
            // выводим сгрупппированные данные пользователя
            $adr_info = "";
            if ($data['fio_new'])
                $adr_info .= ", ФИО: " . $data['fio_new'];
            if ($data['tel_new'])
                $adr_info .= ", тел.: " . $data['tel_new'];
            if ($data['country_new'])
                $adr_info .= ", страна: " . $data['country_new'];
            if ($data['state_new'])
                $adr_info .= ", регион/штат: " . $data['state_new'];
            if ($data['city_new'])
                $adr_info .= ", город: " . $data['city_new'];
            if ($data['index_new'])
                $adr_info .= ", индекс: " . $data['index_new'];
            if ($data['street_new'])
                $adr_info .= ", улица: " . $data['street_new'];
            if ($data['house_new'])
                $adr_info .= ", дом: " . $data['house_new'];
            if ($data['porch_new'])
                $adr_info .= ", подъезд: " . $data['porch_new'];
            if ($data['door_phone_new'])
                $adr_info .= ", код домофона: " . $data['door_phone_new'];
            if ($data['flat_new'])
                $adr_info .= ", квартира: " . $data['flat_new'];
            if ($data['delivtime_new'])
                $adr_info .= ", время доставки: " . $data['delivtime_new'];

            $adr_info = substr($adr_info, 2);

            // Выводим сгруппированные Юр. данные пользователя.
            $yur_info = "";
            if ($data['org_name_new'])
                $yur_info .= ", Наименование организации:" . $data['org_name_new'];
            if ($data['org_inn_new'])
                $yur_info .= ", ИНН:" . $data['org_inn_new'];
            if ($data['org_kpp_new'])
                $yur_info .= ", КПП" . $data['org_kpp_new'];
            if ($data['org_yur_adres_new'])
                $yur_info .= ", Юридический адрес:" . $data['org_yur_adres_new'];
            if ($data['org_fakt_adres_new'])
                $yur_info .= ", Фактический адрес:" . $data['org_fakt_adres_new'];
            if ($data['org_ras_new'])
                $yur_info .= ", Расчётный счёт:" . $data['org_ras_new'];
            if ($data['org_bank_new'])
                $yur_info .= ", Наименование банка:" . $data['org_bank_new'];
            if ($data['org_kor_new'])
                $yur_info .= ", Корреспондентский счёт:" . $data['org_kor_new'];
            if ($data['org_bik_new'])
                $yur_info .= ", БИК:" . $data['org_bik_new'];
            if ($data['org_city_new'])
                $yur_info .= ", Город:" . $data['org_city_new'];
            $yur_info = substr($yur_info, 2);

            if ($mass['main'] == $adrId)
                $main = "+";
            else
                $main = "";

            @$disp.='
            <tr onclick="miniWin(\'adm_adrID.php?adrId=' . $adrId . '&id=' . $id . '\',710,500)"  onmouseover="show_on(\'r' . $adrId . '\')" id="r' . $adrId . '" onmouseout="show_out(\'r' . $adrId . '\')" class=row>
                <td class="forma" align=center>' . $main . '</td>
                <td class="forma">' . $adr_info . '</td>
                <td class="forma">' . $yur_info . '</td>
            </tr>
            ';
        }

    return '<table cellpadding="0" cellspacing="1" width="100%" border="0" >
               <tr>
                   <td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Основной</span></td>
                   <td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Данные</span></td>
                   <td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Юр. данные</span></td>
               </tr>
               ' . @$disp . '
            </table>
            ';
}

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
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Редактирование Пользователя</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">
        <?
        $sql = "select * from " . $SysValue['base']['table_name27'] . " where id=$id";
        $result = mysql_query($sql);
        @$row = mysql_fetch_array(@$result);
        $id = $row['id'];
        $login = $row['login'];
        $password = $row['password'];
        $status = $row['status'];
        $mail = $row['mail'];
        $name = $row['name'];
        $company = $row['company'];
        $inn = $row['inn'];
        $tel = $row['tel'];
        $tel_code = $row['tel_code'];
        $kpp = $row['kpp'];
        $adres = $row['adres'];
        if ($row['enabled'] == 1) {
            $fl = "checked";
        } else {
            $fl2 = "checked";
        }
        ?>
        <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
            <tr bgcolor="#ffffff">
                <td style="padding:10">
                    <b><span name=txtLang id=txtLang>Редактирование Пользователя</span> "<?= $login ?>"</b><br>

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
                                <?= GetUsersStatus($status) ?>
                                &nbsp;&nbsp;&nbsp;
                                <input type="radio" name="enabled_new" value="1" <?= @$fl ?>><span name=txtLang id=txtLang>Вкл. (<input type="checkbox" name="sendActivationEmail" value="1"><span name=txtLang id=txtLang>Уведомить</span>)</span>&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="enabled_new" value="0" <?= @$fl2 ?>><font color="#FF0000"><span name=txtLang id=txtLang>Заблокировать</span></font>
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <FIELDSET id=fldLayout style="width: 480; height: 9em;">
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>Д</u>оступ</span></LEGEND>
                            <div style="padding:10">
                                <table>
                                    <tr>
                                        <td>
                                            <? if ($mail != $login) { ?>
                                                <input type="hidden" name="mail_new" value="<?= $mail ?>">
                                                Логин
                                            <? } else { ?>
                                                E-mail
                                            <? } ?>
                                        </td>
                                        <td width="10"></td>
                                        <td>
                                            <? if ($mail != $login) { ?>
                                                Логин для старых аккаунтов версии 3:
                                            <? } ?>
                                            <input type="text" name="login_new" value="<?= $login ?>" style="width:250px;" >

                                        </td>

                                        <td valign="top" style="padding-left:10">
                                            <button style="width: 14em; height: 2.2em; margin-left:5"  onclick="miniWin('../order/adm_visitor_new.php?userAdd=<?= $id ?>', 670, 550);
                                                    return false;">
                                                <img src="../img/page_attachment.gif" border="0" align="absmiddle" hspace=5>
                                                <span name=txtLang id=txtLang>Новый заказ</span></button>
                                            </div>
                                        </td>
                                    </tr>
                                    <? if ($mail != $login) { ?>
                                        <tr>
                                            <td>E-mail</td>
                                            <td width="10"></td>
                                            <td>
                                                <input type="text" name="mail_new" value="<?= $mail ?>" style="width:250px;" >
                                            </td>
                                        </tr>
                                    <? } ?>
                                    <tr>
                                        <td>Password</td>
                                        <td width="10"></td>
                                        <td><input type="text" name="password_new" style="width:250px;" value="<?= base64_decode($password); ?>"></td>
                                        <td valign="top" style="padding-left:10">
                                            <button style="width: 14em; height: 2.2em; margin-left:5" name="allorder"  onclick="DoReload('orders', '', '', '<?= $mail ?>');">
                                                <img src="../img/page_attachment.gif" border="0" align="absmiddle" hspace=5>
                                                <span name=txtLang id="txtLang">Все заказы</span>
                                            </button>

                                            </div>
                                        </td>
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
                            <div style="padding:10">
                                <table width="">
                                    <tr>
                                        <td>ФИО</td>
                                        <td width="10"></td>
                                        <td><input type="text" name="name_new" style="width:150px;" value="<?= $name; ?>"></td>
                                        <td>
                                            <BUTTON style="width: 15em; height: 2.2em;"  onclick="miniWin('adm_adrID.php?adrId=new&id=<?= $id ?>', 710, 500);
                                                    return false;">
                                                <img src="../icon/page_add.gif" width="16" height="16" border="0" align="absmiddle" hspace="5">
                                                <span name=txtLang id=txtLang>Добавить адрес</span>
                                            </BUTTON></td>
                                    </tr>
                                </table>
                                <br><br>
                                <div align="left" style="width:100%;height:150;overflow:auto"> 
                                    <?= dispAdr($id, unserialize($row['data_adres'])); ?>
                                </div>
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
                        <input type="hidden" name="userID" value="<?= $id ?>" >
                        <input type="submit" name="editID" value="OK" class=but>
                        <input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis();">
                        <input type="hidden" class=but  name="productDELETE" id="productDELETE">
                        <input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
                    </td>
                </tr>
            </table>
        </form>
        <?
        if (isset($allorder)) {
            echo"
<script>
DoReloadMainWindow('orders','','','" . $login_new . "');
</script>
       ";
        }
        if (isset($editID) and !empty($login_new) and !empty($password_new)) {
            if (CheckedRules($UserStatus["shopusers"], 1) == 1) {

                if (!isset($mail_new))
                    $mail_new = $login_new;

                $sql = "UPDATE " . $SysValue['base']['table_name27'] . "
SET
login='$login_new',
mail='$mail_new',
password='" . base64_encode($password_new) . "',
name='$name_new',
company='$company_new',
inn='$inn_new',
tel='$tel_new',
adres='$adres_new',
enabled='$enabled_new',
status='$status_new',
kpp='$kpp_new',
tel_code='$tel_code_new' 
where id='$userID'";
                $result = mysql_query($sql) or @die("Невозможно изменить запись");

                //Обновляем персональную скидку
                //Запрос алгоритма расчета персональной скидки
                $sql_d = "SELECT * FROM `" . $SysValue['base']['table_name28'] . "` WHERE `id` =" . $status_new . " ";
                $query_d = mysql_query($sql_d);
                $row_d = mysql_fetch_array($query_d);
                $cumulative_array = unserialize($row_d['cumulative_discount']);
                $cumulative_array_check = $row_d['cumulative_discount_check'];
                if ($cumulative_array_check == 1) {
                    //Список заказов
                    $sql_order = "SELECT " . $SysValue['base']['table_name1'] . ".* FROM `" . $SysValue['base']['table_name1'] . "` 
                            LEFT JOIN `" . $SysValue['base']['table_name32'] . "` ON " . $SysValue['base']['table_name1'] . ".statusi=" . $SysValue['base']['table_name32'] . ".id 
                            WHERE " . $SysValue['base']['table_name1'] . ".user =  " . $userID . " 
                            AND " . $SysValue['base']['table_name32'] . ".cumulative_action='1' ";
                    $query_order = mysql_query($sql_order);
                    $row_order = mysql_fetch_array($query_order);
                    $sum = '0'; //Очистка суммы
                    do {
                        $orders = unserialize($row_order['orders']);
                        $sum += $orders['Cart']['sum'];
                    } while ($row_order = mysql_fetch_array($query_order));

                    //Узнаем скидку
                    $q_cumulative_discount = '0'; //Очистка скидки
                    foreach ($cumulative_array as $key => $value) {
                        if ($sum >= $value['cumulative_sum_ot'] and $sum <= $value['cumulative_sum_do']) {
                            $q_cumulative_discount = $value['cumulative_discount'];
                            break;
                        }
                    }
                    //Обновляем скидку
                    $sql_update = "UPDATE  `" . $SysValue['base']['table_name27'] . "` SET `cumulative_discount` =  '" . $q_cumulative_discount . "' WHERE `id` =" . $userID . " ";
                    mysql_query($sql_update);
                } else {
                    $sql_update = "UPDATE  `" . $SysValue['base']['table_name27'] . "` SET `cumulative_discount` =  '0' WHERE `id` =" . $userID . " ";
                    mysql_query($sql_update);
                }




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


                    // $zag_adm = $GetSystems['name'] . " -  Сообщение от Администратора";
                    $zag_adm = "Ваш аккаунт был успешно активирован Администратором";
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
        if (@$productDELETE == "doIT") {// Удаление
            if (CheckedRules($UserStatus["shopusers"], 1) == 1) {
                $sql = "delete from " . $SysValue['base']['table_name27'] . "
where id='$userID'";
                $result = mysql_query($sql) or @die("Невозможно изменить запись");
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



