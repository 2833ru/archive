<?php

/**
 * Добавление пятого товара
 */
function specMain_hook($obj,$row) {

    $obj->SysValue['templates']['main_product_forma_5']="product/main_product_forma_4.tpl";
    $obj->cell=4;
    //$obj->limit=5;
    //$obj->debug=true;
 
}

/**
 * Уменьшение описания
 */
function product_grid_hook($obj,$row){
    $obj->set('productDes',substr($row['description'],0,22).'...');
}
 
$addHandler=array
        (
        'product_grid'=>'product_grid_hook',
        '#specMain'=>'specMain_hook'
);
?>