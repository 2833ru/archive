<?
/*
+-------------------------------------+
|  Имя: PHPShopObj                    |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: Родительский класс     |
|  Версия: 1.0                        |
|  Тип: parent class                  |
|  Зависимости: нет                   |
|  Вызов: Parent Object               |
+-------------------------------------+
*/



if (!defined("OBJENABLED")) define("OBJENABLED", dirname(__FILE__));

class PHPShopObj {
     var $objID;
	 var $objBase;
	 var $objRow;
	 var $objDebug=false;
	 
     function PHPShopObj(){
	 $this->setRow();
	 }
	 
	 // Вывод колонки данных
	 function setRow(){
	 $sql="select * from ".$this->objBase." where id=".$this->objID." limit 1";
	   if($this->objDebug)
          $result=mysql_query($sql) or die($this->debug($sql));
		  else $result=mysql_query($sql);
	 $this->objRow=@mysql_fetch_array($result);
	 }
     
	 function debug($sql){
     if($this->objDebug)
	 exit("Нет результата для таблицы  ".$this->objBase."<br>Sql: ".$sql."<br> File: ".OBJENABLED."/".str_replace("phpshop","",get_class($this)).".class.php");
	 }
	 
	 
	 // Вывод параметра
	 function getParam($paramName){
	 return $this->objRow[$paramName];
	 }
	 
	 function getArray(){
	 return $this->objRow;
	 }
	 
	 // Загрузка класса
	 function loadClass($class_name){
	 $class_path=OBJENABLED."/".$class_name.".class.php";
	 if(file_exists($class_path)) include_once($class_path);
	   else echo "Нет файла ".$class_path;
	 }
	 
	 // Десериализация параметра
	 function unserializeParam($paramName){
	 return unserialize($this->getParam($paramName));
	 }

}

?>