<?
/*
+-------------------------------------+
|  Имя: PHPShopNav                    |
|  Разработчик: PHPShop Software      |
|  Использование: Enterprise          |
|  Назначение: Навигация              |
|  Версия: 1.0                        |
|  Тип: class                         |
|  Зависимости: нет                   |
|  Вызов: Object                      |
+-------------------------------------+
*/

class PHPShopNav{
     var $objNav;

	 function PHPShopNav(){
     $url=parse_url("http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);

     // Вырезаем, если в папке
     $path_parts = pathinfo($_SERVER['PHP_SELF']);
     $root= $path_parts['dirname']."/";
     if($root!="//")
       if($root!="\/") $url=str_replace($path_parts['dirname']."/","/",$url);

     $Url=$url["path"];
     @$Query=@$url["query"];
     @$Path=explode("/",$url["path"]);
     @$File=explode("_",$Path[2]);
     @$Prifix=explode(".",$File[1]);
     @$Name=explode(".",$File[0]);
     @$Page=explode(".",$File[2]);
     @$QueryArray=parse_str($Query,$output);
     $this->objNav=array(
                    "truepath"=>$url["path"],
                    "path"=>$Path[1],
                    "nav"=>$File[0],
                    "name"=>$Name[0],
                    "id"=>$Prifix[0],
                    "page"=>$Page[0],
                    "querystring"=>@$url["query"],
                    "query"=>$output,
                    "url"=>$url["path"]);
	 $GLOBALS['SysValue']['nav']=$this->objNav;
	 }
	 
	 function getPath(){
	 return $this->objNav['path'];
	 }
	 
	 function getNav(){
	 return $this->objNav['nav'];
	 }
	 
	 function getId(){
	 return $this->objNav['id'];
	 }
}
?>
