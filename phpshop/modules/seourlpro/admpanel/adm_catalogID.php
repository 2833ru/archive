<?php

// Настройки модуля
class PHPShopSeourlOption extends PHPShopArray {

    function __construct() {
        $this->objType = 3;
        $this->checkKey = true;

        // Память настроек
        $this->memory = __CLASS__;

        $this->objBase = $GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system'];
        parent::__construct('redirect_enabled');
    }

}

// Добавляем значения в функцию actionStart
function addSeoUrlPro($data) {
    global $PHPShopGUI;
    
    $PHPShopSeourlOption = new PHPShopSeourlOption();

    // Каталоги товаров
    if (isset($data['cat_seo_name'])) {

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


        $Tab3 = $PHPShopGUI->setField("SEO ссылка:", $PHPShopGUI->setInput("text", "cat_seo_name_new", $data['cat_seo_name'], "left", false, false, false, false, '/', '.html'), 1, 'Можно использовать вложенные ссылки /sony/plazma/televizor');

        
        if ($PHPShopSeourlOption->getParam('redirect_enabled') == 2)
            $Tab3.= $PHPShopGUI->setField("Старая ссылка:", $PHPShopGUI->setInput("text", "cat_seo_name_old_new", $data['cat_seo_name_old'], "left", false, false, false), 1, 'Старая ссылка для 301 редиректа');

        $PHPShopGUI->addTab(array("SEO", $Tab3, 450));
    }
    // Каталог товаров
    elseif (isset($data['page_cat_seo_name'])) {

        if (empty($data['page_cat_seo_name']))
            $data['news_seo_name'] = PHPShopString::toLatin($data['name']);

        $Tab3 = $PHPShopGUI->setField("SEO ссылка:", $PHPShopGUI->setInput("text", "page_cat_seo_name_new", $data['page_cat_seo_name'], "left", false, false, false, false,'/', '.html'), 1);

        if ($PHPShopSeourlOption->getParam('redirect_enabled') == 2)
            $Tab3.= $PHPShopGUI->setField("Старая ссылка:", $PHPShopGUI->setInput("text", "cat_seo_name_old_new", $data['cat_seo_name_old'], "left", false, false, false, false), 1, 'Старая ссылка для 301 редиректа');

        $PHPShopGUI->addTab(array("SEO", $Tab3, 450));
    }
}

$addHandler = array(
    'actionStart' => 'addSeoUrlPro',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>