<?
/*
+-------------------------------------+
|  Имя: PHPShopArray                  |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: Массив опорных данных  |
|  Версия: 1.0                        |
|  Тип: parent class                  |
|  Зависимости: нет                   |
|  Вызов: Parent Object               |
+-------------------------------------+
*/



if (!defined("OBJENABLED")) define("OBJENABLED", dirname(__FILE__));

class PHPShopArray {
	 var $objBase;
	 var $objRow;
	 var $objDebug=true;
	 var $objType=1; // 1 - многомерный массив, 2 - одномерный масив
	 var $objArg;
	 var $objArgNum;
	 var $objArray;
	 var $objSQL;
	 
     function PHPShopArray(){
	 $this->objArg=func_get_args();
	 $this->objArgNum=func_num_args();
	 $this->setArray();
	 }
	 
	 function setArray(){
	 $array="";
	 if($this->objArgNum>0){
	 $sql_str="";
     foreach($this->objArg as $v) $sql_str.=$v.",";
	 $sql_str=substr($sql_str,0,strlen($sql_str)-1);
	 }
	  else $sql_str="*";
	 
	 $sql="select ".$sql_str." from ".$this->objBase." ".$this->objSQL;
	   if($this->objDebug)
          $objResult=mysql_query($sql) or die($this->debug($sql));
		  else $objResult=mysql_query($sql);
	        while ($objRow = mysql_fetch_array($objResult)){
				 switch($this->objType){
				      case(1):
					  foreach($this->objArg as $val) $_array[$val]=$objRow[$val];
					  $array[$objRow[$this->objArg[0]]]=$_array;
					  break;
					  
					  case(2):
					  $array[$objRow[$this->objArg[0]]]=$objRow[$this->objArg[1]];
					  break;
				}
	 }
	 $this->objArray=$array;
	 @$GLOBALS['SysValue']['sql']['num']++;
	 }
	 
	 
	 function debug($sql){
     if($this->objDebug)
	 exit("Нет результата для таблицы  ".$this->objBase."<br>Sql: ".$sql."<br> File: ".OBJENABLED."/".str_replace("phpshop","",get_class($this)).".class.php");
	 }
	 
	 
	 function getArray(){
	 return $this->objArray;
	 }
	 
	 function getParam($param){
	 $param=explode(".",$param);
	 return $this->objArray[$param[0]][$param[1]];
	 }
	 
	 // Преобразование в ключевой массив
	 function getKey($param){
	 $param=explode(".",$param);
	 $array = $this->objArray;
	 foreach($array as $val)
	        foreach($val as $key=>$v)
	      if($key == $param[1]) $newArray[$val[$param[0]]]=$v;
	 return $newArray;
	 }
}

?>