<?php

function notificationOneclick() {
    global $PHPShopModules;
    
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.oneclick.oneclick_jurnal"));
    $data = $PHPShopOrm->select(array('COUNT(id) as count'),false,false,array('limit'=>'1'));

    echo '<a class="navbar-btn btn btn-sm btn-info navbar-right visible-lg" href="?path=modules.dir.oneclick" data-toggle="tooltip" data-placement="bottom" title="Модуль быстрый заказ"> Заказы <span class="badge">'.$data['count'].'</span></a>';
}

?>