<?php

$TitlePage = __("Панель инструментов");

// Оповещение пользователя по почте
function mailNotice($type, $until_day) {
    global $PHPShopSystem;

    $admoption = $PHPShopSystem->getParam('admoption');
    $option = unserialize($admoption);

    if (empty($option[$type . '_notice'])) {

        PHPShopParser::set('url', $_SERVER['SERVER_NAME']);
        PHPShopParser::set('day', abs(round($until_day)));
        switch ($type) {
            case "license":

                $userContent = PHPShopParser::file("tpl/license.mail.tpl", true, false);
                new PHPShopMail($PHPShopSystem->getEmail(), $PHPShopSystem->getEmail(), 'Заканчивается лицензия для сайта ' . $_SERVER['SERVER_NAME'], $userContent, "text/html");

                break;
            case "support":
                $userContent = PHPShopParser::file("tpl/support.mail.tpl", true, false);
                new PHPShopMail($PHPShopSystem->getEmail(), $PHPShopSystem->getEmail(), 'Заканчивается техническая поддержка для сайта ' . $_SERVER['SERVER_NAME'], $userContent, "text/html");

                break;
        }

        $option[$type . '_notice'] = true;
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);
        $PHPShopOrm->update(array('admoption_new' => serialize($option)));
    }
}

