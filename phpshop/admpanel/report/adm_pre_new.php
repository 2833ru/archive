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
        <title>Создание новой записи</title>
        <META http-equiv=Content-Type content="text/html; charset=windows-1251">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">
        <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
            <tr bgcolor="#ffffff">
                <td style="padding:10">
                    <b><span name=txtLang id=txtLang>Создание Новой Записи</span></b><br>
                    &nbsp;&nbsp;&nbsp;<span name=txtLang id=txtLang>Укажите данные для записи в базу</span>.
                </td>
                <td align="right">
                    <img src="../img/i_balance_med[1].gif" border="0" hspace="10">
                </td>
            </tr>
        </table>
        <form name="product_edit">
            <table cellpadding="5" cellspacing="0" border="0" align="center">
                <tr>
                    <td colspan="2">
                        <FIELDSET id=fldLayout style="height: 8em;">
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>З</u>апрос</span></LEGEND>
                            <div style="padding:10">
                                <input type="text" name="name_new" style="width: 100%;"><br>
                                * <span name=txtLang id=txtLang>Введите поисковые слова через запятую</span> (sony,book). 
                                </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <FIELDSET id=fldLayout style="height: 8em;">
                                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>I</u>D товаров</span></LEGEND>
                                            <div style="padding:10">
                                                <input type="text" name="uid_new" style="width: 100%;"><br>
                                                * <span name=txtLang id=txtLang>Введите идентификаторы (ID) товаров через запятую</span> (100,101). 
                                                <br><br>
                                                <input type="radio" name="enabled_new" value="1" checked><span name=txtLang id=txtLang>Учитывать</span>&nbsp;&nbsp;&nbsp;
                                                <input type="radio" name="enabled_new" value="0"><font color="#FF0000"><span name=txtLang id=txtLang>Заблокировать</span></font>
                                                </td>
                                                </tr>
                                                </table>
                                                <hr>
                                                <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                                                    <tr>
                                                        <td align="right" style="padding:10">
                                                            <input type="submit" name="editID" value="OK" class=but>
                                                            <input type="reset" name="btnLang" value="Сбросить" class=but>
                                                            <input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
                                                        </td>
                                                    </tr>
                                                </table>
                                                </form>
                                                <?
                                                if (isset($editID) and !empty($name_new) and !empty($uid_new)) {// Запись редактирования
                                                    if (CheckedRules($UserStatus["stats1"], 2) == 1) {
                                                        $name = explode(",", $name_new);
                                                        foreach ($name as $v)
                                                            @$string.="i" . $v . "i";
                                                        $sql = "INSERT INTO " . $SysValue['base']['table_name26'] . "
VALUES ('','$string','$uid_new','$enabled_new')";
                                                        $result = mysql_query($sql);
                                                        echo"
	  <script>
DoReloadMainWindow('search_pre');
</script>
	   ";
                                                    }
                                                    else
                                                        $UserChek->BadUserFormaWindow();
                                                }
                                                ?>