<?php

function tab_menu() {
    global $subpath;

    ${'menu_active_' . $subpath[1]} = 'active';

    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_metrica . '"><a href="?path=metrica">' . __('Сводка') . '</a></li>
       <li class="' . $menu_active_traffic . '"><a href="?path=metrica.traffic">' . __('Посещаемость') . '</a></li>
       <li class="' . $menu_active_popular . '"><a href="?path=metrica.popular">' . __('Страницы') . '</a></li>
       <li class="' . $menu_active_sources_summary . '"><a href="?path=metrica.sources_summary">' . __('Источники, сводка') . '</a></li>
       <li class="' . $menu_active_sources_social . '"><a href="?path=metrica.sources_social">' . __('Социальные сети') . '</a></li>
       <li class="' . $menu_active_sources_sites . '"><a href="?path=metrica.sources_sites">' . __('Сайты') . '</a></li>
       <li class="' . $menu_active_search_phrases . '"><a href="?path=metrica.search_phrases">' . __('Поисковые фразы') . '</a></li>
       <li class="' . $menu_active_search_engines . '"><a href="?path=metrica.search_engines">' . __('Поисковые системы') . '</a></li>
       </ul>';

    return $tree;
}

?>
