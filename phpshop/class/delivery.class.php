<?
/*
+-------------------------------------+
|  Имя: PHPShopDelivery               |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: База доставки          |
|  Версия: 1.0                        |
|  Тип: class                         |
|  Зависимости: нет                   |
|  Вызов: Object                      |
+-------------------------------------+
*/


if (!defined("OBJENABLED"))
require_once(dirname(__FILE__)."/obj.class.php");

class PHPShopDelivery extends PHPShopObj{
     
	 function PHPShopDelivery($objID){
	 $this->objID=$objID;
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name30'];
	 parent::PHPShopObj();
	 }
	 
	 
	 function getPrice($sum,$weight=0){
	 $row = $this->objRow;
	 if($row['price_null_enabled'] == 1 and $sum>=$row['price_null']) {
	  return 0;
       } else {
	   if ($row['taxa']>0) {
		$addweight=$weight-500;
		 if ($addweight<0) {$addweight=0;}
		  $addweight=ceil($addweight/500)*$row['taxa'];
		  $endprice=$row['price']+$addweight;
		  return $at.$endprice;
	   } else {
		  return $row['price'];
	  }
       }
	 }
	 
	 
	 function getCity(){
	 return parent::getParam("city");
	 }
	 
}

?>