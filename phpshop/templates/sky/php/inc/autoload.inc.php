<?php

$_SESSION['Memory']["rateForComment"]["oneStarWidth"] = 16; // ширина одной звёздочки
$_SESSION['Memory']["rateForComment"]["oneSpaceWidth"] = 0;

// Разрешенные темы
$skin_base=array('style','theme/aqua/style','theme/aquamarine/style','theme/crimson/style','theme/emerald/style','theme/glitter/style','theme/lavender/style','theme/lime/style','theme/orange/style','theme/rose/style');

// Редактор тем оформления
if ($GLOBALS['SysValue']['template_theme']['user'] == 'true' or !empty($_SESSION['logPHPSHOP'])) {
    $theme_menu = ' 
        

<div class="lb">
  <div class="lb1">&nbsp;</div>
  <div class="lb2"  >
    <h2>Оформление</h2>
    <div class="lmblock" style="padding:15px 20px;">

<div class="theme" style="background:#53A7E3" title="blue" data-template="'.$_SESSION['skin'].'" data-skin="style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#B5EFF0" title="aqua" data-template="'.$_SESSION['skin'].'" data-skin="theme/aqua/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#32B7C0" title="aquamarine" data-template="'.$_SESSION['skin'].'" data-skin="theme/aquamarine/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#DB4546" title="crimson" data-template="'.$_SESSION['skin'].'" data-skin="theme/crimson/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#219B2E" title="emerald" data-template="'.$_SESSION['skin'].'" data-skin="theme/emerald/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#DEDFE3" title="glitter" data-template="'.$_SESSION['skin'].'" data-skin="theme/glitter/style" onclick="setTheme(this)"></div>    
<div class="theme" style="background:#B383B7" title="lavender" data-template="'.$_SESSION['skin'].'" data-skin="theme/lavender/style" onclick="setTheme(this)"></div>   
<div class="theme" style="background:#96F895" title="lime" data-template="'.$_SESSION['skin'].'" data-skin="theme/lime/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#EFA13C" title="orange" data-template="'.$_SESSION['skin'].'" data-skin="theme/orange/style" onclick="setTheme(this)"></div>
<div class="theme" style="background:#E7497A" title="rose" data-template="'.$_SESSION['skin'].'" data-skin="theme/rose/style" onclick="setTheme(this)"></div>   
<div style="clear:both">    
<br></div>';    
    
     if (!empty($_SESSION['logPHPSHOP']))
        $theme_menu.='
                <p><input class="but" type="button" data-template="'.$_SESSION['skin'].'" value="Сохранить" onclick="saveTheme(this)"></p>';
     
$theme_menu.='</div>
  </div>
  <div class="lb3"></div>
</div>
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
