<?php

function success_mod_tinkoff_hook($obj, $value) {
    if (isset($value['tinkoff'])) {
        return [
            'order_metod' => 'modules',
            'order_metod_name' => 'Tinkoff',
            'success_function' => false,
            'inv_id' => str_replace("-", '', $value['OrderId']),
            'out_summ' => $value['Amount']
        ];
    }
}

function message_mod_tinkoff_hook($obj) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment_systems']);
    $option = $PHPShopOrm->select(array('*'), array('id' => '=10032'), false, array('limit' => 1));

    if ($option) {
        $cart_clean = "
            <script>
            if (window.document.getElementById('num')){
            window.document.getElementById('num').innerHTML='0';
            window.document.getElementById('sum').innerHTML='0';
            }
            </script>";

        // ��������� ������������ �� �������� �������
        $message = $option['message'] ? $option['message'] : 'Спасибо за заказ';
        $text = PHPShopText::notice($option['message_header'] . PHPShopText::br(), $icon = false, '14px') . $message . $cart_clean;
        $obj->set('mesageText', $text);
        $obj->set('orderMesage', ParseTemplateReturn($obj->getValue('templates.order_forma_mesage')));
    }
}

$addHandler = array
(
    'index' => 'success_mod_tinkoff_hook',
    'message' => 'message_mod_tinkoff_hook'
);
?>