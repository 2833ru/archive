<?php

/**
 * Элемент стандартных системных переменных
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCoreElement
 * @version 1.3
 * @package PHPShopElements
 */
class PHPShopCoreElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function __construct() {
        parent::__construct();
    }

    /**
     * Назначение текущего шаблона
     * @return string
     */
    function skin() {

        if (empty($_SESSION['skin'])) {

            // Мультибаза
            if (defined("HostSkin"))
                $_SESSION['skin'] = HostSkin;
            else
                $_SESSION['skin'] = $this->PHPShopSystem->getValue('skin');
        }

        return $_SESSION['skin'];
    }

    /**
     * Проверка существования шаблона,
     * смена на другой найденный шаблон при переименовании папки с шаблоном
     * @return string
     */
    function checkskin() {
        if (!@file_exists("phpshop/templates/" . $_SESSION['skin'] . "/main/index.tpl")) {
            $dir = $this->getValue('dir.templates') . chr(47);
            if (is_dir($dir)) {
                if (@$dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if (@is_file($dir . $file . chr(47) . 'main/index.tpl')) {
                            $_SESSION['skin'] = $file;
                            header('Location: /?status=template_error');
                        }
                    }
                    closedir($dh);
                }
            }
            exit('Template error!');
        }
    }

    /**
     * Определение стандратных системных переменных для шаблонов
     * (имя, телефон, почта администратора, дата, логотип)
     */
    function setdefault() {

        // Мультибаза
        if (defined("HostID")) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['servers']);
            $PHPShopOrm->debug = false;
            $showcaseData = $PHPShopOrm->select(array('*'), array('enabled' => "='1'", 'host' => "='" . str_replace('www.', '', $_SERVER['HTTP_HOST']) . "'"), array('order' => 'id'), array('limit' => 1));
            if (is_array($showcaseData)) {

                if (!empty($showcaseData['currency']) and $this->PHPShopNav->notPath('order')){
                    
                    $_SESSION['valuta'] =  $showcaseData['currency'];
                    $_SESSION['lang'] = $showcaseData['lang'];
                    //$this->PHPShopSystem->setParam("dengi", $showcaseData['currency']);
                    //$this->PHPShopSystem->setParam("kurs", $showcaseData['currency']);
                    //$this->PHPShopSystem->setParam("kurs_beznal", $showcaseData['currency']);
                    
                }

                if (!empty($showcaseData['tel']))
                    $this->PHPShopSystem->setParam("tel", $showcaseData['tel']);

                if (!empty($showcaseData['adminmail']))
                    $this->PHPShopSystem->setParam("adminmail2", $showcaseData['adminmail']);

                if (!empty($showcaseData['company']))
                    $this->PHPShopSystem->setParam('company', $showcaseData['company']);

                if (!empty($showcaseData['name']))
                    $this->PHPShopSystem->setParam('name', $showcaseData['name']);

                if (!empty($showcaseData['title']))
                    $this->PHPShopSystem->setParam('title', $showcaseData['title']);

                if (!empty($showcaseData['descrip']))
                    $this->PHPShopSystem->setParam('descrip', $showcaseData['descrip']);

                if (!empty($showcaseData['logo']))
                    $this->PHPShopSystem->setParam('logo', $showcaseData['logo']);

                if (!empty($showcaseData['adres']))
                    $this->set('streetAddress', $showcaseData['adres']);

                if (!empty($showcaseData['skin']))
                    define("HostSkin", $showcaseData['skin']);
            }
        } else {
            $this->set('streetAddress', $this->PHPShopSystem->getSerilizeParam('bank.org_adres'));
            $_SESSION['lang'] = $this->PHPShopSystem->getSerilizeParam("admoption.lang");
        }

        // Язык
        $GLOBALS['PHPShopLang'] = new PHPShopLang(array('locale' => $_SESSION['lang'], 'path' => 'shop'));

        // Телефон
        $tel = $this->PHPShopSystem->getValue('tel');
        $this->set('telNum', $tel);

        // Телефон для звонков
        if (strstr($tel, ","))
            $tel_xs = explode(",", $tel);
        else
            $tel_xs[] = $tel;

        $this->set('telNumMobile', $tel_xs[0]);
        $this->set('rule', $this->lang('rule'));
        $this->set('name', $this->PHPShopSystem->getValue('name'));
        $this->set('company', $this->PHPShopSystem->getValue('company'));
        $this->set('descrip', $this->PHPShopSystem->getValue('descrip'));
        $this->set('adminMail', $this->PHPShopSystem->getValue('adminmail2'));
        $this->set('pathTemplate', $this->getValue('dir.templates') . chr(47) . $_SESSION['skin']);
        $this->set('serverName', $_SERVER['SERVER_NAME']);
        $this->set('serverShop', $_SERVER['SERVER_NAME']);
        if (!empty($_SESSION['UserLogin']))
            $this->set('UserLogin', $_SESSION['UserLogin']);
        $this->set('ShopDir', $this->getValue('dir.dir'));
        $this->set('date', date("d-m-y H:i a"));
        $this->set('year', date("Y"));
        $this->set('user_ip', $_SERVER['REMOTE_ADDR']);
        $this->set('NavActive', $this->PHPShopNav->getPath());
        $v = $this->getValue('upload.version');
        $this->set('version', substr($v, 0, 1) . '.' . substr($v, 1, 1));
        $this->set('hcs', '<!--');
        $this->set('hce', '-->');

        // Цветовая тема шаблона
        $theme = $this->PHPShopSystem->getSerilizeParam('admoption.' . $_SESSION['skin'] . '_theme');
        if (!empty($theme))
            $this->set($_SESSION['skin'] . '_theme', $theme);

        $theme2 = $this->PHPShopSystem->getSerilizeParam('admoption.' . $_SESSION['skin'] . '_theme2');
        if (!empty($theme2))
            $this->set($_SESSION['skin'] . '_theme2', $theme2);

        $theme3 = $this->PHPShopSystem->getSerilizeParam('admoption.' . $_SESSION['skin'] . '_theme3');
        if (!empty($theme3))
            $this->set($_SESSION['skin'] . '_theme3', $theme3);

        // Логотип
        $this->set('logo', $this->PHPShopSystem->getLogo());

        // DaData.ru
        if ($this->PHPShopSystem->getSerilizeParam('admoption.dadata_enabled')) {
            $dadataToken = $this->PHPShopSystem->getSerilizeParam('admoption.dadata_token');
            if (empty($dadataToken))
                $dadataToken = 'b13e0b4fd092a269e229887e265c62aba36a92e5';
            $this->set('dadataToken', $dadataToken);
        }
        else
            $this->set('dadataToken', null);
    }

    /**
     * Стили шаблона дизайна
     * @return string
     */
    function pageCss() {
        $this->set('pathTemplate', $this->getValue('dir.templates') . chr(47) . $_SESSION['skin']);
        return $this->getValue('dir.templates') . chr(47) . $_SESSION['skin'] . chr(47) . $this->getValue('css.default');
    }

}

