<?

if(@$error == "key")
$SysValue['other']['Error']="Неверный ключ, указанный на картинке!";

// Определяем переменые
$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['gbook_forma_otsiv']);
 
// Подключаем шаблон 
@ParseTemplate($SysValue['templates']['shop']);
?>