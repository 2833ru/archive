<?php
/**
 * Обработчик оплаты заказа через Robox
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

// регистрационная информация
$mrh_login = $SysValue['roboxchange']['mrh_login'];    //логин
$mrh_pass1 = $SysValue['roboxchange']['mrh_pass1'];    // пароль1

//параметры магазина
$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

//описание покупки
$inv_desc  = "PHPShopPaymentService";
$out_summ  = $GLOBALS['SysValue']['other']['total']*$SysValue['roboxchange']['mrh_kurs']; //сумма покупки

// формирование подписи
$crc  = md5("$mrh_login:$out_summ:$inv_id:$mrh_pass1");

// вывод HTML страницы с кнопкой для оплаты
$disp= "
<div align=\"center\">
<img src=\"phpshop/lib/templates/icon/bank/robox.png\" border=\"0\">
<h4>
Нажав кнопку Оплатить счет, Вы перейдете в шлюз оплаты сервиса ROBOKASSA, где Вам будет предложено оплатить заказ любым удобным способом: картами Visa, MasterCard, Яндекс-Деньги, Webmoney, терминалы QIWI.
</h4>




<form action='https://merchant.roboxchange.com/Index.aspx' method=POST name=\"pay\">
       <input type=hidden name=MrchLogin  value=$mrh_login>
       <input type=hidden name=OutSum  value=$out_summ>
       <input type=hidden name=InvId  value=$inv_id>
       <input type=hidden name=Desc  value=$inv_desc>
           <input type=hidden name=SignatureValue value=$crc>
	  <table>
<tr>
	<td><img src=\"images/shop/icon-client-new.gif\" alt=\"\" width=\"16\" height=\"16\" border=\"0\" align=\"left\">
	<a href=\"javascript:pay.submit();\">Оплатить сейчас</a></td>
</tr>
</table>
      </form>
</div>";

?>