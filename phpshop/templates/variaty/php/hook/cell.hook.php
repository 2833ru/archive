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
 
/**
 * Изменение списка подкаталогов в каталоге с <li> на <div> + описание
 */
function cid_category_hook($obj,$dataArray,$rout) {

    $dis=null;
    if($rout=='END') {
        if(is_array($dataArray))
            foreach($dataArray as $row) {
                $content=PHPShopText::a($obj->path.'/CID_'.$row['id'].'.html',$row['name']);
                $content.=PHPShopText::p($row['content']);
                $dis.=PHPShopText::div($content,$align="left",$style='float:left;padding:10px');
            }

        // Переназначем переменную списка категорий
        $obj->set('catalogList',$dis);

        // Cпецпредложения товаров
        cid_category_add_spec_hook($obj,$dataArray);
    }
}

/**
 * Добавление в список каталогов спецпредложения товаров в 3 ячейки, лимит 3
 */
function cid_category_add_spec_hook($obj,$row) {
    global $PHPShopProductIconElements;

    // Случайный выбор каталога
    if(is_array($row))
        foreach($row as $val)
            $cat[]=$val['id'];
    $rand=rand(0,count($cat)-1);

    // Используем элемент вывода спецпредложений
    $PHPShopProductIconElements->template='main_product_forma_3';
    $spec=$PHPShopProductIconElements->specMainIcon(false,$cat[$rand],3,3,true);
    $spec=PHPShopText::div(PHPShopText::p($spec),$align="left",$style='float:none;padding:10px');

    // Добавляем в переменную списка категорий вывод спецпредложений
    $obj->set('catalogList',$spec,true);
}

$addHandler=array
        (
        'odnotip'=>'odnotip_hook',
        'CID_Category'=>'cid_category_add_spec_hook'
);

?>