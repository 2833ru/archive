<?
/*
+-------------------------------------+
|  Имя: PHPShopOrder                  |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: База заказов           |
|  Версия: 1.0                        |
|  Тип: Extends class                 |
|  Зависимости: PHPShopSystem,        |
|  PHPShopPayment, PHPShopProduct,    |
|  PHPShopValuta                      |
|  Вызов: Object                      |
+-------------------------------------+
*/


if (!defined("OBJENABLED"))
require_once(dirname(__FILE__)."/obj.class.php");

class PHPShopOrder extends PHPShopObj{
	 var $objID;
	 var $productID;
	 var $default_valuta_iso;
	 var $default_valuta_name;
	 var $default_valuta_code;
	 
	 
	 function PHPShopOrder($objID){
	 $this->objID=$objID;
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name1'];
	 parent::PHPShopObj();
	 $paramOrder=parent::unserializeParam("orders");
	 $this->order_metod_id=$paramOrder['Person']['order_metod'];
	 parent::loadClass("system");
	 $PHPShopSystem = new PHPShopSystem();
	 $this->getDefaultValutaObj($PHPShopSystem);
	 }
	 	 

	 // ID метода оплаты
	 function getOplataMetodId(){
	 return $this->order_metod_id;
	 }
	 
	 // Название метода оплаты
	 function getOplataMetodName(){
	 parent::loadClass("payment");
	 $Payment= new PHPShopPayment($this->order_metod_id);
	 $this->order_metod_name=$Payment->getName();
	 return $this->order_metod_name;
	 }
	 
	 // ID валюты товара в заказе
	 function getValutaId($productID){
	 parent::loadClass("product");
	 $Product = new PHPShopProduct($productID);
     $this->valutaID=$Product->getValutaID();
	 return $this->valutaID;
	 }
	 
	 // ISO валюты товара в заказе
	 function getValutaIso($productID){
	 $this->getValutaId($productID);
	 parent::loadClass("valuta");
	 $valutaID = $this->valutaID;
	 $Valuta = new PHPShopValuta($valutaID);
	 $this->ValutaIso = $Valuta->getIso();
	 
	 if(empty($this->ValutaIso)){
	    return $this->default_valuta_iso;
	 }
	 return $Valuta->getIso();
	 }
	

	 // Валюта по умолчанию в заказе
	 function getDefaultValutaObj($System){
	 $this->default_valuta_id=$System->getDefaultValutaId();
	 parent::loadClass("valuta");
	 $PHPShopValuta = new PHPShopValuta($this->default_valuta_id);
     $this->default_valuta_iso=$PHPShopValuta->getIso();
	 $this->default_valuta_name=$PHPShopValuta->getName();
	 $this->default_valuta_code=$PHPShopValuta->getCode();
	 $this->default_valuta_kurs=$PHPShopValuta->getKurs();
	 
	 $kurs_beznal=$System->getParam("kurs_beznal");
	 $PHPShopValuta = new PHPShopValuta($kurs_beznal);
	 $this->default_valuta_kurs_beznal=$PHPShopValuta->getKurs();
	 }
	 
	 
	 
	 // Сумма c учетом скидки
	 function returnSumma($sum,$disc){
	 global $PHPShopSystem;
	 
	 if(!$PHPShopSystem){
	   $kurs=$this->default_valuta_kurs;
	   $format = 0;
	   }
	   else{
	       $kurs=$PHPShopSystem->getDefaultValutaKurs(true);
		   $format = $PHPShopSystem->getSerilizeParam("admoption.price_znak");
		   }
	       
	  
     $sum*=$kurs;
     $sum=$sum-($sum*$disc/100);
     return number_format($sum,$format,".","");
	 }
	 
	 
	 // Поправки по курсу безнал
	 function returnSummaBeznal($sum,$disc){
	 $kurs=$this->default_valuta_kurs_beznal;
     $sum*=$kurs;
     $sum=$sum-($sum*$disc/100);
     return number_format($sum,"2",".","");
     }
}
?>