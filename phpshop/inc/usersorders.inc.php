<?php
/**
 * Вывод заказов в личном кабинете пользователя
 * @package PHPShopCoreDepricated
 * @param string $n ИД заказа
 * @param string $orderDate дата заказа
 * @return string
 */
function GetOrderDocsList($n,$orderDate) {
    global $SysValue,$LoadItems;

    $n=TotalClean($n,5);
    $sql="select * from ".$SysValue['base']['table_name9']." where uid=$n";
    $result=mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    @$num=mysql_num_rows(@$result);

    if(!empty($num)) {
        $id=$row['id'];
        $datas=$row['datas'];
        $datas_f=$row['datas_f'];

        if($LoadItems['System']['1c_load_accounts'] == 1)
            $dis="<tr>
  <td id=allspec><strong>Документооборот</strong></td>
  <td id=allspec>
  <strong>Дата</strong>
  </td>
   <td id=allspec colspan=2>
  <strong>Загрузка</strong>
  </td>
</td>
</tr>
<tr>
  <td id=allspecwhite>
  <a class=b href=\"../files/docsSave.php?orderId=$n&list=accounts&datas=$orderDate\" title=\"Загрузить счет на оплату\" target=\"_blank\">Счет на оплату</a>
  </td>
  <td id=allspecwhite>
                    ".PHPShopDate::dataV($datas)."
  </td>
   <td id=allspecwhite colspan=2 align=center>
  <a class=b href=\"../files/docsSave.php?orderId=$n&list=accounts&tip=html&datas=$orderDate\" target=\"_blank\" title=\"Формат Web\">HTM</a>&nbsp;&nbsp;&nbsp;
<a class=b href=\"../files/docsSave.php?orderId=$n&list=accounts&tip=doc&datas=$orderDate\" target=\"_blank\" title=\"Формат Word\">DOC</a>&nbsp;&nbsp;&nbsp;
<a class=b href=\"../files/docsSave.php?orderId=$n&list=accounts&tip=xls&datas=$orderDate\" target=\"_blank\" title=\"Формат Excel\">XLS</a>
  </td>
</td>
</tr>";

        // Счет-фактура
        if($datas_f>0 and $LoadItems['System']['1c_load_invoice'] == 1)
            $dis.="
<tr>
  <td id=allspecwhite>
  <a class=b href=\"../files/docsSave.php?orderId=$n&list=invoice&datas=$orderDate\" title=\"Загрузить счет-фактуру\" target=\"_blank\">Счет-фактура</a>
  </td>
  <td id=allspecwhite>
                    ".PHPShopDate::dataV($datas)."
  </td>
  <td id=allspecwhite colspan=2 align=center>
  <a class=b href=\"../files/docsSave.php?orderId=$n&list=invoice&tip=html&datas=$orderDate\" target=\"_blank\" title=\"Формат Web\">HTM</a>&nbsp;&nbsp;&nbsp;
<a class=b href=\"../files/docsSave.php?orderId=$n&list=invoice&tip=doc&datas=$orderDate\" target=\"_blank\" title=\"Формат Word\">DOC</a>&nbsp;&nbsp;&nbsp;
<a class=b href=\"../files/docsSave.php?orderId=$n&list=invoice&tip=xls&datas=$orderDate\" target=\"_blank\" title=\"Формат Excel\">XLS</a>
  </td>
</tr>";
        return $dis;
    }
}

/**
 * Создание массива статусов заказа
 * @package PHPShopDepricated
 * @return string 
 */
function GetOrderStatusArray() {
    global $SysValue;
    
    $sql="select * from ".$SysValue['base']['table_name32'];
    $result=mysql_query($sql);
    while(@$row = mysql_fetch_array(@$result)) {
        $array=array(
                "id"=>$row['id'],
                "name"=>$row['name'],
                "color"=>$row['color'],
                "sklad"=>$row['sklad_action']
        );
        $Status[$row['id']]=$array;
    }
    return $Status;
}


/**
 * Вывод заказов в личном кабиете пользователя
 * @package PHPShopCoreDepricated
 * @param int $n номер заказа
 * @param int $tip параметр вывода
 * @return string 
 */
