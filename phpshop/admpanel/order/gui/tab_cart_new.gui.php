<?php

/**
 * Панель подробного описания товара
 * @param array $row массив данных
 * @return string 
 */
function tab_cart_new($data, $option = false) {
    global $PHPShopGUI, $PHPShopSystem;
    //$PHPShopGUI->addJSFiles('gui/tab_cart.gui.js');

    // Обновление данных при AJAX
    if ($option == 'ajax') {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($data['id'])));
    }

    // Библиотека заказа
    $PHPShopOrder = new PHPShopOrderFunction($data['id']);

    $order = unserialize($data['orders']);

    // Библиотека доставки
    $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);

    // Бланк заказа
    $blank.=$PHPShopGUI->setButton(__('Бланк заказа'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('/phpshop/modules/promotions/admpanel/forms/forma.php?orderID=" . $data['id'] . "'); return false;");
    // Товарный чек
    $blank.=$PHPShopGUI->setButton(__('Товарный чек'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('/phpshop/modules/promotions/admpanel/forms/forma2.php?orderID=" . $data['id'] . "'); return false;");
    // Гарантия
    $blank.=$PHPShopGUI->setButton(__('Гарантия'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('forms/forma3.php?orderID=" . $data['id'] . "'); return false;");
    // Счет
    $blank.=$PHPShopGUI->setButton(__('Счет'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('../../../phpshop/forms/account/forma.html?orderId=".$data['id']."&tip=2&datas=".$data['datas']."'); return false;");
    // Счет в сбербанк
    $blank.=$PHPShopGUI->setButton(__('Сбербанк'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('../../../phpshop/forms/receipt/forma.html?orderId=".$data['id']."&tip=2&datas=".$data['datas']."'); return false;");
    // Счет-Фактур
    $blank.=$PHPShopGUI->setButton(__('Счет-Фактура'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('/phpshop/modules/promotions/admpanel/forms/forma4.php?orderID=" . $data['id'] . "'); return false;");
    // Торг12
    $blank.=$PHPShopGUI->setButton(__('Торг-12'), '../img/action_print.gif', 130, 30, $float = "left", $onclick = "DoPrint('forms/forma5.php?orderID=" . $data['id'] . "'); return false;");

    // Почта
    $mail_blank = $order['Person']['mail'];
    $mail_title_blank = "Re: " . $PHPShopSystem->getParam('name') . " - Заказ №" . $data['uid'];
    $blank.=$PHPShopGUI->setButton(__('E-mail'), '../img/icon_email.gif', 130, 30, $float = "left", $onclick = "window.open('mailto:" . $mail_blank . "?subject=" . $mail_title_blank . "'); return false;");


    $CART = $order['Cart'];
    $PERSON = $order['Person'];
    $cart = $CART['cart'];
    $num = null;
    $sum = null;
    $n = 1;
    if (sizeof($cart) != 0)
        if (is_array($cart))
            foreach ($cart as $val) {

                $disCart.="


<tr class=row3 onmouseover=\"show_on('r" . $val['id'] . "')\" id=\"r" . $val['id'] . "\" onmouseout=\"show_out('r" . $val['id'] . "')\" >
 <td style=\"padding:3\">$n</td> 
  <td style=\"padding:3\">" . $val['uid'] . "</td>
  <td style=\"padding:3\">" . $val['id'] . "</td>
  <td style=\"padding:3\">" . $val['name'] . "</td>
  <td style=\"padding:3\">" . $val['num'] . "</td>
  <td style=\"padding:3\">" . $PHPShopOrder->ReturnSumma($val['price'] * $val['num'], 0) . "</td>
  
</tr>
";

                $n++;
                $num+=$val['num'];
                $sum+=$val['price'] * $val['num'];

                // Определение и суммирование веса
                $goodid = $val['id'];
                $goodnum = $val['num'];
                $wsql = 'select weight from ' . $GLOBALS['SysValue']['base']['table_name2'] . ' where id=\'' . $goodid . '\'';
                $wresult = mysql_query($wsql);
                $wrow = mysql_fetch_array($wresult);
                $cweight = $wrow['weight'] * $goodnum;
                if (!$cweight) {
                    $zeroweight = 1;
                }

                // Один из товаров имеет нулевой вес!
                $weight+=$cweight;
            }

    // Обнуляем вес товаров, если хотя бы один товар был без веса
    if ($zeroweight) {
        $weight = 0;
    }


    $GetDeliveryPrice = $PHPShopOrder->getDeliverySumma();
    $disCart.="
<tr class=row3 onmouseover=\"show_on('r" . $n . "')\" id=\"r" . $n . "\" onmouseout=\"show_out('r" . $n . "')\">
  <td style=\"padding:3\">$n</td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\">Доставка - " . $PHPShopDelivery->getCity() . "</td>
  <td style=\"padding:3\">1</td>
  <td style=\"padding:3\">" . $GetDeliveryPrice . "</td>
  
</tr>
";
    $n++;
    while ($n < 11) {
        $disCart.="
 <tr bgcolor=\"ffffff\">
  <td style=\"padding:3\" height=\"20\">$n</td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
  <td style=\"padding:3\"></td>
</tr>
                        ";
        $n++;
    }
    if($PERSON['tip_disc']==1) {
      $tip_disc = '%';
    }
    else {
      $tip_disc = 'руб.';
    }
    $disCart.="
<tr bgcolor=\"#C0D2EC\">
  <td style=\"padding:3\" colspan=\"3\" align=center><span name=txtLang id=txtLang>Итого с учетом скидки</span> " . $PERSON['discount_promo'] ." ". $tip_disc ."</td>
  <td style=\"padding:3\"><b>" . ($num + 1) . "</b> <span name=txtLang id=txtLang>шт.</span></td>
  <td style=\"padding:3\" colspan=\"2\" align=\"center\"><b>" . ($PHPShopOrder->returnSumma($CART['sum'], $PERSON['discount']) + $GetDeliveryPrice) . "</b> " . $PHPShopOrder->default_valuta_code . "</td>
</tr>
";



    $script = '<script type="text/javascript" src="/phpshop/modules/promotions/js/jquery-1.7.1.min.js"></script>
<script>
$(document).ready(function(){
  $(".tab").each(function(i,elem) {
    if ($(elem).text()=="Корзина") {
      $(elem).hide();
      return false;
    }
  });
  $("#tab_pretab2_0").html( $("#blank").html() );
  $("#blank").hide();
});
</script>';

  $div = '<div id="blank">'.$blank.'</div>';

    $disp = $div.$script."

    <fieldset style='float:left;padding:2%; width:95%;'>
      <div style='font-size:13px;'>Для данного заказа применена промо-скидка!<br>Номер промо-кода: <b>".$PERSON['promocode']."</b></div>
    </fieldset>

    <table width=\"100%\"  cellpadding=\"0\" cellspacing=\"0\">
	<tr>
	<td valign=\"top\">
		<div align=\"left\" style=\"width:100%;height:255;overflow:auto\"> 
	<div id=interfaces>
	<table cellpadding=\"0\" cellspacing=\"1\" width=\"100%\" border=\"0\" class=\"table\">
<tr>
	<td id=pane align=center width=\"10\"><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Артикул</span></td>
        <td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>ID</span></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Наименование</span></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Кол-во</span></td>
	<td id=pane align=center><img src=../img/arrow_d.gif width=7 height=7 border=0 hspace=5><span name=txtLang id=txtLang>Сумма</span> " . $PHPShopOrder->default_valuta_code . "</td>
</tr>
" . $disCart . "
</table>
</div>

	</td>
</tr>
</table>


<style>
div#tab_1.tab-page {
  visibility:hidden;
}
</style>

";


    return $disp;
}

?>
