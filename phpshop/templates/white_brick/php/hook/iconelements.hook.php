<?php

/**
 * Отключение спецпредложений в подробном описании товара
 */
function specMainIcon_white_blick_hook($obj){
    if($obj->PHPShopNav->getPath() == 'id' or $obj->PHPShopNav->getNav() == 'UID')
        return true;
}


$addHandler = array
    (
    'specMainIcon' => 'specMainIcon_white_blick_hook'
);
?>