function GetUsersOrdersList($n,$tip) {
    global $SysValue;
    
    $n=TotalClean($n,5);
    $dis=null;
    if($tip==2) $sql="select * from ".$SysValue['base']['table_name1']." where uid='".htmlspecialchars($n)."'";
    if($tip==1) $sql="select * from ".$SysValue['base']['table_name1']." where user='$n' order by datas desc";
    
    $result=mysql_query($sql) or die("Техническая поддержка: <A href=\"mailto:support@phpshop.ru\">support@phpshop.ru</A>");
    while(@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $datas=$row['datas'];
        $uid=$row['uid'];
        $order=unserialize($row['orders']);
        $status=unserialize($row['status']);
        $statusi=$row['statusi'];
        
        if($tip==1) $link="?orderId=$uid#PphpshopOrder";
        else $link="/users/register.html";

        $GetOrderStatusArray=GetOrderStatusArray();
        if($statusi==0) {
            $bg="C0D2EC";
            $status_name="Новый заказ";
        }
        else {
            $bg=$GetOrderStatusArray[$statusi]['color'];
            $status_name=$GetOrderStatusArray[$statusi]['name'];
        }

        $docsLink2='<a href="javascript:void(0)"  onclick="miniWinFull(\'phpshop/forms/2/forma.html?orderId='.$id.'&tip='.$tip.'&datas='.$datas.'\',650,550);" ><img src="images/shop/interface_dialog.gif" alt="Квитанция Сбербанка" width="16" height="16" border="0" hspace="1" align="absmiddle"></a>';
        $docsLink1='<a href="javascript:void(0)"  onclick="miniWinFull(\'phpshop/forms/1/forma.html?orderId='.$id.'&tip='.$tip.'&datas='.$datas.'\',650,550);" ><img src="images/shop/interface_browser.gif" alt="Счет в банк" width="16" height="16" border="0" hspace="1" align="absmiddle"></a>';
        if($order['Person']['order_metod']==3)  $docsLink="";

        // Сумма заказа
        $SummaOrder=ReturnSummaNal($order['Cart']['sum'],$order['Person']['discount']);

        $dis.='<tr>
	<td id=allspecwhite>
	<a href="'.$link.'" class="b" title="Информация о заказе № '.$uid.'"><img src="images/shop/icon-setup.gif" alt="" width="16" height="16" border="0" align="absmiddle" hspace="5">'.$uid.'</a>
	</td>
	<td id=allspecwhite>
	'.PHPShopDate::dataV($datas).'
	</td>
	<td id=allspecwhite>
	'.(0+$order['Cart']['num']).'
	</td>
	<td id=allspecwhite>
	'.$order['Person']['discount'].'
	</td>
	<td id=allspecwhite>
	'.($SummaOrder+GetDeliveryPrice($order['Person']['dostavka_metod'],$SummaOrder,$order['Cart']['weight'])).' '.GetValutaOrder().'
	</td>
	<td  bgcolor="'.$bg.'">
	'.$status_name.'
	</td>';
    }
    $dis='
<table id=allspecwhite cellpadding=3 width="95%">
<tr>
	<td id=allspec>
	<b>№ Заказа</b>
	</td>
	<td id=allspec>
	<b>Дата</b>
	</td>
	<td id=allspec>
	<b>Кол-во</b>
	</td>
	<td id=allspec>
	<b>Скидка %</b>
	</td>
	<td id=allspec>
	<b>Сумма</b>
	</td>
	<td id=allspec>
	<b>Статус</b>
	</td>
</tr>'.$dis.'</table>';
    
    return $dis;
}

/**
 * Шифрование ссылки на файлы
 * @package PHPShopDepricated
 * @param string $files имя файла
 * @return string 
 */
function CloseUrlFile($files) {
    global $SysValue;
    
    $str=array(
            "files"=>$files,
            "time"=>(time("U")+($SysValue['my']['digital_time']*86400))
    );
    $str=serialize($str);
    $code=base64_encode($str);
    $code2=str_replace($SysValue['my']['digital_pass1'],"!",$code);
    $code2=str_replace($SysValue['my']['digital_pass2'],"$",$code2);
    
    return $code2;
}

/**
 * Проверка электронного платежа
 * @package PHPShopDepricated
 * @param int $uid номер заказа
 * @return int 
 */
function CheckPayment($uid) {
    global $SysValue;

    $sql="select * from ".$SysValue['base']['table_name33']." where uid='$uid'";
    @$result=mysql_query($sql);
    $num = mysql_numrows(@$result);
    
    return $num;
}

/**
 * Вывод файлов к товару в личном кабинете пользователя
 * @package PHPShopDepricated
 * @param int $n ИД товара
 * @param int $num
 * @param bool $sklad наличие на складе
 * @param int $uid номер заказа
 * @return string
 */
