<?
if(isset($_POST['getopros'])){

// Определяем переменые
if(isset($_COOKIE['opros']))
Update_opros_base($_POST['getopros'],0);
else {
// Пишим куку
setcookie("opros", $_POST['getopros'], time()+60*60*24*1, "/opros/", $SERVER_NAME, 0);
Update_opros_base($_POST['getopros'],1);
}}

$SysValue['other']['DispShop'] = Vivod_opros_result();
// Подключаем шаблон 
@ParseTemplate($SysValue['templates']['shop']);
?>
	