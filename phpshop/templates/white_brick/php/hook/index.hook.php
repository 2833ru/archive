<?php
/**
 * Изменение сетки товаров в "Спецпредложения на главной"
 * @param array $obj объект
 */

function specmain_hook($obj){
	// если прогрузка идёт от модуля шаблона для фейсбука
	// ставим кол-во спецов на главную = 15
//	$obj->limit = 15;
}


/**
 * Изменение сетки категорий в "Таблице категорий на главной"
 * @param array $obj объект
 */
function leftCatalTable_hook($obj) {

    // Выключаем блок
    return true;
    
    $obj->cell=1;
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
function compile_hook($obj) {
    $ul='<ul>'.$obj->product_grid.'</ul>';
    $obj->product_grid=null;
    return $ul;
}



$addHandler=array
        (
        '#setCell'=>'setcell_hook',
        '#compile'=>'compile_hook',
        'specMain'=>'specmain_hook'
);

?>