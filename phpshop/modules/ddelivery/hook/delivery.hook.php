<?php

if (substr(phpversion(), 0, 3) > 5.2) {
    include_once( $_SERVER['DOCUMENT_ROOT'] . '/phpshop/modules/ddelivery/class/application/bootstrap.php');
    include_once( $_SERVER['DOCUMENT_ROOT'] . '/phpshop/modules/ddelivery/class/mrozk/IntegratorShop.php' );
}

/**
 * Настройка модуля
 */
function search_ddelivery_delivery() {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['ddelivery']['ddelivery_system']);
    $data = $PHPShopOrm->select(array('settings'), array('id' => '=1'));
    if (!isset($data['settings']) || empty($data['settings'])) {
        $settings = array('self_way' => array(), 'courier_way' => array());
    } else {
        $settings = json_decode($data['settings'], true);
    }
    $dd = array_merge($settings['self_way'], $settings['courier_way']);
    return $dd;
}

/**
 * Хук
 */
function delivery_hook($obj, $data) {


    if (substr(phpversion(), 0, 3) > 5.2) {

        $_RESULT = $data[0];
        $xid = $data[1];

        $dd = search_ddelivery_delivery();


        if (is_array($dd) && in_array($xid, $dd)) {
            $ddID = (int) $_POST['order_id'];
            foreach ($dd as $val)
                if ($val == $xid)
                    $keyDelivery = $val;
            if ($ddID) {
                try {
                    $IntegratorShop = new IntegratorShop();
                    $ddeliveryUI = new \DDelivery\DDeliveryUI($IntegratorShop, true);
                    $order = $ddeliveryUI->initOrder($ddID);
                    $deliveryPrice = $ddeliveryUI->getOrderClientDeliveryPrice($order);
                    $hook['delivery'] = $deliveryPrice;
                    $hook['total'] = $_RESULT['total'] + $deliveryPrice;
                } catch (\DDeliveryException $e) {
                    $ddeliveryUI->logMessage($e);
                }
            }
            $hook['adresList'] = $_RESULT['adresList'];
            $hook['dellist']=$_RESULT['dellist'];
            $hook['hook']='DDeliveryIntegration.openPopup();';

            return $hook;
        }
    }
}

$addHandler = array(
    'delivery' => 'delivery_hook'
);
?>
