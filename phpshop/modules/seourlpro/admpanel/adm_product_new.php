<?php

function addSeoUrl($data) {
    global $PHPShopGUI;

     $Tab3 = $PHPShopGUI->setField("SEO ссылка:", $PHPShopGUI->setInput("text", "prod_seo_name_new", str_replace("_", "-", $data['prod_seo_name']), "left", 400,false,false,false,'http://'.$_SERVER['SERVER_NAME'].'/id/','-'.$data['id'].'.html <p>* ћожно использовать вложенные ссылки /sony/plazma/televizor</p>'), "none");
    
    $PHPShopGUI->addTab(array("SEO",$Tab3,450));
}


$addHandler=array(
        'actionStart'=>'addSeoUrl',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>