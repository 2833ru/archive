<?php

function map_seourlpro_hook($obj, $array) {

    $val = $array['val'];
    $dir = $array['dir'];

    switch ($dir) {
        case 'category':
            $link = '/shop/CID_' . $array['val'] .'.html';
            break;

        case 'category_page':
            $link = '/page/CID_' . $array['val']  . '.html';
            break;
    }
    
    $GLOBALS['PHPShopSeoPro']->setMemory($val,$array['name']);

    return $link;
}

$addHandler = array
    (
    'seourl' => 'map_seourlpro_hook'
);
?>