function CheckProductFiles($n,$num,$sklad,$uid) {
    global $SysValue,$LoadItems;

    $n=TotalClean($n,5);
    $file_list=null;
    $sql="select files from ".$SysValue['base']['table_name2']." where id=$n limit 1";
    $result=mysql_query($sql);
    $row = mysql_fetch_array(@$result);
    $files=unserialize($row['files']);
    $admoption=unserialize($LoadItems['System']['admoption']);

    // Если заказ оплачен электронно
    $CheckPayment=CheckPayment($uid);
    if(is_array($files) and ($sklad==1 or $CheckPayment>0) and $admoption['digital_product_enabled'] == 1) {

        foreach($files as $f) {
            $_Name=pathinfo($f);
            $F=CloseUrlFile($f);

            // Размер
            @$fstat = @stat($DOCUMENT_ROOT.$f);

            $file_list.="<tr>
	<td id=allspecwhite><a href=\"../files/filesSave.php?F=$F\" class=b title=\"".$SysValue['lang']['load']." ".$_Name['basename']."\"><img src=\"images/shop/action_save.gif\"  width=16 height=16 border=0 align=\"absmiddle\" hspace=5>".$_Name['basename']."</a></td>
	<td id=allspecwhite>".round($fstat['size']/1000)." Кб</td></tr>";
        }
        $file_list="<table>$file_list</table>";
    }

    return $file_list;
}

/**
 * Вывод информации по заказу в личном кабинете пользователя
 * @package PHPShopDepricated
 * @param string $n номер закащза
 * @param int $tip параметр вывода платежных документов
 * @return string 
 */
