<?php

include_once dirname(__DIR__) . '/class/include.php';

function send_to_order_yandexdelivery_hook($obj, $row, $rout)
{
    $YandexDelivery = new YandexDelivery();

    if($YandexDelivery->isYandexDeliveryMethod((int) $_POST['d'])) {
        if(!empty($_POST['yadelivery_sum'])) {
            if ($rout === 'START') {
                $obj->delivery_mod = number_format($_POST['yadelivery_sum'], 0, '.', '');
                $obj->set('deliveryInfo', $_POST['yadelivery_info']);

                if(empty($_POST['fio_new'])) {
                    $_POST['fio_new'] = $_POST['name_new'];
                }

                $_POST['yadelivery_order_data_new'] = serialize([
                    'type'          => $_POST['yadelivery_type'],
                    'pvz_id'        => !empty($_POST['yadelivery_pvz_id']) ? $_POST['yadelivery_pvz_id'] : null,
                    'tariff_id'     => !empty($_POST['yadelivery_tariff_id']) ? $_POST['yadelivery_tariff_id'] : null,
                    'partner_id'    => !empty($_POST['yadelivery_partner_id']) ? $_POST['yadelivery_partner_id'] : null,
                    'status'        => YandexDelivery::STATUS_ORDER_PREPARED,
                    'status_text'   => 'ќжидает отправки',
                    'delivery_info' => $_POST['yadelivery_info']
                ]);
            }

            if ($rout === 'END' and $YandexDelivery->options['status'] == 0) {
                $orm = new PHPShopOrm('phpshop_orders');
                $order = $orm->getOne(array('*'), array('uid' => "='" . $obj->ouid . "'"));

                if(is_array($order)) {
                    $YandexDelivery->createOrder($order);
                }
            }
        }
    }
}

$addHandler = ['send_to_order' => 'send_to_order_yandexdelivery_hook'];
?>