<?php

function addSeoUrl($data) {
    global $PHPShopGUI;

     $Tab3 = $PHPShopGUI->setField("SEO ссылка:", $PHPShopGUI->setInput("text", "prod_seo_name_new", $data['prod_seo_name'], "left", false,false,false,false,'/id/','-'.$data['id'].'.html'), 1, 'ћожно использовать вложенные ссылки /sony/plazma/televizor');
    
    $PHPShopGUI->addTab(array("SEO",$Tab3,450));
}


$addHandler=array(
        'actionStart'=>'addSeoUrl',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>