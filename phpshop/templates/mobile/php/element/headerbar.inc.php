<?php

/**
 * Элемент вывода случайного товара в перменную @showcase@
 */
class AddToTemplate extends PHPShopProductElements {

    var $debug = false;
    var $memory = true;

    function __construct() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::__construct();
    }

    function header_bar() {
        global $PHPShopSystem;

        $this->set('version', ', Mobile Template 1.9', true);

        // Иконка
        $logo=$this->memory_get('mobile.logo');
        if(!empty($logo))
           $this->set('descrip',  '<img class="header-logo" src="'.$logo.'" />');
        
        // Телефон
        $returncall=$this->memory_get('mobile.returncall',true);
        
        if($returncall == 1)
           $this->set('tel',  '<a href="tel:'. $this->get('telNum').'">'. $this->get('telNum').'</a>');
        else {
            $_SESSION['mod_returncall_captcha']='off';
            $this->set('tel',  '<span class="icon icon-sound2"></span> <a href="#setRetunCall" onclick="modal_on(this.hash)">Заказать звонок</a>');
        }
        

        if (empty($_SESSION['MobileDetect'])) {
            $this->set('fix_css', '<link href= "' . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/pc.css" rel= "stylesheet">');
        } 
        elseif ($_SESSION['MobileDetect'] == 1) {
            $this->set('fix_css', null);
        }
        elseif ($_SESSION['MobileDetect'] == 'symbian') {
            $this->set('fix_css', '<link href= "' . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/symbian.css" rel= "stylesheet">');
        }


        // Активная закладка авторизации пользователя
        if (!empty($_SESSION['UsersId'])) {
            $this->set('user_active', 'active');

            $PHPShopUser = new PHPShopUser($_SESSION['UsersId']);
            $status = $PHPShopUser->getStatusName();
            if (empty($status))
                $status = 'Авторизованный пользователь';
            $this->set('UsersStatusName', $status);
            $this->set('UsersStatusDiscount', (0 + $PHPShopUser->getDiscount()) . '%');
        }

        // Навигация Назад
        if (!empty($_SERVER['HTTP_REFERER']))
            $this->set('history_back', $_SERVER['HTTP_REFERER']);

        // Активная закладка корзины
        if (!empty($_SESSION['cart'])) {
            $this->set('cart_active', 'active');

            $PHPShopCart = new PHPShopCart();
            $this->set('cart_active_num', '<span class="badge badge-positive">' . $PHPShopCart->getNum() . '</span>');
        }

        /*
        if (!empty($_GET['mobile']) and $_GET['mobile'] == 'true') {
            $this->set('pageTitl', 'Мобильная версия');
            $url = str_replace("?mobile=true", "", $_SERVER['REQUEST_URI']);
            exit(header('Location: '.$url));
        }*/

        if (!empty($_GET['fullversion'])) {
            $_SESSION['skin'] = $PHPShopSystem->getParam('skin');
            $url = str_replace("?fullversion=true", "", $_SERVER['REQUEST_URI']);
            exit(header('Location: ' . $url));
        }
        /*
          if (!empty($_COOKIE['mobile_skin']) and $_COOKIE['mobile_skin'] != 'ratchet.css') {
          $this->set('mob_css_style', '<link href="' . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/ratchetcss/' . $this->check($_COOKIE['mobile_skin']) . '" rel="stylesheet">');
          }

          // Принудительная смена из native [android|ios]
          if (!empty($_GET['native'])) {
          $this->set('mob_css_style', '<link href="' . $GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/ratchetcss/' . $this->check('ratchet-theme-' . $_GET['native'].'.css').'" rel="stylesheet">');
          } */
    }

    function check($css) {
        if (!strstr((string) $css, '/') and !strstr((string) $css, '%'))
            return $css;
    }

    function block() {

        if ( $this->PHPShopNav->notPath(array('order', 'shop', 'index', 'done', 'news', 'search', 'page', 'users','returncall'))) {
            exit(header('Location: /'));
        }
    }

}

// Добавляем в шаблон элемент 
$AddToTemplate = new AddToTemplate();
$AddToTemplate->header_bar();
$AddToTemplate->block();
?>