<?php

function promotions_write($obj, $data, $rout) {

    if ($rout == 'START' and !empty($_SESSION['promocode'])) {

        // Отключение промокода одноразового
        if ($_SESSION['codetip'] == 1) {
            if ($_SESSION['multicodes'] == 1) {
                $PHPShopOrm = new PHPShopOrm($obj->PHPShopModules->getParam("base.promotions.promotions_codes"));
            } else {
                $PHPShopOrm = new PHPShopOrm($obj->PHPShopModules->getParam("base.promotions.promotions_forms"));
            }
            $PHPShopOrm->debug = false;
            $PHPShopOrm->update(array('enabled_new' => 0), array('code' => '="' . $_SESSION['promocode'] . '"'));
        }

        // Флаер
        $PHPShopOrm = new PHPShopOrm($obj->PHPShopModules->getParam("base.promotions.promotions_forms"));
        $data_promo = $PHPShopOrm->select(array('header_mail', 'content_mail'), array('code' => '="' . $_SESSION['promocode'] . '"'), false, array('limit' => 1));

        // Почта
        if (!empty($data_promo['header_mail']) and !empty($data_promo['content_mail'])) {
            $PHPShopMail = new PHPShopMail($_POST['mail'], $obj->adminmail, $data_promo['header_mail'], '', true, true);
            $obj->set('message', $data_promo['content_mail']);
            $PHPShopMail->sendMailNow(ParseTemplateReturn('./phpshop/lib/templates/order/blank.tpl', true));
        }
    }
}

function promotions_mail($obj, $data, $rout) {

    if ($rout == 'START') {

        // Бесплатная доставка
        if ($_SESSION['freedelivery'] == 0) {
            $obj->delivery = 0;
        }


        // Обновление цен в корзине
        $sum = $sum_promo = 0;
        $sum_check = false;
        foreach ($_SESSION['cart'] as $key => $product) {

            // Сумма
            if (!empty($product['promo_sum']) and !$sum_check) {

                // Цена без скидки
                $_SESSION['cart'][$key]['promo_price'] = $product['price'];

                if ($product['price'] > $product['promo_sum']) {
                    $_SESSION['cart'][$key]['price'] = $product['price'] - $product['promo_sum'] / $product['num'];
                    $_SESSION['cart'][$key]['name'].=' ['.__('скидка').' ' . $product['promo_sum'] / $product['num'] . ' ' . $obj->currency . ']';
                    $sum_check = true;
                    
                    $sum_promo+=$product['num'] * $_SESSION['cart'][$key]['price'];
                }
                
                
            }
            // Процент от суммы
            else if (!empty($product['promo_percent'])) {

                // Цена без скидки
                $_SESSION['cart'][$key]['promo_price'] = $product['price'];

                $_SESSION['cart'][$key]['price'] = $product['price'] - ($product['price'] * $product['promo_percent'] / 100);
                $_SESSION['cart'][$key]['name'].=' ['.__('скидка').' ' . $product['promo_percent'] . '%]';

                $sum_promo+=$product['num'] * $_SESSION['cart'][$key]['price'];
            }
            
            // Остальные
            else {
                 $sum+=$product['num'] * $product['price'];
            }
        }
        
        // Пересчет итоговой стоимости
        $obj->sum = $obj->PHPShopOrder->returnSumma($sum_promo)+$obj->PHPShopOrder->returnSumma($sum, $obj->discount);
        $obj->total = $obj->sum + $obj->delivery;
        
        unset($_SESSION['totalsummainput']);
    }
}

$addHandler = array
    (
    'write' => 'promotions_write',
    'mail' => 'promotions_mail',
);
?>