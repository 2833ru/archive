<?php

function send_to_order_mod_netpay_hook($obj, $value, $rout) {

    if ($rout == 'END' and $value['order_metod'] == 10017) {

        // Настройки модуля
        include_once(dirname(__FILE__) . '/mod_option.hook.php');
        include_once('phpshop/modules/netpay/class/netpay.request.php');
        $PHPShopNetPayArray = new PHPShopNetPayArray();
        $option = $PHPShopNetPayArray->getArray();

        // При активной системе оплаты
        if (empty($option['status'])) {

            // Номер счета
            $mrh_ouid = explode("-", $value['ouid']);
            $inv_id = $mrh_ouid[0] . $mrh_ouid[1];
			
			// Тестовый режим или рабочий
			$is_test = ($option['work'] != 1); 

            // Сумма покупки
            $out_summ = number_format($obj->get('total'), 2, '.', '');
			
			$products = $obj->PHPShopCart->_CART;
			$discount = $obj->get('discount');
			$delivery_title = $obj->get('deliveryCity');
			$delivery = $obj->get('deliveryPrice');
			
			$NetpayRequest = new NetpayRequest(
				$option['apikey'], $option['auth'],
				$inv_id, $out_summ, $option['hold'], $is_test, 
				$option['online_bill'], $option['inn'], $option['tax'], $products,
				$discount, $delivery, $delivery_title,
				$option['expiredtime'], $option['autosubmit'], 'Оплатить сейчас'
				);
			$link = $NetpayRequest->getButton();
			
            $obj->set('payment_forma', $link);
            $obj->set('payment_info', $option['title']);
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['netpay']['netpay_payment_form'], true);
        }
        else {
            $obj->set('mesageText', $option['title_sub'] );
            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['order_forma_mesage']);
        }

        $obj->set('orderMesage', $forma);
    }
}

$addHandler = array('send_to_order' => 'send_to_order_mod_netpay_hook');
?>