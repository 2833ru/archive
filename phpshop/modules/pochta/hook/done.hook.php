<?php

include_once dirname(__DIR__) . '/class/include.php';

function send_to_order_pochta_hook($obj, $row, $route) {

    $Pochta = new Pochta();

    if($Pochta->isCourier((int) $_POST['d']) || $Pochta->isPostOffice((int) $_POST['d'])) {

        if ($route === 'START') {
            $obj->delivery_mod = round($_POST['pochta_delivery_cost'], $Pochta->settings->format);
        }

        if ($route === 'END' && (int) $Pochta->settings->get('status') === 0) {
            $orm = new PHPShopOrm('phpshop_orders');
            $order = $orm->getOne(array('*'), array('uid' => "='" . $obj->ouid . "'"));
            if(is_array($order)) {
                $Pochta->send($order);
            }
        }
    }
}

$addHandler = array ('send_to_order' => 'send_to_order_pochta_hook');