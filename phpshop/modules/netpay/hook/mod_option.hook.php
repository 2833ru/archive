<?php

// Настройки модуля
PHPShopObj::loadClass("array");

class PHPShopNetPayArray extends PHPShopArray {
    function __construct() {
        $this->objType=3;
        $this->objBase=$GLOBALS['SysValue']['base']['netpay']['netpay_system'];
        parent::__construct("status","title",'title_sub','autosubmit','expiredtime','merchant_key','merchant_skey');
    }
}

?>
