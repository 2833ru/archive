<?php

function addSeoUrl($data) {
    global $PHPShopGUI;
    
    // Добавляем значения в функцию actionStart
    if(empty($data['prod_seo_name'])) {
        PHPShopObj::loadClass("string");
        $data['prod_seo_name']=PHPShopString::toLatin($data['name']);
        $data['prod_seo_name'] = str_replace("_", "-", $data['prod_seo_name']);
    }
     $Tab3 = $PHPShopGUI->setField("SEO ссылка:", $PHPShopGUI->setInput("text", "prod_seo_name_new", $data['prod_seo_name'], "left", false,false,false,false,'/id/','-'.$data['id'].'.html'), 1, 'Можно использовать вложенные ссылки /sony/plazma/televizor');
    
    $PHPShopGUI->addTab(array("SEO",$Tab3,true));
}


$addHandler=array(
        'actionStart'=>'addSeoUrl',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>