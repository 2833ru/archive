<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль OrderFunction PayOnline     |
+-------------------------------------+
*/

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));

$cart_list=Summa_cart();
$ChekDiscount=ChekDiscount($cart_list[1]);
$GetDeliveryPrice=GetDeliveryPrice($_POST['dostavka_metod'],$cart_list[1],$cart_list[2]);
$sum_pol=(ReturnSummaNal($cart_list[1],$ChekDiscount[0])+$GetDeliveryPrice);
$sum_pol = number_format($sum_pol,2,".","");
	 

// регистрационная информация
$PrivateSecurityKey=$SysValue['payonlinesystem']['PrivateSecurityKey'];
$MerchantId=$SysValue['payonlinesystem']['MerchantId'];

$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

$OrderId=$inv_id;
$Amount=$sum_pol;
$Currency="RUB";

$SecurityKey=md5("MerchantId=$MerchantId&OrderId=$OrderId&Amount=$Amount&Currency=$Currency&PrivateSecurityKey=$PrivateSecurityKey");


// обратный адрес
$FailUrl="http://".$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']."/fail/";


// вывод HTML страницы с кнопкой для оплаты
$disp= "
<div align=\"center\">

 <p><br></p>
 
 <img src=\"images/bank/visa.gif\" border=\"0\" hspace=5>
  <img src=\"images/bank/mastercard.gif\" border=\"0\" hspace=5>
  <p><br></p>

<p>Вы можете оплатить свои заказы в режиме он-лайн кредитные картами (VISA, MasterCard, DCL, JCB, AmEx). Обработка платежей осуществляется процессинговым центром <b>PayOnline System</b>. </p>

<form name=\"PaymentForm\" action=\"https://secure.payonlinesystem.com/ru/payment/\" method=\"get\" target=\"_top\" >
<input type=\"hidden\" name=\"OrderId\" id=\"OrderId\" value=\"$OrderId\">
<input type=\"hidden\" name=\"Amount\" id=\"Amount\" value=\"$Amount\">
<input type=\"hidden\" name=\"MerchantId\" value=\"$MerchantId\">
<input type=\"hidden\" name=\"Currency\" value=\"$Currency\">
<input type=\"hidden\" name=\"SecurityKey\" value=\"$SecurityKey\">
<input type=\"hidden\" name=\"FailUrl\" value=\"".urlencode($FailUrl)."\">
	<table>
<tr><td><img src=\"images/shop/icon-setup.gif\" width=\"16\" height=\"16\" border=\"0\"></td>
	<td align=\"center\"><a href=\"javascript:history.back(1)\"><u>
	Вернуться к оформлению<br>
	покупки</u></a></td>
	<td width=\"20\"></td>
	<td><img src=\"images/shop/icon-client-new.gif\" alt=\"\" width=\"16\" height=\"16\" border=\"0\" align=\"left\">
	<a href=\"javascript:PaymentForm.submit();\">Оплатить через платежную систему</a></td>
</tr>
</table>
</form>

</div>";

?>