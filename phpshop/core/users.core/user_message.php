<?php

/**
 * Вывод сообщений с администрацией в личном кабинете пользователя
 * @package PHPShopCoreDepricated
 * @param int $UID ИД пользователя
 * @return string
 */
function MessageList($UID = 0) {
    global $SysValue,$link_db;
    $display = null;

    // Создание запроса к БД
    $sql = Page_messages($UID);

    $result = mysqli_query($link_db,$sql);
    while ($row = mysqli_fetch_array($result)) {
        $UID = $row['UID'];
        $AID = $row['AID'];
        if ($AID) { //Получаем имя администратора, если сообщение от админа
            $sqlad = 'select * from ' . $SysValue['base']['table_name19'] . ' WHERE id=' . intval($AID);
            $resultad = mysqli_query($link_db,$sqlad);
            $rowad = mysqli_fetch_array($resultad);
            if (strlen($rowad['name'])) {
                $name = $rowad['name'];
            } else {
                $name = 'Менеджер';
            }
            $color = 'style="background:#C0D2EC;"';
        } else { //или имя пользователя
            $sqlus = 'select * from ' . $SysValue['base']['table_name27'] . ' WHERE id=' . intval($UID);
            $resultus = mysqli_query($link_db,$sqlus);
            $rowus = mysqli_fetch_array($resultus);
            $name = $rowus['name'];
            $color = '';
        }
        $DataTime = $row['DateTime'];
        $Subject = PHPShopSecurity::TotalClean($row['Subject']);
        $Message = strip_tags($row['Message'],'<b><hr><br>');

        if (strlen($Subject) > 1) {
            $Subject = '<B>' . $Subject . '</B><BR>';
        }

        $display.="<tr >
	<td " . $color . " id=allspecwhite>
                $DataTime<BR>
	От: <B>$name</B>
	</td>
	<td " . $color . " id=allspecwhite>
                $Subject
                $Message</td></tr>";
    }
    return $display;
}

/**
 * Создание SQL запроса к БД на вывод сообщений пользователя
 * @package PHPShopCoreDepricated
 * @param int $UID ИД пользователя
 * @return string
 */
function Page_messages($UID = 0) {
    global $SysValue;

    $p = $SysValue['nav']['id'];
    if (empty($p))
        $p = 1;
    $num_row = 10;
    $num_ot = 0;
    $q = 0;
    while ($q < $p) {
        $sql = "select * from " . $SysValue['base']['table_name37'] . " where (UID=" . $UID . ") order by DateTime DESC LIMIT $num_ot, $num_row ";
        $q++;
        $num_ot = $num_ot + $num_row;
    }
    return $sql;
}

// Вывод кол-ва
function NumFrom($from_base, $query) {
    global $SysValue,$link_db;
    $sql = "select COUNT('id') as count from " . $SysValue['base'][$from_base] . " " . $query;
    @$result = mysqli_query($link_db,$sql);
    @$row = mysqli_fetch_array(@$result);
    @$num = $row['count'];
    return @$num;
}

/**
 * Навигация по сообщениям в личном кабинете пользователя
 * @package PHPShopCoreDepricated
 * @param int $UID ИД пользователя
 * @return string
 */
function Nav_messages($UID = 0) {
    global $SysValue;

    $navigat = $nava = null;
    $p = $SysValue['nav']['id'];
    if (empty($p))
        $p = 1;
    $num_row = 10;
    $num_page = NumFrom("table_name37", " where (UID=" . intval($UID) . ")");
    $i = 1;
    $num = $num_page / $num_row;
    while ($i < $num + 1) {
        if ($i != $p) {
            if ($i == 1) {
                $pageOt = $i + $pageDo;
            } else {
                $pageOt = $i + $pageDo - $i;
            }
            $pageDo = $i * $num_row;
            $navigat.="\n<a href=\"./message_" . $i . ".html\">" . $pageOt . "-" . $pageDo . "</a> | ";
        } else {
            if ($i == 1) {
                $pageOt = $i + @$pageDo;
            } else {
                $pageOt = $i + @$pageDo - $i;
            }
            $pageDo = $i * $num_row;
            $navigat.="\n<b>" . $pageOt . "-" . $pageDo . "</b> | ";
        }
        $i++;
    }
    if ($num > 1) {
        if ($p > $num) {
            $p_to = $i - 1;
        } else {
            $p_to = $p + 1;
        }
        $nava = "<table cellpadding=\"0\" cellpadding=\"0\" border=\"0\"><tr ><td class=style5>
	" . $SysValue['lang']['page_now'] . ":
	<a href=\"./message_" . ($p - 1) . ".html\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
                $navigat&nbsp<a href=\"./message_" . $p_to . ".html\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
		</td></tr></table>";
    }
    return $nava;
}

/**
 * Отправление сообщение менеджеру из личного кабинета пользователя
 * @package PHPShopCoreDepricated
 * @param int $UsersId ИД пользователя
 * @return string
 */
