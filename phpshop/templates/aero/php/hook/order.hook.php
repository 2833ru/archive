<?php
function ordercartforma_hook($val,$option,$rout) {
    if($rout == 'START') {
        $PHPShopProduct = new PHPShopProduct($val['id']);
        PHPShopParser::set('cart_image',$PHPShopProduct->getParam('pic_small'));
        PHPShopParser::set('cart_id',$val['id']);
        PHPShopParser::set('cart_xid',$option['xid']);
        PHPShopParser::set('cart_name',$val['name']);
        PHPShopParser::set('cart_num',$val['num']);
        PHPShopParser::set('cart_price',$val['price']);

        return ParseTemplateReturn('./phpshop/templates/'.$_SESSION['skin'].'/order/product.tpl',true);
    }
}

function productcartforma_hook($obj,$var,$rout) {
    global $PHPShopOrder;
    if($rout == 'START') {

        $obj->set('currency',$PHPShopOrder->default_valuta_code);
        $cart=$obj->PHPShopCart->display('ordercartforma');
        $obj->set('display_cart',$cart);
        $obj->set('cart_num',$obj->PHPShopCart->getNum());
        $obj->set('cart_sum',$obj->PHPShopCart->getSum(false));
        $obj->set('discount',$PHPShopOrder->ChekDiscount($obj->PHPShopCart->getSum()));
        $obj->set('cart_weight',$obj->PHPShopCart->getWeight());

        // Стоимость доставки
        PHPShopObj::loadClass('delivery');
        $obj->set('delivery_price',PHPShopDelivery::getPriceDefault());

        // Итоговая стоимость
        $obj->set('total',$PHPShopOrder->returnSumma($obj->get('cart_sum')+$obj->get('delivery_price'),$obj->get('discount')) );

        return ParseTemplateReturn('./phpshop/templates/'.$_SESSION['skin'].'/order/cart.tpl',true);
    }
}

$addHandler=array
        (
    'ordercartforma'=>'ordercartforma_hook',
    '#product'=>'productcartforma_hook'

);
?>