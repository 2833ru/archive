<?php

$_SESSION['Memory']["rateForComment"]["oneStarWidth"] = 16; // ширина одной звёздочки
$_SESSION['Memory']["rateForComment"]["oneSpaceWidth"] = 0;

// Разрешенные темы
$skin_base=array('style','theme/red/style','theme/green/style','theme/yellow/style');

// Редактор тем оформления
if ($GLOBALS['SysValue']['template_theme']['user'] == 'true' or !empty($_SESSION['logPHPSHOP'])) {
    $theme_menu = ' 

<div id="bg_catalog_1" style="margin-top:10px">Оформление</div>
<div id="bg_catalog_2"></div>
<div style="padding-top:5px">

<div class="theme" style="background:#3D6C8E" title="Синий" data-template="'.$_SESSION['skin'].'" data-skin="style" onclick="setTheme(this)"></div>
<div class="theme" style="background:red" title="Красный" data-template="'.$_SESSION['skin'].'" data-skin="theme/red/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:green" title="Зеленый" data-template="'.$_SESSION['skin'].'" data-skin="theme/green/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:yellow" title="Желтый" data-template="'.$_SESSION['skin'].'" data-skin="theme/yellow/style" onclick="setTheme(this)"></div><div style="clear:both"><br></div>';    
    
     if (!empty($_SESSION['logPHPSHOP']))
        $theme_menu.='
                <p><input class="but" type="button" data-template="'.$_SESSION['skin'].'" value="Сохранить" onclick="saveTheme(this)"></p>';
     
$theme_menu.='</div>



                            ';

    if(!empty($GLOBALS['SysValue']['other']['skinSelect']) or !empty($_SESSION['logPHPSHOP']))
    $GLOBALS['SysValue']['other']['skinSelect'].= $theme_menu;
}





// Цветовые темы CSS
if (isset($_COOKIE[$_SESSION['skin'].'_theme'])) {
   if (in_array($_COOKIE[$_SESSION['skin'].'_theme'],$skin_base)) {
        $GLOBALS['SysValue']['other'][$_SESSION['skin'].'_theme'] = $_COOKIE[$_SESSION['skin'].'_theme'];
   }
   else $GLOBALS['SysValue']['other'][$_SESSION['skin'].'_theme'] = 'style';
} /*elseif (!empty($GLOBALS['SysValue']['other']['template_theme']))
    $GLOBALS['SysValue']['other']['classic_theme'] = $GLOBALS['SysValue']['other']['template_theme'];*/
elseif(empty($GLOBALS['SysValue']['other'][$_SESSION['skin'].'_theme']))
    $GLOBALS['SysValue']['other'][$_SESSION['skin'].'_theme'] = 'style';
?>
