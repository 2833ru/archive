<?php

// Настройки модуля
PHPShopObj::loadClass("array");
class PHPShopSeourlOption extends PHPShopArray {
    function PHPShopSeourlOption() {
        $this->objType=3;
        $this->checkKey=true;
        $this->objBase=$GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system'];
        parent::__construct('paginator','serial','cat_content_enabled', 'seo_brands_enabled');
    }
}

?>
