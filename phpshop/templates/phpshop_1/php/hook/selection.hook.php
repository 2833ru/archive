<?php

/**
 * Изменение сетки товаров в спецпредложениях
 * @param array $obj объект
 * @param array $row массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function v_selection_hook($obj, $row, $rout) {

    if ($rout == 'START') {
        $obj->cell = 2;
    }
}

$addHandler = array
    (
    'v' => 'v_selection_hook',
);
?>