function GetUsersOrdersInfo($n,$tip=1) {
    global $SysValue,$LoadItems;
    
    $n=TotalClean($n,5);
    $disCart=null;
    $num=null;
    $sum=null;
    
    $sql="select * from ".$SysValue['base']['table_name1']." where uid='$n'";
    $result=mysql_query($sql);
    $num=mysql_num_rows($result);
    $row = mysql_fetch_array(@$result);
    $id=$row['id'];
    $datas=$row['datas'];
    $uid=$row['uid'];
    $order=unserialize($row['orders']);
    $status=unserialize($row['status']);
    $statusi=$row['statusi'];
    
    // Массив статусов заказа
    $GetOrderStatusArray=GetOrderStatusArray();
    
    if($statusi==0) {
        $bg="C0D2EC";
        $status_name="Новый заказ";
    }
    else {
        $bg=$GetOrderStatusArray[$statusi]['color'];
        $status_name=$GetOrderStatusArray[$statusi]['name'];
        $status_id=$GetOrderStatusArray[$statusi]['id'];
    }

    $cart=$order['Cart']['cart'];
    
    // Вывод корзины заказа
    if(sizeof($cart)!=0)
        foreach($cart as $val) {
            $disCart.="<tr>
  <td id=allspecwhite><a href=\"/shop/UID_".$val['id'].".html\" target=\"_blank\" class=b title=\"".$val['name']."\"><img src=\"images/shop/icon-setup.gif\"  width=16 height=16 border=0 align=\"absmiddle\" hspace=5>".$val['name']."</a>
".CheckProductFiles($val['id'],0,$GetOrderStatusArray[$statusi]['sklad'],$uid)."
</td>
  <td id=allspecwhite>".$val['num']."</td>
  <td id=allspecwhite>".ReturnSummaNal($val['price']*$val['num'],$order['Person']['discount'])." ".GetValutaOrder()."</td></tr>";
            $num+=$val['num'];
            $sum+=$val['price']*$val['num'];
        }

    // Доставка
    $GetDeliveryPrice=GetDeliveryPrice($order['Person']['dostavka_metod'],$sum,$order['Cart']['weight']);

    // Итоговая сумма
    $TotalSumOrder = ReturnSummaNal($sum,$order['Person']['discount'])+$GetDeliveryPrice;
    
    $disCart.="<tr>
  <td id=allspecwhite>Доставка -  ".GetDeliveryBase($order['Person']['dostavka_metod'],"city")."</td>
  <td id=allspecwhite>1</td>
  <td id=allspecwhite>".$GetDeliveryPrice." ".GetValutaOrder()."</td>
</tr>
<tr>
  <td id=allspecwhite>Итого с учетом скидки <b>".$order['Person']['discount']."</b>%</td>
  <td id=allspecwhite><b>".$num."</b> шт.</td>
  <td colspan=\"2\" id=allspecwhite><b>".$TotalSumOrder."</b> ".GetValutaOrder()."</td>
</tr>
<tr>
  <td id=allspecwhite><img src=\"images/shop/icon_clock.gif\" alt=\"Время изменения статуса заказа\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=5>Время обработки заказа: ".$status['time']."</td>
  <td colspan=\"3\" bgcolor=".$bg.">".$status_name."</td>
</tr>
<tr>
  <td id=allspec><strong>Способ оплаты</strong></td>
  <td colspan=\"3\" id=allspecwhite>";

    // Ссылка на тип оплаты
    $GetPathOrdermetod = GetPathOrdermetod($order['Person']['order_metod']);

    // Ссылки на оплаты и документы
    switch($GetPathOrdermetod['path']) {
        
        case("message"):
            $disCart.="<strong>".$GetPathOrdermetod['name']."</strong>";
            break;
        
        case("bank"):
            if($LoadItems['System']['1c_load_accounts']!=1) {
                $disCart.="<a href=\"phpshop/forms/1/forma.html?orderId=$id&tip=$tip&datas=$datas\" class=b title=\"".$GetPathOrdermetod['name']."\"  target=\"_blank\"><img src=\"images/shop/interface_browser.gif\" alt=\"".$GetPathOrdermetod['name']."\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" vspace=3  hspace=5>".$GetPathOrdermetod['name']."</a>";
            }else {
                $disCart.="<strong>".$GetPathOrdermetod['name']."</strong>. Ожидайте счета, после проведения документа он автоматически появится в данном разделе личного кабинета.";
            }
            break;

        case("sberbank"):
            $disCart.="
	<a href=\"phpshop/forms/2/forma.html?orderId=$id&tip=$tip&datas=$datas\"  class=b  title=\"".$GetPathOrdermetod['name']."\" target=\"_blank\" ><img src=\"images/shop/interface_dialog.gif\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\" hspace=5 alt=\"".$GetPathOrdermetod['name']."\">".$GetPathOrdermetod['name']."</a>";
            break;

        case("webmoney"):
            
            // регистрационная информация
            $LMI_PAYEE_PURSE = $SysValue['webmoney']['LMI_PAYEE_PURSE'];    //кошелек
            $wmid = $SysValue['webmoney']['wmid'];    //аттестат

            //параметры магазина
            $mrh_ouid = explode("-", $uid);
            $inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

            //описание покупки
            $inv_desc  = "Оплата заказа $inv_id";
            $out_summ  = $TotalSumOrder*$SysValue['webmoney']['kurs']; //сумма покупки

            if($status_id == 101)
                $disCart.="<strong>".$GetPathOrdermetod['name']."</strong>";
            else
                $disCart.="
	 <form id=pay name=paywebmoney method=\"POST\" action=\"https://merchant.webmoney.ru/lmi/payment.asp\" name=\"paywebmoney\">
    <input type=hidden name=LMI_PAYMENT_AMOUNT value=\"$out_summ\">
	<input type=hidden name=LMI_PAYMENT_DESC value=\"$inv_desc\">
	<input type=hidden name=LMI_PAYMENT_NO value=\"$inv_id\">
	<input type=hidden name=LMI_PAYEE_PURSE value=\"$LMI_PAYEE_PURSE\">
	<input type=hidden name=LMI_SIM_MODE value=\"0\">
	<a href=\"javascript:void(0)\" class=b title=\"Оплатить ".$GetPathOrdermetod['name']."\" onclick=\"javascript:paywebmoney.submit();\" ><img src=\"images/shop/coins.gif\" alt=\"Оплатить ".$GetPathOrdermetod['name']."\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\"  hspace=5>".$GetPathOrdermetod['name']."</a></form>";
            break;

        case("robox"):
            
            // регистрационная информация
            $mrh_login = $SysValue['roboxchange']['mrh_login'];    //логин
            $mrh_pass1 = $SysValue['roboxchange']['mrh_pass1'];    // пароль1

            // параметры магазина
            $mrh_ouid = explode("-", $uid);
            $inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

            // описание покупки
            $inv_desc  = "PHPShopPaymentService";
            $out_summ  = $TotalSumOrder*$SysValue['roboxchange']['mrh_kurs']; //сумма покупки
            $shp_item = 2;                //тип товара

            // формирование подписи
            $crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1:shp_item=$shp_item");

            if($status_id == 101)
                $disCart.="<strong>".$GetPathOrdermetod['name']."</strong>";
            else
                $disCart.="<form action='https://www.roboxchange.com/ssl/calc.asp' method=POST name=\"payrobots\">
      <input type=hidden name=mrh value=$mrh_login>
      <input type=hidden name=out_summ value=$out_summ>
      <input type=hidden name=inv_id value=$inv_id>
      <input type=hidden name=inv_desc value=$inv_desc>
	<a href=\"javascript:void(0)\" class=b title=\"Оплатить ".$GetPathOrdermetod['name']."\" onclick=\"javascript:payrobots.submit();\" ><img src=\"images/shop/coins.gif\" alt=\"Оплатить ".$GetPathOrdermetod['name']."\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\"  hspace=5>".$GetPathOrdermetod['name']."</a></form>";
            break;

        case("payonlinesystem"):

            // регистрационная информация
            $PrivateSecurityKey=$SysValue['payonlinesystem']['PrivateSecurityKey'];
            $MerchantId=$SysValue['payonlinesystem']['MerchantId'];
            $mrh_ouid = explode("-", $uid);
            $inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета
            $OrderId=$inv_id;
            $Amount=number_format($TotalSumOrder,2,".","");
            $Currency="RUB";
            $SecurityKey=md5("MerchantId=$MerchantId&OrderId=$OrderId&Amount=$Amount&Currency=$Currency&PrivateSecurityKey=$PrivateSecurityKey");

            if($status_id == 101)
                $disCart.="<strong>".$GetPathOrdermetod['name']."</strong>";
            else
                $disCart.="<form name=\"PaymentForm\" action=\"https://secure.payonlinesystem.com/ru/payment/\" method=\"get\" target=\"_top\" >
<input type=\"hidden\" name=\"OrderId\" id=\"OrderId\" value=\"$OrderId\">
<input type=\"hidden\" name=\"Amount\" id=\"Amount\" value=\"$Amount\">
<input type=\"hidden\" name=\"MerchantId\" value=\"$MerchantId\">
<input type=\"hidden\" name=\"Currency\" value=\"$Currency\">
<input type=\"hidden\" name=\"SecurityKey\" value=\"$SecurityKey\">
<a href=\"javascript:void(0)\" class=b title=\"Оплатить ".$GetPathOrdermetod['name']."\" onclick=\"javascript:PaymentForm.submit();\" ><img src=\"images/shop/coins.gif\" alt=\"Оплатить ".$GetPathOrdermetod['name']."\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\"  hspace=5>".$GetPathOrdermetod['name']."</a></form>";
            break;

        default:
            $disCart.="<strong>".$GetPathOrdermetod['name']."</strong>";
            break;
    }
    $disCart.="  </td></tr>";

    // Просмотр документов
    $disCart.=GetOrderDocsList($id,$datas);
    $disCart='<table  id=allspecwhite cellpadding=3 width="95%"><tr>
  <td id=allspec><b>Наименование</b></td>
  <td id=allspec><b>Кол-во</b></td>
  <td id=allspec><b>Сумма</b></td></tr>'.$disCart.'</table>';

    if(!empty($status['maneger']))
        $disCart.='<div id=allspec>
<img src="images/shop/comment.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Дополнительная информация</b>
</div><p><img src="images/shop/icon_user.gif" alt="Менеджер" width="16" height="16" border="0" hspace="5" align="absmiddle"><a href="/users/message.html" title="Связь с менеджером" class="b">Менеджер</a>: '.$status['maneger'].'</p>';

    if($num>0) return $disCart;
    else return "<font color=#FF0000>Некорректный номер заказа</font>";
}

/**
 * Вывод заказов пользователей
 * @package PHPShopCoreDepricated
 * @param int $UsersId ИД пользователя
 * @return string
 */
function UsersOrders($UsersId) {
    global $SysValue;
    
    $UsersId=TotalClean($UsersId,5);
    $dis='<div id=allspec><img src="images/shop/date.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Архив заказов</b></div>
<p>'.GetUsersOrdersList($UsersId,1).'</p>';

    if(!empty($_GET['orderId']))
        $dis.='<div id=allspec>
<A id="PphpshopOrder"></A>
<img src="images/shop/icon_info.gif" alt="" width="16" height="16" border="0" hspace="5" align="absmiddle"><b>Информация по заказу № '.TotalClean($_GET['orderId'],5).'</b>
</div><p>'.GetUsersOrdersInfo($_GET['orderId']).'</p>';

    return $dis;
}
?>