/**
 * Элемент формы авторизации пользователя
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopUserElement
 * @version 1.3
 * @package PHPShopElements
 */
class PHPShopUserElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function __construct() {
        $this->debug = false;
        $this->template_debug = true;
        $this->objBase = $GLOBALS['SysValue']['base']['shopusers'];
        parent::__construct();

        // Если есть параметр from, нужно сохранить реферальную страницу и вернуть на нее пользователя после авторизации, регистрации.
        if ($_REQUEST['from'] AND !$_REQUEST['fromSave'])
            $this->set('fromSave', $_SERVER['HTTP_REFERER']);
        else
            $this->set('fromSave', $_REQUEST['fromSave']);

        // Экшены
        $this->setAction(array('post' => array('user_enter', 'user_register'), 'get' => 'logout'));
    }

    /**
     * Кодирование пароля
     * @param string $str строка
     * @return string кодированная строка
     */
    function encode($str) {
        return base64_encode(trim($str));
    }

    /**
     * Экшен выхода пользователя
     */
    function logout() {
        unset($_SESSION['UsersId']);
        unset($_SESSION['UsersStatus']);
        unset($_SESSION['UsersLogin']);
        unset($_SESSION['UsersName']);
        unset($_SESSION['UsersMail']);
        unset($_SESSION['UsersStatus']);
        unset($_SESSION['UsersStatusPice']);
        $url_user = str_replace("?logout=true", "", $_SERVER['REQUEST_URI']);
        header("Location: " . $url_user);
    }

    /**
     * ссылка на вишлист с кол-вом в нём товара
     */
    function wishlist() {
        if (!empty($_SESSION['UsersId']) and PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $this->set('wishlistCount', $_SESSION['wishlistCount']);
            $dis = $this->parseTemplate('users/wishlist/wishlist_top_enter.tpl');
        } else {
            //$this->set('wishlistCount', 0);
            $this->set('wishlistCount', count($_SESSION['wishlist']));
            $dis = $this->parseTemplate('users/wishlist/wishlist_top_enter.tpl');
        }
        return $dis;
    }

    /**
     * Проверка авторизации
     * @return bool
     */
    function autorization() {
        if (PHPShopSecurity::true_login($_POST['login']) and PHPShopSecurity::true_passw($_POST['password'])) {
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->debug = $this->debug;
            $data = $PHPShopOrm->select(array('*'), array('login' => '="' . trim($_POST['login']) . '"', 'password' => '="' . $this->encode($_POST['password']) . '"', 'enabled' => "='1'"), false, array('limit' => 1));
            if (is_array($data) AND PHPShopSecurity::true_num($data['id'])) {

                // сохраняем вишлист который был в сессии до авторизаци.
                $wishlist = unserialize($data['wishlist']);
                if (!is_array($wishlist))
                    $wishlist = array();
                if (is_array($_SESSION['wishlist']))
                    foreach ($_SESSION['wishlist'] as $key => $value) {
                        $wishlist[$key] = 1;
                    }
                $_SESSION['wishlistCount'] = count($wishlist);

                // Очищаем вишлист из сессии, он сохранён в БД
                unset($_SESSION['wishlist']);

                $wishlist = serialize($wishlist);
                $PHPShopOrm->update(array('wishlist' => $wishlist), array('id' => '=' . $data['id']), false);

                // ID пользователя
                $_SESSION['UsersId'] = $data['id'];

                // Логин пользователя
                $_SESSION['UsersLogin'] = $data['login'];

                // Имя пользователя
                $_SESSION['UsersName'] = $data['name'];

                // Статус пользователя
                $_SESSION['UsersStatus'] = $data['status'];

                // E-mail пользователя для заказа
                if (PHPShopSecurity::true_email($data['login']))
                    $_SESSION['UsersMail'] = $data['login'];
                else
                    $_SESSION['UsersMail'] = $data['mail'];

                // Дата входа
                $this->log();

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $data);


                return true;
            }
            else
                $this->set("shortAuthError", __("Неверный логин или пароль"));
        }
        else
            $this->set("shortAuthError", __("Неверный логин или пароль"));
    }

    /**
     * Запись даты авторизации пользователя
     */
    function log() {
        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->update(array('datas_new' => time()), array('id' => '=' . $_SESSION['UsersId']));
    }

    /**
     * Экшен входа регистраци пользователя по страницы оформления заказа
     */
    function user_register() {
        // Импортируем роутер личного кабинета для возможности регистрации со страницы оформления заказа
        if (!class_exists('PHPShopUsers'))
            PHPShopObj::importCore('users');
        if (class_exists('PHPShopUsers')) {
            $PHPShopUsers = new PHPShopUsers();
            $PHPShopUsers->action_add_user();
        }
    }

    /**
     * Экшен входа пользователя
     */
    function user_enter() {
        if ($this->autorization()) {

            // Запоминаем пользователя в cookie
            if (!empty($_POST['safe_users'])) {
                setcookie("UserLogin", trim($_POST['login']), time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
                setcookie("UserPassword", trim($_POST['password']), time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
                setcookie("UserChecked", 1, time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
            } else {
                setcookie("UserLogin", "", time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
                setcookie("UserPassword", "", time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
                setcookie("UserChecked", "", time() + 60 * 60 * 24 * 30, "/", $_SERVER['SERVER_NAME'], 0);
            }

            // Редирект
            if (preg_match("/LogOut/", $_SERVER['REQUEST_URI']))
                $url_user = str_replace("?LogOut", "#userPage", $_SERVER['REQUEST_URI']);
            elseif (!empty($_GET['key']))
                $url_user = $this->getValue('dir.dir') . '/users/';
            else
                $url_user = $_SERVER['REQUEST_URI'];

            // header("Location: " . $url_user);
            $this->checkRedirect();
        }
        else
            $this->set('usersError', $this->lang('error_login'));
    }

    /**
     * если после авторизации, регистрации необходимо направить на страницу с которой пришли, перенаправляем
     */
    function checkRedirect() {
        // если после авторизации, регистрации необходимо направить на страницу с которой пришли, перенаправляем
        if ($_REQUEST['from'] AND $_REQUEST['fromSave'])
            header("Location: " . $_REQUEST['fromSave']);
    }

    /**
     * Форма авторизации пользователя
     */
    function usersDisp() {
        if (!empty($_SESSION['UsersId']) and PHPShopSecurity::true_num($_SESSION['UsersId'])) {
            $this->set('UsersLogin', $_SESSION['UsersLogin']);
            $this->set('UsersName', $_SESSION['UsersName']);
            $dis = $this->parseTemplate($this->getValue('templates.users_forma_enter'));
        } else {

            // Блок авторизации, данные из cookie
            if (PHPShopSecurity::true_num($_COOKIE['UserChecked']))
                $this->set('UserChecked', 'checked');

            if (PHPShopSecurity::true_email($_COOKIE['UserLogin']))
                $this->set('UserLogin', $_COOKIE['UserLogin']);

            if (PHPShopSecurity::true_passw($_COOKIE['UserPassword']))
                $this->set('UserPassword', $_COOKIE['UserPassword']);

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__);

            $dis = $this->parseTemplate($this->getValue('templates.users_forma'));
        }
        return $dis;
    }

}

/**
 * Элемент каталоги страниц
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopPageCatalogElement
 * @version 1.2
 * @package PHPShopElements
 */
class PHPShopPageCatalogElement extends PHPShopElements {

    /**
     * @var bool проверять на единичные каталоги
     */
    var $chek_page = true;
    var $debug = false;

    /**
     * Конструктор
     */
    function __construct() {
        $this->template_debug = true;
        $this->objBase = $GLOBALS['SysValue']['base']['page_categories'];
        parent::__construct();
    }

    /**
     * Вывод навигации каталогов
     * @return string
     */
    function pageCatal() {
        $dis = null;
        $i = 0;

        $this->PHPShopOrm->cache = true;
        $data = $this->PHPShopOrm->select(array('*'), array('parent_to' => '=0'), array('order' => 'num'), array("limit" => 100));

        // Перехват модуля в начале
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $data, 'START');
        if ($hook)
            return $hook;

        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogId', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));

                // Если есть страницы
                if ($this->chek($row['id'])) {

                    $this->set('catalogName', $row['name']);
                    $this->set('catalogId', $row['id']);
                    $this->set('catalogPodcatalog', null);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_page_forma_2'));
                } else {
                    $this->set('catalogPodcatalog', $this->subcatalog($row['id']));
                    $this->set('catalogName', $row['name']);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_page_forma'));
                }

                $i++;
            }
        return $dis;
    }

    /**
     * Проверка подкаталогов
     * @param Int $id ИД каталога
     * @return bool
     */
    function chek($id) {
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.page_categories'));
        $PHPShopOrm->debug = $this->debug;
        $num = $PHPShopOrm->select(array('id'), array('parent_to' => "=$id"), false, array('limit' => 1));
        if (empty($num['id']))
            return true;
    }

    /**
     * Вывод подкаталогов
     * @param Int $n ИД каталога
     * @return string
     */
    function subcatalog($n) {
        $dis = null;
        $i = 0;
        $n = PHPShopSecurity::TotalClean($n, 1);
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.page_categories'));
        $data = $PHPShopOrm->select(array('*'), array('parent_to' => '=' . $n), array('order' => 'num'), array("limit" => 100));

        // Перехват модуля в начале
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $data, 'START');
        if ($hook)
            return $hook;

        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogId', $n);
                $this->set('catalogUid', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogLink', 'CID_' . $row['id']);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));
                $this->set('catalogName', $row['name']);
                $i++;

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.podcatalog_page_forma'));
            }
        return $dis;
    }

}

