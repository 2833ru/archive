<?php

// Настройки модуля
PHPShopObj::loadClass("array");
class PHPShopDeltaKeyArray extends PHPShopArray {
    function PHPShopDeltaKeyArray() {
        $this->objType=3;
        $this->objBase=$GLOBALS['SysValue']['base']['deltakey']['deltakey_system'];
        parent::PHPShopArray("status","title",'title_end','merchant_id','merchant_key','merchant_skey');
    }
}

?>
