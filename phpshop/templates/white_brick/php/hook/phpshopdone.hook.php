<?php


function done_index_nt_hook($obj, $dataArray, $rout) {

    if ($rout == 'START') {
        $obj->set('UidLeftColHide','span12');
    }
}

/**
 * Добавление в список каталогов спецпредложения товаров в 3 ячейки, лимит 3
 */
$addHandler = array
    (
    'index' => 'done_index_nt_hook'
);
?>