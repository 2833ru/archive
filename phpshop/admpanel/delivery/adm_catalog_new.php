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
        <title>Создание Доставки</title>
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
                        <b><span name=txtLang id=txtLang>Создание Нового Каталога Доставки</span></b><br>

                    </td>
                    <td align="right">
                        <img src="../img/i_mail_forward_med[1].gif" border="0" hspace="10">
                    </td>
                </tr>
            </table>
            <table class=mainpage4 cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
                <tr>
                    <td>

                        <FIELDSET>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang><u>К</u>аталог</span>:</LEGEND>
                            <div style="padding:10">
                                <?

                                function Disp_cat_pod($category) {// вывод каталогов в выборе подкаталогов
                                    global $SysValue;
                                    $sql = "select city from " . $SysValue['base']['table_name30'] . " where id='$category'";
                                    $result = mysql_query($sql);
                                    $row = mysql_fetch_array($result);
                                    @$name = $row['city'];
                                    return @$name . " -> ";
                                }

                                function Disp_cat($category) {// вывод каталогов в выборе
                                    global $SysValue;
                                    $sql = "select city,PID from " . $SysValue['base']['table_name30'] . " where id=$category";
                                    $result = mysql_query($sql);
                                    @$row = mysql_fetch_array(@$result);
                                    @$num = mysql_num_rows(@$result);
                                    if ($num > 0) {
                                        $name = $row['city'];
                                        $parent_to = $row['PID'];
                                        $dis = Disp_cat_pod($parent_to) . $name;
                                    }
                                    return @$dis;
                                }

                                echo '
<input type=text id="myName"  style="width: 170" value="' . Disp_cat($_GET['categoryID']) . '">
<input type="hidden" value="' . $_GET['categoryID'] . '" name="NPID" id="myCat">
<BUTTON style="width: 3em; height: 2.2em; margin-left:5"  onclick="miniWinFull(\'adm_cat.php?category=' . $_GET['categoryID'] . '\',300,400,300,200)"><img src="../img/icon-move-banner.gif"  width="16" height="16" border="0"></BUTTON>';
                                ?>
                        </FIELDSET>
                    </TD>
                    <td colspan=2>
                        <fieldset style="float:none;padding:5px;margin-left:0px;padding-top:0px">
                            <legend>Иконка</legend>

                            <div style="float:left;padding:14px;">
                                <input type="text" value="<?= $row['icon'] ?>" name="icon_new" id="icon_new" style="width:100px;" class="" onclick="" title=""> </div>
                            <div style="float:right;padding:5px;">
                                <button style="width:100px; height:25px; margin-left:5" onclick="ReturnPic('icon_new');
                                        return false;">
                                    <img src="../img/icon-move-banner.gif" width="16" height="16" border="0" align="absmiddle" hspace="3">
                                    Выбрать
                                </button></div>

                        </fieldset>
                    </td>
                </TR><TR>     <TD>

                        <FIELDSET style="height:80px">
                            <LEGEND><span name=txtLang id=txtLang><u>Н</u>азвание</span></LEGEND>
                            <div style="padding:10">
                                <input name="city_new" style="width:100%"><br>
                                <input type="checkbox" name="flag_new" value="1"><span name=txtLang id=txtLang>Доставка по умолчанию</span>
                            </div>
                        </FIELDSET>
                    </td>
                    <td style="vertical-align:top;">
                        <FIELDSET style="height:60px">
                            <LEGEND><span name=txtLang id=txtLang>Номер по порядку</span></LEGEND>
                            <div style="padding:10">
                                <input type="text" value="0" name="num_new" id="num_new" style="width:50px;" class="" onclick="" title="">
                            </div>
                        </FIELDSET>
                    </td>
                    <td>
                        <FIELDSET style="height:80px">
                            <LEGEND><span name=txtLang id=txtLang><u>У</u>читывать</span></LEGEND>
                            <div style="padding:10">
                                <input type="radio" name="enabled_new" value="1" checked><span name=txtLang id=txtLang>Да</span><br>
                                <input type="radio" name="enabled_new" value="0"><font color="#FF0000"><span name=txtLang id=txtLang>Нет</span></font>
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">

                    </td>
                </tr>

                <tr>
                    <td colspan="3">

                    </td>
                </tr>


            </table>
            <hr>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                <tr>
                    <td align="left" style="padding:10">
                        <BUTTON class="help" onclick="helpWinParent('delivery')">Справка</BUTTON></BUTTON>
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
        if (isset($editID) and !empty($city_new)) {// Запись редактирования
            if (CheckedRules($UserStatus["delivery"], 2) == 1) {


                // обнуляем статус "по умолчанию" для всех, чтобы назначить для текущего, если выбрали
                if ($flag_new)
                    mysql_query("UPDATE " . $SysValue['base']['table_name30'] . " SET flag='0' WHERE is_folder='1'") or die(mysql_error());

                $sql = "INSERT INTO " . $SysValue['base']['table_name30'] . "
VALUES ('','$city_new','$price_new','$enabled_new','$flag_new','$price_null_new','$price_null_enabled_new','$NPID','$taxa_new','1','','','$num_new','$icon_new')";
                $result = mysql_query($sql) or @die("" . mysql_error() . "");
                echo'
	  <script>
CLREL("left");
</script>
	   ';
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        ?>



