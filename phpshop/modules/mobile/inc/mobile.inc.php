<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class AddToTemplateMDetect extends PHPShopProductElements {

    var $memory = true;

    function option() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['mobile']['mobile_system']);
        $option = $PHPShopOrm->select();
        return $option;
    }

    function __construct() {
        global $PHPShopSystem;

        $skin = 'mobile';

        parent::PHPShopProductElements();

        // Настройки
        $option = $this->option();

        if (!empty($_GET['mobile']) and $_GET['mobile'] == 'true') {
            if (file_exists("phpshop/templates/" . $skin . "/index.html")) {
                $_SESSION['skin'] = $skin;

                $option = null;
                if (!empty($_GET['native']))
                    $option = '?native=' . $_GET['native'];

                $url = str_replace("?mobile=true", "", $_SERVER['REQUEST_URI']);

                unset($_SESSION['Memory']);

                // Учет модуля SEOURLPRO
                if (empty($GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system']))
                    exit(header('Location: ' . $url . $option));
            }
        }

        if (!empty($_GET['fullversion'])) {
            $_SESSION['skin'] = $PHPShopSystem->getParam('skin');
            $url = str_replace("?fullversion=true", "", $_SERVER['REQUEST_URI']);
            unset($_SESSION['Memory']);
            header('Location: ' . $url);
            exit();
        }

        $this->memory_set('mobile.logo', $option['logo']);
        $this->memory_set('mobile.returncall', $option['returncall']);
    }

    function message() {

        if ($this->detect() and empty($_SESSION['MobileDetectConfirm'])) {

            // Настройки
            $option = $this->option();

            if (empty($option['message']))
                $option['message'] = "Доступна мобильная версия сайта, перейти?";

            $js = '
        <!-- MobileDetectConfirm -->
		<script>
        if(confirm("' . $option['message'] . '"))
		  window.location.replace("/?mobile=true");
		</script>
        <!-- MobileDetectConfirm -->';

            //$this->set('leftMenu', $js, true);
            $_SESSION['MobileDetectConfirm'] = true;
            return $js;
        }
    }

    function detect() {
        $ipad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
        $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
        $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
        $palmpre = strpos($_SERVER['HTTP_USER_AGENT'], "webOS");
        $berry = strpos($_SERVER['HTTP_USER_AGENT'], "BlackBerry");
        $ipod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
        $mobile = strpos($_SERVER['HTTP_USER_AGENT'], "Mobile");
        $symb = strpos($_SERVER['HTTP_USER_AGENT'], "Symbian");
        $operam = strpos($_SERVER['HTTP_USER_AGENT'], "Opera M");
        $htc = strpos($_SERVER['HTTP_USER_AGENT'], "HTC_");
        $fennec = strpos($_SERVER['HTTP_USER_AGENT'], "Fennec/");
        $winphone = strpos($_SERVER['HTTP_USER_AGENT'], "Windows Phone");
        $wp7 = strpos($_SERVER['HTTP_USER_AGENT'], "WP7");
        $wp8 = strpos($_SERVER['HTTP_USER_AGENT'], "WP8");

        if ($ipad || $iphone || $ipod === true)
            $detect = 'ios';
        elseif ($android)
            $detect = 'android';
        elseif ($winphone || $wp7 || $wp8 === true)
            $detect = 'wp';
        elseif ($symb)
            $detect = 'symbian';
        elseif ($palmpre || $berry || $mobile || $operam || $htc || $fennec === true)
            $detect = 'other';

        if ($detect)
            return $detect;
    }

}

$GLOBALS['AddToTemplateMDetect'] = new AddToTemplateMDetect();
?>