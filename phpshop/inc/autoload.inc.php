<?php
/**
 * Автозагрузка элементов
 * @package PHPShopInc
 */

// Определяем переменные
$SysValue['other']['telNum']=$PHPShopSystem->getValue('tel');
$SysValue['other']['name']=$PHPShopSystem->getValue('name');
$SysValue['other']['company']=$PHPShopSystem->getValue('company');
$SysValue['other']['descrip']=$PHPShopSystem->getValue('descrip');
$SysValue['other']['adminMail'] =$PHPShopSystem->getValue('adminmail2');
$SysValue['other']['pageCss']=$SysValue['dir']['templates'].chr(47).$_SESSION['skin'].chr(47).$SysValue['css']['default'];
$SysValue['other']['pathTemplate']=$SysValue['dir']['templates'].chr(47).$_SESSION['skin'];
$SysValue['other']['serverName']=$_SERVER['SERVER_NAME'];
$SysValue['other']['UserLogin']=$_SESSION['UserLogin'];
$SysValue['other']['UserPassword']=$_SESSION['UserPassword'];
$SysValue['other']['UserChecked']=$_SESSION['UserChecked'];
$SysValue['other']['ShopDir']=$SysValue['dir']['dir'];

/*
 * Загрузка модулей
*/
include($SysValue['class']['modules']);
$PHPShopModules = new PHPShopModules();
$PHPShopModules->doLoad();

/*
 * Подключаем кеш API 2.X
*/
$LoadItems=CacheReturnBase($sid);
if(!$LoadItems["Podcatalog"])
    $LoadItems["Podcatalog"]=&$LoadItems["Catalog"];

/*
 * Подключаем модули autoload
*/
foreach($SysValue['autoload'] as $val)
    if (is_file($val)) include_once($val);

/*
 * Поддежка API 2.X
*/
include($SysValue['file']['meta']);
include($SysValue['file']['lastmodified']);
if(empty($GLOBALS['p'])) $GLOBALS['p']=1;

// Вывод дерево каталогов товаров
$SysValue['other']['leftCatal']=Vivod_cats();  

// Вывод каталогов страниц
$SysValue['other']['pageCatal']=Vivod_page_cats();    

// Элементы для витрины начальной страницы API 2.X
$GLOBALS['admoption']=unserialize($LoadItems['System']['admoption']);
if($SysValue['nav']['truepath'] == "/") {

    // Таблица категорий
    include($SysValue['file']['catalogtable']);
    $SysValue['other']['leftCatalTable']=Vivod_cat_table();

    // Элемент спецпредложений на главную
    $SysValue['other']['specMain']=DispSpecMain();

    // Элемент новинок на главную
    $SysValue['other']['specMainTitle'] = $SysValue['lang']['newprod'];
    $SysValue['other']['specMainIcon'] = DispNewIcon("and newtip='1'");
    if(empty($SysValue['other']['specMainIcon']))
        $SysValue['other']['specMainIcon'] = DispNewIcon();

    // Загрузка RSS
    include($SysValue['file']['rssgraber']);
    if($GLOBALS['admoption']['rss_graber_enabled']==1) rss_graber();

    // Элемент оформления последних покупок пользователей
    include($SysValue['file']['nowbuy']);
    
    switch($admoption['nowbuy_enabled']) {
        // Упрощенный вывод товаров ссылками
        case 2: $SysValue['other']['nowBuy']=HitTradeMain();
            break;
        // Обычный вывод товаров с картинкой
        default: $SysValue['other']['nowBuy']=nowbuy();
            break;
    }
}

/**
 * Элементы для всех страниц API 2.X
 */

// Новинки в правую колонку
if($SysValue['nav']['path'] != "shop") {

    $SysValue['other']['specMainTitle'] = $SysValue['lang']['newprod'];
    $SysValue['other']['specMainIcon'] = DispNewIcon("and newtip='1'");

    if(empty($SysValue['other']['specMainIcon'])) {
        $SysValue['other']['specMainTitle'] = $SysValue['lang']['specprod'];
        $SysValue['other']['specMainIcon'] = DispNewIcon("and spec='1'");
    }
}