function actionStart() {
    global $PHPShopInterface, $PHPShopSystem, $PHPShopGUI, $TitlePage, $PHPShopBase;


    // Поисковые запросы
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['search_jurnal']);
    $data = $PHPShopOrm->select(array('name'), array('num' => '>0'), array('order' => 'id desc'), array('limit' => 10));
    $search_jurnal = null;
    $search_jurnal_title = 'Новые поисковые запросы <a href="#" class="search pull-right">Расширенный поиск</a>';
    $search_jairnal_icon = 'search';
    $search_jurnal_class = null;
    if (is_array($data)) {
        foreach ($data as $row) {
            if (strlen($row['name']) > 5)
                $search_jurnal.='<a href="?path=report.searchjurnal" class="btn btn-default btn-xs search_var">' . PHPShopSecurity::true_search(substr($row['name'], 0, 30)) . '</a> ';
        }
    }

    // Статусы заказов
    PHPShopObj::loadClass('order');
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $status_array = $PHPShopOrderStatusArray->getArray();
    $status[0] = __('Новый заказ');
    $order_status_value[] = array(__('Новый заказ'), 0, 0);
    if (is_array($status_array))
        foreach ($status_array as $k => $status_val) {
            $status[$k] = $status_val['name'];
            $order_status_value[] = array($status_val['name'], $status_val['id'], 0);
        }

    // Поиск
    $where = null;
    if (is_array($_GET['where'])) {
        foreach ($_GET['where'] as $k => $v) {
            if (!empty($v))
                $where.= ' ' . $k . ' = "' . $v . '" or';
        }

        if ($where)
            $where = 'where' . substr($where, 0, strlen($where) - 2);

        // Дата
        if (!empty($_GET['date_start']) and !empty($_GET['date_end'])) {
            if ($where)
                $where.=' and ';
            else
                $where = ' where ';
            $where.=' a.datas between ' . (PHPShopDate::GetUnixTime($_GET['date_start']) - 1) . ' and ' . (PHPShopDate::GetUnixTime($_GET['date_end']) + 259200 / 2) . '  ';
            $TitlePage.=' с ' . $_GET['date_start'] . ' по ' . $_GET['date_end'];
        }
    }

    $PHPShopGUI->action_button['Время'] = array(
        'name' => '<span class=clock-tmp>' . date("H:i:s", time()) . '</span>',
        'class' => 'btn btn-default btn-sm clock navbar-btn hidden-xs',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-time'
    );

    $License = parse_ini_file_true("../../license/" . PHPShopFile::searchFile('../../license/', 'getLicense'), 1);


    // Проверка обновлений
    if (!isset($_SESSION['update_check'])) {
        define("UPDATE_PATH", "http://phpshop.ru/update/update5.php?from=" . $_SERVER['SERVER_NAME'] . "&version=" . $GLOBALS['SysValue']['upload']['version'] . "&support=" . $License['License']['SupportExpires'] . '&serial=' . $License['License']['Serial'] . '&path=intro');

        $update_enable = @xml2array(UPDATE_PATH, "update", true);
        if (is_array($update_enable) and $update_enable['status'] != 'no_update') {
            $_SESSION['update_check'] = intval($update_enable['name'] - $update_enable['num']);
        }
        else
            $_SESSION['update_check'] = 0;
    }


    if ($License['License']['Pro'] == 'Start') {
        $_SESSION['mod_limit'] = 5;
    }
    else
        $_SESSION['mod_limit'] = 50;

    // Заканчивается поддержка
    $LicenseUntilUnixTime = $License['License']['SupportExpires'];
    $until = $LicenseUntilUnixTime - date("U");
    $until_day = abs(round($until / (24 * 60 * 60)));
    if (is_numeric($LicenseUntilUnixTime))
        if ($until_day < 8 and $until_day > 0) {
            mailNotice('support', $until_day);
            $search_jurnal = 'В течение 1 месяца для Вас действует <b>льготный тариф на техподдержку</b>, чтобы Вы смогли своевременно получать обновления и технические консультации в течение года.';
            $search_jurnal_title = 'Техническая поддержка заканчивается через <span class="label label-warning">' . abs(round($until_day)) . '  дней</span><a class="pull-right btn btn-xs btn-default" href="http://phpshop.ru/order/" target="_blank"><span class="glyphicon glyphicon-ruble"></span> Купить</a>';
            $search_jurnal_class = 'panel-success';
            $search_jairnal_icon = 'exclamation-sign';
        }

    // Заканчивается лицензия
    $LicenseUntilUnixTime = $License['License']['Expires'];
    $until = $LicenseUntilUnixTime - date("U");
    $until_day = abs(round($until / (24 * 60 * 60)));
    if (is_numeric($LicenseUntilUnixTime))
        if ($until_day < 8 and $until_day > 0) {
            mailNotice('license', $until_day);
            $search_jurnal = 'Для перехода на полную версию необходимо приобрести лицензию. <b>Все изменения, произведенные на демо-версии сайта, сохранятся</b>.';
            $search_jurnal_title = 'Лицензия заканчивается через <span class="label label-primary">' . abs(round($until_day)) . '  дней</span><a class="pull-right btn btn-xs btn-primary" href="http://phpshop.ru/order/" target="_blank"><span class="glyphicon glyphicon-ruble"></span> Купить</a>';
            $search_jurnal_class = 'panel-danger';
            $search_jairnal_icon = 'exclamation-sign';
        }


    $PHPShopGUI->setActionPanel($TitlePage, false, array('Время'));
    $PHPShopGUI->addJSFiles('js/chart.min.js', 'intro/gui/intro.gui.js');
    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setCaption(array("", "10%"), array("", "20%"), array("", "20%"), array("", "15%"), array("", "15%", array('align' => 'right')));


    // Знак рубля
    if ($PHPShopSystem->getDefaultValutaIso() == 'RUB')
        $currency = '<span class="rubznak hidden-xs">p</span>';
    else
        $currency = $PHPShopSystem->getDefaultValutaCode();


    // Таблица с данными заказов
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $PHPShopOrm->Option['where'] = ' or ';
    $PHPShopOrm->debug = false;
    $PHPShopOrm->sql = 'SELECT a.*, b.mail FROM ' . $GLOBALS['SysValue']['base']['orders'] . ' AS a 
        LEFT JOIN ' . $GLOBALS['SysValue']['base']['shopusers'] . ' AS b ON a.user = b.id  ' . $where . ' 
            order by a.id desc limit 8';
    $canvas_value = $canvas_label = null;
    $data = $PHPShopOrm->select();
    if (is_array($data))
        foreach ($data as $row) {

            // Библиотека заказа
            $PHPShopOrder = new PHPShopOrderFunction($row['id'], $row);

            if (empty($row['fio'])) {
                $row['fio'] = $row['mail'];
            }

            $total = $PHPShopOrder->getTotal(false);
            $canvas_value.='"' . $total . '",';


            $d_array = array(
                'm' => date("m", $row['datas']),
                'd' => date("d", $row['datas'])
            );

            $canvas_label.='"' . $d_array['d'] . '.' . $d_array['m'] . '",';

            $datas = PHPShopDate::get($row['datas']);

            // Статус
            if (!empty($status[$row['statusi']]))
                $status_name = $status[$row['statusi']];
            else
                $status_name = __('Не определен');

            if ($row['id'] < 100)
                $uid = '<span class="hidden-xs">' . __('Заказ ') . '</span>' . $row['uid'];
            else
                $uid = $row['uid'];

            $PHPShopInterface->setRow(array('name' => '<span class="label label-info" title="'.$status_name.'" style="background-color:' . $PHPShopOrder->getStatusColor() . '"><span class="hidden-xs">' . substr($status_name,0,25) . '</span></span>', 'link' => '?path=order&return=intro&id=' . $row['id'], 'class' => 'label-link'), array('name' => $uid, 'link' => '?path=order&return=intro&id=' . $row['id']), array('name' => $row['fio'], 'link' => '?path=shopusers&return=intro&id=' . $row['user']), array('name' => $datas, 'class' => 'text-muted'), array('name' => $PHPShopOrder->getTotal(false, ' ') . ' ' . $currency, 'align' => 'right', 'class' => 'strong'));
        }

    $order_list = $PHPShopInterface->getContent();

    // Авторизация
    $PHPShopInterface = new PHPShopInterface();
    $PHPShopInterface->checkbox_action = false;
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['jurnal']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id desc'), array('limit' => 5));
    if (is_array($data))
        foreach ($data as $row) {

            if (empty($row['flag'])) {
                $status = '<span class="glyphicon glyphicon-ok"></span>';
                $link = '?path=users&id=' . $row['id'];
            } else {
                $status = '<span class="glyphicon glyphicon-remove" style="color:red"></span>';
                $link = '?path=users.stoplist&action=new&ip=' . $row['ip'];
            }

            $PHPShopInterface->setRow($status, array('name' => $row['user'], 'link' => $link, 'align' => 'left'), array('name' => $row['ip'], 'align' => 'right'), array('name' => PHPShopDate::get($row['datas'], true), 'align' => 'right'));
        }
    $user_list = $PHPShopInterface->getContent();


    // Права менеджеров
    if ($PHPShopSystem->ifSerilizeParam('admoption.rule_enabled', 1) and !$PHPShopBase->Rule->CheckedRules('catalog', 'remove')) {
        $where = array('user' => "=" . intval($_SESSION['idPHPSHOP']));
    }

    
    // Убираем подтипы
    $where['parent_enabled'] = "='0'";

    // Новые товары
    $PHPShopInterface = new PHPShopInterface();
    $PHPShopInterface->checkbox_action = false;
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $data = $PHPShopOrm->select(array('id,name,items,datas'), $where, array('order' => 'datas desc'), array('limit' => 5));
    if (is_array($data))
        foreach ($data as $row) {


            $PHPShopInterface->setRow(
                    array('name' => $row['name'], 'link' => '?path=product&return=catalog&id=' . $row['id'], 'align' => 'left'), array('name' => PHPShopDate::get($row['datas'], false), 'align' => 'right'));
        }
    $product_list = $PHPShopInterface->getContent();


    $PHPShopGUI->_CODE.='
     <div class="row intro-row">
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-flag"></span> Новых заказов</div>
                <div class="panel-body text-right panel-intro">
                <a href="?path=order">' . $PHPShopBase->getNumRows('orders', 'where statusi=0') . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-envelope"></span> Cообщений</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=shopusers.messages">' . $PHPShopBase->getNumRows('messages', "where enabled='0'") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 hidden-xs hidden-sm">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-list-alt"></span> Комментариев</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=shopusers.comment">' . $PHPShopBase->getNumRows('comment', "where enabled != '1'") . '</a>
               </div>
          </div>
       </div>

       <div class="col-md-6 col-xs-12">
          <div class="panel panel-default ' . $search_jurnal_class . '">
             <div class="panel-heading"><span class="glyphicon glyphicon-' . $search_jairnal_icon . '"></span> ' . $search_jurnal_title . '</div>
                <div class="panel-body">
                 ' . $search_jurnal . '
               </div>
          </div>
       </div>
   </div>   

   <div class="row intro-row">
       <div class="col-xs-12 col-md-12 col-lg-6">
           <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-shopping-cart"></span> Последние заказы <a class="pull-right" href="?path=order">Показать больше</a></div>
                   <table class="table table-hover intro-list">' . $order_list . '</table>
          </div>
       </div>
       <div class="visible-lg col-lg-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-stats"></span> Статистика заказов 
             <span class="pull-right hidden-xs">
             
