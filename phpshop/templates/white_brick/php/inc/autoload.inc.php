<?php

function create_theme_menu($file) {
    static $i;
    $return = null;
    $color = array(
        'black' => '#F7F308',
        'iron' => '#A13599',
        'flatly' => '#E9D3B3',
        'wood' => '#EAD5B7',
    );
    if (preg_match("/^style-theme-([a-zA-Z0-9_]{1,30}).css$/", $file, $match)) {
        $icon = $color[$match[1]];
        if (empty($icon))
            $icon = $match[1];

        return '<div class="bootstrap-theme" style="background:' . $icon . '" title="' . $match[1] . '" data-skin="css/style-theme-' . $match[1] . '"></div>';
    }
}

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
                       <div>  <div class="bootstrap-theme" style="background:#6C809A" title="default" data-skin="/style"></div>
                       ' . PHPShopFile::searchFile($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/css/', 'create_theme_menu') . '</div>
                    <div class="clearfix"></div>';
    if (!empty($_SESSION['logPHPSHOP']))
        $theme_menu.='<br>
                <button class="btn btn-default btn-sm saveTheme" role="button"><span class="icon icon-ok"></span> Сохранить</button>';
    $theme_menu.='
                </div>
                 </div>
</div>
                            ';
    if(!empty($GLOBALS['SysValue']['other']['skinSelect']) or !empty($_SESSION['logPHPSHOP']))
    $GLOBALS['SysValue']['other']['skinSelect'].= $theme_menu;
}


// Цветовые темы CSS
if (isset($_COOKIE[$_SESSION['skin'].'_theme'])) {
    if (PHPShopSecurity::true_skin($_COOKIE[$_SESSION['skin'].'_theme'])) {
        $GLOBALS['SysValue']['other'][$_SESSION['skin'].'_theme'] = $_COOKIE[$_SESSION['skin'].'_theme'];
    }
    else
        $GLOBALS['SysValue']['other'][$_SESSION['skin'].'_theme'] = '/style';
} 
elseif(empty($GLOBALS['SysValue']['other'][$_SESSION['skin'].'_theme']))
    $GLOBALS['SysValue']['other'][$_SESSION['skin'].'_theme'] = '/style';

?>