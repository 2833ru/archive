<?php

$_SESSION['Memory']["rateForComment"]["oneStarWidth"] = 16; // ширина одной звёздочки
$_SESSION['Memory']["rateForComment"]["oneSpaceWidth"] = 0;

// Разрешенные темы
$skin_base=array('style','theme/pink/style','theme/grass/style','theme/grey/style');

// Редактор тем оформления
if ($GLOBALS['SysValue']['template_theme']['user'] == 'true' or !empty($_SESSION['logPHPSHOP'])) {
    $theme_menu = ' 
        

<div class="plashka">
<div class="plashka_zag">Оформление</div>
</div>
<div class="textblock_bg">
<div class="theme" style="background:#4495B0" title="Синий" data-template="'.$_SESSION['skin'].'" data-skin="style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#D13080" title="Розовый" data-template="'.$_SESSION['skin'].'" data-skin="theme/pink/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#12AF60" title="Зеленый" data-template="'.$_SESSION['skin'].'" data-skin="theme/grass/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#848484" title="Серый" data-template="'.$_SESSION['skin'].'" data-skin="theme/grey/style" onclick="setTheme(this)"></div>

<div style="clear:both"><br></div>';    
    
     if (!empty($_SESSION['logPHPSHOP']))
        $theme_menu.='
                <p><input type="button" data-template="'.$_SESSION['skin'].'" value="Сохранить" onclick="saveTheme(this)"></p>';
     
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
