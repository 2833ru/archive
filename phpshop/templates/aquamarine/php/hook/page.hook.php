<?php

/**
 * Изменение сетки сопутствующих товаров, сетка товаров = 3
 */
function odnotip_hook($obj,$row,$rout) {
    if($rout=='START') {
        $obj->odnotip_setka_num=3;
        $obj->template_odnotip='main_product_forma_3';
        $obj->line=true;
    }
}

$addHandler=array
        (
        'odnotip'=>'odnotip_hook'
);
?>