<div class="dropdown">
  <button class="btn btn-default btn-xs dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    <span class="glyphicon glyphicon-cog"></span>
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu dropdown-menu-right canvas-select">
    <li class="disabled"><a href="#" class="canvas-line">Линейная диаграмма</a></li>
    <li><a href="#" class="canvas-bar">Гистограмма</a></li>
    <li><a href="#" class="canvas-radar">Радар диаграмма</a></li>
    <li class="divider"></li>
    <li><a href="?path=report.statproduct">Больше отчетов</a></li>
  </ul>
</div>

             
                </span>
              </div>
                <div class="panel-body">
                 <div class="intro-canvas">
                     <canvas id="canvas" data-currency="' . $PHPShopSystem->getDefaultValutaCode() . '"  data-value=\'[' . substr($canvas_value, 0, strlen($canvas_value) - 1) . ']\' data-label=\'[' . substr($canvas_label, 0, strlen($canvas_label) - 1) . ']\'></canvas>
                 </div>
               </div>
          </div>
       </div>
   </div>
   
    <div class="row intro-row">
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-eye-open"></span> На витрине</div>
                <div class="panel-body text-right panel-intro">
                <a href="?path=catalog&where[enabled]=1">' . $PHPShopBase->getNumRows('products', "where enabled='1' and parent_enabled='0'") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-eye-close"></span> Скрыто</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=catalog&where[enabled]=0&where[parent_enabled]=0">' . $PHPShopBase->getNumRows('products', "where enabled='0' and parent_enabled='0'") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-bell"></span> Нет в наличии</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=catalog&where[sklad]=1">' . $PHPShopBase->getNumRows('products', "where sklad = '1'") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 col-xs-6">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-folder-open"></span> Категории</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=catalog">' . $PHPShopBase->getNumRows('categories', "") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 hidden-xs hidden-sm">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Покупатели</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=shopusers">' . $PHPShopBase->getNumRows('shopusers', "where enabled = '1'") . '</a>
               </div>
          </div>
       </div>
       <div class="col-md-2 hidden-xs hidden-sm">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-shopping-cart"></span> Заказы</div>
                <div class="panel-body text-right panel-intro">
                 <a href="?path=order">' . $PHPShopBase->getNumRows('orders', "") . '</a>
               </div>
          </div>
       </div>
   </div>   
   
<div class="row intro-row">
       <div class="col-md-6 col-xs-12">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-user"></span> Журнал авторизации <a class="pull-right" href="?path=users.jurnal">Показать больше</a></div>
                <table class="table table-hover intro-list">' . $user_list . '</table>
          </div>
       </div>
       <div class="col-md-6 hidden-xs hidden-sm">
          <div class="panel panel-default">
             <div class="panel-heading"><span class="glyphicon glyphicon-refresh"></span> Обновление товаров <a class="pull-right" href="?path=catalog&order[datas]=desc">Показать больше</a></div>
                <table class="table table-hover intro-list">' . $product_list . '</table>
          </div>
       </div>
   </div>   
';

    $PHPShopGUI->Compile();
}

?>