<?php

function ordercartforma_hook($val, $option, $rout) {
    if ($rout == 'START') {

        $PHPShopProduct = new PHPShopProduct($val['id']);
        PHPShopParser::set('cart_image', $PHPShopProduct->getParam('pic_small'));
        PHPShopParser::set('cart_id', $val['id']);
        PHPShopParser::set('cart_xid', $option['xid']);
        PHPShopParser::set('cart_name', $val['name']);
        PHPShopParser::set('cart_num', $val['num']);
        PHPShopParser::set('cart_price', $val['price']);

        return ParseTemplateReturn('./phpshop/templates/' . $_SESSION['skin'] . '/order/product.tpl', true);
    }
}

function order_first_order_check($obj, $data, $rout) {

    if ($rout == 'END') {
        $cart_min = $obj->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');

        // Сообщение о регистрации
        $mes = $obj->lang('order_registration');
        if (empty($mes))
            $mes = 'Требуется обязательная регистрация пользователя';

        if (!empty($_SESSION['UsersId'])) {
            $PHPShopOrm = new PHPShopOrm($obj->objBase);

            $data = $PHPShopOrm->select(array('id'), array('user' => '=' . $_SESSION['UsersId']), false, array('limit' => 1));

            if (empty($data['id']) and $cart_min > $obj->PHPShopCart->getSum(false)) {
                $obj->set('orderContent', $obj->message($obj->lang('cart_minimum') . ' ' . $cart_min, $obj->lang('bad_order_mesage_2')));
            } else {
                $obj->set('orderContent', parseTemplateReturn($obj->template_order_forma));
            }
        } else {
            $obj->set('orderContent', PHPShopText::notice($mes));
        }
    }
}

$addHandler = array
    (
    '#ordercartforma' => 'ordercartforma_hook',
    '#order' => 'order_first_order_check'
);
?>