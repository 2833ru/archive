<?php

/**
 * Изменение сетки категорий в "Таблице категорий на главной"
 * @param array $obj объект
 */
function leftCatal_mob_hook($obj, $where, $rout) {

    $id = intval($obj->PHPShopNav->getId());

    $obj->chek($id);
    if ($obj->memory_get('product_enabled.' . $id) == 1)
        return ' ';

    if ($rout == 'START' and ! empty($id)) {
        $PHPShopCategory = new PHPShopCategory($id);
        return '<li class="table-view-divider">' . $PHPShopCategory->getName() . '</li>' . $obj->subcatalog(array('id' => $id));
    }
}

function subcatalog_mob_hook($obj, $row) {

    if (!empty($row['count']))
        $obj->set('cat_count', '<span class="badge">' . $row['count'] . '</span>');
}

$addHandler = array
    (
    'leftCatal' => 'leftCatal_mob_hook',
    'subcatalog' => 'subcatalog_mob_hook'
);
?>