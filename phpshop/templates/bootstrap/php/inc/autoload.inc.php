<?php

$_SESSION['Memory']["rateForComment"]["oneStarWidth"] = 16; // ширина одной звёздочки
$_SESSION['Memory']["rateForComment"]["oneSpaceWidth"] = 0;

// Цветовые темы CSS
if (isset($_COOKIE['bootstrap_theme'])) {
    if (PHPShopSecurity::true_skin($_COOKIE['bootstrap_theme'])) {
        $GLOBALS['SysValue']['other']['bootstrap_theme'] = $_COOKIE['bootstrap_theme'];
    }
    else
        $GLOBALS['SysValue']['other']['bootstrap_theme'] = 'bootstrap';
} /* elseif (!empty($GLOBALS['SysValue']['other']['template_theme']))
  $GLOBALS['SysValue']['other']['bootstrap_theme'] = $GLOBALS['SysValue']['other']['template_theme']; */
elseif (empty($GLOBALS['SysValue']['other']['bootstrap_theme']))
    $GLOBALS['SysValue']['other']['bootstrap_theme'] = 'bootstrap';

function create_theme_menu($file) {
    
    $current = $_COOKIE['bootstrap_theme'];
    if (empty($current))
        $current = 'bootstrap';

    $color = array(
        'cerulean' => '#178ACC',
        'cyborg' => '#000',
        'flatly' => '#D9230F',
        'spacelab' => '#46709D',
        'slate' => '#4E5D6C',
        'yeti' => '#008CBA',
        'simplex' => '#DF691A',
        'sardbirds' => '#45B3AF',
        'wordless' => '#468966',
        'wildspot' => '#564267',
        'loving' => '#FFCAEA'
    );
    if (preg_match("/^bootstrap-theme-([a-zA-Z0-9_]{1,30}).css$/", $file, $match)) {
        $icon = $color[$match[1]];
        if (empty($icon))
            $icon = $match[1];

        if ($current == 'bootstrap-theme-' . $match[1])
            $check = '<span class="glyphicon glyphicon-ok"></span>';
        else
            $check = null;


        return '<div class="bootstrap-theme text-center" style="background:' . $icon . '" title="' . $match[1] . '" data-skin="bootstrap-theme-' . $match[1] . '">' . $check . '</div>';
    }
}

// Редактор тем оформления
if ($GLOBALS['SysValue']['template_theme']['user'] == 'true' or !empty($_SESSION['logPHPSHOP'])) {
    
    
    if($_COOKIE['bootstrap_theme'] == 'bootstrap' or empty($_COOKIE['bootstrap_theme']))
         $check = '<span class="glyphicon glyphicon-ok"></span>';
    else $check=null;
    
    $theme_menu = ' 
  
<div class="panel panel-success">
  <div class="panel-heading">
    <a href="http://faq.phpshop.ru/page/template-theme.html" target="_blank" title="Помощь" class="pull-right"><span class="glyphicon glyphicon-info-sign"></span></a>
    <h3 class="panel-title">Оформление</h3>
  </div>
  <div class="panel-body">
                    <div class="center-block">
                        <div class="bootstrap-theme text-center" style="background:#CCC;" title="default" data-skin="bootstrap">'.$check.'</div>
                        ' . PHPShopFile::searchFile($GLOBALS['SysValue']['dir']['templates'] . chr(47) . $_SESSION['skin'] . '/css/', 'create_theme_menu') . '
                            </div>
                            
<div class="clearfix"><br></div>
    <a href="?skin=bootstrap_fluid" class="btn btn-default btn-sm" data-toggle="tooltip" data-placement="bottom" title="Резиновый размер"><span class="glyphicon glyphicon-fullscreen"></span> Fluid</a> <a href="?skin=bootstrap" class="btn btn-default btn-sm active" data-toggle="tooltip" data-placement="bottom" title="Фиксированный размер"><span class="glyphicon glyphicon-retweet"></span> Fixed</a>

                    <div class="clearfix"></div>';
    if (!empty($_SESSION['logPHPSHOP']))
        $theme_menu.='<br>
                <button class="btn btn-success btn-sm saveTheme" role="button"><span class="glyphicon glyphicon-floppy-disk"></span> Сохранить</button>';
    $theme_menu.=' 
                </div>
</div>
                            ';
    if (!empty($GLOBALS['SysValue']['other']['skinSelect']) or !empty($_SESSION['logPHPSHOP']))
        $GLOBALS['SysValue']['other']['skinSelect'].= $theme_menu;
}

// Мобильная корзина
if (!empty($_SESSION['cart'])) {
    $PHPShopCart = new PHPShopCart();
    $GLOBALS['SysValue']['other']['cart_active_num'] = $PHPShopCart->getNum();
    $GLOBALS['SysValue']['other']['cart_active'] = 'active';
}

// Личный кабинет
if (!empty($_SESSION['UsersId'])) {
    $GLOBALS['SysValue']['other']['user_link'] = 'href="/users/"';
    $GLOBALS['SysValue']['other']['user_active'] = 'active';
} else {
    $GLOBALS['SysValue']['other']['user_link'] = 'href="#"  data-toggle="modal"';
}



// Меню брендов
$PHPShopBrandsElement = new PHPShopBrandsElement();
$PHPShopBrandsElement->init('topBrands');
?>