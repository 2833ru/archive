<?php

/**
 * Добавление поля промоакции
 */
function order_promotions_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {
        global $promotionslistCode;


        if(isset($promotionslistCode)) {
            foreach ($promotionslistCode as $key => $value) {
                if($value['code_check']==1) {
                    $code_check = 1;
                    break;
                }
                else {
                    $code_check = 0;
                }
            }
        }

        if($code_check==1) {

            $html = PHPShopParser::file('./phpshop/modules/promotions/templates/order/cart_input.tpl', true, false, true);
        }


        $order_action_add = '
        <script>
        // Promotions PHPShop Module
        $(document).ready(function() {
            $(\'' . $html . '\').insertAfter(".img_fix");
        });</script><script src="phpshop/modules/promotions/js/promotions-main.js"></script>';


        if($_GET['promoselect']!='yes')
            $order_action_add .= '<script>setInterval(UpdatePromotion("*"), 1000);</script>';

        unset($_SESSION['discpromo']);
        unset($_SESSION['freedelivery']);
        unset($_SESSION['tip_disc']);
        unset($_SESSION['totalsumma']);
        unset($_SESSION['promocode']);
        unset($_SESSION['codetip']);
        unset($_SESSION['discpromo']);

        // Добавляем JS в форму заказа
        $obj->set('order_action_add', $order_action_add, true);
    }
}

$addHandler = array
    (
    'order' => 'order_promotions_hook'
);
?>