<?
require("../connect.php");
@mysql_connect("$host", "$user_db", "$pass_db") or @die("Невозможно подсоединиться к базе");
mysql_select_db("$dbase") or @die("Невозможно подсоединиться к базе");
require("../enter_to_admin.php");

if (empty($_GET['id']))
    $_GET['id'] = $_GET['n'];

$sql = "select * from " . $SysValue['base']['table_name35'] . " where id=" . intval($_GET['id']);
$result = mysql_query($sql);
$row = mysql_fetch_array($result);
$id = $row['id'];
$name = $row['name'];
$s_name = str_replace(".", "s.", $name);
$num = $row['num'];

${"s_" . $num . ""} = "selected";
$info = $row['info'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
    <head>
        <title>Изображение: <?= $name ?>
        </title>
        <META http-equiv=Content-Type content="text/html; charset=windows-1251">
        <LINK href="../skins/<?= $_SESSION['theme'] ?>/texts.css" type=text/css rel=stylesheet>
        <SCRIPT language="JavaScript" src="../../lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>
        <script language="JavaScript" src="../java/javaMG.js" type="text/javascript"></script>
        <script>

            // Стандартную форму обновляем
            function UpdateMainForma(img, img_s) {
                window.opener.document.getElementById('pic_small_new').value = img_s;
                window.opener.document.getElementById('pic_big_new').value = img;
//                self.close();
            }


            // Удаление из галереи
            function DoUpdateNumFotoList(xid, num, info, main) {
                var uid = window.opener.document.getElementById('productID').value;
                var req = new Subsys_JsHttpRequest_Js();
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.responseJS) {
                            window.opener.document.getElementById('fotolist').innerHTML = req.responseJS.interfaces;
                            setTimeout("self.close()", 500);
                        }
                    }
                }
                req.caching = false;
                // Подготваливаем объект.
                req.open('POST', 'action.php?do=num', true);
                req.send({xid: xid, uid: uid, num: num, info: info, main: main});
            }

            // Иконка загрузки
            function setPreloadAnimation() {
                window.opener.document.getElementById('fotolist').innerHTML = "<b>Обновление...</b>"
            }

            // Удаление из галереи
            function DoDeleteFotoList(xid, img) {
                var uid = window.opener.document.getElementById('productID').value;
                var req = new Subsys_JsHttpRequest_Js();
                req.onreadystatechange = function() {
                    if (req.readyState == 4) {
                        if (req.responseJS) {
                            window.opener.document.getElementById('fotolist').innerHTML = req.responseJS.interfaces;
                            setTimeout("self.close()", 500);
                            window.opener.document.getElementById('pic_small_new').value = req.responseJS.openerWindowInsImg.pic_small;
                            window.opener.document.getElementById('pic_big_new').value = req.responseJS.openerWindowInsImg.pic_big;
                        }
                    }
                }
                req.caching = false;
                // Подготваливаем объект.
                req.open('POST', 'action.php?do=del', true);
                req.send({xid: xid, uid: uid, img: img});
            }


            function UpdateNum(xid, main) {
                var nums = document.getElementById('nums').value;
                var info = document.getElementById('info').value;
                DoUpdateNumFotoList(xid, nums, info, main);
            }

            function PromptThisDelete(xid, img) {
                if (confirm("Вы действительно хотите удалить изображение?")) {
                    setPreloadAnimation();
                    DoDeleteFotoList(xid, img);
                }
            }


        </script>
    </head>
    <body style="overflow:hidden;margin:0;">
        <?
        echo '
<table width="100%" >
<tr>
	<td valign="top" width="65%">
        <FIELDSET><legend>Изображение для подробного описания</legend>
        <iframe src="' . $name . '" height="350" width="100%" name="frame1" frameborder="0"></iframe>
        </FIELDSET>   
            </td>
	<td valign="top" width="35%">
	<div align="center">
        <FIELDSET><legend>Превью</legend>
	<iframe src="' . $s_name . '" height="240" width="100%" name="frame2" frameborder="0"></iframe>
        </FIELDSET>  
	</div>
        <FIELDSET><legend>Приоритет вывода</legend>
	<select id="nums" size="1">        
	<option value="0" >0</option>
											<option value="1" ' . $s_1 . '>1</option>
											<option value="2" ' . $s_2 . '>2</option>
											<option value="3" ' . $s_3 . '>3</option>
											<option value="4" ' . $s_4 . '>4</option>
											<option value="5" ' . $s_5 . '>5</option>
											<option value="6" ' . $s_6 . '>6</option>
											<option value="7" ' . $s_7 . '>7</option>
											<option value="8" ' . $s_8 . '>8</option>
											<option value="9" ' . $s_9 . '>9</option>
											<option value="10" ' . $s_10 . '>10</option>
</select>
<br> 
	</div>
	<div>
	Комментарий:<br>
	<textarea style="width: 200px" id="info">' . $info . '</textarea>
	</div>
	</td>
</tr>
</table>
<hr>
<table cellpadding="0" cellspacing="0" width="100%">
<tr>
    <td align="left" style="padding-left: 10px">
	<input type=button class=but value="Назначить главным" style="width: 130px"  onClick="setPreloadAnimation(); UpdateMainForma(\'' . $name . '\',\'' . $s_name . '\'); UpdateNum(' . $id . ', 1);">
	</td>
	<td align="right" style="padding:10">
<input type=button value="ОК" class=but onClick="setPreloadAnimation();UpdateNum(' . $id . ', 0);">
<input type="button" class=but value="Удалить" onClick="PromptThisDelete(' . $id . ',\'' . $name . '\');return false;">
<input type="button" value="Отмена" onClick="return onCancel();" class=but>
	</td>
</tr>
</table>
';
        ?>



