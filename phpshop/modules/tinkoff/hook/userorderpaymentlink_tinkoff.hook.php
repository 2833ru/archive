<?php

function userorderpaymentlink_tinkoff_hook($obj, $PHPShopOrderFunction) {
    global $PHPShopSystem;

    // Настройки модуля
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    include_once $GLOBALS['SysValue']['class']['tinkoff'];
    $PHPShopTinkoffArray = new PHPShopTinkoffArray();
    $option = $PHPShopTinkoffArray->getArray();

    // Контроль оплаты от статуса заказа
    if ($PHPShopOrderFunction->order_metod_id == 10032)
        if ($PHPShopOrderFunction->getParam('statusi') == $option['status'] or empty($option['status'])) {

            $tinkoff = new Tinkoff();

            $email['mail'] = $PHPShopOrderFunction->getMail();

            $obj->ouid = $PHPShopOrderFunction->objRow['uid'];

            $obj->tinkoff_total = floatval(number_format($PHPShopOrderFunction->getTotal(), 2, '.', '')) * 100;

            $obj->tinkoff_cart = $PHPShopOrderFunction->unserializeParam('orders');

            // Доставка
            if (!empty($obj->tinkoff_cart['Cart']['dostavka'])) {

                PHPShopObj::loadClass('delivery');
                $PHPShopDelivery = new PHPShopDelivery($obj->tinkoff_cart['Person']['dostavka_metod']);

                if($ofd_nds = $PHPShopDelivery->getParam('ofd_nds'))
                    $tax = $PHPShopDelivery->getParam('ofd_nds');
                else
                    $tax = $PHPShopSystem->getParam('nds');

                $obj->tinkoff_delivery_nds = $tax;

                $obj->delivery = floatval(number_format($obj->tinkoff_cart['Cart']['dostavka'], 2, '.', ''));
            }

            $request = $tinkoff->getPaymentUrl($obj, $email, false, 'userpaymentlink');

            $return = PHPShopText::setInput('button', 'send', "Оплатить заказ", $float = "none", 250, "window.location.replace('" . $request['url'] . "')");

        } elseif ($PHPShopOrderFunction->getSerilizeParam('orders.Person.order_metod') == 10032)
            $return = ', Заказ обрабатывается менеджером';

    return $return;
}

$addHandler = array
    (
    'userorderpaymentlink' => 'userorderpaymentlink_tinkoff_hook'
);
?>