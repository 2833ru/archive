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

    /**
     * Конструктор
     * @param int $objID ИД метода оплаты
     */
    function PHPShopPayment($objID) {
        $this->objID=$objID;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name48'];
        parent::PHPShopObj();
    }

    /**
     * Имя метода оплаты
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * ИД метода
     * @return int
     */
    function getId() {
        return parent::getParam("id");
    }
}
?>