<?php

function order_pochta_hook($obj, $row, $rout) {

    if ($rout === 'MIDDLE') {
        $obj->set('order_action_add','<script src="phpshop/modules/pochta/templates/pochta.js"></script>',true);
    }
    
}

$addHandler = array ('order' => 'order_pochta_hook');
?>