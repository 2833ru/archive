<?php

function ordercartforma_seourlpro_hook($row, $option, $rout) {
    if ($rout == 'START') {
        if (!empty($row['prod_seo_name']))
            $GLOBALS['PHPShopSeoPro']->setMemory($row['id'], $row['prod_seo_name'], 2, false);
        else
            $GLOBALS['PHPShopSeoPro']->setMemory($row['id'], $row['name'], 2);
    }
}

$addHandler = array
    (
    'ordercartforma' => 'ordercartforma_seourlpro_hook'
);
?>