function user_message($obj) {
    global $SysValue,$link_db;

    $statusMail = null;
    $sql = "select * from " . $SysValue['base']['table_name27'] . " where id=" . intval($obj->UsersId) . " LIMIT 0, 1";
    $result = mysqli_query($link_db,$sql);
    $row = mysqli_fetch_array($result);
    $id = $row['id'];
    $login = $row['login'];
    $mail = $row['mail'];
    $name = $row['name'];

    // mail менеджеру
    if (!empty($_POST['message'])) {
//        $zag_adm = $obj->PHPShopSystem->getName() . " - Поступило сообщение от пользователя " . $name;
        $zag_adm = "Поступило сообщение от пользователя " . $name;
        $content_adm = "
Доброго времени!
--------------------------------------------------------

Поступил вопрос с интернет-магазина '" . $obj->PHPShopSystem->getName() . "'
от пользователя " . $name . "

Логин: " . $login . "
---------------------------------------------------------

" . PHPShopSecurity::TotalClean($_POST['message'], 2) . "

Дата/время: " . date("d-m-y H:i a") . "
IP:" . $_SERVER['REMOTE_ADDR'];

        // Отправка e-mail администратору
        new PHPShopMail($obj->PHPShopSystem->getValue('adminmail2'), $obj->PHPShopSystem->getValue('adminmail2'), $zag_adm, $content_adm, false,false,array('replyto'=>$mail));
        $sql = 'select * from ' . $SysValue['base']['table_name37'] . ' where (UID=' . $id . ') order by DateTime DESC';
        $result = mysqli_query($link_db,$sql);
        $row = mysqli_fetch_array($result);

        if ($row['AID'] == "0") {
            $DateTime = $row['DateTime'];
            $message = PHPShopSecurity::TotalClean($_POST['message'], 2) . "<HR>" . $row['DateTime'] . ": " . $row['Message'];
            $sql = 'UPDATE ' . $SysValue['base']['table_name37'] . ' SET Message="' . $message . '", DateTime="' . date("Y-m-d H:i:s") . '", enabled=\'0\' WHERE ID=' . $row['ID'];
            $result = mysqli_query($link_db,$sql);
            $p = $SysValue['nav']['id'];
            if (empty($p))
                $p = 1;
            if ($p > 1) {
                $nav = '_' . $p;
            } else {
                $nav = '';
            }
            header("Location: ./message$nav.html");
        } else {
            $sql = 'INSERT INTO ' . $SysValue['base']['table_name37'] . ' VALUES ("",0,' . $id . ',\'\',\'' . date("Y-m-d H:i:s") . '\',\'' . PHPShopSecurity::TotalClean($_POST['Subject'], 2) . '\',\'' . PHPShopSecurity::TotalClean($_POST['message'], 2) . '\',"0")';
            $result = mysqli_query($link_db,$sql);
            header("Location: ./message.html");
        }
        $statusMail = '<div id=allspecwhite><img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><font color="#008000"><b>Сообщение менеджеру отправлено</b></font></div>';
    }
    // Вывод списка сообещний
    $display = MessageList($id);

    // Дописать сообщение
    $sql = 'select * from ' . $SysValue['base']['table_name37'] . ' where (UID=' . intval($id) . ') order by DateTime DESC';
    $result = mysqli_query($link_db,$sql);
    $i = mysqli_num_rows($result);
    $row = mysqli_fetch_array($result);

    if (($row['AID'] == 0) && ($i)) {
        $Subject = $row['Subject'];
        $Subjectreadonly = ' readonly disabled';
        $message = $row['Message'];
        $oldmessage = '<B>Вы можете дополнить ваше сообщение. Введите дополнительный текст:</B><BR>';
    } else {
        $Subject = '';
        $Subjectreadonly = '';
        $message = '';
        $oldmessage = '  <B>Текст сообщения</B><br>';
    }

    if ($i) {
        $display = '<H3>История сообщений</H3>
<table id="allspecwhite" cellpadding="1" cellspacing="1" width="100%" class="table table-striped">
<tr>
	<td width="20%"  id=allspec><span name=txtLang id=txtLang>Дата</span></td>
	<td width="80%"  id=allspec><span name=txtLang id=txtLang>Сообщение</span></td>
</tr>
	' . $display . '</table>' . Nav_messages($id);
    } else {
        $display = '';
    }

    $disp = '<div id=allspec>
</div>
<table style="width:80%;">
<tr>
  <td style="width:80%;height:100px;">
  <form method="post" name="forma_message" id="forma_message">
  <B>Заголовок сообщения</B><BR>
  <input type="TEXT" style="width:80%;" value="' . $Subject . '" ' . $Subjectreadonly . ' name="Subject"><BR>
  ' . $oldmessage . '
  <textarea style="width:80%;height:100px;" name="message" id="message"></textarea>
  <div>
  <br>
  <input type="button" value="Задать вопрос менеджеру" id="CheckMessage" onclick="if(typeof checkMessageText == \'function\') checkMessageText();">
  </div>
  </form>
  </td>
</tr>
</table>
   ' . $display . '
  ' . $statusMail . '<br><BR><p><br></p>';

    $obj->set('formaTitle', __('Связь с менеджерами'));
    $obj->set('formaContent', $disp);
    $obj->ParseTemplate($obj->getValue('templates.users_page_list'));
}

?>
