<?php
/**
 * Вывод сообщений с администрацией в личном кабинете пользователя
 * @package PHPShopCoreDepricated
 * @param int $UID ИД пользователя
 * @return string
 */
function MessageList ($UID=0) {
    global $SysValue;
    $display=null;

    // Создание запроса к БД
    $sql=Page_messages($UID);

    $result=mysql_query($sql);
    $lvl++;
    while ($row = mysql_fetch_array($result)) {
        $id=$row['ID'];
        $UID=$row['UID'];
        $AID=$row['AID'];
        if ($AID) { //Получаем имя администратора, если сообщение от админа
            $sqlad='select * from '.$SysValue['base']['table_name19'].' WHERE id='.$AID;
            $resultad=mysql_query($sqlad);
            $rowad = mysql_fetch_array($resultad);
            if (strlen($rowad['name'])) {
                $name=$rowad['name'];
            } else {
                $name='Менеджер';
            }
            $color='style="background:#C0D2EC;"';
        } else { //или имя пользователя
            $sqlus='select * from '.$SysValue['base']['table_name27'].' WHERE id='.$UID;
            $resultus=mysql_query($sqlus);
            $rowus = mysql_fetch_array($resultus);
            $name=$rowus['name'];
            $color='';
        }
        $DataTime=$row['DateTime'];
        $Subject=$row['Subject'];
        $Message=$row['Message'];

        if (strlen($Subject)>1) {
            $Subject='<B>'.$Subject.'</B><BR>';
        }

        $display.="<tr >
	<td ".$color." id=allspecwhite>
                $DataTime<BR>
	От: <B>$name</B>
	</td>
	<td ".$color." id=allspecwhite>
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
function Page_messages($UID=0) {
    global $SysValue;

    $p=$SysValue['nav']['id'];
    if(empty($p)) $p=1;
    $num_row=10;
    $num_ot=0;
    $q=0;
    while($q<$p) {
        $sql="select * from ".$SysValue['base']['table_name37']." where (UID=".$UID.") order by DateTime DESC LIMIT $num_ot, $num_row ";
        $q++;
        $num_ot=$num_ot+$num_row;
    }
    return $sql;
}

// Вывод кол-ва
function NumFrom($from_base,$query) {
    global $SysValue;
    $sql="select COUNT('id') as count from ".$SysValue['base'][$from_base]." ".$query;
    @$result=mysql_query(@$sql);
    @$row = mysql_fetch_array(@$result);
    @$num=$row['count'];
    return @$num;
}

/**
 * Навигация по сообщениям в личном кабинете пользователя
 * @package PHPShopCoreDepricated
 * @param int $UID ИД пользователя
 * @return string
 */
function Nav_messages($UID=0) {
    global $SysValue;

    $navigat=null;
    $p=$SysValue['nav']['id'];
    if(empty($p)) $p=1;
    $num_row=10;
    $num_page=NumFrom("table_name37"," where (UID=".$UID.")");
    $i=1;
    $num=$num_page/$num_row;
    while ($i<$num+1) {
        if($i!=$p) {
            if($i==1) {
                $pageOt=$i+$pageDo;
            } else {
                $pageOt=$i+$pageDo-$i;
            }
            $pageDo=$i*$num_row;
            $navigat.="\n<a href=\"./message_".$i.".html\">".$pageOt."-".$pageDo."</a> | ";
        } else {
            if($i==1) {
                $pageOt=$i+@$pageDo;
            } else {
                $pageOt=$i+@$pageDo-$i;
            }
            $pageDo=$i*$num_row;
            $navigat.="\n<b>".$pageOt."-".$pageDo."</b> | ";
        }
        $i++;
    }
    if($num>1) {
        if($p>$num) {
            $p_to=$i-1;
        } else {
            $p_to=$p+1;
        }
        $nava="<table cellpadding=\"0\" cellpadding=\"0\" border=\"0\"><tr ><td class=style5>
	".$SysValue['lang']['page_now'].":
	<a href=\"./message_".($p-1).".html\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
                $navigat&nbsp<a href=\"./message_".$p_to.".html\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
		</td></tr></table>";
    }
    return $nava;
}


/**
 * Отпарвление сообщение менеджеру из личного кабинета пользователя
 * @package PHPShopCoreDepricated
 * @param int $UsersId ИД пользователя
 * @return string
 */
function user_message($obj) {
    global $SysValue,$LoadItems;

    $UsersId=TotalClean($UsersId,1);
    $statusMail=null;
    $sql="select * from ".$SysValue['base']['table_name27']." where id=$obj->UsersId LIMIT 0, 1";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $id=$row['id'];
    $login=$row['login'];
    $password=$row['password'];
    $status=$row['status'];
    $mail=$row['mail'];
    $name=$row['name'];
    $company=$row['company'];
    $inn=$row['inn'];
    $tel=$row['tel'];
    $adres=$row['adres'];

    // mail менеджеру
    if(!empty($_POST['message'])) {
        $codepage  = "windows-1251";
        $header_adm  = "MIME-Version: 1.0\n";
        $header_adm .= "From:   <".$mail.">\n";
        $header_adm .= "Content-Type: text/plain; charset=$codepage\n";
        $header_adm .= "X-Mailer: PHP/";
        $zag_adm=$LoadItems['System']['name']." - Поступило сообщение от пользователя ".$name;
        $content_adm="
Доброго времени!
--------------------------------------------------------

Поступил вопрос с интернет-магазина '".$obj->PHPShopSystem->getName()."'
от пользователя ".$name."

Логин: ".$login."
---------------------------------------------------------

".PHPShopSecurity::TotalClean($_POST['message'],2)."

Дата/время: ".date("d-m-y H:i a")."
IP:".$_SERVER['REMOTE_ADDR'];

        mail($LoadItems['System']['adminmail2'],$zag_adm, $content_adm, $header_adm);

        $sql='select * from '.$SysValue['base']['table_name37'].' where (UID='.$id.') order by DateTime DESC';
        $result=mysql_query($sql);
        $row = mysql_fetch_array($result);

        if ($row['AID']=="0") {
            $DateTime=$row['DateTime'];
            $message=PHPShopSecurity::TotalClean($_POST['message'],2)."<HR>".$row['DateTime'].": ".$row['Message'];
            $sql='UPDATE '.$SysValue['base']['table_name37'].' SET Message="'.$message.'", DateTime="'.date("Y-m-d H:i:s").'" WHERE ID='.$row['ID'];
            $result=mysql_query($sql);
            $p=$SysValue['nav']['id'];
            if(empty($p)) $p=1;
            if ($p>1) {
                $nav='_'.$p;
            } else {
                $nav='';
            }
            header("Location: /users/message$nav.html");
        } else {
            $sql='INSERT INTO '.$SysValue['base']['table_name37'].' VALUES ("",0,'.$id.',\'\',\''.date("Y-m-d H:i:s").'\',\''.TotalClean($_POST['Subject'],2).'\',\''.TotalClean($_POST['message'],2).'\',"0")';
            $result=mysql_query($sql);
            header ("Location: /users/message.html");
        }
        $statusMail='<div id=allspecwhite><img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><font color="#008000"><b>Сообщение менеджеру отправлено</b></font></div>';
    }
    // Вывод списка сообещний
    $display= MessageList($id);

    // Дописать сообщение
    $sql='select * from '.$SysValue['base']['table_name37'].' where (UID='.$id.') order by DateTime DESC';
    $result=mysql_query($sql);
    $i=mysql_num_rows($result);
    $row = mysql_fetch_array($result);

    if (($row['AID']==0) && ($i)) {
        $Subject=$row['Subject'];
        $Subjectreadonly=' readonly disabled';
        $message=$row['Message'];
        $oldmessage='<B>Вы можете дополнить ваше сообщение. Введите дополнительный текст:</B><BR>';
    } else {
        $Subject='';
        $Subjectreadonly='';
        $message='';
        $oldmessage='  <B>Текст сообщения</B>';
    }

    if ($i) {
        $display='<H3>История сообщений</H3>
<table id=allspecwhite cellpadding="1" cellspacing="1" width="100%">
<tr>
	<td width="20%"  id=allspec><span name=txtLang id=txtLang>Дата</span></td>
	<td width="80%"  id=allspec><span name=txtLang id=txtLang>Сообщение</span></td>
</tr>
	'.$display.'</table>'.Nav_messages($id);
    } else {
        $display='';
    }

    $disp='<div id=allspec>
<img src="images/shop/icon_info.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Задать вопрос менеджеру</b>
</div>
<table style="width:100%;">
<tr>
  <td style="width:100%;height:100px;">
  <form method="post" name="forma_message">
  <B>Заголовок сообщения</B><BR>
  <input type="TEXT" style="width:100%;" value="'.$Subject.'" '.$Subjectreadonly.' name="Subject"><BR>
  '.$oldmessage.'
  <textarea style="width:100%;height:100px;" name="message" id="message"></textarea>
  <div>
  <input type="button" value="Задать вопрос менеджеру" onclick="CheckMessage()">
  </div>
  </form>
  </td>
</tr>
</table>
   '.$display.'
  '.$statusMail.'<br><BR><p><br></p>';

    $obj->set('formaTitle',__('Связь с менеджерами'));
    $obj->set('formaContent',$disp);
    $obj->ParseTemplate($obj->getValue('templates.users_page_list'));
}
?>
