<?php

/**
 * Добавление данных в заказ, регистрация заказа в службе доставки
 * param obj $obj
 * param array $row
 * param string $rout
 */
function send_to_order_boxberrywidget_hook($obj, $row, $rout)
{
    include_once 'phpshop/modules/boxberrywidget/class/BoxberryWidget.php';
    $BoxberryWidget = new BoxberryWidget();

    if(in_array($_POST['d'], @explode(",", $BoxberryWidget->option['delivery_id'])) or in_array($_POST['d'], @explode(",", $BoxberryWidget->option['express_delivery_id'])))
    {
        if(!empty($_POST['DeliverySum']))
        {
            if ($rout == 'START') {
                $obj->delivery_mod = number_format($_POST['DeliverySum'], 0, '.', '');
                $obj->manager_comment = $_POST['boxberryInfo'];
                $obj->set('deliveryInfo', $_POST['boxberryInfo']);
                $_POST['boxberry_pvz_id_new'] = $_POST['boxberry_pvz_id'];
            }

            if ($rout == 'MIDDLE' and $BoxberryWidget->option['status'] == 0) {

                $BoxberryWidget->setDataFromDoneHook($obj, $row, $_POST);
                $BoxberryWidget->setProducts($obj->PHPShopCart->getArray(), $obj->discount);

                if(in_array($_POST['d'], @explode(",", $BoxberryWidget->option['delivery_id'])))
                    $BoxberryWidget->parameters['vid'] = 1;
                else {
                    if(!empty($_POST['street_new']))
                        $street = ', ' . $_POST['street_new'];
                    else
                        $street = '';
                    if(!empty($_POST['house_new']))
                        $house = ', ' . $_POST['house_new'];
                    else
                        $house = '';
                    if(!empty($_POST['flat_new']))
                        $flat = ', ' . $_POST['flat_new'];
                    else
                        $flat = '';
                    $BoxberryWidget->parameters['vid'] = 2;
                    $BoxberryWidget->parameters['kurdost'] = array(
                        'index'    => $_POST['index_new'],
                        'citi'     => PHPShopString::win_utf8($_POST['city_new']),
                        'addressp' => PHPShopString::win_utf8($_POST['index_new'] . ', ' . $_POST['city_new'] . ', ' . $street . $house . $flat)
                );
                }

                $BoxberryWidget->request('ParselCreate');

                $_POST['boxberry_pvz_id_new'] = '';
            }
        }
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_boxberrywidget_hook'
);
?>