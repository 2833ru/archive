<?php
/**
 * Добавление данных в заказ, регистрация заказа в службе доставки
 * param obj $obj
 * param array $row
 * param string $rout
 */
function send_to_order_shoplogistics_hook($obj, $row, $rout)
{
    include_once 'phpshop/modules/shoplogisticswidget/class/ShopLogistics.php';
    $ShopLogistics = new ShopLogistics();


    if(in_array($_POST['d'], @explode(",", $ShopLogistics->option['delivery_id'])))
    {
        if(!empty($_POST['shoplogisticsInfo']))
        {
            if ($rout === 'START') {
                $obj->delivery_mod = number_format($_POST['DeliverySum'], 0, '.', ' ');
                $obj->manager_comment = $_POST['shoplogisticsInfo'];
                $obj->set('deliveryInfo', $_POST['shoplogisticsInfo']);
                $dateObj = new DateTime();

                if(strpos($_POST['shoplogistics_delivery_date'], '-') !== false) {
                    $date = explode('-', $_POST['shoplogistics_delivery_date']);
                    $max_date = $date[1];
                }
                else
                    $max_date = $_POST['shoplogistics_delivery_date'];

                $dateObj->modify('+' . $max_date . ' day');
                $_POST['shoplogistics_order_data_new'] = serialize(array(
                    'pvz_id'        => $_POST['shoplogistics_pvz_id'],
                    'pvz_address'   => $_POST['shoplogistics_pvz_adress'],
                    'delivery_date' => $dateObj->format('Y-m-d')
                ));
            }

            if ($rout === 'MIDDLE' and $ShopLogistics->option['status'] == 0) {

                $ShopLogistics->setDataFromDoneHook($obj, $row);
                $ShopLogistics->Request();
                $_POST['shoplogistics_order_data_new'] = '';
            }
        }
    }
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_shoplogistics_hook'
);
?>