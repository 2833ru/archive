<?

// Определяем переменые
if($_REQUEST['order'] and $_REQUEST['mail'])
$SysValue['other']['formaContent']=ClientsCheck($_REQUEST['order'],$_REQUEST['mail']);
else{
$SysValue['other']['UserMail']=@$UserMail;
$SysValue['other']['UserOrder']=@$UserOrder;
$SysValue['other']['formaContent']=
ParseTemplateReturn($SysValue['templates']['clients_forma']);
}

$SysValue['other']['DispShop']=
ParseTemplateReturn($SysValue['templates']['clients_page_list']);

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>