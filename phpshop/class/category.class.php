<?php
/**
 * Категории
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
        parent::PHPShopObj();
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
class PHPShopPages extends PHPShopObj{

         /**
          * Конструктор
          * @param int $objID ИД страницы
          */
	 function PHPShopPages($objID){
	 $this->objID=$objID;
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name11'];
	 parent::PHPShopObj();
	 }
         /**
          * Выдача имени страницы
          * @return string
          */
	 function getName(){
	 return parent::getParam("name");
	 }

         /**
          * Выдача содержания
          * @return string
          */
	 function getContent(){
	 return parent::getParam("content");
	 }
}
?>