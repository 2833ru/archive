<?php

if (!defined("OBJENABLED"))
    require_once(dirname(__FILE__)."/obj.class.php");

/**
 * Библиотека данных по методам оплаты
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopPayment extends PHPShopObj {
    var $debug=false;

    /**
     * Конструктор
     * @param int $objID ИД метода оплаты
     */
    function PHPShopPayment($objID) {
        $this->objID=$objID;
        $this->order=array('order'=>'num');
        $this->objBase=$GLOBALS['SysValue']['base']['payment_systems'];
        parent::PHPShopObj();
    }

    /**
     * Имя метода оплаты
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    function getPath() {
        return parent::getParam("path");
    }

    /**
     * ИД метода
     * @return int
     */
    function getId() {
        return parent::getParam("id");
    }
}

/**
 * Массив способов оплат
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopPaymentArray extends PHPShopArray {

    function PHPShopPaymentArray() {
        $this->order=array('order'=>'num');
        $this->objBase=$GLOBALS['SysValue']['base']['payment_systems'];
        parent::PHPShopArray('id',"name",'path','enabled');
    }
}
?>