<?php

function send_to_order_mod_tinkoff_hook($obj, $value, $rout)
{
    if ($rout == 'MIDDLE' && $value['order_metod'] == 10032) {
        include_once dirname(__FILE__) . '/mod_option.hook.php';
        include_once $GLOBALS['SysValue']['class']['tinkoff'];

        $obj->cart_clean_enabled = false;
        $tinkoff = new Tinkoff();
        //  $amount = number_format($obj->get('total') * $rate, 2, '.', '');
        /* $paymentUrl = $tinkoff->getPaymentUrl($obj->ouid, $obj->get('total'), $value['mail']);*/
        $request = $tinkoff->getPaymentUrl($obj, $value, $rout);

        if ($request['url']) {
            $obj->set('payment_forma',
                PHPShopText::button('Оплатить через Тинькофф Банк', "window.location.replace('" . $request['url'] . "')", 'paybutton')
            );
        } else {
            $obj->set('payment_forma', $request['error']);
        }

        $form = ParseTemplateReturn($GLOBALS['SysValue']['templates']['tinkoff']['tinkoff_payment_form'], true);

        // Очищаем корзину
        unset($_SESSION['cart']);

        $obj->set('orderMesage', $form);
    }
}

$addHandler = array
(
    'send_to_order' => 'send_to_order_mod_tinkoff_hook'
);

?>