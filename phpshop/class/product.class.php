<?
/*
+-------------------------------------+
|  Имя: PHPShopProduct                |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: База товаров           |
|  Версия: 1.0                        |
|  Тип: Extends class                 |
|  Зависимости: PHPShopUser                   |
|  Вызов: Object                      |
+-------------------------------------+
*/


if (!defined("OBJENABLED")){
require_once(dirname(__FILE__)."/obj.class.php");
require_once(dirname(__FILE__)."/array.class.php");
}

class PHPShopProduct extends PHPShopObj{
	 
	 function PHPShopProduct($objID){
	 $this->objID=$objID;
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name2'];
	 parent::PHPShopObj();
	 }
	 
	 function getName(){
	 return parent::getParam("name");
	 }
	 
	 
	 function getValutaID(){
	 return parent::getParam("baseinputvaluta");
	 }
}


class PHPShopProductArray extends PHPShopArray{
	 
	 function PHPShopProductArray($sql=""){
	 $this->objSQL=$sql;
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name2'];
	 parent::PHPShopArray('id','uid','name','category','price','price_n','sklad','odnotip','vendor','title_enabled',
	 'datas','page','user','descrip_enabled','keywords_enabled','pic_small','pic_big','parent','baseinputvaluta');
	 }
}


class PHPShopProductFunction{
     
	 // Цена с учетом валюты
     function GetPriceValuta($id,$price,$formats=0,$baseinputvaluta=false,$order=false){ 
     global $SysValue,$LoadItems,$PHPShopValutaArray,$PHPShopSystem;
	 
	 if(!$LoadItems){
	 $LoadItems['Valuta']=$PHPShopValutaArray->getArray();
	 $LoadItems['System']=$PHPShopSystem->getArray();
	 }
	 
	 // Если выбрана другая валюта
	 
     $format = $PHPShopSystem->getSerilizeParam("admoption.price_znak");
     
	 // Выборка из базы нужной колонки цены для автор. пользователя
	 if(!empty($_SESSION['UsersStatus'])){
	 $PHPShopUser = new PHPShopUserStatus($_SESSION['UsersStatus']);
     $GetUsersStatusPrice=$PHPShopUser->getPrice();
	  if($GetUsersStatusPrice>1){
	   $pole="price".$GetUsersStatusPrice;
	   $PHPShopProduct = new PHPShopProduct($id);
	   
	   $user_price=$PHPShopProduct->getParam($pole);
	   if(!empty($user_price)) $price=$user_price;
	   
	 }}
	 
	 
	 // Учет валюты товара
     if ($baseinputvaluta) { //Если прислали баз. валюту
	   if ($baseinputvaluta!==$LoadItems['System']['dengi']) {//Если присланная валюта отличается от базовой
		$price=$price/$LoadItems['Valuta'][$baseinputvaluta]['kurs']; //Приводим цену в базовую валюту
	   }
     }

	 // Если выбрана другая валюта, order - флаг для расчета корзины толкьо в деф. валюте
	 if($order) $valuta=$LoadItems['System']['dengi'];
	  elseif (isset($_SESSION['valuta'])) $valuta=$_SESSION['valuta'];
        else $valuta=$LoadItems['System']['dengi'];
     
	 
	 // Правка на курс
     $price=$price*$LoadItems['Valuta'][$valuta]['kurs'];
	 
	 // Наценка
	 $price=($price+(($price*$LoadItems['System']['percent'])/100));
     return number_format($price,$format,'.','');
     }
}
?>