/**
 * Элемент текстовые блоки
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopTextElement
 * @version 1.1
 * @package PHPShopElements
 */
class PHPShopTextElement extends PHPShopElements {

    var $debug = false;

    /**
     * Конструктор
     */
    function PHPShopTextElement() {
        $this->objBase = $GLOBALS['SysValue']['base']['menu'];
        $this->template_debug = true;
        parent::__construct();
    }

    /**
     * Вывод текстового блока в левую часть
     * @return string
     */
    function leftMenu() {
        $dis = null;

        $where['flag'] = "='1'";
        $where['element'] = "='0'";

        // Мультибаза
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['element'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        $data = $this->PHPShopOrm->select(array('*'), $where, array('order' => 'num'), array("limit" => 20));
        if (is_array($data))
            foreach ($data as $row) {
                if (empty($row['dir'])) {

                    // Определяем переменные
                    $this->set('leftMenuName', $row['name']);
                    $this->set('leftMenuContent', Parser($row['content']));

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row);

                    // Подключаем шаблон
                    $dis.=$this->parseTemplate($this->getValue('templates.left_menu'));
                } else {
                    $dirs = explode(",", $row['dir']);
                    foreach ($dirs as $dir)
                        if (@strpos($_SERVER['REQUEST_URI'], $dir) or $_SERVER['REQUEST_URI'] == $dir) {
                            $this->set('leftMenuName', $row['name']);
                            $this->set('leftMenuContent', Parser($row['content']));

                            // Перехват модуля
                            $this->setHook(__CLASS__, __FUNCTION__, $row);

                            // Подключаем шаблон
                            $dis.=$this->parseTemplate($this->getValue('templates.left_menu'));
                        }
                }
            }
        return $dis;
    }

