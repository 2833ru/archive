<?php

function tab_menu() {
    global $subpath;

    ${'menu_active_' . $subpath[1]} = 'active';
    
    if(!empty($_GET['date_start']))
    $date = '&date_start='.$_GET['date_start'].'&date_end='.$_GET['date_end'].'&group_date='.$_GET['group_date'];
    else $date=null;

    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_metrica . '"><a href="?path=metrica'.$date.'">' . __('Сводка') . '</a></li>
       <li class="' . $menu_active_traffic . '"><a href="?path=metrica.traffic'.$date.'">' . __('Посещаемость') . '</a></li>
       <li class="' . $menu_active_popular . '"><a href="?path=metrica.popular'.$date.'">' . __('Страницы') . '</a></li>
       <li class="' . $menu_active_sources_summary . '"><a href="?path=metrica.sources_summary'.$date.'">' . __('Источники, сводка') . '</a></li>
       <li class="' . $menu_active_sources_social . '"><a href="?path=metrica.sources_social'.$date.'">' . __('Социальные сети') . '</a></li>
       <li class="' . $menu_active_sources_sites . '"><a href="?path=metrica.sources_sites'.$date.'">' . __('Сайты') . '</a></li>
       <li class="' . $menu_active_search_phrases . '"><a href="?path=metrica.search_phrases'.$date.'">' . __('Поисковые фразы') . '</a></li>
       <li class="' . $menu_active_search_engines . '"><a href="?path=metrica.search_engines'.$date.'">' . __('Поисковые системы') . '</a></li>
       </ul>';

    return $tree;
}

?>
