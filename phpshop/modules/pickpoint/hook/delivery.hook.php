<?php

/**
 * Настройка модуля
 */
function pickpoin_option() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['pickpoint']['pickpoint_system']);
    return $PHPShopOrm->select();
}

/**
 * Поиск доставки по имени
 */
function search_pickpoin_delivery($city, $xid) {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
    $data = $PHPShopOrm->select(array('*'), array('city' => " REGEXP '" . $city . "'", 'id' => '=' . $xid, 'is_folder' => "!='1'"), false, array('limit' => 1));
    if (is_array($data))
        return $data;
}

/**
 * Хук
 */
function delivery_hook($obj, $data) {

    $_RESULT = $data[0];
    $xid = $data[1];

    $option = pickpoin_option();

    // ИД доставки pickpoin
    $pickpoin_delivery = search_pickpoin_delivery($option['city'], $xid);

    if (is_numeric($pickpoin_delivery['id']))
        if ($xid == $pickpoin_delivery['id']) {

            $hook['dellist']=$_RESULT['dellist'];
            $hook['hook']='PickPoint.open(pickpoint_phpshop);';
            $hook['delivery'] = $_RESULT['delivery'];
            $hook['total'] = $_RESULT['total'];
            $hook['adresList'] = $_RESULT['adresList'];

            return $hook;
        }
}

$addHandler = array
    (
    'delivery' => 'delivery_hook'
);
?>
