<?php

/**
 * Раздел личного кабинета пользователей
 * @package PHPShopCoreDepricated
 */

// Выход пользователя
if($SysValue['nav']['querystring']=="LogOut") {
    session_unregister('UsersId');
    session_unregister('UsersStatus');
}

// Перенавправление формы авторизации
if($_POST['login'] and $_POST['password']) {
    $ChekUsersBase=ChekUsersBase($_POST['login'],$_POST['password']);

    if($ChekUsersBase!=0) {
        $UsersId=$ChekUsersBase[0];
        $UsersStatus=$ChekUsersBase[1];
        session_register('UsersId');
        session_register('UsersStatus');

        // Запоминаем пользователя
        if(isset($_POST['user_enter'])) {
            if($_POST['safe_users']==1) {
                setcookie("UserLogin", $_POST['login'], time()+60*60*24*30, "/",$_SERVER['SERVER_NAME'], 0);
                setcookie("UserPassword", $_POST['password'], time()+60*60*24*30, "/",$_SERVER['SERVER_NAME'], 0);
                setcookie("UserChecked", "checked", time()+60*60*24*30, "/",$_SERVER['SERVER_NAME'], 0);
            }else {
                setcookie("UserLogin", "", time()+60*60*24*30, "/",$SERVER_NAME, 0);
                setcookie("UserPassword", "", time()+60*60*24*30, "/",$SERVER_NAME, 0);
                setcookie("UserChecked", "", time()+60*60*24*30, "/",$SERVER_NAME, 0);
            }
        }

        if(preg_match("/LogOut/",$_SERVER['REQUEST_URI']))
            $url_user = str_replace("?LogOut","#userPage",$_SERVER['REQUEST_URI']);
        else $url_user =$_SERVER['REQUEST_URI'];

        header("Location: ".$url_user);
    }
    else $SysValue['other']['usersError']=$SysValue['lang']['error_login'];
}


// Определяем переменные
if(isset($_SESSION['UsersId'])) {

    switch ($SysValue['nav']['name']) {

        case("message"):
            $SysValue['other']['formaContent']=
                    UsersMessage($_SESSION['UsersId']);
            $SysValue['other']['formaTitle']="Обратная связь";
            break;

        case("order"):

            // Функции заказов пользователя
            include_once($SysValue['file']['usersorders']);

            $SysValue['other']['formaContent']=
                    UsersOrders($_SESSION['UsersId']);
            $SysValue['other']['formaTitle']="Контроль заказов";
            break;

        case("notice"):

            // Функции уведомлений пользователя
            include_once($SysValue['file']['usersnotice']);

            $productId = $SysValue['nav']['query']['productId'];
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

    switch ($SysValue['nav']['name']) {

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

$SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['users_page_list']);

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>