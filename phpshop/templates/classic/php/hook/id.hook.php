<?php
 
/**
 * Хук хлебных крошек 
 */
function uid_bread_crumbs_hook($obj, $row, $rout) {
 
     if($rout == 'MIDDLE'){
 
     // Навигация хлебных крошек с сылками
     $obj->navigation($obj->category, $row['name']);
 
     // Имя каталога
     $obj->set('catalogCategory', $obj->category_name);
 
     // ID категории
     $obj->set('pcatalogId', $obj->category);
     }   
}
 
$addHandler = array
    (
    'UID' => 'uid_bread_crumbs_hook'
);
?>