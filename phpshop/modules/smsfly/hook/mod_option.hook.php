<?php

// Настройки модуля
PHPShopObj::loadClass("array");
class PHPShopSmsflyArray extends PHPShopArray {
    function __construct() {
        $this->objType=3;
        $this->objBase=$GLOBALS['SysValue']['base']['smsfly']['smsfly_system'];
        parent::PHPShopArray("merchant_user","merchant_pwd","phone","sandbox");
    }
}



?>
