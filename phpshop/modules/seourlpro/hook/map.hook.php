<?php

function map_seourlpro_hook() {
    $GLOBALS['PHPShopSeoPro']->catArrayToMemory();
}

$addHandler = array
    (
    'seourl' => 'map_seourlpro_hook'
);
?>