<?php

// Настройки модуля
PHPShopObj::loadClass("array");
class PHPShopPaypalArray extends PHPShopArray {
    function PHPShopPaypalArray() {
        $this->objType=3;
        $this->objBase=$GLOBALS['SysValue']['base']['paypal']['paypal_system'];
        parent::PHPShopArray("status","sandbox","title","title_end",'merchant_id','merchant_sig','merchant_pwd','message_header','message','link','currency_id');
    }
}

?>
