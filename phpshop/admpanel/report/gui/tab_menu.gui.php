<?php

function tab_menu() {
    global $subpath;

    ${'menu_active_' . $subpath[1]} = 'active';
    
    $tree = '
       <ul class="nav nav-pills nav-stacked">
       <li class="' . $menu_active_statorder . '"><a href="?path=report.statorder">Отчеты по заказам</a></li>
       <li class="' . $menu_active_statuser . '"><a href="?path=report.statuser">Топ 10 покупатели</a></li>
       <li class="' . $menu_active_statpayment . '"><a href="?path=report.statpayment">Статусы заказов</a></li>
           <li class="' . $menu_active_crm . '"><a href="?path=report.crm">CRM журнал</a></li>
       </ul>';
    
    return $tree;
}

?>
