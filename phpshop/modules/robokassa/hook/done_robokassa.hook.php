<?php

function send_to_order_mod_robokassa_hook($obj, $value, $rout) {
    global $PHPShopSystem;

    if ($rout == 'MIDDLE' and $value['order_metod'] == 10020) {

        // Настройки модуля
        include_once(dirname(__FILE__) . '/mod_option.hook.php');

        $PHPShopRobokassaArray = new PHPShopRobokassaArray();
        $option = $PHPShopRobokassaArray->getArray();

        // Контроль оплаты от статуса заказа
        if (empty($option['status'])) {

            // Номер счета
            $mrh_ouid = explode("-", $value['ouid']);
            $inv_id = $mrh_ouid[0] . $mrh_ouid[1];

            // Сумма покупки
            $out_summ = number_format($obj->get('total'), 2, '.', '');

            // Платежная форма
            $payment_forma .= PHPShopText::setInput('hidden', 'MrchLogin', trim($option['merchant_login']), false, 10);
            $payment_forma .= PHPShopText::setInput('hidden', 'OutSum', $out_summ, false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'InvId', $inv_id, false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'Desc', $value['ouid'], false, 10);

            // НДС
            if ($PHPShopSystem->getParam('nds_enabled') == '') {
                $tax = $tax_delivery = 'none';
            } else {
                $tax = 'vat' . $PHPShopSystem->getParam('nds');
            }

            // Корзина
            if ($obj->PHPShopCart->getNum() > 0) {
                $cart = $obj->PHPShopCart->getArray();

                foreach ($cart as $product) {
                    $ym_merchant_receipt['items'][] = array(
                        'name' => $product['name'],
                        'quantity' => floatval(number_format($product['num'], 3, '.', '')),
                        'sum' => floatval(number_format($product['price'], 2, '.', '')),
                        'tax' => $tax,
                        'payment_method' => 'full_prepayment',
                        'payment_object' => 'commodity'
                    );
                }
            }

            // Доставка
            if ($obj->delivery > 0) {

                // НДС Доставки
                $tax_delivery = $obj->PHPShopDelivery->getParam('ofd_nds');
                if ($tax_delivery == '')
                    $tax_delivery = $tax;
                else
                    $tax_delivery = 'vat' . $tax_delivery;

                $ym_merchant_receipt['items'][] = array(
                    'name' => 'Доставка',
                    'quantity' => floatval(number_format(1, 3, '.', '')),
                    'sum' => floatval(number_format($obj->delivery, 2, '.', '')),
                    'tax' => $tax_delivery,
                    'payment_method' => 'full_prepayment',
                    'payment_object' => 'service'
                );
            }

            $Receipt = urlencode(PHPShopString::json_safe_encode($ym_merchant_receipt));

            // Подпись
            $crc = md5(trim($option['merchant_login']) . ':' . $out_summ . ':' . $inv_id . ':' . $Receipt . ':' . trim($option['merchant_key']));

            $payment_forma.=PHPShopText::setInput('hidden', 'Receipt', $Receipt, false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'SignatureValue', $crc, false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'Encoding', 'utf-8', false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'Email', $_POST['mail'], false, 10);
            $payment_forma.=PHPShopText::setInput('submit', 'send', $option['title'], $float = "left; margin-left:10px;", 250);

            // Данные в лог
            $PHPShopRobokassaArray->log(array('action' => 'done', 'MrchLogin' => trim($option['merchant_login']), 'sum' => $out_summ, 'Email' =>$_POST['mail'], 'orderNumber' => $inv_id, 'Receipt' => $Receipt), $inv_id, 'форма готова к отправке', 'данные формы для отправки на оплату');

            $obj->set('payment_forma', PHPShopText::form($payment_forma, 'pay', 'post', 'https://merchant.roboxchange.com/Index.aspx'));
            $obj->set('payment_info', $option['title_end']);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['robokassa']['robokassa_payment_forma'], true);
        } else {
            $obj->set('mesageText', $option['title_sub']);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['order_forma_mesage']);
        }

        $obj->set('orderMesage', $forma);
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_mod_robokassa_hook'
);
?>