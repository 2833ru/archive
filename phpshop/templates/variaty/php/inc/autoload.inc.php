<?php

// Редактор тем оформления
if ($GLOBALS['SysValue']['template_theme']['user'] == 'true' or !empty($_SESSION['logPHPSHOP'])) {
    $theme_menu = ' 
  
<div style="clear:both"></div>
<div class="box">
    <div class="box-heading">
    <h2>Оформление <a href="http://faq.phpshop.ru/page/template-theme.html" target="_blank" title="Помощь" class="pull-right"><span class="icon icon-info-sign"></span></a></h2></div>
    <div class="box-content">
        <div class="box-category">
            <div style="clear:both"></div>
            Стиль
            <div style="clear:both"></div>
                       <div>  <div class="bootstrap-theme" data-for="" style="background:#f16325" title="default" data-skin="default"></div>
                       ' . PHPShopFile::searchFile($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/css/', 'create_theme_menu') . '
                       </div>
            <div style="clear:both"></div>
            Фон          
            <div style="clear:both"></div>
                        <div>
                       ' . PHPShopFile::searchFile($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/css/', 'create_theme_menu2') . '
                        </div> 
            <div style="clear:both"></div>
            <br>          
            <div style="clear:both"></div>
                        <div>
                       ' . PHPShopFile::searchFile($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/css/', 'create_theme_menu3') . '
                        </div> 

                    <div class="clearfix"></div>';
    if (!empty($_SESSION['logPHPSHOP']))
        $theme_menu.='<br>
                <button class="btn btn-default btn-sm saveTheme" role="button"><span class="icon icon-ok"></span> Сохранить</button>';
    $theme_menu.='
                </div>
                 </div>
</div>
                            ';
    if (!empty($GLOBALS['SysValue']['other']['skinSelect']) or !empty($_SESSION['logPHPSHOP']))
        $GLOBALS['SysValue']['other']['skinSelect'].= $theme_menu;
}


// Цветовые темы CSS - цвет
if (isset($_COOKIE[$_SESSION['skin'] . '_theme'])) {
    if (PHPShopSecurity::true_skin($_COOKIE[$_SESSION['skin'] . '_theme'])) {
        $GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme'] = $_COOKIE[$_SESSION['skin'] . '_theme'];
    }
    else
        $GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme'] = 'default';
}
elseif (empty($GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme']))
    $GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme'] = 'default';

// Цветовые темы CSS - фон
if (isset($_COOKIE[$_SESSION['skin'] . '_theme2'])) {
    if (PHPShopSecurity::true_skin($_COOKIE[$_SESSION['skin'] . '_theme2'])) {
        $GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme2'] = $_COOKIE[$_SESSION['skin'] . '_theme2'];
    }
    else
        $GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme2'] = 'style-theme2-retina_wood';
}
elseif (empty($GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme2']))
    $GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme2'] = 'style-theme2-retina_wood';

// Цветовые темы CSS - layout
if (isset($_COOKIE[$_SESSION['skin'] . '_theme3'])) {
    if (PHPShopSecurity::true_skin($_COOKIE[$_SESSION['skin'] . '_theme3'])) {
        $GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme3'] = $_COOKIE[$_SESSION['skin'] . '_theme3'];
    }
    else
        $GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme3'] = 'style-theme3-Boxed';
}
elseif (empty($GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme3']))
    $GLOBALS['SysValue']['other'][$_SESSION['skin'] . '_theme3'] = 'style-theme3-Boxed';


function create_theme_menu($file) {
    static $i;
    $return = null;
    $color = array(
        'blue' => '#0069b4',
        'brown' => '#5d514b',
        'green' => '#009640',
        'orange' => '#f16325',
        'red' => '#e81863',
    );
    if (preg_match("/^style-theme-([a-zA-Z0-9_]{1,30}).css$/", $file, $match)) {
        $icon = $color[$match[1]];
        if (empty($icon))
            $icon = $match[1];

        return '<div class="bootstrap-theme" data-for=""  style="background:' . $icon . '" title="' . $match[1] . '" data-skin="style-theme-' . $match[1] . '"></div>';
    }
}

function create_theme_menu2($file) {
    static $i;
    $return = null;
    if (preg_match("/^style-theme2-([a-zA-Z0-9_]{1,30}).css$/", $file, $match)) {

        return '<div class="bootstrap-theme" data-for="2" style="background:url(images/backgrounds/' . $match[1] . '.png);" title="' . $match[1] . '" data-skin="style-theme2-' . $match[1] . '"></div>';
    }
}

function create_theme_menu3($file) {
    static $i;
    $return = null;
    $color = array(
        'Boxed' => 'C полями',
        'Wide' => 'Без полей',
    );
    if (preg_match("/^style-theme3-([a-zA-Z0-9_]{1,30}).css$/", $file, $match)) {
        $icon = $color[$match[1]];

        return '<button  data-for="3"  class="bootstrap-theme-button btn btn-default btn-sm"  title="' . $icon . '"  data-skin="style-theme3-' . $match[1] . '">'.$icon.'</button>&nbsp;';
    }
} 
?>