    /**
     * Вывод текстового блока в правую часть
     * @return string
     */
    function rightMenu() {
        $dis = null;

        $where['flag'] = "='1'";
        $where['element'] = "='1'";

        // Мультибаза
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['element'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'num'), array("limit" => 20));
        if (is_array($data))
            foreach ($data as $row) {
                if (empty($row['dir'])) {

                    // Определяем переменные
                    $this->set('leftMenuName', $row['name']);
                    $this->set('leftMenuContent', Parser($row['content']));

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row);

                    $dis.=$this->parseTemplate($this->getValue('templates.right_menu'));
                } else {
                    $dirs = explode(",", $row['dir']);
                    foreach ($dirs as $dir)
                        if (@strpos($_SERVER['REQUEST_URI'], $dir) or $_SERVER['REQUEST_URI'] == $dir) {
                            $this->set('leftMenuName', $row['name']);
                            $this->set('leftMenuContent', Parser($row['content']));

                            // Перехват модуля
                            $this->setHook(__CLASS__, __FUNCTION__, $row);

                            // Подключаем шаблон
                            $dis.=$this->parseTemplate($this->getValue('templates.right_menu'));
                        }
                }
            }
        return $dis;
    }

    /**
     * Вывод главного навигационного меню
     * @return string
     */
    function topMenu() {
        $dis = null;

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, null, 'START');
        if ($hook)
            return $hook;

        $where['category'] = "=1000";
        $where['enabled'] = "='1'";

        // Мультибаза
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        $objBase = $GLOBALS['SysValue']['base']['page'];
        $PHPShopOrm = new PHPShopOrm($objBase);
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('name', 'link'), $where, array('order' => 'num'), array("limit" => 20));
        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('topMenuName', $row['name']);
                $this->set('topMenuLink', $row['link']);

                // Активная страница
                if ($row['link'] == $this->PHPShopNav->getName(true))
                    $this->set('topMenuActive', 'active');
                else
                    $this->set('topMenuActive', '');

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.top_menu'));
            }

        return $dis;
    }

}

