<?php


/**
 * Добавление SEO ссылки к товарам
 */
function checkStore_seourlpro_element_hook($obj,$row) {
    //$obj->set('nameLat',$GLOBALS['seourl_pref'].PHPShopString::toLatin($row['name']));
    if($GLOBALS['PHPShopSeoPro'])
    $GLOBALS['PHPShopSeoPro']->setMemory($row['id'],$row['name'],2);
    else {
        $GLOBALS['modules']['seourlpro']['map_prod']['shop/UID_'. $row['id']] = 'id/' . str_replace("_", "-", PHPShopString::toLatin($row['name'])).'-'.$row['id'];
    }
    return true;
}

$addHandler=array
        (
        'checkStore'=>'checkStore_seourlpro_element_hook'
);


?>