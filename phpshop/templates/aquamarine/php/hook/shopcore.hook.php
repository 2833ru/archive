<?php
/**
 * Добавление пятого товара
 */
function CID_Product_hook($obj,$row,$rout) {
 
    if($rout == 'START'){
    //$obj->SysValue['templates']['main_product_forma_5']="product/main_product_forma_4.tpl";
    $obj->PHPShopCategory->setParam('num_row', 4);
    } 
 
}
 
$addHandler=array
        (
        'CID_Product'=>'CID_Product_hook'
);
?>