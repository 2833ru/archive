<?php

/**
 * Добавление js
 * param obj $obj
 * param array $row
 * param string $rout
 */
function order_shoplogisticswidget_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {

        include_once 'phpshop/modules/shoplogisticswidget/class/ShopLogistics.php';
        $ShopLogistics = new ShopLogistics();

        $PHPShopCart = new PHPShopCart();
        $weight = $PHPShopCart->getWeight();
        if(empty($weight))
            $weight = $ShopLogistics->option['weight'];
        if($ShopLogistics->option['dev_mode'] == 1) {
            $script = '<script type="text/javascript" src="https://test.client-shop-logistics.ru/index.php?route=widget/lkwidget/bootstart"></script>';
        } else {
            $script = '<script type="text/javascript" src="https://client-shop-logistics.ru/index.php?route=widget/lkwidget/bootstart"></script>';
        }

        $obj->set('order_action_add', '
<input type="hidden" id="shoplogisticsKey" value="' . $ShopLogistics->option[key] .'">
<input type="hidden" id="shoplogisticsCartWeight" value="' . floatval($weight/1000) .'">
<input type="hidden" id="shoplogisticsDefaultLength" value="' . $ShopLogistics->option[length] . '">
<input type="hidden" id="shoplogisticsDefaultWidth" value="' . $ShopLogistics->option[width] . '">
<input type="hidden" id="shoplogisticsDefaultHeight" value="' . $ShopLogistics->option[height] . '">
' . $script . '
</script><script type="text/javascript" src="phpshop/modules/shoplogisticswidget/js/shoplogisticswidget.js" /></script>
', true);

        $obj->set('UserAdresList', '<input type="hidden" name="DeliverySum" value=""><input type="hidden" name="shoplogisticsInfo" value=""><input type="hidden" name="shoplogistics_pvz_id" value=""><input type="hidden" name="shoplogistics_pvz_adress" value=""><input type="hidden" name="shoplogistics_delivery_date" value="">', true);
    }
}

$addHandler = array
    (
    'order' => 'order_shoplogisticswidget_hook'
);
?>