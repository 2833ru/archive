<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль OrderFunction Interkassa    |
+-------------------------------------+
*/

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));


$cart_list=Summa_cart();
$ChekDiscount=ChekDiscount($cart_list[1]);
$GetDeliveryPrice=GetDeliveryPrice($_POST['dostavka_metod'],$cart_list[1]);
$sum_pol=(ReturnSummaNal($cart_list[1],$ChekDiscount[0])+$GetDeliveryPrice);

 
// регистрационная информация
$LMI_PAYEE_PURSE = $SysValue['interkassa']['LMI_PAYEE_PURSE'];    //кошелек
$LMI_SECRET_KEY = $SysValue['interkassa']['LMI_SECRET_KEY'];    //кошелек



//параметры магазина
$mrh_ouid = explode("-", $_POST['ouid']);
$inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета


//описание покупки
$inv_desc  = "Оплата заказа №$inv_id";
$out_summ  = $sum_pol; //сумма покупки


$ik_shop_id = $LMI_PAYEE_PURSE;
$ik_payment_amount = $out_summ;
$ik_payment_id = $inv_id;
$ik_payment_desc = $inv_desc;
$ik_paysystem_alias = '';
$ik_sign_hash = '';
$ik_baggage_fields = '';
$secret_key = $LMI_SECRET_KEY;

$ik_sign_hash_str = $ik_shop_id.':'.
$ik_payment_amount.':'.
$ik_payment_id.':'.
$ik_paysystem_alias.':'.
$ik_baggage_fields.':'.
$secret_key;

$ik_sign_hash = md5($ik_sign_hash_str);



// вывод HTML страницы с кнопкой для оплаты
$disp= "
<div align=\"center\">

 <p><br></p>

<form name=\"pay\" action=\"https://interkassa.com/lib/payment.php\" method=\"post\" target=\"_top\">
<input type=\"hidden\" name=\"ik_shop_id\" value=\"$LMI_PAYEE_PURSE\">
<input type=\"hidden\" name=\"ik_payment_amount\" value=\"$out_summ\">
<input type=\"hidden\" name=\"ik_payment_id\" value=\"$inv_id\">
<input type=\"hidden\" name=\"ik_payment_desc\" value=\"$inv_desc\">
<input type=\"hidden\" name=\"ik_paysystem_alias\" value=\"\">
<input type=\"hidden\" name=\"ik_sign_hash\" value=\"$ik_sign_hash\">

	  <table>
<tr><td><img src=\"images/shop/icon-setup.gif\" width=\"16\" height=\"16\" border=\"0\"></td>
	<td align=\"center\"><a href=\"javascript:history.back(1)\"><u>
	Вернуться к оформлению<br>
	покупки</u></a></td>
	<td width=\"20\"></td>
	<td><img src=\"images/shop/icon-client-new.gif\" alt=\"\" width=\"16\" height=\"16\" border=\"0\" align=\"left\">
	<a href=\"javascript:pay.submit();\"><u>Оплатить через платежную систему</u></a></td>
</tr>
</table>
      </form>
</div>";
?>