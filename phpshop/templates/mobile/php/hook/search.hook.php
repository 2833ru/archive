<?php

/**
 * Изменение сетки товаров в поиске
 * @param array $obj объект
 * @param array $row массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function words_searchcore_hook($obj, $row, $rout) {

    if ($rout == 'START') {
        $obj->set('search_active','active');
        $obj->cell = 1;
        $obj->line = false;
        $obj->grid = false;
        $obj->num_row = 100;
    }
}

function setcell_hook($obj, $arg) {

    $li = null;

    foreach ($arg as $val) {
        if (!empty($val)) {
            $li.='<li class="table-view-cell media">' . $val . '</li>';
        }
    }

    return $li;
}

/**
 * Изменение формата решетки между товарами c <td> на <li>, компиляция списка в <ul>
 * @return string
 */
function compile_hook($obj) {
    $ul = '<ul class="table-view">' . $obj->product_grid . '</ul>';
    $obj->product_grid = null;
    return $ul;
}

$addHandler = array
    (
    'words' => 'words_searchcore_hook',
    'setCell' => 'setcell_hook',
);
?>
