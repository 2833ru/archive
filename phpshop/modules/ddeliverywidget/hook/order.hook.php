<?php

/**
 * Добавление jss
 */
function order_ddeliverywidget_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {

        // API
        include_once 'phpshop/modules/ddeliverywidget/class/ddeliverywidget.class.php';
        $ddeliverywidget = new ddeliverywidget();
        $option = $ddeliverywidget->option();

        // Список товаров
        $PHPShopCart = new PHPShopCart();
        $cart = $PHPShopCart->getArray();

        if (is_array($cart))
            foreach ($cart as $val) {
                $list[] = array('id' => $val['id'], 'name' => PHPShopString::win_utf8($val['name']), 'price' => $val['price'], 'weight' => floatval($val['weight']/1000), 'quantity' => $val['num'], 'sku' => $val['uid']);
            }


        $obj->set('order_action_add', '
            <script src="//sdk.ddelivery.ru/assets/ddelivery.js"></script>
            <script src="phpshop/modules/ddeliverywidget/js/ddeliverywidget.js"></script>
            
        <input class="cartListJson" type="hidden" value=\'' . json_encode($list) . '\'/>
        <input id="ddeliveryId" type="hidden" value="' . $option['shop_id'] . '">
            
        <!-- Модальное окно ddeliverywidget -->
        <div class="modal fade bs-example-modal" id="ddeliverywidgetModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Доставка</h4>
                    </div>
                    <div class="modal-body" style="height:600px;width:500px">
                        
                         <div id="widget"></div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="ddelivery-close">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Модальное окно ddeliverywidget -->
            
', true);
    }
}

$addHandler = array
    (
    'order' => 'order_ddeliverywidget_hook'
);
?>