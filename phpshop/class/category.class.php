<?php

if (!defined("OBJENABLED")){
require_once(dirname(__FILE__)."/obj.class.php");
require_once(dirname(__FILE__)."/array.class.php");
}

/**
 * Категории товаров
 * Упрощенный доступ к категориями
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopCategory extends PHPShopObj {
    /**
     * Конструктор
     * @param int $objID ИД категории
     */
    function PHPShopCategory($objID) {
        $this->objID=$objID;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name'];
        $this->cache=false;
        $this->debug=false;
        parent::PHPShopObj('id');
    }
    /**
     * Выдача имени категории
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }
    /**
     * Выдача описания категории
     * @return string
     */
    function getContent() {
        return parent::getParam("content");
    }
    /**
     * Проверка на существование
     * @return bool
     */
    function init() {
        $id=parent::getParam("id");
        if(!empty($id)) return true;
    }

}

/**
 * Страницы
 * Упрощенный доступ к страницам
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopPages extends PHPShopObj {

    /**
     * Конструктор
     * @param int $objID ИД страницы
     */
    function PHPShopPages($objID) {
        $this->objID=$objID;
        $this->objBase=$GLOBALS['SysValue']['base']['table_name11'];
        parent::PHPShopObj();
    }
    /**
     * Выдача имени страницы
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }

    /**
     * Выдача содержания
     * @return string
     */
    function getContent() {
        return parent::getParam("content");
    }
}

/**
 * Категории страниц
 * Упрощенный доступ к категориями
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopObj
 */
class PHPShopPageCategory extends PHPShopObj {
    /**
     * Конструктор
     * @param int $objID ИД категории
     */
    function PHPShopPageCategory($objID) {
        $this->objID=$objID;
        $this->objBase=$GLOBALS['SysValue']['base']['page_categories'];
        $this->cache=true;
        $this->debug=false;
        parent::PHPShopObj('id');
    }
    /**
     * Выдача имени категории
     * @return string
     */
    function getName() {
        return parent::getParam("name");
    }
    /**
     * Выдача описания категории
     * @return string
     */
    function getContent() {
        return parent::getParam("content");
    }
    /**
     * Проверка на существование
     * @return bool
     */
    function init() {
        $id=parent::getParam("id");
        if(!empty($id)) return true;
    }

}

/**
 * Массив категории товаров
 * Упрощенный доступ к категориями
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopCategoryArray extends PHPShopArray {

    function PHPShopCategoryArray() {
        $this->cache=false;
        $this->order=array('order'=>'num');
        $this->objBase=$GLOBALS['SysValue']['base']['categories'];
        parent::PHPShopArray("id","name","parent_to");
    }
}
?>