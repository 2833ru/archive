<?php

// Добавляем значения в функцию actionStart
function addSeoUrlPro($data) {
    global $PHPShopGUI;

    if (empty($data['cat_seo_name'])) {
        PHPShopObj::loadClass("string");
        $data['cat_seo_name'] = PHPShopString::toLatin($data['name']);
        $data['cat_seo_name'] = str_replace("_", "-", $data['cat_seo_name']);
    }

    // Добавление /cat/ для сложных ссылок
    $true_link = str_replace('cat/', '', $data['cat_seo_name']);
    if (stristr($true_link, '/')) {
        $data['cat_seo_name'] = 'cat/' . $true_link;
    }



    $Tab3 = $PHPShopGUI->setField("SEO ссылка:", $PHPShopGUI->setInput("text", "cat_seo_name_new", $data['cat_seo_name'], "left", 400,false,false,false,'http://'.$_SERVER['SERVER_NAME'].'/','.html <p>* Можно использовать вложенные ссылки /sony/plazma/televizor</p>'), "none");
    $PHPShopGUI->addTab(array("SEO", $Tab3, 450));
}


$addHandler = array(
    'actionStart' => 'addSeoUrlPro',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>