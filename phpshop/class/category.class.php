<?
/*
+-------------------------------------+
|  Имя: PHPShopValuta                 |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: База валют             |
|  Версия: 1.0                        |
|  Тип: Extends class                 |
|  Зависимости: нет                   |
|  Вызов: Object                      |
+-------------------------------------+
*/


if (!defined("OBJENABLED")){
require_once(dirname(__FILE__)."/obj.class.php");
require_once(dirname(__FILE__)."/array.class.php");
}

class PHPShopCategory extends PHPShopObj{
	 
	 function PHPShopCategory($objID){
	 $this->objID=$objID;
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name'];
	 parent::PHPShopObj();
	 }
	 
	 function getName(){
	 return parent::getParam("name");
	 }
}


class PHPShopCategoryArray extends PHPShopArray{
	 
	 function PHPShopCategoryArray(){
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name'];
	 parent::PHPShopArray("id","name","parent_to","num_row","num_cow","sort","title_enabled",
     "descrip_enabled","keywords_enabled","skin_enabled","skin","order_by","order_to","vid","servers");
	 }
}

?>