<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));


class AddToTemplateRegionElement extends PHPShopElements {

    var $debug = false;

    function display() {
        global $PHPShopModules;

            $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.promotions.promotions_forms"));
            $PHPShopOrm->debug=false;
            $where['code'] = '="'.PHPShopSecurity::TotalClean(trim('*')).'"';
            $where['enabled'] = '="1"';
            $GLOBALS['promotionslist'] = $promotionslist = $PHPShopOrm->select(array('*'),$where,array('order'=>'id'));

            //где код не пустой
            $whereCode['code'] = '!="*"';
            $whereCode['enabled'] = '="1"';
            $GLOBALS['promotionslistCode'] = $promotionslistCode = $PHPShopOrm->select(array('*'),$whereCode,array('order'=>'id'),array('limit'=>'300'));

    }

}

$AddToTemplateRegionElement = new AddToTemplateRegionElement();
$AddToTemplateRegionElement->display();
?>