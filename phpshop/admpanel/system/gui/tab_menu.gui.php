<?php

function tab_menu() {
    global $subpath;

    ${'menu_active_' . $subpath[1]} = 'active';
    
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_system . '"><a href="?path=system">'.__('Основные настройки').'</a></li>
       <li class="' . $menu_active_company . '"><a href="?path=system.company">'.__('Реквизиты').'</a></li>
       <li class="' . $menu_active_sync . '"><a href="?path=system.sync">'.__('Документоборот (CRM)').'</a></li>
       <li class="' . $menu_active_seo . '"><a href="?path=system.seo">'.__('SEO настройки').'</a></li>
       <li class="' . $menu_active_currency . '"><a href="?path=system.currency">'.__('Валюты').'</a></li>
       <li class="' . $menu_active_image . '"><a href="?path=system.image">'.__('Изображения').'</a></li>
       <li class="' . $menu_active_servers . '"><a href="?path=system.servers">'.__('Витрины').'</a></li>   
       <li class="' . $menu_active_integration . '"><a href="?path=system.integration">'.__('Интеграция с сервисами').'</a></li>         
       <li><a href="?path=tpleditor">'.__('Шаблоны дизайна').'</a></li>
       </ul>';
    
    return $tree;
}

?>
