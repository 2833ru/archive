<?php

function Compile_seourlpro_hook($obj) {
    $GLOBALS['PHPShopSeoPro']->Compile($obj);
    return true;
}

$addHandler = array
    (
    'Compile' => 'Compile_seourlpro_hook'
);
?>