<?php

function send_to_order_russianpostcalc_hook($obj, $row, $rout) {

    if ($rout == 'START') {

        // API
        include_once 'phpshop/modules/russianpostcalc/class/russianpostcalc.class.php';
        $russianpostcalc = new russianpostcalc();
        $option = $russianpostcalc->option();

        if ($_POST['d'] == $option['delivery_id'] and strlen($_POST['index_new']) == 6) {

            // Запрос
            $weight = $obj->PHPShopCart->getWeight() / 1000;
            $ret = $russianpostcalc->russianpostcalc_api_calc($option['key'], $option['password'], $option['delivery_index'], $_POST['index_new'], $weight, intval($obj->PHPShopCart->getSum(false) * $option['cennost']) / 100);

            // Тип посылки
            if ($option['type'] == 1 and $ret['calc'][0]['type'] == 'rp_1class')
                $obj->delivery_mod = $ret['calc'][0]['cost'];
            else
                $obj->delivery_mod = $ret['calc'][1]['cost'];

            // Наценка
            if (!empty($option['fee'])) {
                if ($option['fee_type'] == 1)
                    $obj->delivery_mod = $obj->delivery_mod + ($obj->delivery_mod * $option['fee'] / 100);
                else
                    $obj->delivery_mod = $obj->delivery_mod + $option['fee'];
            }
        }
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_russianpostcalc_hook'
);
?>