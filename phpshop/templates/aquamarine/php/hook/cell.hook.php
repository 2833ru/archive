<?php

function setcell_div_hook($obj,$arg) {

    $div=null;
    $panel=array('panel_l','panel_r','panel_l','panel_r','panel_l');

    foreach($arg as $key=>$val) {
        if(!empty($val)) {
            $div.='<div class="'.$panel[$key].'">'.$key.'</div>';
        }
    }

    return $div;
}

/**
 * Изменение формата решетки между товарами c <td> на <li>
 * @param array $obj объект
 * @param array $arg массив данных
 * @return string
 */
function setcell_hook($obj,$arg) {

    $li=null;
    $panel=array('panel_l','panel_r','panel_l','panel_r');

    foreach($arg as $key=>$val) {
        if(!empty($val)) {
            $li.='<li class="'.$panel[$key].'">'.$val.'</li>';
        }
    }

    return $li;
}

/**
 * Изменение формата решетки между товарами c <td> на <li>, компиляция списка в <ul>
 * @return string
 */
function compile_hook($obj) {
    $ul='<ul>'.$obj->product_grid.'</ul>';
    $obj->product_grid=null;
    return $ul;
}

/**
 * Изменение сетки сопутствующих товаров, сетка товаров = 3
 */
function odnotip_hook($obj,$row,$rout) {
    if($rout=='START') {
        $obj->odnotip_setka_num=3;
        //$obj->template_odnotip='main_product_forma_3';
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

function cid_product_sorttemplate_hook($obj,$row,$rout){
    if($rout == 'START'){
        $obj->sort_template = 'sorttemplatehook';
    }
}

/**
 * Шаблон вывода характеристик
 * @param array $value массив значений $value[]=array('моя цифра 1',123,'selected');
 * @param int $n integer
 * @param string $title название характеристики
 * @param array $vendor массив значений характеристик товара
 * @return string 
 */
function sorttemplatehook($value, $n, $title, $vendor) {
    $disp = null;

    if (is_array($value)) {
        foreach ($value as $p) {
            if (is_array($vendor[$n])) {
                foreach ($vendor[$n] as $value) {

                    if ($value == $p[1])
                        $text = PHPShopText::b($p[0]);
                    else
                        $text = $p[0];

                    $disp.=PHPShopText::br() . PHPShopText::a('?v[' . $n . ']=' . $p[1], $text, $p[0], $color = false, $size = false, $target = false, $class = false);
                }
            }else {
                if ($vendor[$n] == $p[1])
                    $text = PHPShopText::b($p[0]);
                else
                    $text = $p[0];

                $disp.=PHPShopText::br() . PHPShopText::a('?v[' . $n . ']=' . $p[1], $text, $p[0], $color = false, $size = false, $target = false, $class = false);
            }
        }
    }
    return $disp;
}


function CID_Product_cell_hook($obj, $row, $rout) {
    if ($rout == "START"){
        if($obj->PHPShopCategory->getParam('num_row') == 4)
        $obj->PHPShopCategory->setParam('num_row', 3);
    }
}

$addHandler=array
        (
        'odnotip'=>'odnotip_hook',
        '#setCell'=>'setcell_div_hook',
        '#compile'=>'compile_hook',
        'CID_Category'=>'cid_category_add_spec_hook',
        'CID_Product' => 'CID_Product_cell_hook'

);

?>