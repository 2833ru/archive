<?php

/**
 * �������������� ���������
 */
function tab_menu_xml() {
    global $subpath;

    ${'menu_active_' . $subpath[2]} = 'active';
    
   
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li><a href="/yml/" target="_blank">'.__('������.������').'</a></li>
       <li><a href="/rss/google.xml" target="_blank">Google Merchant</a></li>
       </ul>';
    
    return $tree;
}

?>