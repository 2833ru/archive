<?
/*
+-------------------------------------+
|  Имя: PHPShopDate                   |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: Операции с датами      |
|  Версия: 1.0                        |
|  Тип: class                         |
|  Зависимости: нет                   |
|  Вызов: Function                    |
+-------------------------------------+
*/

class PHPShopDate {

     function dataV($nowtime){
     $Months = array("01"=>"января","02"=>"февраля","03"=>"марта", 
     "04"=>"апреля","05"=>"мая","06"=>"июня", "07"=>"июля",
     "08"=>"августа","09"=>"сентября",  "10"=>"октября",
     "11"=>"ноября","12"=>"декабря");
     $curDateM = date("m",$nowtime); 
     $t=date("d",$nowtime)."-".$curDateM."-".date("y",$nowtime)." ".date("H:i ",$nowtime); 
     return $t;
     }
     
	 function GetUnicTime($data){
     $array=explode("-",$data);
     return @mktime(12, 0, 0, $array[1], $array[0], $array[2]);
     }
	 
}


?>
