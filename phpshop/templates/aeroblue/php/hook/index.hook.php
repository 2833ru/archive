<?php

/**
 * Изменение сетки товаров в "Сейчас покупают"
 * @param array $obj объект
 */
function nowBuy_hook($obj) {
    $obj->cell = 2;
    $obj->limitpos = 4;
}

/**
 * Изменение сетки товаров в "Спецпредложения на главной"
 * @param array $obj объект
 */
function specMain_hook($obj) {
    $obj->cell = 2;
    $obj->limit = 4;
}

/**
 * Изменение сетки категорий в "Таблице категорий на главной"
 * @param array $obj объект
 */
function leftCatalTable_hook($obj,$data,$rout) {

    // Выключаем блок
    return true;
    
    //$obj->cell=1;

}

$addHandler = array
    (
    'nowBuy' => 'nowBuy_hook',
    'specMain' => 'specMain_hook',
    'leftCatalTable' => 'leftCatalTable_hook'
);
?>