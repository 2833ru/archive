<?php

function send_to_order_mod_tinkoff_hook($obj, $value, $rout)
{
    if ($rout == 'MIDDLE' && $value['order_metod'] == 10032) {
        include_once dirname(__FILE__) . '/mod_option.hook.php';
        include_once $GLOBALS['SysValue']['class']['tinkoff'];
        $PHPShopTinkoffArray = new PHPShopTinkoffArray();
        $option = $PHPShopTinkoffArray->getArray();

        // Контроль оплаты от статуса заказа
        if (empty($option['status'])) {

            $obj->cart_clean_enabled = false;
            $tinkoff = new Tinkoff();

            $obj->tinkoff_total = $obj->get('total') * 100;
            $obj->tinkoff_cart = $obj->PHPShopCart->getArray();
            $obj->tinkoff_delivery_nds = $obj->PHPShopDelivery->objRow['ofd_nds'];

            $request = $tinkoff->getPaymentUrl($obj, $value);

            if ($request['url'])
                $obj->set('payment_forma', PHPShopText::button('Оплатить через Тинькофф Банк', "window.location.replace('" . $request['url'] . "')", 'paybutton'));
            else
                $obj->set('payment_forma', $request['error']);

            $form = ParseTemplateReturn($GLOBALS['SysValue']['templates']['tinkoff']['tinkoff_payment_form'], true);
            // Очищаем корзину
            unset($_SESSION['cart']);

            $obj->set('orderMesage', $form);
        } else {

            $clean_cart = "
               <script>
                   if(window.document.getElementById('num')){
                       window.document.getElementById('num').innerHTML='0';
                       window.document.getElementById('sum').innerHTML='0';
                   }
               </script>";
            $obj->set('mesageText', $option['title_end'] . $clean_cart);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['order_forma_mesage']);

            // Очищаем корзину
            unset($_SESSION['cart']);

            $obj->set('orderMesage', $forma);
        }
    }
}

$addHandler = array
(
    'send_to_order' => 'send_to_order_mod_tinkoff_hook'
);

?>