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


            // Тип оплаты
            $v = $PHPShopYandexkassaArray->get_pay_variants_array(unserialize($option['pay_variants']), true);
            
            $payment_forma.=PHPShopText::select('paymentType', $v, 250, 'left') . ' ';
            $payment_forma.=PHPShopText::setInput('submit', 'send', $option['title'], $float = "left; margin-left:10px;", 250);

            if ($option['test'])
                $action = 'https://demomoney.yandex.ru/eshop.xml';
            else
                $action = 'https://money.yandex.ru/eshop.xml';

            // данные в лог
            $PHPShopYandexkassaArray->log(array('action' => $action, 'shopId' => $option['merchant_id'], 'scid' => trim($option['merchant_scid']), 'sum' => $out_summ, 'customerNumber' => $value['mail'], 'orderNumber' => $inv_id), $inv_id, 'форма готова к отправке', 'данные формы для отправки на оплату');

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