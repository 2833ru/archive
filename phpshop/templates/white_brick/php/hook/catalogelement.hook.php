<?php
/**
 * Изменение шаблона ссылок таблицы категорий со строки на столбик
 * @param array $obj объект
 * @param array $val массив данных
 */
function template_cat_table_hook($obj,$val) {
    return PHPShopText::a('/shop/CID_'.$val['id'].'.html',$val['name'],$val['name']);
}
 
$addHandler=array
        (
        'template_cat_table'=>'template_cat_table_hook'
);
?>