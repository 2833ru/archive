<?php
/**
 * Обработчик оплаты заказа через Robox
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopPayment
 */

if(empty($GLOBALS['SysValue'])) exit(header("Location: /"));


	$out_summ = $GLOBALS['SysValue']['other']['total']*$SysValue['roboxchange']['mrh_kurs']; //сумма покупки
	$out_summ = number_format($out_summ, 2, '', '');

	// вывод HTML страницы с кнопкой для оплаты
	$disp= '

	<div align="center">
<h4>
Нажав кнопку Оплатить счет, Вы перейдете в шлюз оплаты пластиковой картой, где Вам будет предложено оплатить заказ картами Visa, MasterCard.
</h4>

	<form method="post" action="/payment/alfabank/result.php">
	<input type="hidden" name="orderNumber" value="'.$_POST['ouid'].'" /><br />
	<input type="hidden" name="amount" value="'.$out_summ.'" /><br />
	<button type="submit" class="button_send">Оплатить сейчас</button>
	</form>

	<style>
	.button_send {
		background: greenyellow;
    width: 429px;
    height: 95px;
    border-radius: 50px;
		padding: 0 34px;
		margin: 0 -29px 0 -19px;
		text-align: center;
		color: #000;
		font-size: 30px;
		font-weight: normal;
		line-height: 68px;
		border: 0;
		text-shadow: 0 2px 2px #d1ff94, 0 2px 2px #d1ff94;
		cursor: pointer;
	}
	.button_send:hover {
		background:#92FF2F;
	}
	</style>


	';


?>