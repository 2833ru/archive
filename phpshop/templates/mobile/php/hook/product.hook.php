<?php

/**
 * Изменение сетки категорий в "Таблице категорий на главной"
 * @param array $obj объект
 */
function leftCatal_hook($obj,$where,$rout) {

    $id=intval($obj->PHPShopNav->getId());
    
    $obj->chek($id);
    if($obj->memory_get('product_enabled.' . $id) == 1)
            return ' ';
    
    if($rout == 'START' and !empty($id)){

        return $obj->subcatalog(array('id'=>$id));


    }
}

$addHandler=array
        (
        'leftCatal'=>'leftCatal_hook'
);

?>