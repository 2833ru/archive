<?php

// Настройки модуля
PHPShopObj::loadClass("array");
class PHPShopSeourlOption extends PHPShopArray {
    function PHPShopSeourlOption() {
        $this->objType=3;
        $this->objBase=$GLOBALS['SysValue']['base']['seourl']['seourl_system'];
        parent::PHPShopArray("paginator",'serial');
    }
}

?>
