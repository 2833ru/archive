<?php


function specMain_hook($obj) {
    $obj->check_index = true;
}

$addHandler = array
    (
    'specMain' => 'specMain_hook',
);
?>