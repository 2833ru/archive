<?php

/**
 * Добавление js
 * param obj $obj
 * param array $row
 * param string $rout
 */
function order_cdek_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {

        include_once 'phpshop/modules/cdekwidget/class/CDEKWidget.php';
        $CDEKWidget = new CDEKWidget();

        $PHPShopCart = new PHPShopCart();
        $weight = $PHPShopCart->getWeight();
        if(empty($weight))
            $weight = $CDEKWidget->option['weight'];
        if(empty($CDEKWidget->option['default_city']))
            $defaultCity = 'auto';
        else
            $defaultCity = $CDEKWidget->option['default_city'];

        $obj->set('order_action_add', '
 <!-- Модальное окно cdekwidget -->
        <div class="modal fade bs-example-modal" id="cdekwidgetModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">x</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Доставка</h4>
                    </div>
                    <div class="modal-body" style="width:100%;">
                        
                         <div id="forpvz" style="height: 600px"></div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal" id="ddelivery-close">Закрыть</button>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Модальное окно cdekwidget -->
        
        <input type="hidden" id="cdekwidgetCityFrom" value="' . $CDEKWidget->option[city_from] . '">
        <input type="hidden" id="cdekwidgetdefaultCity" value="' . $defaultCity . '">
<input type="hidden" id="сdekCartWeight" value="' . $weight . '">
<input type="hidden" id="сdekDefaultLength" value="' . $CDEKWidget->option[length] . '">
<input type="hidden" id="сdekDefaultWidth" value="' . $CDEKWidget->option[width] . '">
<input type="hidden" id="сdekDefaultHeight" value="' . $CDEKWidget->option[height] . '">
<script type="text/javascript" src="phpshop/modules/cdekwidget/js/widjet.js" /></script><script type="text/javascript" src="phpshop/modules/cdekwidget/js/cdekwidget.js" /></script>
', true);
    }
}

$addHandler = array
    (
    'order' => 'order_cdek_hook'
);
?>