// Подключаем поиск брендов
//$SysValue['other']['vendorDispA'] = dispBrend(1);

// Авторизация пользователей
if(PHPShopSecurity::true_param($_POST['login'],$_POST['password'])) {

    // Проверка логина и пароля в БД
    $ChekUsersBase=ChekUsersBase($_POST['login'],$_POST['password']);
    if(!empty($ChekUsersBase)) {

        // Логин пользователя
        $_SESSION['UsersId']=$ChekUsersBase[0];

        // Статус пользователя
        $_SESSION['UsersStatus']=$ChekUsersBase[1];

        // Запоминаем пользователя в cookie
        if(isset($_POST['user_enter'])) {
            if(!empty($_POST['safe_users'])) {
                setcookie("UserLogin", $_POST['login'], time()+60*60*24*30, "/",$_SERVER['SERVER_NAME'], 0);
                setcookie("UserPassword", $_POST['password'], time()+60*60*24*30, "/",$_SERVER['SERVER_NAME'], 0);
                setcookie("UserChecked", "checked", time()+60*60*24*30, "/",$_SERVER['SERVER_NAME'], 0);
            }else {
                setcookie("UserLogin", "", time()+60*60*24*30, "/",$_SERVER['SERVER_NAME'], 0);
                setcookie("UserPassword", "", time()+60*60*24*30, "/",$_SERVER['SERVER_NAME'], 0);
                setcookie("UserChecked", "", time()+60*60*24*30, "/",$_SERVER['SERVER_NAME'], 0);
            }
        }

        if(preg_match("/LogOut/",$_SERVER['REQUEST_URI']))
            $url_user = str_replace("?LogOut","#userPage",$_SERVER['REQUEST_URI']);
        else $url_user = $_SERVER['REQUEST_URI'];

        header("Location: ".$url_user);
    }
    else $SysValue['other']['usersError']=$SysValue['lang']['error_login'];
}

// Выход пользователя
if(isset($GLOBALS['SysValue']['nav']['query']['LogOut'])){
    unset($_SESSION['UsersId']);
    unset($_SESSION['UsersStatus']);
    $url_user = str_replace("?LogOut","",$_SERVER['REQUEST_URI']);
    header("Location: ".$url_user);
}

// Форма авторизации пользователя
if(!isset($_SESSION['UsersId'])) $SysValue['other']['usersDisp']=ParseTemplateReturn($SysValue['templates']['users_forma']);
else $SysValue['other']['usersDisp']=ParseTemplateReturn($SysValue['templates']['users_forma_enter']);

// Перенавправление формы смены валюты
if(isset($_POST['valuta'])) {
    $_SESSION['valuta']=$_POST['valuta'];
    header("Location: ".$_SERVER['REQUEST_URI']);
}

// Опрос
$PHPShopOprosElement = &new PHPShopOprosElement();
$PHPShopOprosElement->init('oprosDisp');

// Мини-новости
$PHPShopNewsElement = &new PHPShopNewsElement();
$PHPShopNewsElement->init('miniNews');

// Баннер
$PHPShopBannerElement = &new PHPShopBannerElement();
$PHPShopBannerElement->init('banersDisp');

// Облако тегов
$PHPShopCloudElement = &new PHPShopCloudElement();
$PHPShopCloudElement->init('cloud');

// Выбор шаблона
$PHPShopSkinElement = &new PHPShopSkinElement();
$PHPShopSkinElement->init('skinSelect');

// Текстовый блок
$PHPShopTextElement = &new PHPShopTextElement();
$PHPShopTextElement->init('leftMenu'); // Вывод левого блока
$PHPShopTextElement->init('rightMenu'); // Вывод правого блока
$PHPShopTextElement->init('topMenu'); // Вывод главного меню

// Выбор валюты
$PHPShopCurrencyElement = new PHPShopCurrencyElement();
$PHPShopCurrencyElement->init('valutaDisp');

// Корзина
$PHPShopCartElement = new PHPShopCartElement();
$PHPShopCartElement->init('miniCart');
?>