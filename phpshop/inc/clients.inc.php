<?
/**
 * Форматирование даты
 * @package PHPShopCoreDepricated
 * @param int $nowtime дата в Unix формате
 * @return string
 */
function dataV($nowtime) {
    $Months = array("01"=>"января","02"=>"февраля","03"=>"марта",
            "04"=>"апреля","05"=>"мая","06"=>"июня", "07"=>"июля",
            "08"=>"августа","09"=>"сентября",  "10"=>"октября",
            "11"=>"ноября","12"=>"декабря");
    $curDateM = date("m",$nowtime);
    $t=date("d",$nowtime)."-".$curDateM."-".date("y",$nowtime)."";
    return $t;
}


/**
 * Вывод корзины заказа пользователя в лично кабинете
 * @package PHPShopCoreDepricated
 * @param array $CART массив корзины
 * @return string
 */
function ViewCart($CART) {
    global $SysValue;
    $cart=$CART['cart'];
    $kurs=$CART['kurs'];
    $n=1;$num=null;$sum=null;

    if(is_array($cart))
        foreach($cart as $val) {
            $disCart.="
<tr bgcolor=\"ffffff\">
  <td style=\"padding:3\">".$n."</td>
  <td style=\"padding:3\"><a href=\"/shop/UID_".$val['id'].".html\">".$val['name']."</a></td>
  <td style=\"padding:3\">".$val['num']."</td>
  <td style=\"padding:3\">".$val['price']*$kurs."</td>
  <td style=\"padding:3\">".substr($val['price'],0,strlen($val['price'])-2)."</td>
</tr>
";
            $n++;
            $num+=$val['num'];
            $sum+=$val['price'];
        }
    while($n<6) {
        $disCart.="
 <tr bgcolor=\"ffffff\">
  <td style=\"padding:3\" height=\"20\">".$n."</td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
</tr>
 ";
        $n++;
    }
    $ChekDiscount=ChekDiscount($sum*$kurs);
    $disCart.="
<tr bgcolor=\"#C0D2EC\">
  <td style=\"padding:3\" colspan=\"2\" id=pane align=center>Итого с учетом скидки ".$ChekDiscount[0]."%</td>
  <td style=\"padding:3\"><b>".$num."</b> шт.</td>
  <td style=\"padding:3\" colspan=2 align=\"center\"><b>".$ChekDiscount[1]."</b> руб.</td>
</tr>
";

    return $disCart;
}

/**
 * Проверка заказа on-line
 * @package PHPShopCoreDepricated
 * @param string $order номер заказа
 * @param string $mail почта пользователя
 * @return string
 */
function ClientsCheck($order,$mail) {
    global $SysValue,$LoadItems;
    $order=TotalClean($order,5);
    $statusMail=null;

    $sql="select * from ".$SysValue['base']['table_name1']." where uid='$order'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $id=$row['id'];
    $datas=$row['datas'];
    $uid=$row['uid'];
    $order=unserialize($row['orders']);
    $status=unserialize($row['status']);

    if($mail==$order['Person']['mail']) {
        if($status['status']=="Новый заказ") {
            $forma="black";
        }
        elseif($status['status']=="Выполняется") {
            $forma="green";
        }
        elseif($status['status']=="Выполнен") {
            $forma="red";
        }

        // Шлем мыло менеджеру
        if($_POST['message']) {
            $codepage  = "windows-1251";
            $header_adm  = "MIME-Version: 1.0\n";
            $header_adm .= "From:   <".$mail.">\n";
            $header_adm .= "Content-Type: text/plain; charset=$codepage\n";
            $header_adm .= "X-Mailer: PHP/";
            $zag_adm=$LoadItems['System']['name']." - Поступил запрос по заказу  №".$uid."/".$datas;
            $content_adm="
Доброго времени!
--------------------------------------------------------

Поступил вопрос с интернет-магазина '".$LoadItems['System']['name']."'
по заказу №".$uid." от ".dataV($datas)."

Сообщение от ".$order['Person']['name_person']."
---------------------------------------------------------

".TotalClean($_POST['message'],2)."

Дата/время: ".date("d-m-y H:i a")."
IP:".$_SERVER['REMOTE_ADDR']."
---------------------------------------------------------


Powered & Developed by www.PHPShop.ru
".$SysValue['license']['product_name'];
            mail($LoadItems['System']['adminmail2'],$zag_adm, $content_adm, $header_adm);
            $statusMail='
<div id=allspecwhite>
<img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><font color="#008000"><b>Сообщение менеджеру отправлено</b></font></div>
';
        }



        $disp='
<div id=allspec>
<img src="images/shop/date.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Архив заказов</b>
</div>
<p>
'.GetUsersOrdersList($_REQUEST['order'],2).'
</p>
<div id=allspec>
<img src="images/shop/icon_info.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Информация</b>
</div>
<p>
'.GetUsersOrdersInfo($_REQUEST['order'],2).'
</p>
<div id=allspec>
<img src="images/shop/icon_user.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Задать вопрос менеджеру</b>
</div>
<p>

<table width="100%">
<tr>
  <td >
  <form method="post" name="forma_message">
  <textarea style="width:100%;height:100px;" name="message" id="message"></textarea>
  '.@$statusMail.'<br>
  <div>
  <input type="button" value="Задать вопрос менеджеру" onclick="CheckMessage()">
  <input type="hidden" value="'.$_REQUEST['order'].'" name="order">
  <input type="hidden" value="'.$_REQUEST['mail'].'" name="mail">
  </div>
  </form>
  </td>
</tr>
</table>
  <div  id=allspecwhite><img src="images/shop/icon_key.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle">
<a href="/users/register.html" class="b">Зарегистрируйтесь</a> и получите дополнительные возможности и <b>скидки</b> в нашем магазине.
</div>
<p><br></p>
';
        return $disp;
    }
    else return "Заказа с такими данными не обнаружено в базе.<br>
<a href=\"/clients/\" class=\"b\">Попробовать еще раз</a>";
}
?>