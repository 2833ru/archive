<?php

if (!defined("OBJENABLED")){
require_once(dirname(__FILE__)."/array.class.php");
}

/**
 * Массив с характеристиками товаров
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopSortArray extends PHPShopArray{
	 
	 function PHPShopSortArray(){
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name21'];
	 parent::PHPShopArray('id','name','page');
	 }
}

/**
 * Массив с характеристиками категорий товаров
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopArray
 */
class PHPShopSortCategoryArray extends PHPShopArray{
	 
	 function PHPShopSortCategoryArray(){
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name20'];
	 parent::PHPShopArray('id','name','category','filtr','page','flag','goodoption');
	 }
}
?>