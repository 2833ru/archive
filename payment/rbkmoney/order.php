<?php
/**
 * ќбработчик оплаты заказа через RBKMoney
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if (empty($GLOBALS['SysValue'])) exit(header("Location: /"));

// регистрационна€ информаци€
$MerchantId = $SysValue['rbk']['eshopId'];
$serviceName = $SysValue['rbk']['serviceName'];
$Currency = $SysValue['rbk']['Currency'];

$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0] . "" . $mrh_ouid[1];     //номер счета

$OrderId = $inv_id;
$Amount = $GLOBALS['SysValue']['other']['total'];

// обратный адрес
$ReturnUrl = urlencode("http://" . $_SERVER['SERVER_NAME'] . "/success/?payment=rbkmoney&inv_id=".$inv_id);
$FailUrl = urlencode("http://" . $_SERVER['SERVER_NAME'] . $SysValue['dir']['dir'] . "/fail/");

// вывод HTML страницы с кнопкой дл€ оплаты
$disp = "
<div align=\"center\">

 <p><br></p>
 
 <img src=\"phpshop/lib/templates/icon/bank/visa.gif\" border=\"0\" hspace=5>
  <img src=\"phpshop/lib/templates/icon/bank/mastercard.gif\" border=\"0\" hspace=5>
  <p><br></p>

<p>¬ы можете оплатить свои заказы в режиме он-лайн кредитными картами и другими способами оплаты. ќбработка платежей осуществл€етс€ процессинговым центром <b>RBK money</b>. </p>

                <form name=\"PaymentForm\" action=\"https://rbkmoney.ru/acceptpurchase.aspx\" method=post>
                   <input type=\"hidden\" name=\"eshopId\" value=\"$MerchantId\">
                   <input type=\"hidden\" name=\"user_email\" value=\"" . $_POST['mail'] . "\">
                   <input type=\"hidden\" name=\"orderId\" value=\"$OrderId\">
                   <input type=\"hidden\" name=\"serviceName\" value=\"$serviceName\">
                   <input type=\"hidden\" name=\"recipientAmount\" value=\"$Amount\">
                   <input type=\"hidden\" name=\"recipientCurrency\" value=\"$Currency\">
                   <input type=\"hidden\" name=\"version\" value=\"2\">
                   <input type=\"hidden\" name=\"successUrl\" value=\"$ReturnUrl\">
                   <input type=\"hidden\" name=\"failUrl\" value=\"$FailUrl\">
	<table>
<tr>
	<td><img src=\"images/shop/icon-client-new.gif\" alt=\"\" width=\"16\" height=\"16\" border=\"0\" align=\"left\">
	<a href=\"javascript:PaymentForm.submit();\">ќплатить через платежную систему RBK money</a></td>
</tr>
</table>
</form>

</div>";
?>