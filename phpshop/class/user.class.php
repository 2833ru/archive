<?
/*
+-------------------------------------+
|  Имя: PHPShopUser                   |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: Авт. Пользователи      |
|  Версия: 1.0                        |
|  Тип: Extends class                 |
|  Зависимости: нет                   |
|  Вызов: Object                      |
+-------------------------------------+
*/

if (!defined("OBJENABLED")){
require_once(dirname(__FILE__)."/obj.class.php");
}

class PHPShopUser extends PHPShopObj{
	 
	 function PHPShopUser($objID){
	 $this->objID=$objID;
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name27'];
	 parent::PHPShopObj();
	 }
	 
	 function getName(){
	 return parent::getParam("name");
	 }

}


class PHPShopUserStatus extends PHPShopObj{
	 
	 function PHPShopUserStatus($objID){
	 $this->objID=$objID;
	 $this->objBase=$GLOBALS['SysValue']['base']['table_name28'];
	 parent::PHPShopObj();
	 }
	 
	 function getPrice(){
	 return parent::getParam("price");
	 }
	 
	 function getDiscount(){
	 return parent::getParam("discount");
	 }

}


class PHPShopUserFunction{

     function ChekDiscount($mysum){
	 global $PHPShopSystem;
	 $maxsum=0;
	 $userdiscount=0;
     $sql="select * from ".$GLOBALS['SysValue']['base']['table_name23']." where sum < '$mysum' and enabled='1'";
     $result=mysql_query($sql);
     while($row = mysql_fetch_array($result)){
          $sum=$row['sum'];
          if($sum>$maxsum){
	      $maxsum=$sum;
	      $maxdiscount=$row['discount'];
	  }
     }
	 
	 if(!empty($_SESSION['UsersStatus'])){
	 $PHPShopUserStatus = new PHPShopUserStatus($_SESSION['UsersStatus']);
	 $userdiscount = $PHPShopUserStatus->getDiscount();
	 } else $userdiscoun=0;
     if($userdiscount>@$maxdiscount) @$maxdiscount=$userdiscount;
     $sum=$mysum-($mysum*@$maxdiscount/100);
	 $format = $PHPShopSystem->getSerilizeParam("admoption.price_znak");
     $array=array(0+@$maxdiscount,number_format($sum,$format,".",""));
     return $array;
	 }

}
?>