/**
 * Элемент cмена шаблонов
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSkinElement
 * @version 1.1
 * @package PHPShopElements
 */
class PHPShopSkinElement extends PHPShopElements {

    function __construct() {
        parent::__construct();

        // Экшены
        $this->setAction(array('post' => 'skin', 'get' => 'skin'));
    }

    /**
     * Экшен по умолчанию, вывод формы выбора шаблона
     * @return string
     */
    function index() {
        if ($this->PHPShopSystem->getSerilizeParam("admoption.user_skin") == 1) {
            $dir = $this->getValue('dir.templates') . chr(47);
            if (is_dir($dir)) {
                if (@$dh = opendir($dir)) {
                    while (($file = readdir($dh)) !== false) {
                        if (@file_exists($dir . '/' . $file . "/main/index.tpl")) {
                            if ($_SESSION['skin'] == $file)
                                $sel = "selected";
                            else
                                $sel = "";

                            if ($file != "." and $file != ".." and $file != "index.html") {


                                $value[] = array($file, $file, $sel);
                            }
                        }
                    }
                    closedir($dh);
                }
            }


            // Определяем переменные
            $forma = PHPShopText::div(PHPShopText::form(PHPShopText::select('skin', $value, 150, $float = "none", $caption = false, $onchange = "ChangeSkin()"), 'SkinForm', 'get'), 'left', 'padding:10px');
            $this->set('leftMenuContent', $forma);
            $this->set('leftMenuName', __("Сменить дизайн"));

            // Подключаем шаблон
            $dis = $this->parseTemplate($this->getValue('templates.left_menu'));
        }
        return $dis;
    }

    /**
     * Экшен смены шаблона
     */
    function skin() {
        if ($this->PHPShopSystem->getValue('num_vitrina')) {
            if (@file_exists("phpshop/templates/" . $_REQUEST['skin'] . "/main/index.tpl")) {
                $skin = $_REQUEST['skin'];
                if (PHPShopSecurity::true_skin($skin)) {
                    unset($_SESSION['Memory']);
                    unset($_SESSION['gridChange']);
                    $_SESSION['skin'] = $skin;
                    // используется в модуле skinpage
                    $_SESSION['skinSave'] = $skin;
                }
            }
        }
    }

}

/**
 * Элемент последние новости
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopNewsElement
 * @version 1.1
 * @package PHPShopElements
 */
class PHPShopNewsElement extends PHPShopElements {

    /**
     * @var bool Показывать новости только на главной
     */
    var $disp_only_index = true;

    /**
     * @var int  Кол-во новостей
     */
    var $limit = 5;

