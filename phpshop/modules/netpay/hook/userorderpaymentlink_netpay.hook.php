<?php

function userorderpaymentlink_mod_netpay_hook($obj, $PHPShopOrderFunction) {

    // Настройки модуля
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    include_once('phpshop/modules/netpay/class/netpay.class.php');
    $PHPShopDeltaArray = new PHPShopNetPayArray();
    $option = $PHPShopDeltaArray->getArray();


    // Контроль оплаты от статуса заказа
    if ($PHPShopOrderFunction->getParam('statusi') == $option['status']) {
        // Номер счета
        $mrh_ouid = explode("-", $PHPShopOrderFunction->objRow['uid']);
        $inv_id = $mrh_ouid[0] . "" . $mrh_ouid[1];

        // Сумма покупки
        $out_summ = $PHPShopOrderFunction->getTotal();


        $params = array();
        $params['description'] = "New Order № $inv_id";
        $params['amount'] = $out_summ;
        $params['currency'] = 'RUB';
        $params['orderID'] = $inv_id;
        $params['phone'] = '';
        $params['email'] = '';
        $params['successUrl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/success/?from=netpay';
        $params['failUrl'] = 'http://' . $_SERVER['HTTP_HOST'] . '/fail/';

        $keys = array();
        $keys['api_key'] = $option['merchant_key'];
        $keys['auth_signature'] = $option['merchant_skey'];

        $settings = array();
        $settings['expiredtime'] = intval($option['expiredtime']);

        $settings['autosubmit'] = '0';
        $settings['target'] = '0';

        $settings['submitval'] = 'Оплатить сейчас';

        $netpay = new Netpay();
        $link =  $netpay->getlink($params, $keys, $settings); 
        $return = PHPShopText::a($link,$settings['submitval'],$settings['submitval'], false, false, '_blank', 'btn btn-success pull-right');
        

    } elseif ($PHPShopOrderFunction->getSerilizeParam('orders.Person.order_metod') == 10017)
        $return = ', Заказ обрабатывается менеджером';

    return $return;
}

$addHandler = array('userorderpaymentlink' => 'userorderpaymentlink_mod_netpay_hook');
?>