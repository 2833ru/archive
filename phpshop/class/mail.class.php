<?
/*
+-------------------------------------+
|  Имя: PHPShopMail                   |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: Отправка почты         |
|  Версия: 1.0                        |
|  Тип: class                         |
|  Зависимости: нет                   |
|  Вызов: Class                       |
+-------------------------------------+
*/

class PHPShopMail {

     var $codepage="windows-1251";
	 var $mime  = "1.0";
	 var $type = "text/plain";
	 var $from;
	 var $zag;
	 var $to;
	 
	 function PHPShopMail($to,$from,$zag,$content){   
     $this->from=$from;
	 $this->zag="=?".$this->codepage."?B?".base64_encode($zag)."?=";
	 $this->to=$to;
	 $content.=$this->getCopyright();
	 $header=$this->getHeader();
	 $this->sendMail($content,$header);
	 }
	 
	 function getHeader(){
	 $header = "MIME-Version: ".$this->mime."\n";
     $header.= "From:   <".$this->from.">\n";
     $header.= "Content-Type: ".$this->type."; charset=".$this->codepage."\n";
	 $header.= "Content-Transfer-Encoding: 8bit\n";
	 return $header;
	 }
	 
	 function sendMail($content,$header){
	 mail($this->to,$this->zag,$content,$header);
	 }
	 
	 function getCopyright(){
	 $s="
	 
	 
Powered & Developed by www.PHPShop.ru
".$GLOBALS['SysValue']['license']['product_name'];
     return $s;
	 }
}


?>
