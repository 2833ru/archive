<?php

include_once dirname(__DIR__) . '/class/PickPoint.php';

function send_to_order_pikpoint_hook($obj, $row, $rout)
{
    $PickPoint = new PickPoint();

    if((int) $_POST['d'] === (int) $PickPoint->getPickpointDeliveryId()) {
        if(!empty($_POST['pickpoint_sum'])) {
            if ($rout === 'START') {
                $obj->delivery_mod = number_format($_POST['pickpoint_sum'], 0, '.', '');
                $_POST['pickpoint_data_new'] = serialize([
                    'pvz_id' => $_POST['pickpoint_id'],
                    'sent'   => false
                ]);
            }
            if ($rout === 'END' and $PickPoint->options['status'] == 0) {
                $orm = new PHPShopOrm('phpshop_orders');
                $order = $orm->getOne(['*'], ['uid' => "='" . $obj->ouid . "'"]);

                if(is_array($order)) {
                    $PickPoint->createOrder($order);
                }
            }
        }
    }
}

$addHandler = [
    'send_to_order' => 'send_to_order_pikpoint_hook'
];
?>