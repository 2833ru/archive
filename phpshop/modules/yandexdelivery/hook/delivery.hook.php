<?php

include_once dirname(__DIR__) . '/class/include.php';

/**
 * Внедрение js функции
 *
 * param object $obj
 * param array $data
 */
function yandexdelivery_delivery_hook($obj, $data) {

    $YandexDelivery = new YandexDelivery();

    if ($YandexDelivery->isYandexDeliveryMethod($data[1])) {

        $hook['dellist'] = $data[0]['dellist'];
        $hook['hook'] = 'yandexdeliveryStart();';
        $hook['delivery'] = $data[0]['delivery'];
        $hook['total'] = $data[0]['total'];
        $hook['adresList'] = $data[0]['adresList'];
        $hook['success'] = 1;

        return $hook;
    }
}

$addHandler = array('delivery' => 'yandexdelivery_delivery_hook');
?>