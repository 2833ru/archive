<?php

// Настройки модуля
PHPShopObj::loadClass("array");
class PHPShopAssistmoneyArray extends PHPShopArray {
    function PHPShopAssistmoneyArray() {
        $this->objType=3;
        $this->objBase=$GLOBALS['SysValue']['base']['assist']['assistmoney_system'];
        parent::PHPShopArray("status","title",'title_end','merchant_id','merchant_sig','assist_url');
    }
}

?>