<?php
/**
 * Изменение сетки товаров в "Спецпредложения на главной"
 * @param array $obj объект
 */

function specmainFacebook_hook($obj){
	// если прогрузка идёт от модуля шаблона для фейсбука
	// ставим кол-во спецов на главную = 15
	$obj->limit = 15;
}


/**
 * Изменение формата решетки между товарами c <td> на <li>
 * @param array $obj объект
 * @param array $arg массив данных
 * @return string
 */
function setcell2_hook($obj,$arg) {
    $li=null;
    $panel=array('panel_l','panel_r','panel_l','panel_r');

    foreach($arg as $key=>$val) {
        if(!empty($val)) {
            $li.='<li>'.$val.'</li>';
        }
    }

    return $li;
}

/**
* Изменения кол-ва спецов на главной
*/

/**
 * Изменение формата решетки между товарами c <td> на <li>, компиляция списка в <ul>
 * @return string
 */
function compile2_hook($obj) {
    $ul='<ul>'.$obj->product_grid.'</ul>';
    $obj->product_grid=null;
    return $ul;
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
        'setCell'=>'setcell2_hook',
        'compile'=>'compile2_hook',
        '#specMain'=>'specmainFacebook_hook',
	'CID_Category'=>'cid_category_add_spec_hook'
);

?>