<?php

function send_to_order_ddeliverywidget_hook($obj, $row, $rout) {

    // API
    include_once 'phpshop/modules/ddeliverywidget/class/ddeliverywidget.class.php';
    $ddeliverywidget = new ddeliverywidget();
    $option = $ddeliverywidget->option();
    $sessionId = $_POST['ddeliveryToken'];
    $apiKey = $option['key'];

    // Библиотека
    $helper = new DDeliveryHelper($apiKey);


    if (in_array($_POST['d'], @explode(",", $option['delivery_id'])) and !empty($_POST['ddeliverySum'])) {

        if ($rout == 'START') {

            $obj->delivery_mod = number_format($_POST['ddeliverySum'], 0, '.', ' ');

            // Token
            $_POST['ddelivery_token_new'] = $sessionId;

            // Информация по доставке в комментарий заказа
            $obj->manager_comment = $_POST['ddeliveryReq'];
            $obj->set('deliveryInfo', $_POST['ddeliveryReq']);
        }


        if ($rout == 'MIDDLE' and $option['status'] == 0) {

            $params = array(
                'id' => $sessionId,
                'cms_id' => $obj->ouid,
                'payment_method' => PHPShopString::win_utf8($obj->get('payment'))
            );

            $result = $helper->sendOrder($params);
            if($result['status'] == 'ok')
                $_POST['ddelivery_token_new']=0;
            
        }
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_ddeliverywidget_hook'
);
?>