<?php

/**
 * Добавление js
 * param obj $obj
 * param array $row
 * param string $rout
 */
function order_boxberrywidget_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {

        include_once 'phpshop/modules/boxberrywidget/class/BoxberryWidget.php';
        $BoxberryWidget = new BoxberryWidget();

        $PHPShopCart = new PHPShopCart();
        $weight = $PHPShopCart->getWeight();
        if(empty($weight))
            $weight = $BoxberryWidget->option['weight'];

        $obj->set('order_action_add', '
<input type="hidden" id="boxberryApiKey" value="' . $BoxberryWidget->option[api_key] . '">
<input type="hidden" id="boxberryCity" value="' . $BoxberryWidget->option[city] . '">
<input type="hidden" id="boxberryCartWeight" value="' . $weight . '">
<input type="hidden" id="boxberryCartDepth" value="' . $BoxberryWidget->option[depth] . '">
<input type="hidden" id="boxberryCartHeight" value="' . $BoxberryWidget->option[height] . '">
<input type="hidden" id="boxberryCartWidth" value="' . $BoxberryWidget->option[width] . '">
<script type="text/javascript" src="//points.boxberry.ru/js/boxberry.js" /></script><script type="text/javascript" src="phpshop/modules/boxberrywidget/js/boxberrywidget.js" /></script>', true);


    $obj->set('UserAdresList', '<input type="hidden" name="DeliverySum" value=""><input type="hidden" name="boxberryInfo" value=""><input type="hidden" name="boxberry_pvz_id" value="">', true);
    }
}

$addHandler = array
    (
    'order' => 'order_boxberrywidget_hook'
);
?>