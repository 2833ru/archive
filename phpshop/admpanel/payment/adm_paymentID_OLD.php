<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Редактирование Способа Оплаты</title>
        <META http-equiv=Content-Type content="text/html; charset=<?= $SysValue['Lang']['System']['charset'] ?>">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <?
//Check user's Browser
        if (strpos($_SERVER["HTTP_USER_AGENT"], "MSIE"))
            echo "<script language=JavaScript src='../editor3/scripts/editor.js'></script>";
        else
            echo "<script language=JavaScript src='../editor3/scripts/moz/editor.js'></script>";
        ?>
        <SCRIPT language="JavaScript" src="/phpshop/lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript1.2" src="../java/javaMG.js" type="text/javascript"></script>
    </head>
    <body bottommargin="0"  topmargin="0" leftmargin="0" rightmargin="0">
        <?

// Выбор файла
        function GetTipPayment($dir) {

            $path = "../../../payment/";

            if ($dh = opendir($path)) {
                while (($file = readdir($dh)) !== false) {
                    if ($file != "." && $file != "..") {

                        if (is_dir($path . $file)) {
                            if ($dir == $file)
                                $s = "SELECTED";
                            else
                                $s = "";
                            @$dis.="<option value=$file $s>" . TipPayment($file) . "</option>";
                        }
                    }
                }
                closedir($dh);
            }

            $dis = "
<select name=\"path_new\">
$dis
</select>
";
            return $dis;
        }

