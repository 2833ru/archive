<?php

/**
 * Внедрение js функции
 *
 * param object $obj
 * param array $data
 */
function boxberrywidget_delivery_hook($obj, $data) {

    $_RESULT = $data[0];
    $xid = $data[1];

    // API
    include_once '../modules/boxberrywidget/class/BoxberryWidget.php';
    $BoxberryWidget = new BoxberryWidget();

    if(in_array($xid, @explode(",", $BoxberryWidget->option['delivery_id']))) {
        $hook['dellist'] = $_RESULT['dellist'];
        $hook['hook'] = 'boxberrywidgetStart();';
        $hook['delivery'] = $_RESULT['delivery'];
        $hook['total'] = $_RESULT['total'];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['success'] = 1;

        return $hook;

    }elseif(in_array($xid, @explode(",", $BoxberryWidget->option['express_delivery_id'])) and strlen($_POST['zip']) == 6) {
        $BoxberryPrice = $BoxberryWidget->getCourierPrice($_POST['zip'], $_POST['weight'], $_POST['depth'], $_POST['height'], $_POST['width']);

        if(!empty($BoxberryPrice['error'])) {
            $hook['success'] = 'indexError';
            $hook['message'] = PHPShopString::win_utf8($BoxberryPrice['error']);

            return $hook;
        } else {
            $hook['delivery'] = $BoxberryPrice;
            $hook['total'] = $_RESULT['total'] + $hook['delivery'];
            $hook['dellist'] = $_RESULT['dellist'];
            $hook['hook'] = '';
            $hook['adresList'] = $_RESULT['adresList'];
            $hook['success'] = 'index';
            $hook['message'] = PHPShopString::win_utf8('Стоимость курьерской доставки составит ' . $hook['delivery'] . ' руб.');

            return $hook;
        }
    } elseif (in_array($xid, @explode(",", $BoxberryWidget->option['express_delivery_id'])) and !empty($_POST['zip']) and (strlen($_POST['zip']) > 6 or strlen($_POST['zip']) < 6)) {
        $hook['success'] = 'indexError';
        $hook['message'] = PHPShopString::win_utf8('Введите корректный индекс получателя');

        return $hook;
    }
}

$addHandler = array
    (
    'delivery' => 'boxberrywidget_delivery_hook'
);
?>
