<?php

function payment_mod_paypal_hook($obj, $value) {

    // Настройки модуля
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    $option = new PHPShopPaypalArray();

    $value[] = array($option->getParam('title'), 10003, false);
    $obj->set('orderOplata', PHPShopText::select('order_metod', $value, 250));
    ;
    return true;
}

/**
 * Добавление кнопки быстрого заказа
 */
function order_mod_paypal_hook($obj, $row, $rout) {
    if ($rout == 'END') {
        $cart_min = $obj->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');
        if ($cart_min <= $obj->PHPShopCart->getSum(false))
            $obj->set('orderContent', parseTemplateReturn('phpshop/modules/paypal/templates/main_order_forma.tpl', true));
    }
}

$addHandler = array
    (
    'payment' => 'payment_mod_paypal_hook',
    'order' => 'order_mod_paypal_hook'
);
?>