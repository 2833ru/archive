<?php

/**
 * Изменение сетки товаров в "Спецпредложения на главной"
 * @param array $obj объект
 */

function specmain_index_hook($obj){
	// если прогрузка идёт от модуля шаблона для фейсбука
	// ставим кол-во спецов на главную = 15
	$obj->limit = 15;
}




$addHandler=array
        (
        'specMain'=>'specmain_index_hook'
);

?>