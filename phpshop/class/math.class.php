<?
/*
+-------------------------------------+
|  Имя: PHPShopMath                   |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: Вычисление             |
|  Версия: 1.0                        |
|  Тип: class                         |
|  Зависимости: PHPShopSystem         |
|  Вызов: Function                    |
+-------------------------------------+
*/


class PHPShopMath {

     function DoZero($price){
     if(empty($price)) return 0;
     else return $price;
     }
	 
	 function Zero($price){
     return PHPShopMath::DoZero($price);
     }
	 
	 // Подсчет суммы от скидки и курса
	 function ReturnSumma($sum,$disc){
	 global $PHPShopSystem;
	 if(!$PHPShopSystem){
	 $PHPShopSystem = new PHPShopSystem();
	 }
     $kurs=$PHPShopSystem->getDefaultValutaKurs();
     $sum*=$kurs;
     $sum=$sum-($sum*$disc/100);
     return number_format($sum,"2",".","");
     }
}


?>
