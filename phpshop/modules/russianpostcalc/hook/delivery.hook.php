<?php

/**
 * Хук
 */
function russianpostcalc_delivery_hook($obj, $data) {

    $_RESULT = $data[0];
    $xid = $data[1];

    // API
    include_once '../modules/russianpostcalc/class/russianpostcalc.class.php';
    $russianpostcalc = new russianpostcalc();
    $option = $russianpostcalc->option();
    $PHPShopOrder = new PHPShopOrderFunction();

    if ($xid == $option['delivery_id'] and strlen($_POST['index']) == 6) {

        // Запрос
        $ret = $russianpostcalc->russianpostcalc_api_calc($option['key'], $option['password'], $option['delivery_index'], $_POST['index'], floatval($_RESULT['wsum']), intval((float) str_replace(' ', '', $_RESULT['total']) * $option['cennost']) / 100);

        if (isset($ret['msg']['type']) and $ret['msg']['type'] == "done") {

            // Тип посылки
            if ($option['type'] == 1 and $ret['calc'][0]['type'] == 'rp_1class')
                $hook['delivery'] = $ret['calc'][0]['cost'];
            else
                $hook['delivery'] = $ret['calc'][1]['cost'];

            // Наценка
            if(!empty($option['fee'])){
                if($option['fee_type'] == 1)
                    $hook['delivery'] = $hook['delivery'] + ($hook['delivery']*$option['fee'] / 100);
                else $hook['delivery'] = $hook['delivery'] + $option['fee'];
            }

            $hook['total'] = $PHPShopOrder->returnSumma($_REQUEST['sum'], $PHPShopOrder->ChekDiscount($_REQUEST['sum']),' ', $hook['delivery'] );
            $hook['dellist'] = $_RESULT['dellist'];
            $hook['hook'] = '';
            $hook['adresList'] = $_RESULT['adresList'];
            $hook['success'] = 'index';
            $hook['message'] = PHPShopString::win_utf8('Стоимость доставки в город ' . PHPShopString::utf8_win1251($ret['info']['to_city']) . ' составит ' . $hook['delivery'] . ' руб.');
            $hook['city'] = $ret['info']['to_city'];

            return $hook;
        } else {
            $hook['message'] = $ret['msg']['text'];
            $hook['success'] = 'indexError';

            return $hook;
        }
    } elseif ($xid == $option['delivery_id'] and strlen($_POST['index']) > 6) {
        $hook['success'] = 'indexError';
        $hook['message'] = PHPShopString::win_utf8('Неверно введен индекс получателя!');

        return $hook;
    } elseif ($xid == $option['delivery_id'] and empty($_POST['index'])) {
        $hook['hook'] = '';
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['dellist'] = $_RESULT['dellist'];
        $hook['total'] = $_RESULT['total'];
        $hook['success'] = '1';

        return $hook;
    }
}

$addHandler = array
(
    'delivery' => 'russianpostcalc_delivery_hook'
);
?>