    /**
     * Конструктор
     */
    function __construct() {
        $this->debug = false;
        $this->template_debug = true;
        $this->objBase = $GLOBALS['SysValue']['base']['news'];
        parent::__construct();
    }

    /**
     * Вывод последних новостей
     * @return string
     */
    function index() {
        $dis = null;

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, false, 'START');
        if ($hook)
            return $hook;

        // Выполнение только на главной странице
        if ($this->disp_only_index) {
            if ($this->PHPShopNav->index())
                $view = true;
            else
                $view = false;
        }
        else
            $view = true;

        if (!empty($view)) {

            $result = $this->PHPShopOrm->select(array('id', 'zag', 'datas', 'kratko'), false, array('order' => 'id DESC'), array("limit" => $this->limit));

            // Проверка на еденичную запись
            if ($this->limit > 1)
                $data = $result;
            else
                $data[] = $result;

            if (is_array($data))
                foreach ($data as $row) {

                    // Определяем переменные
                    $this->set('newsId', $row['id']);
                    $this->set('newsZag', $row['zag']);
                    $this->set('newsData', $row['datas']);
                    $this->set('newsKratko', $row['kratko']);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    // Подключаем шаблон
                    $dis.=$this->parseTemplate($this->getValue('templates.news_main_mini'));
                }
            return $dis;
        }
    }

}

/**
 * Элемент вывода изображений в слайдер
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSliderElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopSliderElement extends PHPShopElements {

    /**
     * @var bool Показывать слайдер только на главной
     */
    var $disp_only_index = true;
    var $template_debug = false;
    var $debug = false;

    /**
     * @var int  Кол-во изображений
     */
    var $limit = 7;

    /**
     * Конструктор
     */
    function __construct() {
        $this->objBase = $GLOBALS['SysValue']['base']['slider'];
        parent::__construct();
    }

    /**
     * Вывод изображений в слайдер
     * @return string
     */
    function index() {
        $dis = null;

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, false, 'START');

        // Выполнение только на главной странице
        if ($this->disp_only_index) {
            if ($this->PHPShopNav->index())
                $view = true;
            else
                $view = false;
        }
        else
            $view = true;

        $where['enabled'] = '="1"';

        // Мультибаза
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['enabled'].= ' and (servers ="" or servers REGEXP "i1000i")';

        if (!empty($view)) {
            $result = $this->PHPShopOrm->select(array('image', 'alt', 'link'), $where, array('order' => 'num, id DESC'), array("limit" => $this->limit));

            // Проверка на еденичню запись
            if ($this->limit > 1)
                $data = $result;
            else
                $data[] = $result;

            if (is_array($data))
                foreach ($data as $row) {

                    // Определяем переменные
                    $this->set('image', $row['image']);
                    $this->set('alt', $row['alt']);
                    $this->set('link', $row['link']);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    // Подключаем шаблон
                    $dis.=$this->parseTemplate("/slider/slider_oneImg.tpl");
                }
            if (@$dis) {
                $this->set('imageSliderContent', $dis);
                return$this->parseTemplate("/slider/slider_main.tpl");
            }
            return false;
        }
    }

}

/**
 * Элемент Форма опросов
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopOprosElement
 * @version 1.1
 * @package PHPShopElements
 */
class PHPShopOprosElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function __construct() {
        $this->debug = false;
        parent::__construct();
    }

    /**
     * Вывод формы голосования
     * @return string
     */
    function oprosDisp() {

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.opros_categories'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('*'), array('flag' => "='1'"), array('order' => 'id DESC'), array('limit' => 10));
        $content = null;
        if (is_array($dataArray))
            foreach ($dataArray as $row) {

                if (empty($row['dir'])) {
                    // Определяем переменные
                    $this->set('oprosName', $row['name']);
                    $this->set('oprosContent', $this->getOprosValue($row['id'], "FORMA"));

                    // Подключаем шаблон
                    $content.= $this->parseTemplate($this->getValue('templates.opros_list'));
                } else {

                    // Если через запятую указано
                    if (strpos($row['dir'], ","))
                        $dirs = explode(",", $row['dir']);
                    else
                        $dirs[] = $row['dir'];

                    foreach ($dirs as $dir)
                        if (!empty($dir))
                            if (strpos($_SERVER['REQUEST_URI'], $dir) or $_SERVER['REQUEST_URI'] == $dir) {

                                // Определяем переменные
                                $this->set('oprosName', $row['name']);
                                $this->set('oprosContent', $this->getOprosValue($row['id'], "FORMA"));

                                // Перехват модуля
                                $this->setHook(__CLASS__, __FUNCTION__, $row);

                                // Подключаем шаблон
                                $content.= $this->parseTemplate($this->getValue('templates.opros_list'));
                            }
                }
            }

        return $content;
    }

    /**
     * Вывод ответов
     * @param int $n ИД опроса
     * @param string $flag [FORMA|RESULT] опция места вывода (форма опроса или результат опросов)
     * @return string
     */
    function getOprosValue($n, $flag) {
        $dis = null;
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.opros'));
        $PHPShopOrm->comment = 'getOprosValue';
        $PHPShopOrm->debug = $this->debug;
        $this->dataArray = $PHPShopOrm->select(array('*'), array('category' => '=' . $n), array('order' => 'num'), array('limit' => 100));
        if (is_array($this->dataArray))
            foreach ($this->dataArray as $row) {

                if ($row['total'] > 0)
                    $total = $row['total'];
                else
                    $total = "--";

                // Определяем переменые
                $this->set('valueName', $row['name']);
                $this->set('valueId', $row['id']);

                // Подключаем шаблон
                if ($flag == "FORMA")
                    $dis.=$this->parseTemplate($this->getValue('templates.opros_forma'));
                elseif ($flag == "RESULT") {
                    $sum = $this->getSumValue($row['category']);
                    $pr = @number_format(($total * 100) / $sum, "1", ".", "");

                    // Определяем переменые
                    $this->set('valueSum', $total);
                    $this->set('valueProc', $pr);
                    $this->set('valueWidth', $pr * 3 + 1);

                    $dis.=$this->parseTemplate($this->getValue('templates.opros_page_forma'));
                }
            }
        return $dis;
    }

    /**
     * Сумма значений
     * @param int $n ИД опроса
     * @return int
     */
    function getSumValue($n) {
        $objBase = $this->getValue('base.opros');
        $PHPShopOrm = new PHPShopOrm($objBase);
        $result = $PHPShopOrm->query("select SUM(total) as sum from " . $objBase . " where category=" . $n);
        $row = mysqli_fetch_array($result);
        return $row['sum'];
    }

}

/**
 * Элемент баннер
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopBannerElement
 * @version 1.4
 * @package PHPShopElements
 */
class PHPShopBannerElement extends PHPShopElements {

    function __construct() {
        $this->debug = false;
        $this->template_debug = true;
        $this->objBase = $GLOBALS['SysValue']['base']['banner'];
        parent::__construct();
    }

    /**
     * Вывод баннера
     * @return string
     */
    function index() {

        $data = $this->PHPShopOrm->select(array('*'), array("flag" => "='1'"), array('order' => 'RAND()'), array("limit" => 30));

        if (is_array($data))
            foreach ($data as $row) {
                if (empty($row['dir'])) {

                    // Определяем переменные
                    $this->set('banerContent', $row['content']);
                    $this->set('banerTitle', $row['name']);

                    // Подключаем шаблон
                    return $this->parseTemplate($this->getValue('templates.baner_list_forma'));
                } else {
                    $dirs = explode(",", $row['dir']);

                    foreach ($dirs as $dir)
                        if (!empty($dir))
                            if (stristr($_SERVER['REQUEST_URI'],trim($dir)) or $_SERVER['REQUEST_URI'] == trim($dir)) {

                                // Определяем переменные
                                $this->set('banerContent', $row['content']);
                                $this->set('banerTitle', $row['name']);

                                // Подключаем шаблон
                                return $this->parseTemplate($this->getValue('templates.baner_list_forma'));
                            }
                }
            }
    }

}

/**
 * Элемент фото галерея
 * @author PHPShop Software
 * @version 1.2
 * @package PHPShopElements
 */
class PHPShopPhotoElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function __construct() {

        // Отладка
        $this->debug = false;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['photo_categories'];
        parent::__construct();
    }

    /**
     * Вывод фото по таргетингу
     * @return string
     */
    function getPhotos() {
        $dis = null;
        $url = addslashes(substr($this->SysValue['nav']['url'], 1));
        if (empty($url))
            $url = '/';

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.photo_categories'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('enabled' => "='1'", "page" => " LIKE '%$url%'"), array('order' => 'num'), array("limit" => 100));

        if (is_array($data))
            foreach ($data as $row) {
                $this->set('photoTitle', $row['name']);
                $this->set('photoLink', $row['id']);
                $this->set('photoContent', $this->ListPhoto($row['id'], $row['num']));
                $dis.=$this->parseTemplate('./phpshop/lib/templates/photo/photo_list_forma.tpl', true);
            }
        return $dis;
    }

    /**
     * Вывод фото
     * @param int $cat ИД категории фото
     * @param int $num кол-во фото для вывода
     * @return string
     */
    function ListPhoto($cat, $num) {
        $dis = null;

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.photo'));
        $PHPShopOrm->debug = $this->debug;
        $data = $PHPShopOrm->select(array('*'), array('category' => '=' . intval($cat), 'enabled' => "='1'"), array('order' => 'num'), array('limit' => $num));
        if ($num == 1)
            $this->dataArray[] = $data;
        else
            $this->dataArray = $data;

        if (is_array($this->dataArray))
            foreach ($this->dataArray as $row) {

                $name_s = str_replace(".", "s.", $row['name']);
                $this->set('photoIcon', $name_s);
                $this->set('photoInfo', $row['info']);
                $this->set('photoImg', $row['name']);

                $dis.=$this->parseTemplate('./phpshop/lib/templates/photo/photo_element_forma.tpl', true);
            }
        return $dis;
    }

}

