<?php

function userorderpaymentlink_mod_yandexkassa_hook($obj, $PHPShopOrderFunction) {
    global $PHPShopSystem;

    // Настройки модуля
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    $PHPShopYandexkassaArray = new PHPShopYandexkassaArray();
    $option = $PHPShopYandexkassaArray->getArray();


    // Контроль оплаты от статуса заказа
    if ($PHPShopOrderFunction->order_metod_id == 10004)
        if ($PHPShopOrderFunction->getParam('statusi') == $option['status'] or empty($option['status'])) {

            // Номер счета
            $mrh_ouid = explode("-", $PHPShopOrderFunction->objRow['uid']);
            $inv_id = $mrh_ouid[0] . "" . $mrh_ouid[1];

            // Сумма покупки
            $out_summ = floatval(number_format($PHPShopOrderFunction->getTotal(), 2, '.', ''));

            // Платежная форма
            $payment_forma .= PHPShopText::setInput('hidden', 'shopId', trim($option['merchant_id']), false, 10);
            $payment_forma .= PHPShopText::setInput('hidden', 'scid', trim($option['merchant_scid']), false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'sum', $out_summ, false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'orderNumber', $inv_id, false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'cps_email', $PHPShopOrderFunction->getMail(), false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'cms_name', 'phpshop', false, 10);

            // ОФД
            $ym_merchant_receipt['customerContact'] = $PHPShopOrderFunction->getMail();

            // НДС
            if ($PHPShopSystem->getParam('nds_enabled') == '') {
                $tax = $tax_delivery = 2;
            } else {

                switch ($PHPShopSystem->getParam('nds')) {
                    case 0:
                        $tax = 2;
                        break;
                    case 10:
                        $tax = 3;
                        break;
                    case 18:
                        $tax = 4;
                        break;
                    default: $tax = 2;
                }
            }

            $order = $PHPShopOrderFunction->unserializeParam('orders');

            // Корзина
            if (is_array($order['Cart']['cart'])) {


                foreach ($order['Cart']['cart'] as $product) {

                    // Скидка
                    if ($order['Person']['discount'] > 0)
                        $price = $product['price'] - ($product['price'] * $order['Person']['discount'] / 100);
                    else
                        $price = $product['price'];

                    $ym_merchant_receipt['items'][] = array('text' => $product['name'], 'quantity' => floatval(number_format($product['num'], 3, '.', '')), 'price' => array('amount' => floatval(number_format($price, 2, '.', ''))), 'tax' => $tax);
                }
            }

            // Доставка
            if (!empty($order['Cart']['dostavka'])) {

                PHPShopObj::loadClass('delivery');
                $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);

                switch ($PHPShopDelivery->getParam('ofd_nds')) {
                    case 0:
                        $tax_delivery = 2;
                        break;
                    case 10:
                        $tax_delivery = 3;
                        break;
                    case 18:
                        $tax_delivery = 4;
                        break;
                    default: $tax_delivery = $tax;
                }

                $ym_merchant_receipt['items'][] = array('text' => 'Доставка', 'quantity' => floatval(number_format(1, 3, '.', '')), 'price' => array('amount' => floatval(number_format($order['Cart']['dostavka'], 2, '.', ''))), 'tax' => $tax_delivery);
            }
            
            $payment_forma.="<input type='hidden' name='ym_merchant_receipt' value='" . PHPShopString::json_safe_encode($ym_merchant_receipt) . "'>";

            // Тип оплаты
            $v = $PHPShopYandexkassaArray->get_pay_variants_array(unserialize($option['pay_variants']), true);
            $payment_forma.=PHPShopText::select('paymentType', $v, 250, 'left', false, false, false, 1, false, 'form-control') . ' ';

            $payment_forma.=PHPShopText::setInput('submit', 'send', $option['title'], $float = "none", 250);

            if ($option['test'])
                $action = 'https://demomoney.yandex.ru/eshop.xml';
            else
                $action = 'https://money.yandex.ru/eshop.xml';

            // данные в лог
            $PHPShopYandexkassaArray->log(array('action' => $action, 'shopId' => $option['merchant_id'], 'scid' => trim($option['merchant_scid']), 'sum' => $out_summ, 'customerNumber' => $PHPShopOrderFunction->getMail(), 'orderNumber' => $inv_id, 'ym_merchant_receipt' => $ym_merchant_receipt), $inv_id, 'форма готова к отправке', 'данные формы для отправки на оплату');

            $return = PHPShopText::form($payment_forma, 'yandexpay', 'post', $action, '_blank');
        } elseif ($PHPShopOrderFunction->getSerilizeParam('orders.Person.order_metod') == 10004)
            $return = ', Заказ обрабатывается менеджером';

    return $return;
}

$addHandler = array
    (
    'userorderpaymentlink' => 'userorderpaymentlink_mod_yandexkassa_hook'
);
?>