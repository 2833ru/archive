<?php

function send_to_order_ddeliverywidget_hook($obj, $row, $rout) {

    // API
    include_once 'phpshop/modules/ddeliverywidget/class/ddeliverywidget.class.php';
    $ddeliverywidget = new ddeliverywidget();
    $option = $ddeliverywidget->option();
    $sessionId = $_POST['ddeliveryToken'];
    $apiKey = $option['key'];

    // Библиотека
    $helper = new DDeliveryHelper($apiKey, false);


    if (in_array($_POST['d'], @explode(",", $option['delivery_id'])) and !empty($_POST['ddeliverySum'])) {

        if ($rout == 'START') {

            $obj->delivery_mod = number_format($_POST['ddeliverySum'], 0, '.', ' ');

            // Token
            $_POST['ddelivery_token_new'] = $sessionId;

            $ddelivery_info = json_fix_utf($helper->getOrder($sessionId));

            // Заполняем данные
            if ($ddelivery_info['success'] == 1) {

                // ПВЗ
                if (!empty($ddelivery_info['data']['to_street'])){
                    $_POST['street_new'] = $ddelivery_info['data']['to_street'];
                    $_POST['flat_new'] = $ddelivery_info['data']['to_flat'];
                    $_POST['house_new'] = $ddelivery_info['data']['to_house'];
                    $_POST['city_new'] = $ddelivery_info['data']['city'];
                }
                // Point
                else{
                    $_POST['street_new'] = $ddelivery_info['data']['company_info']['address'];
                    $_POST['city_new'] = $ddelivery_info['data']['company_info']['city'];
                }

                
            }

            // Информация по доставке в комментарий заказа
            $obj->manager_comment = $_POST['ddeliveryReq'];
            $obj->set('deliveryInfo', $_POST['ddeliveryReq']);
        }


        if ($rout == 'MIDDLE' and $option['status'] == 0) {

            $params = array(
                'session' => $sessionId,
                'to_name' => PHPShopString::win_utf8($obj->get('user_name')),
                'to_phone' => '+7' . str_replace(array('(', ')', ' ', '+', '-'), '', $_POST['tel_new']),
                'shop_refnum' => $obj->ouid,
                'to_email' => $obj->get('mail'),
                //'payment_price' =>$obj->total,
                'comment' => PHPShopString::win_utf8($_POST['dop_info']),
                'to_flat' => PHPShopString::win_utf8($_POST['flat_new']),
                'to_street' => PHPShopString::win_utf8($_POST['street_new']),
                'to_house' => PHPShopString::win_utf8($_POST['house_new']),
                'payment_variant' => PHPShopString::win_utf8($obj->get('payment'))
            );

            $helper->sendOrder($sessionId, $params);
        }
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_ddeliverywidget_hook'
);
?>