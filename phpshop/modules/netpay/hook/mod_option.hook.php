<?php

// Настройки модуля
PHPShopObj::loadClass("array");

class PHPShopNetPayArray extends PHPShopArray {
    function __construct() {
        $this->objType=3;
        $this->objBase=$GLOBALS['SysValue']['base']['netpay']['netpay_system'];
        parent::__construct(
			'status', 'work', 
			'apikey','auth',
			'title','title_sub','autosubmit','expiredtime',
			'status_paid', 'status_refund', 
			'online_bill', 'inn', 'tax', 'hold', 'status_hold'
			);
    }
}

?>
