<?php

function notificationOneclick() {
    global $PHPShopModules;
    
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.oneclick.oneclick_jurnal"));
    $data = $PHPShopOrm->select(array('COUNT(id) as count'),array('status'=>"='1'"),false,array('limit'=>'1'));

    if(!empty($data['count']))
    echo '<a class="navbar-btn btn btn-sm btn-info navbar-right visible-lg" href="?path=modules.dir.oneclick" data-toggle="tooltip" data-placement="bottom" title="'.__('Модуль быстрый заказ').'"> '.__('1 Клик').' <span class="badge">'.$data['count'].'</span></a>';
}

?>