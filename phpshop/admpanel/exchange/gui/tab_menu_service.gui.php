<?php

/**
 * Дополнительная навигация
 */
function tab_menu_service() {
    global $subpath;

    ${'menu_active_' . $subpath[1]} = 'active';
    
   
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . @$menu_active_service. '"><a href="?path=exchange.service">'.__('Очистка базы данных').'</a></li>
       <li class="' . @$menu_active_file. '"><a href="?path=exchange.file">'.__('Проверка изображений').'</a></li>
       <li class="' . @$menu_active_product. '"><a href="?path=product">'.__('Проверка товаров').'</a></li>
       <li class="' . @$menu_active_sql . '"><a href="?path=exchange.sql">'.__('SQL запрос к базе').'</a></li>
       <li class="' . @$menu_active_backup . '"><a href="?path=exchange.backup">'.__('Резервное копирование').'</a></li>
       </ul>';
    
    return $tree;
}

?>