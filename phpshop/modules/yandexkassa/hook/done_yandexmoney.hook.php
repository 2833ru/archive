<?php

function send_to_order_mod_yandexkassa_hook($obj, $value, $rout) {
    global $PHPShopSystem;

    if ($rout == 'MIDDLE' and $value['order_metod'] == 10004) {


        // Настройки модуля
        include_once(dirname(__FILE__) . '/mod_option.hook.php');
        $PHPShopYandexkassaArray = new PHPShopYandexkassaArray();
        $option = $PHPShopYandexkassaArray->getArray();

        // Контроль оплаты от статуса заказа
        if (empty($option['status'])) {
            // Номер счета
            $mrh_ouid = explode("-", $value['ouid']);
            $inv_id = $mrh_ouid[0] . $mrh_ouid[1];

            // Сумма покупки
            $out_summ = $obj->get('total');

            // Платежная форма
            $payment_forma .= PHPShopText::setInput('hidden', 'shopId', trim($option['merchant_id']), false, 10);
            $payment_forma .= PHPShopText::setInput('hidden', 'scid', trim($option['merchant_scid']), false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'sum', $out_summ, false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'customerNumber', $value['mail'], false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'orderNumber', $inv_id, false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'cms_name', 'phpshop', false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'cps_phone', $_POST['tel_new'], false, 10);
            $payment_forma.=PHPShopText::setInput('hidden', 'cps_email', $_POST['mail'], false, 10);


            // ОФД

            $ym_merchant_receipt['customerContact'] = $_POST['mail'];

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

            // Корзина
            if ($obj->PHPShopCart->getNum() > 0) {
                $cart = $obj->PHPShopCart->getArray();

                foreach ($cart as $product) {
                    $ym_merchant_receipt['items'][] = array(
                        'text' => $product['name'],
                        'quantity' => floatval(number_format($product['num'], 3, '.', '')),
                        'price' => array('amount' => floatval(number_format($product['price'], 2, '.', ''))),
                        'tax' => $tax
                    );
                }
            }

            // Доставка
            if ($obj->delivery > 0) {

                switch ($obj->PHPShopDelivery->getParam('ofd_nds')) {
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

                $ym_merchant_receipt['items'][] = array('text' => 'Доставка', 'quantity' => floatval(number_format(1, 3, '.', '')), 'price' => array('amount' => floatval(number_format($obj->delivery, 2, '.', ''))), 'tax' => $tax_delivery);
            }

            $payment_forma.="<input type='hidden' name='ym_merchant_receipt' value='" . PHPShopString::json_safe_encode($ym_merchant_receipt) . "'>";

            // Тип оплаты
            $v = $PHPShopYandexkassaArray->get_pay_variants_array(unserialize($option['pay_variants']), true);

            $payment_forma.=PHPShopText::select('paymentType', $v, 350, 'left', false, false, false, 1, false, 'form-control') . ' ';
            $payment_forma.=PHPShopText::setInput('submit', 'send', $option['title'], $float = "left; margin-left:10px;", 250);

            if ($option['test'])
                $action = 'https://demomoney.yandex.ru/eshop.xml';
            else
                $action = 'https://money.yandex.ru/eshop.xml';

            // Данные в лог
            $PHPShopYandexkassaArray->log(array('action' => $action, 'shopId' => $option['merchant_id'], 'scid' => trim($option['merchant_scid']), 'sum' => $out_summ, 'customerNumber' => $value['mail'], 'orderNumber' => $inv_id,'ym_merchant_receipt'=>$ym_merchant_receipt), $inv_id, 'форма готова к отправке', 'данные формы для отправки на оплату');

            $obj->set('payment_forma', PHPShopText::form($payment_forma, 'yandexpay', 'post', $action, '_blank'));
            $obj->set('payment_info', $option['title_end']);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['yandexkassa']['yandexmoney_payment_forma'], true);
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
        }

        $obj->set('orderMesage', $forma);
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_mod_yandexkassa_hook'
);
?>