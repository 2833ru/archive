<?php

/**
 * Добавление jss
 */
function order_russianpostcalc_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {
        $obj->set('order_action_add','<script src="phpshop/modules/russianpostcalc/js/russianpostcalc.js"></script>',true);
    }
    
}

$addHandler = array
    (
    'order' => 'order_russianpostcalc_hook'
);
?>