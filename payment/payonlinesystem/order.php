<?php
/**
 * Обработчик оплаты заказа через PayOnline
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));


// регистрационная информация
$PrivateSecurityKey=$SysValue['payonlinesystem']['PrivateSecurityKey'];
$MerchantId=$SysValue['payonlinesystem']['MerchantId'];

$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

$OrderId=$inv_id;
$Amount=number_format($GLOBALS['SysValue']['other']['total'], 2, '.', '');
$Currency="RUB";

$SecurityKey=md5("MerchantId=$MerchantId&OrderId=$OrderId&Amount=$Amount&Currency=$Currency&PrivateSecurityKey=$PrivateSecurityKey");


// обратный адрес
$FailUrl="http://".$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']."/fail/";


// вывод HTML страницы с кнопкой для оплаты
$disp= "
<script>
function CheckAgreement(){
if(document.getElementById('agreement').checked)
  PaymentForm.submit();
else alert('Необходимо согласиться с условиями оферты');
}
</script>
<div align=\"center\" width=\"400\">

 <p><br></p>
 
 <img src=\"phpshop/lib/templates/icon/bank/visa.png\" border=\"0\" hspace=\"5\">
  <img src=\"phpshop/lib/templates/icon/bank/mastercard.png\" border=\"0\" hspace=\"5\">
  <p><br></p>

<h4>Вы можете оплатить заказ в режиме online кредитными картами VISA, MasterCard. Обработка платежей осуществляется процессинговым центром <b>PayOnline System</b>. </h4>

<form name=\"PaymentForm\" action=\"https://secure.payonlinesystem.com/ru/payment/\" method=\"get\" target=\"_top\" >
<input type=\"hidden\" name=\"OrderId\" id=\"OrderId\" value=\"$OrderId\">
<input type=\"hidden\" name=\"Amount\" id=\"Amount\" value=\"$Amount\">
<input type=\"hidden\" name=\"MerchantId\" value=\"$MerchantId\">
<input type=\"hidden\" name=\"Currency\" value=\"$Currency\">
<input type=\"hidden\" name=\"SecurityKey\" value=\"$SecurityKey\">
<input type=\"hidden\" name=\"FailUrl\" value=\"".urlencode($FailUrl)."\">
<input type=\"checkbox\" id=\"agreement\" value=\"1\"> Я принимаю <a href=\"/page/agreement.html\" target=\"_blank\">условия оферты</a>
	<table>
<tr>
	<td><img src=\"images/shop/icon-client-new.gif\" alt=\"\" width=\"16\" height=\"16\" border=\"0\" align=\"left\">
        
	<a href=\"javascript:CheckAgreement();\">Оплатить сейчас</a></td>
</tr>
</table>
</form>

</div>";

?>