<?
// API подключение PHP файла


// Титл
@$SysValue['other']['pageTitl']= "Проверка работы php";


// PHP логика
// Возврат значений только через return!!!
function myFunction(){
global $SERVER_NAME;
$dis="Имя сервера: ".$SERVER_NAME;
return $dis;
}


// Заглавие
@$SysValue['other']['pageTitle']= "PHP TEST";

// Подклюбчения php логики
$SysValue['other']['pageContent']= myFunction();

// Подключение шаблона вывода страниц
$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['page_page_list']);

// Подключаем общий шаблон 
@ParseTemplate($SysValue['templates']['shop']);
?>
	