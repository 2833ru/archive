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



$addHandler = array
    (
    'leftCatal' => 'leftCatal_productoption_hook',
    'subcatalog' => 'subcatalog_productoption_hook',
);
?>
