<?php

PHPShopObj::loadClass("array");
/**
 * Класс получения настроек модуля
 */
class PHPShopSberbankRFArray extends PHPShopArray {

    function __construct() {
        $this->objType = 3;
        $this->objBase = $GLOBALS['SysValue']['base']['sberbankrf']['sberbankrf_system'];
        parent::__construct("login", "password", "dev_mode", "status", "title_sub");
    }
}