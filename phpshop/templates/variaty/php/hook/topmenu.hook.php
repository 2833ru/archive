<?php

/**
 * Изменение сетки сопутствующих товаров, сетка товаров = 3
 */
function topMenu_hook($obj, $row) {
    $GLOBALS['SysValue']['other']['topMenuFooter'].=$obj->parseTemplate("main/top_menu_footer.tpl");
}

$addHandler = array
    (
    'topMenu' => 'topMenu_hook',
);
?>