<?
// Определяем переменые
if(isset($_SESSION['UsersId'])){

switch ($SysValue['nav']['name']){

     case("message"): 
	 $SysValue['other']['formaContent']=
     UsersMessage($_SESSION['UsersId']);
	 $SysValue['other']['formaTitle']="Обратная связь";
	 break;
	 
	 case("order"): 
	 $SysValue['other']['formaContent']=
     UsersOrders($_SESSION['UsersId']);
	 $SysValue['other']['formaTitle']="Контроль заказов";
	 break;
	 
	 case("notice"): 
	     if(isset($productId))
	     $SysValue['other']['formaContent']=UsersNotice($_SESSION['UsersId'],$productId);
		   else $SysValue['other']['formaContent']=UsersNoticeList($_SESSION['UsersId']);
	 $SysValue['other']['formaTitle']="Уведомления";
	 break;
	 
	 
	 default: $SysValue['other']['formaContent']=UsersRom($_SESSION['UsersId']);
	 $SysValue['other']['formaTitle']="Персональные данные";
	 break;
}

}
else {
   
   switch ($SysValue['nav']['name']){
   
	 
	 case("register"): 
	 $SysValue['other']['formaContent']=GetUserRegister();
	 $SysValue['other']['formaTitle']="Регистрация нового пользователя";
	 break;
	 
	 case("useractivite"): 
	 $SysValue['other']['formaContent']=GetUserActivite($_GET['key']);
	 $SysValue['other']['formaTitle']="Регистрация нового пользователя";
	 break;
	 
	 case("sendpassword"): 
	 $SysValue['other']['formaContent']=SendUserPassword();
	 $SysValue['other']['formaTitle']="Личный кабинет";
	 break;
	 
	 default: 
	 $SysValue['other']['formaTitle']="Регистрация нового пользователя";
	 $SysValue['other']['formaContent']=GetUserRegister();
	 break;
}

}


$SysValue['other']['DispShop']=
ParseTemplateReturn($SysValue['templates']['users_page_list']);

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>