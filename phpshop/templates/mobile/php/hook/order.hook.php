<?php

function delivery_mob_hook() {
    $dis = null;
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
    $data = $PHPShopOrm->select(array('*'), array('enabled' => "='1'", 'is_folder' => "='0'"), array('order' => 'price'), array('limit' => 100));
    if (is_array($data))
        foreach ($data as $row) {
            $dis.='<li class="table-view-cell"> <a class="navigate-right" href="#modalDelivery" ontouchstart="setDelivery(\'' . $row['price'] . '\',' . $row['id'] . ')" onclick="setDelivery(\'' . $row['price'] . '\',' . $row['id'] . ')">' . $row['city'] . '</a> </li>';
        }
    return $dis;
}

/**
 * Выбор способа оплаты
 */
function payment_mob_hook() {
    $dis = null;
    PHPShopObj::loadClass('payment');
    $PHPShopPayment = new PHPShopPaymentArray();
    $Payment = $PHPShopPayment->getArray();
    if (is_array($Payment))
            foreach ($Payment as $row)
                if(!empty($row['enabled']) or $row['path'] == 'modules')
                $dis.='<li class="table-view-cell"> <a class="navigate-right" href="#modalPayment" ontouchstart="setPayment(\'' . $row['name'] . '\',' . $row['id'] . ')" onclick="setPayment(\'' . $row['name'] . '\',' . $row['id'] . ')">' . $row['name'] . '</a> </li>';

    return $dis;
}

function mob_hook_getDeliveryDefaultId() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
        $row=$PHPShopOrm->select(array('id'),array('flag'=>"='1'",'enabled'=>"='1'"),false,array('limit'=>1));
        return $row['id'];
    }

function productcartforma_hook($obj, $var, $rout) {
    global $PHPShopOrder;
    if ($rout == 'START') {
        
        // Активная закладка
        $obj->set('cart_active','active');

        $obj->set('currency', $PHPShopOrder->default_valuta_code);
        $cart = $obj->PHPShopCart->display('ordercartforma');
        $obj->set('display_cart', $cart);
        $obj->set('cart_num', $obj->PHPShopCart->getNum());
        $obj->set('cart_sum', $obj->PHPShopCart->getSum(false));
        $obj->set('discount', $PHPShopOrder->ChekDiscount($obj->PHPShopCart->getSum()));
        $obj->set('cart_weight', $obj->PHPShopCart->getWeight());

        // Стоимость доставки
        PHPShopObj::loadClass('delivery');
        
        $obj->set('delivery_price', intval(PHPShopDelivery::getPriceDefault()));
        $obj->set('delivery_id',mob_hook_getDeliveryDefaultId());

        $obj->set('delivery', delivery_mob_hook());
        $obj->set('payment',payment_mob_hook());

        // Итоговая стоимость
        $obj->set('total', $PHPShopOrder->returnSumma($obj->get('cart_sum') + $obj->get('delivery_price'), $obj->get('discount')));

        return ParseTemplateReturn('./phpshop/templates/' . $_SESSION['skin'] . '/order/cart.tpl', true);
    }
}

function error_mob_hook($obj){
    
    $obj->set('orderMesage', '<button onclick="history.back()" class="btn btn-negative btn-block btn-outlined"><span class="icon icon-info"></span> '.$obj->lang('bad_cart_1').'</button>');
}


function id_edit_mob_hook($obj){
    $PHPShopCart = new PHPShopCart();
    $obj->set('cart_active_num', '<span class="badge badge-positive">'.$PHPShopCart->getNum().'</span>');
}

function order_mob_hook($obj,$row,$rout){
    if($rout == 'START' and isset($_GET['from']))
        header('Location: ./');
    if ($rout == 'END') {
        
        $order_modals=PHPShopParser::file('./phpshop/templates/mobile/order/order_forma_modal.tpl',true);
        PHPShopParser::set('order_modals',$order_modals);
        
        return true;
    }
}

$addHandler = array
    (
    'product' => 'productcartforma_hook',
    'error'=>'error_mob_hook',
    'index'=>'id_edit_mob_hook',
    'order'=>'order_mob_hook'
);
?>