<?php

/**
 * Дополнительная навигация
 */
function tab_menu_xml() {
    global $subpath;

    ${'menu_active_' . $subpath[2]} = 'active';
    
   
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li><a href="'.$GLOBALS['SysValue']['dir']['dir'].'/yml/" target="_blank">'.__('Яндекс.Маркет').'</a></li>
       <li><a href="'.$GLOBALS['SysValue']['dir']['dir'].'/yml/?getall&available" target="_blank">'.__('Яндекс.Вебмастер').'</a></li>
       <li><a href="'.$GLOBALS['SysValue']['dir']['dir'].'/yml/?marketplace=ozon" target="_blank">'.__('Ozon').'</a></li>
       <li><a href="'.$GLOBALS['SysValue']['dir']['dir'].'/rss/google.xml" target="_blank">Google Merchant</a></li>
       <li><a href="'.$GLOBALS['SysValue']['dir']['dir'].'/yml/?marketplace=sbermarket" target="_blank">'.__('СберМегаМаркет').'</a></li>
       <li><a href="'.$GLOBALS['SysValue']['dir']['dir'].'/yml/?marketplace=aliexpress" target="_blank">AliExpress</a></li>
       <li><a href="'.$GLOBALS['SysValue']['dir']['dir'].'/yml/?marketplace=cdek" target="_blank">'.__('СДЭК.Маркет').'</a></li>
       </ul>';
    
    return $tree;
}

?>