/**
 * Элемент защитной картинки Recaptcha
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopRecaptchaElement extends PHPShopElements {

    /**
     * Секретные ключи по умолчанию общие
     * @var string
     */
    public $secret = '6LdhAiYUAAAAAGzO0wlENkavrN49gFhHiHqH9vkv';
    public $public = '6LdhAiYUAAAAAO1uc9b8KfotAyfoInSrWuygbQKC';

    /**
     * API URL
     * @var string
     */
    protected $api = 'https://www.google.com/recaptcha/api/siteverify';

    public function __construct() {

        parent::__construct();

        $public = $this->PHPShopSystem->getSerilizeParam('admoption.recaptcha_pkey');
        if (!empty($public))
            $this->public = $public;

        $secret = $this->PHPShopSystem->getSerilizeParam('admoption.recaptcha_skey');

        if (!empty($secret))
            $this->secret = $secret;
    }

    /**
     * Проверка правильности заполнения каптчи
     * @return boolean
     */
    public function check() {
        if (!empty($_POST['g-recaptcha-response'])) {
            $res = $this->request();

            if (!empty($res['success']))
                return true;
        }
    }

    /**
     * Проверка ботов
     * @param array $option параметры проверки [url|captcha|referer]
     * @return boolean
     */
    function security($option = array('url' => false, 'captcha' => true, 'referer' => true)) {

        // Проверка вхождения ссылок
        if (!empty($option['url'])) {
            preg_match_all('/http:?/', $_POST[$option['url']], $url, PREG_SET_ORDER);
            if (count($url) > 0)
                return false;
        }

        // Проверка Referer
        if (!empty($option['referer'])) {
            if (!strpos($_SERVER["HTTP_REFERER"], $_SERVER['SERVER_NAME']))
                return false;
        }

        // Проверка каптчи
        if ($option['captcha'] === true) {

            // Recaptcha
            if ($this->true()) {
                $result = $this->check();
                return $result;
            }

            // Обычная каптча
            elseif (!empty($_SESSION['text']) and strtoupper($_POST['key']) == strtoupper($_SESSION['text'])) {
                return true;
            }
            else
                return false;
        }

        return true;
    }

    /**
     * Проверка каптчи в Google
     * @return array
     */
    protected function request() {

        $recaptcha = $_POST['g-recaptcha-response'];
        $rout = "?secret=" . $this->secret . "&response=" . $recaptcha;

        // Локальный режим
        if ($_SERVER["SERVER_ADDR"] == "127.0.0.1" and getenv("COMSPEC")) {
            $responsecontent = file_get_contents($this->api . $rout);
        } else {
            $data_string = $rout;
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $this->api . $rout);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            ));

            $output = curl_exec($ch);
            curl_close($ch);

            $response = explode("\r\n\r\n", $output);
            $responsecontent = $response[1];
        }

        return json_decode($responsecontent, true);
    }

    /**
     * Вывод зашитной картинки recaptcha
     * @param string $name ИД каптчи
     * @param string $size размер каптчи [normal|compact]   
     * @return string
     */
    public function captcha($name = 'default', $size = 'normal') {

        if ($this->PHPShopSystem->ifSerilizeParam('admoption.recaptcha_enabled')) {
            $dis.='<div id="recaptcha_' . $name . '" data-size="' . $size . '" data-key="' . $this->public . '"></div>';
            $this->recaptcha = true;
        } else {
            $dis = '<img src="phpshop/lib/captcha/captcha.php" align="left" style="margin-right:10px"> <input type="text" name="key" class="form-control" placeholder="' . __('Код с картинки') . '..." style="width:100px" required="">';
            $this->recaptcha = false;
        }

        return $dis;
    }

    /**
     * Используется рекаптча
     * @return boolen
     */
    public function true(){
    return $this->recaptcha;


    }

}

?>