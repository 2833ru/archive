<?php

function leftCatal_productoption_hook($obj, $row, $rout) {
    if($rout == 'END'){
    $obj->set('catalogOption1', $row['option6']);
    $obj->set('catalogOption2', $row['option7']);
    $obj->set('catalogOption3', $row['option8']);
    $obj->set('catalogOption4', $row['option9']);
    $obj->set('catalogOption5', $row['option10']);
    return true;
    }
}

function subcatalog_productoption_hook($obj, $row) {
    $obj->set('catalogOption1', $row['option6']);
    $obj->set('catalogOption2', $row['option7']);
    $obj->set('catalogOption3', $row['option8']);
    $obj->set('catalogOption4', $row['option9']);
    $obj->set('catalogOption5', $row['option10']);
    return true;
}

function optionsLeftCatalTable_hook($obj, $row, $rout) {
    if($rout == 'END') {
        if($row['id']  > 0) {
            $PHPShopCategory = new PHPShopCategory($row['id']);
            $obj->set('catalogOption1', $PHPShopCategory->getParam("option6"));
            $obj->set('catalogOption2', $PHPShopCategory->getParam("option7"));
            $obj->set('catalogOption3', $PHPShopCategory->getParam("option8"));
            $obj->set('catalogOption4', $PHPShopCategory->getParam("option9"));
            $obj->set('catalogOption5', $PHPShopCategory->getParam("option10"));
        }
    }
}

$addHandler = array
    (
    'leftCatal' => 'leftCatal_productoption_hook',
    'subcatalog' => 'subcatalog_productoption_hook',
    'leftCatalTable' => 'optionsLeftCatalTable_hook'
);
?>
