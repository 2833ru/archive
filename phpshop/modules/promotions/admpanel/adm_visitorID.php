<?php

function addCartPromotions($data) {
    global $PHPShopGUI;

    //узнаем, применен ли промокод
    $orders = unserialize($data['orders']);
    $promocode = $orders['Person']['promocode'];
    
    if($promocode=='*'):
        // Корзина
        $Tab5 = $PHPShopGUI->loadLib('tab_cart_newo', $data, '../../phpshop/modules/promotions/admpanel/');
        $PHPShopGUI->addTab(array("Корзина",$Tab5,350));
    elseif($promocode!=''):
	    // Корзина
	    $Tab5 = $PHPShopGUI->loadLib('tab_cart_new', $data, '../../phpshop/modules/promotions/admpanel/');
	    $PHPShopGUI->addTab(array("Корзина промо-акции",$Tab5,350));
    endif;
}


$addHandler=array(
        'actionStart'=>'addCartPromotions',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>