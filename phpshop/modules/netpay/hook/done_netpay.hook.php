<?php

function send_to_order_mod_netpay_hook($obj, $value, $rout) {

    if ($rout == 'MIDDLE' and $value['order_metod'] == 10017) {

        // Настройки модуля
        include_once(dirname(__FILE__) . '/mod_option.hook.php');
        include_once('phpshop/modules/netpay/class/netpay.class.php');
        $PHPShopNetPayArray = new PHPShopNetPayArray();
        $option = $PHPShopNetPayArray->getArray();

        // Контроль оплаты от статуса заказа
        if (empty($option['status'])) {

            // Номер счета
            $mrh_ouid = explode("-", $value['ouid']);
            $inv_id = $mrh_ouid[0] . $mrh_ouid[1];

            // Сумма покупки
            $out_summ = number_format($obj->get('total'), 2, '.', '');

            $params = array();
            $params['description'] = "New Order № $inv_id";
            $params['amount'] = $out_summ;
            $params['currency'] = 'RUB';
            $params['orderID'] = $inv_id;
            $params['phone'] = '';
            $params['email'] = '';
            $params['successUrl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/success/?from=netpay';
            $params['failUrl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/fail/';

            // Холдирование
            if ($option['hold'] == 2)
                $params['isHold'] = 'true';

            $keys = array();
            $keys['api_key'] = $option['merchant_key'];
            $keys['auth_signature'] = $option['merchant_skey'];

            $settings = array();
            $settings['expiredtime'] = intval($option['expiredtime']);

            if ($option['autosubmit'] == 2)
                $settings['autosubmit'] = '0';
            else
                $settings['autosubmit'] = '1';

            $settings['target'] = '0';

            $settings['submitval'] = 'Оплатить сейчас';

            $netpay = new Netpay();
            $link = $netpay->getbutton($params, $keys, $settings);

            $obj->set('payment_forma', $link);
            $obj->set('payment_info', $option['title']);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['netpay']['netpay_payment_forma'], true);
        }
        else {
            $obj->set('mesageText', $option['title_sub']);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['order_forma_mesage']);
        }

        $obj->set('orderMesage', $forma);
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_mod_netpay_hook'
);
?>