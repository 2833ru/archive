<?php
function sendToOrderOzonRocketWidgetHook($obj, $row, $rout)
{
    include_once 'phpshop/modules/ozonrocket/class/OzonRocketWidget.php';
    include_once dirname(dirname(dirname(__DIR__))) . '/class/date.class.php';

    $OzonRocketWidget = new OzonRocketWidget();

    if ((int)$_POST['d'] === (int)$OzonRocketWidget->options['delivery_id']) {
        if (!empty($_POST['ozonrocketType'])) {
            if ($rout === 'START') {
                $obj->delivery_mod = number_format($_POST['ozonrocketSum'], 0, '.', '');
                $obj->set('deliveryInfo', $_POST['ozonrocketType'] . '. ' . $_POST['address']);

                $_POST['ozonrocket_order_data_new'] = serialize(array(
                    'delivery_type' => $_POST['ozonrocketType'],
                    'status'        => $OzonRocketWidget->options['status'],
                    'status_text'   => __('ќжидает отправки в Ozon'),
                    'address' => $_POST['address'],
                    'delivery_id' => $_POST['delivery_id']
                ));
            }

            if ($rout === 'END' && $OzonRocketWidget->options['status'] == 0) {
                $orm = new PHPShopOrm('phpshop_orders');
                $order = $orm->getOne(['*'], ['uid' => "='" . $obj->ouid . "'"]);
                if(is_array($order)) {
                    $OzonRocketWidget->send($order);
                }
            }
        }
    }
}

$addHandler = ['send_to_order' => 'sendToOrderOzonRocketWidgetHook'];