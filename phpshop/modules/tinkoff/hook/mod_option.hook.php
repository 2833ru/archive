<?php

// Настройки модуля
PHPShopObj::loadClass("array");

class PHPShopTinkoffArray extends PHPShopArray
{
    function __construct()
    {
        $this->objType = 3;
        $this->objBase = $GLOBALS['SysValue']['base']['tinkoff']['tinkoff_system'];
        parent::__construct('title', 'terminal', 'secret_key', 'gateway', 'enabled_taxation', 'taxation', 'status', 'title_end');
    }
}