// Редоктирование записей книги
        $sql = "select * from " . $GLOBALS['SysValue']['base']['table_name48'] . " where id=" . intval($_GET['id']);
        $result = mysql_query($sql);
        @$row = mysql_fetch_array(@$result);
        $id = $row['id'];
        $name = $row['name'];
        $path = $row['path'];
        $num = $row['num'];
        if ($row['enabled'] == 1) {
            $fl = "checked";
        } else {
            $fl2 = "checked";
        }

        if ($row['yur_data_flag'] == 1) {
            $yur_data_flag = "checked";
        } else {
            $yur_data_flag = "";
        }

        $message_header = $row['message_header'];
        $message = $row['message'];
        ?>
        <form name="product_edit"  method=post onsubmit="Save()">
            <table cellpadding="0" cellspacing="0" width="100%" height="50" id="title">
                <tr bgcolor="#ffffff">
                    <td style="padding:10">
                        <b><span name=txtLang id=txtLang>Редактирование Способа Оплаты</span> "<?= $name ?>"</b><br>

                    </td>
                    <td align="right">
                        <img src="../img/i_visa_med[1].gif" border="0" hspace="10">
                    </td>
                </tr>
            </table>
            <table cellpadding="5" cellspacing="0" border="0" align="center" width="100%">
                <tr>
                    <td>
                        <FIELDSET>
                            <LEGEND><span name=txtLang id=txtLang>Наименование</span></LEGEND>
                            <div style="padding:10">
                                <input type="text" name="name_new" value="<?= $name ?>" style="width: 100%; "><br><br>
                                <input type="radio" name="enabled_new" value="1" <?= @$fl ?>><span name=txtLang id=txtLang>Показывать</span>&nbsp;&nbsp;&nbsp;
                                <input type="radio" name="enabled_new" value="0" <?= @$fl2 ?>><span name=txtLang id=txtLang>Скрыть</span>
                            </div>
                        </FIELDSET>
                    </td>
                    <td valign="top">
                        <FIELDSET>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang>Тип подключения</span></LEGEND>
                            <div style="padding:10">
                                <?= GetTipPayment($path) ?><br><br>
                                Сортировка: <input type="text" name="num_new" value="<?= $num ?>" style="width: 30px; ">
                                <input type="checkbox" name="yur_data_flag_new" <?= $yur_data_flag ?> value="1"> Требовать юр. данные
                            </div>
                        </FIELDSET>
                    </td>
                </tr>
                <tr>
                    <td>
                        <FIELDSET>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang>Заголовок сообщения после оплаты</span></LEGEND>
                            <input type="text" name="message_header_new" value="<?= $message_header ?>" style="width:100%"><br>

                        </FIELDSET>
                    </td>
                    <td>
                        <fieldset>
                            <legend>Иконка</legend>

                            <input type="text" value="<?= $row['icon'] ?>" name="icon_new" id="icon_new" style="width:150px;" class="" onclick="" title=""> </div>
                            <button style="width:100px; height:25px; margin-left:5" onclick="ReturnPic('icon_new');
                return false;">
                                <img src="../img/icon-move-banner.gif" width="16" height="16" border="0" align="absmiddle" hspace="3">
                                Выбрать
                            </button>

                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <FIELDSET>
                            <LEGEND id=lgdLayout><span name=txtLang id=txtLang>Сообщения после оплаты</span></LEGEND>

                            <?
                            $GetSystems = GetSystems();
                            $option = unserialize($GetSystems['admoption']);
                            if ($option['editor'] != "none") {
                                $MyStyle = $GLOBALS['SysValue']['dir']['dir'] . chr(47) . "phpshop" . chr(47) . "templates" . chr(47) . $GetSystems['skin'] . chr(47) . $GLOBALS['SysValue']['css']['default'];
                                echo'
<pre id="idTemporary" name="idTemporary" style="display:none">
' . $message . '
</pre>
	<script>
		var oEdit1 = new InnovaEditor("oEdit1");
	oEdit1.cmdAssetManager="modalDialogShow(\'' . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/admpanel/editor3/assetmanager/assetmanager.php\',640,500)";
		oEdit1.width=600;
		oEdit1.height=50;
		oEdit1.btnStyles=true;
	    oEdit1.css="' . $MyStyle . '";
		oEdit1.RENDER(document.getElementById("idTemporary").innerHTML);
	</script>
	<input type="hidden" name="EditorContent" id="EditorContent">
	';
                            } else {
                                echo '
<textarea name="EditorContent" id="EditorContent" style="width:100%;height: 150px">' . $message . '</textarea>
';
                            }
                            ?>

                        </FIELDSET>
                    </td>
                </tr>
            </table>
            <hr>
            <table cellpadding="0" cellspacing="0" width="100%" height="50" >
                <tr>
                    <td align="left" style="padding:10">
                        <BUTTON class="help" onclick="helpWinParent('payment')">Справка</BUTTON></BUTTON>
                    </td>
                    <td align="right" style="padding:10">
                        <input type="hidden" name="id" value="<?= $id ?>" >
                        <input type="submit" name="editID" value="OK" class=but>
                        <input type="button" name="btnLang" class=but value="Удалить" onClick="PromptThis();">
                        <input type="hidden" class=but  name="productDELETE" id="productDELETE">
                        <input type="button" name="btnLang" value="Отмена" onClick="return onCancel();" class=but>
                    </td>
                </tr>
            </table>
        </form>
        <?
        if (isset($editID) and @$name_new != "") {// Запись редактирования
            if (CheckedRules($UserStatus["visitor"], 1) == 1) {
                $sql = "UPDATE " . $GLOBALS['SysValue']['base']['table_name48'] . "
SET
name='$name_new',
path='$path_new',
num='$num_new',
enabled='$enabled_new',
icon='$icon_new',
message='" . addslashes($EditorContent) . "',
yur_data_flag='$yur_data_flag_new',
message_header='$message_header_new'
where id='$id'";
                $result = mysql_query($sql) or @die("Невозможно изменить запись");
                echo"
	  <script>
DoReloadMainWindow('payment');
</script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        if (@$productDELETE == "doIT") {// Удаление записи
            if (CheckedRules($UserStatus["visitor"], 2) == 1) {
                $sql = "delete from " . $GLOBALS['SysValue']['base']['table_name48'] . "
where id='$id'";
                $result = mysql_query($sql) or @die("Невозможно изменить запись");
                echo"
	  <script>
DoReloadMainWindow('payment');
</script>
	   ";
            }
            else
                $UserChek->BadUserFormaWindow();
        }
        ?>
