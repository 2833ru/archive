<?php

function tab_menu() {
    global $subpath;

    ${'menu_active_' . $subpath[1]} = 'active';
    
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_system . '"><a href="?path=system">Основные настройки</a></li>
       <li class="' . $menu_active_company . '"><a href="?path=system.company">Реквизиты</a></li>
       <li class="' . $menu_active_sync . '"><a href="?path=system.sync">Документоборот (CRM)</a></li>
       <li class="' . $menu_active_seo . '"><a href="?path=system.seo">SEO настройки</a></li>
       <li class="' . $menu_active_currency . '"><a href="?path=system.currency">Валюты</a></li>
       <li class="' . $menu_active_image . '"><a href="?path=system.image">Изображения</a></li>
       <li class="' . $menu_active_servers . '"><a href="?path=system.servers">Витрины</a></li>   
       <li><a href="?path=tpleditor">Шаблоны дизайна</a></li>
       </ul>';
    
    return $tree;
}

?>
