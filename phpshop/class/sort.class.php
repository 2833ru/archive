<?
/*
+-------------------------------------+
|  Имя: PHPShopSort                   |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: База характеристик     |
|  Версия: 1.0                        |
|  Тип: Extends class                 |
|  Зависимости: нет                   |
|  Вызов: Object                      |
+-------------------------------------+
*/


if (!defined("OBJENABLED")){
require_once(dirname(__FILE__)."/array.class.php");
}


class PHPShopSortArray extends PHPShopArray{
	 
	 function PHPShopSortArray(){
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name21'];
	 parent::PHPShopArray('id','name','page');
	 }
}

class PHPShopSortCategoryArray extends PHPShopArray{
	 
	 function PHPShopSortCategoryArray(){
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name20'];
	 parent::PHPShopArray('id','name','category','filtr','page','flag','goodoption');
	 }
}
?>