<?php

function robox_users_repay($obj,$PHPShopOrderFunction) {
    global $PHPShopBase;

    // Регистрационная информация
    $mrh_login = $PHPShopBase->getParam('roboxchange.mrh_login');    //логин
    $mrh_pass1 = $PHPShopBase->getParam('roboxchange.mrh_pass1');   // пароль1

    //параметры магазина
    $mrh_ouid = explode("-", $row['uid']);
    $inv_id = $mrh_ouid[0]."".$mrh_ouid[1];     //номер счета

    //описание покупки
    $inv_desc  = 'PHPShopPaymentService';
    $out_summ  = $PHPShopOrderFunction->getTotal(); //сумма покупки

    // Если заказ не оплачен
    if($PHPShopOrderFunction->getParam('statusi') != 101)
        $disp="<form action='https://www.roboxchange.com/ssl/calc.asp' method=POST name=\"payrobots\">
      <input type=hidden name=mrh value=$mrh_login>
      <input type=hidden name=out_summ value=$out_summ>
      <input type=hidden name=inv_id value=$inv_id>
      <input type=hidden name=inv_desc value=$inv_desc>
      <input type=hidden name=crc value=$crc>
	<a href=\"javascript:void(0)\" class=b title=\"".__('Оплатить')." ".$PHPShopOrderFunction->getOplataMetodName()."\" onclick=\"javascript:payrobots.submit();\" ><img src=\"images/shop/coins.gif\" alt=\"Оплатить\" width=\"16\" height=\"16\" border=\"0\" align=\"absmiddle\"  hspace=5>".$PHPShopOrderFunction->getOplataMetodName()."</a>
            </form>";
    else $disp=PHPShopText::b($PHPShopOrderFunction->getOplataMetodName());

    return $disp;
}
?>