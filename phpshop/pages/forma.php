<? 

// Определяем переменые
$SysValue['other']['pageTitle']= "Форма связи";


// Шлем мыло менеджеру
if(@$_POST['send'] == 1){


  if(!empty($_SESSION['text']) and $_POST['key'] == $_SESSION['text']){

$codepage  = "windows-1251";     
$header_mes  = "MIME-Version: 1.0\n";
$header_mes .= "From:   <".$_POST['mail'].">\n";
$header_mes .= "Content-Type: text/plain; charset=$codepage\n";
$header_mes .= "X-Mailer: PHP/";
$zag_mes=$LoadItems['System']['name']." - ".$_POST['tema'];
$content_mes="
Доброго времени!
--------------------------------------------------------

Поступил сообщение с интернет-магазина '".$LoadItems['System']['name']."'

Тема: ".$_POST['tema']."
Контактное лицо: ".$_POST['name']."
E-mail: ".$_POST['mail']."
Телефон: ".$_POST['tel']."
Компания: ".$_POST['company']."
Сообщение: ".$_POST['content']."

---------------------------------------------------------


Дата/время: ".date("d-m-y H:i a")."
IP:".$REMOTE_ADDR."
---------------------------------------------------------


Powered & Developed by www.PHPShop.ru
".$SysValue['license']['product_name'];

session_unregister('text');
mail($LoadItems['System']['adminmail2'],$zag_mes, $content_mes, $header_mes);

   // Определяем переменые
$SysValue['other']['mesageText']= "<FONT style=\"font-size:14px;color:red\">
<B>".$SysValue['lang']['good_message_mesage_1']."</B></FONT><BR>".$SysValue['lang']['good_order_mesage_2'];
   
   // Подключаем шаблон
   $SysValue['other']['pageContent']=ParseTemplateReturn($SysValue['templates']['order_forma_mesage']);


   }
   else{
       $SysValue['other']['Error']= "Ошибка кода, повторите попытку ввода кода на картинке";
	   $SysValue['other']['pageContent']=ParseTemplateReturn("/forma/page_forma_list.tpl");

       }
 }
 else{


$SysValue['other']['pageContent']= ParseTemplateReturn("/forma/page_forma_list.tpl");


}



// Подключаем шаблон
 $SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['page_page_list']);
@ParseTemplate($SysValue['templates']['shop']);

?>