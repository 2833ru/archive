<?php

/**
 * Добавление поля промоакции
 */
function order_rewardpoints_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {
        $obj->PHPShopUserBBc= new PHPShopUser($_SESSION['UsersId']);
        $balanceP = $obj->PHPShopUserBBc->getParam('point');
        if($balanceP=='')
            $balanceP = 0;


        if($balanceP>0) {
            if($_SESSION['UsersId']!='') {
                $html = PHPShopParser::file('./phpshop/modules/rewardpoints/templates/order/okpoints.tpl', true, false, true);
            }
        }
        

        $order_action_add = '<script>
        // Rewardpoints PHPShop Module
        $(document).ready(function() {
            $(\'' . $html . '\').insertAfter(".img_fix");
        });</script>
        <script src="phpshop/modules/rewardpoints/js/points-main.js"></script>
        <link rel="stylesheet" type="text/css" href="phpshop/modules/rewardpointscss/rewardpoints.css">';

        if($_SESSION['UsersId']!='')
            $order_action_add .= '<script>setInterval(UpdateRewardpoints(), 1000);</script>';

        //Убираем сессии при загрузке корзины
        unset($_SESSION['pointOk']);
        unset($_SESSION['sumitog']);

        // Добавляем JS в форму заказа
        $obj->set('order_action_add', $order_action_add, true);
    }
}


$addHandler = array
    (
    'order' => 'order_rewardpoints_hook'
);
?>