<?php

function addNews($data) {
    global $PHPShopGUI;
    
    
    // SEO
    //$Tab3 = 'seo';
    $Tab3 = $PHPShopGUI->setField("Title:", $PHPShopGUI->setInput("text", "meta_title_new", $data['meta_title'], "left", 600), "none", 5);
    $Tab3 .= $PHPShopGUI->setField("Keywords:", $PHPShopGUI->setInput("text", "meta_keywords_new", $data['meta_keywords'], "left", 600), "none", 5);
    $Tab3 .= $PHPShopGUI->setField("Description:", $PHPShopGUI->setTextArea("meta_description_new", $data['meta_description'], false, '450px', '100px'));
    $PHPShopGUI->addTab(array("SEO",$Tab3,350));
}


$addHandler=array(
        'actionStart'=>'addNews',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>