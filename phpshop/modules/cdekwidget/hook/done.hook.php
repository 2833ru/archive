<?php

/**
 * Добавление данных в заказ, регистрация заказа в службе доставки
 * param obj $obj
 * param array $row
 * param string $rout
 */
function send_to_order_cdekwidget_hook($obj, $row, $rout)
{
    include_once 'phpshop/modules/cdekwidget/class/CDEKWidget.php';
    $CDEKWidget = new CDEKWidget();


    if(in_array($_POST['d'], @explode(",", $CDEKWidget->option['delivery_id'])))
    {
        if(!empty($_POST['cdekInfo']))
        {
            if ($rout == 'START') {
                $obj->delivery_mod = number_format($_POST['cdekSum'], 0, '.', ' ');
                $obj->manager_comment = $_POST['cdekInfo'];
                $obj->set('deliveryInfo', $_POST['cdekInfo']);
                $_POST['cdek_order_data_new'] = serialize(array(
                    'type'         => $_POST['cdek_type'],
                    'city_id'      => $_POST['cdek_city_id'],
                    'cdek_pvz_id'  => $_POST['cdek_pvz_id'],
                    'tariff'       => $_POST['cdek_tariff']
                ));
            }

            if ($rout == 'MIDDLE' and $CDEKWidget->option['status'] == 0) {

                $CDEKWidget->setDataFromDoneHook($obj, $row);
                $CDEKWidget->setProducts($obj->PHPShopCart->getArray(), $obj->discount);
                $CDEKWidget->Request();
                $_POST['cdek_order_data_new'] = '';
            }
        }
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_cdekwidget_hook'
);
?>