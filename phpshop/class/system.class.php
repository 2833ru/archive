<?
/*
+-------------------------------------+
|  Имя: PHPShopSystem                 |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: Системные настройки    |
|  Версия: 1.0                        |
|  Тип: Extends class                 |
|  Зависимости: нет                   |
|  Вызов: Object                      |
+-------------------------------------+
*/

if (!defined("OBJENABLED"))
require_once(dirname(__FILE__)."/obj.class.php");



class PHPShopSystem extends PHPShopObj{
     
	 function PHPShopSystem(){
	 $this->objID=1;
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name3'];
	 parent::PHPShopObj();
	 }
	 
	 function getName(){
	 return parent::getParam("name");
	 }
	 
	 // Вывод сериализованного значения [param.val]
	 function getSerilizeParam($param){
	 $param=explode(".",$param);
	 $val=parent::unserializeParam($param[0]);
	 return $val[$param[1]];
	 }
	 
	 
	 function getDefaultValutaId(){
     return parent::getParam("dengi");
	 }
	 
	 function getDefaultOrderValutaId(){
     return parent::getParam("kurs");
	 }
	 
	 // Курс валюты по умочанию, order - валюта в заказе (true)
	 function getDefaultValutaKurs($order=false){
	 if(!class_exists("phpshopvaluta")) parent::loadClass("phpshopvaluta");
	   if($order) $valuta_id = $this->getDefaultOrderValutaId();
         else $valuta_id = $this->getDefaultValutaId();
	 $PV = new PHPShopValuta($valuta_id);
	 return $PV->getKurs();
	 }
	 
	 // Iso валюты по умочанию, order - валюта в заказе (true)
	 function getDefaultValutaIso($order=false){
	 if(!class_exists("phpshopvaluta")) parent::loadClass("valuta");
       if($order) $valuta_id = $this->getDefaultOrderValutaId();
         else $valuta_id = $this->getDefaultValutaId();
	 $PV = new PHPShopValuta($valuta_id);
	 return $PV->getIso();
	 }
	 
	 // Код валюты по умочанию, order - валюта в заказе только с курсом для заказа (true)
	 function getDefaultValutaCode($order=false){
	 if(!class_exists("phpshopvaluta")) parent::loadClass("valuta");
	 
	 if($order) $valuta_id = $this->getDefaultOrderValutaId();
	  elseif(isset($_SESSION['valuta'])) $valuta_id=$_SESSION['valuta'];
	       else $valuta_id = $this->getDefaultValutaId();
	 
	 $PV = new PHPShopValuta($valuta_id);
	 return $PV->getCode();
	 }
	 
	 function getLogo(){
	 $logo = parent::getParam("logo");
	 if(empty($logo)) return "../../img/phpshop_logo.gif";
       else return $logo;
	 }
	 
	 function getArray(){
	 $array = $this->objRow;
	 foreach($array as $key=>$v)
	      if(is_string($key)) $newArray[$key]=$v;
	 return $newArray;
	 }
}
?>