<?php
if (empty($_GET['path']))
    header('Location: ?path=intro');

// Фрейм
if (isset($_GET['frame'])) {
    $isFrame = ' hidden ';
    $frameWidth = 'width:100%;';
    $isMobile = null;
} else {
    $isFrame = $frameWidtn = null;
    $isMobile = 'visible-xs';
}

session_start();
$_classPath = "../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass(array("base", "system", "admgui", "orm", "date", "xml", "security", "string", "parser", "mail", "lang"));


$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
$PHPShopBase->chekAdmin();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();
$_SESSION['lang'] = $PHPShopSystem->getSerilizeParam("admoption.lang");
$PHPShopLang = new PHPShopLang(array('locale' => $_SESSION['lang'], 'path' => 'admin'));

$_SESSION['imageResultPath'] = $PHPShopSystem->getSerilizeParam('admoption.image_result_path');
$_SESSION['imageResultDir'] = $PHPShopBase->getParam('dir.dir');
if ($PHPShopSystem->ifSerilizeParam('admoption.dadata_enabled')) {
    $DADATA_TOKEN = $PHPShopSystem->getSerilizeParam('admoption.dadata_token');
    if (empty($DADATA_TOKEN))
        $DADATA_TOKEN = 'b13e0b4fd092a269e229887e265c62aba36a92e5';
}
else
    $DADATA_TOKEN = null;

// Редактор GUI
$PHPShopGUI = new PHPShopGUI();
$PHPShopInterface = new PHPShopInterface();

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/*
 *  Роутер
 */

// Меню модулей
$modulesMenu = modulesMenu();

// Подраздел [cat.sub]
if (strpos($_GET['path'], '.')) {
    $subpath = explode(".", $_GET['path']);

    // Редирект [cat.id]
    if (is_numeric($subpath[1])) {
        if ($subpath[0] == 'catalog')
            header('Location: ?path=' . $subpath[0] . '&cat=' . $subpath[1]);
        else if ($subpath[0] == 'sort')
            header('Location: ?path=' . $subpath[0] . '&cat=' . $subpath[1]);
        else
            header('Location: ?path=' . $subpath[0] . '&id=' . $subpath[1]);
    }
    else
        $loader_file = $subpath[0] . '/admin_' . $subpath[1] . '.php';
}
else
    $subpath = array($_GET['path'], $_GET['path']);

if (!empty($_GET['path'])) {

    if (empty($_REQUEST['id'])) {

        $loader_file = $subpath[0] . '/admin_' . $subpath[1] . '.php';
    } else {

        $loader_file = $subpath[0] . '/adm_' . $subpath[1] . 'ID.php';
    }
    if ($_REQUEST['action'] == 'new') {
        $loader_file = $subpath[0] . '/adm_' . $subpath[1] . '_new.php';
    }
    $active_path = str_replace(".", "_", $_GET['path']);
    ${'menu_active_' . $active_path} = 'active';
}

$loader_function = 'actionStart';
if (file_exists($loader_file)) {

    if (empty($_REQUEST['id']) and empty($_REQUEST['action']))
        require_once($loader_file);
    else {
        ob_start();
        require_once($loader_file);
        $interface = ob_get_clean();
    }
}

// Подключение меню модулей
function modulesMenu() {
    global $notificationList;

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['modules']);
    if (!empty($_SESSION['mod_limit']))
        $mod_limit = intval($_SESSION['mod_limit']);
    else
        $mod_limit = 50;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'date desc'), array('limit' => $mod_limit));
    $dis = $db = null;
    if (is_array($data))
        foreach ($data as $row) {
            $path = $row['path'];
            $menu = "../modules/" . $path . "/install/module.xml";
            $db = xml2array($menu, "adminmenu", true);
            if ($db['capability']) {
                $dis.='<li><a href="?path=modules&id=' . $path . '">' . $db['title'] . '</a></li>';
            }

            // Notification
            if (!empty($db['notification'])) {
                $notificationList[] = $path;
            }

            // Redirect module.xml redirect.from -> redirect.to
            if (is_array($db['redirect'])) {
                if ($_GET['path'] == $db['redirect']['from'] and empty($_GET['id'])) {

                    // Сохранение GET параметров
                    parse_str($_SERVER['QUERY_STRING'], $source_query);
                    $source_query['path'] = 'modules.' . $db['redirect']['to'];

                    return header('Location: ?' . http_build_query($source_query));
                }
            }
        }

    return $dis;
}

// Автостарт презентации
if (empty($_COOKIE['presentation']) or $_COOKIE['presentation'] == 'true')
    $presentation_checked = 'checked';
else
    $presentation_checked = null;

// Тема оформления
if(empty($_SESSION['admin_theme']))
$theme = PHPShopSecurity::TotalClean($PHPShopSystem->getSerilizeParam('admoption.theme'));
else $theme = $_SESSION['admin_theme'];
if (!file_exists('./css/bootstrap-theme-' . $theme . '.css'))
    $theme = 'default';

$version = null;
$adm_title = $adm_brand = $PHPShopSystem->getSerilizeParam('admoption.adm_title');
foreach (str_split($GLOBALS['SysValue']['upload']['version']) as $w)
    $version.=$w . '.';
$brand = 'PHPShop ' . substr($version, 0, 3);
if (empty($adm_title)) {
    $adm_title = 'PHPShop';
    $adm_brand = $brand;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $GLOBALS['PHPShopLang']->code; ?>">
    <head>
        <meta charset="<?php echo $GLOBALS['PHPShopLang']->charset; ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $adm_title . ' - ' . PHPShopSecurity::TotalClean($TitlePage); ?></title>
        <meta name="author" content="PHPShop Software">
        <meta name="description" content="<?php echo $brand; ?>">
        <link rel="apple-touch-icon" href="./apple-touch-icon.png">
        <link rel="icon" href="./favicon.ico"> 

        <!-- Bootstrap -->
        <link id="bootstrap_theme" href="./css/bootstrap-theme-<?php echo $theme; ?>.css" rel="stylesheet">


        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body role="document" id="body" data-token="<?php echo $DADATA_TOKEN; ?>">

        <!-- jQuery plugins -->
        <link href="./css/jquery.dataTables.css" rel="stylesheet">
        <link href="./css/bootstrap-select.min.css" rel="stylesheet">
        <link href="./css/jquery.treegrid.css" rel="stylesheet">
        <link href="./css/admin.css" rel="stylesheet">
        <link href="./css/bar.css" rel="stylesheet">
        <link href="./css/bootstrap-tour.min.css" rel="stylesheet">

        <!-- jQuery -->
        <script src="js/jquery-1.11.0.min.js" data-rocketoptimized="false" data-cfasync="false"></script>

        <!-- Localization -->
        <script src="../locale/<?php echo $_SESSION['lang']; ?>/gui.js" data-rocketoptimized="false" data-cfasync="false"></script>

        <div class="container" style="<?php echo $frameWidth; ?>">

            <nav class="navbar navbar-default <?php echo $isFrame; ?>">
                <div>

                    <!-- Brand  -->
                    <div class="navbar-header">
                        <a class="navbar-brand" href="../../" title="<?php _e('Перейти в магазин'); ?>" target="_blank"><span class="glyphicon glyphicon-cog"></span> <?php echo $adm_brand ?></a>
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar1" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div id="navbar1" class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li class="dropdown <?php echo $menu_active_modules; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php _e('Модули'); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu" id="modules-menu">
                                    <li class="dropdown-header"><?php _e('Установленные модули'); ?></li>
                                    <?php echo $modulesMenu; ?>
                                    <li class="divider"></li>
                                    <li><a href="?path=modules"><span class="glyphicon glyphicon-tasks"></span> <?php _e('Управление модулями'); ?></a></li>

                                </ul>
                            </li>
                            <li class="dropdown <?php echo $menu_active_system . $menu_active_system_company . $menu_active_system_seo . $menu_active_system_sync . $menu_active_tpleditor . $menu_active_system_image . $menu_active_system_servers . $menu_active_system_integration; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php _e('Настройки'); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=system"><?php _e('Основные'); ?></a></li>
                                    <li><a href="?path=system.company"><?php _e('Реквизиты'); ?></a></li>
                                    <li><a href="?path=system.sync"><?php _e('Документооборот'); ?></a></li>
                                    <li><a href="?path=system.seo"><?php _e('SEO заголовки'); ?></a></li>
                                    <li><a href="?path=system.currency"><?php _e('Валюты'); ?></a></li>
                                    <li><a href="?path=system.image"><?php _e('Изображения'); ?></a></li>
                                    <li><a href="?path=system.servers"><?php _e('Витрины'); ?></a></li>
                                    <li><a href="?path=system.integration"><?php _e('Интеграция'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=tpleditor"><span class="glyphicon glyphicon-picture"></span> <?php _e('Шаблоны дизайна'); ?></a></li>
                                </ul>
                            </li>
                            <li class="dropdown <?php echo $menu_active_exchange_export . $menu_active_exchange_import . $menu_active_exchange_sql . $menu_active_exchange_backup . $menu_active_exchange_service . $menu_active_exchange_export_order . $menu_active_exchange_export_user . $menu_active_exchange_export_catalog . $menu_active_exchange_import_order . $menu_active_exchange_import_user . $menu_active_exchange_import_catalog; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php _e('База'); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=exchange.import"><span class="glyphicon glyphicon-import"></span> <?php _e('Импорт данных'); ?></a></li>
                                    <li><a href="?path=exchange.export"><span class="glyphicon glyphicon-export"></span> <?php _e('Экспорт данных'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=exchange.service"><?php _e('Обслуживание'); ?></a></li>
                                    <li><a href="?path=exchange.sql"><?php _e('SQL запрос к базе'); ?></a></li>
                                    <li><a href="?path=exchange.backup"><?php _e('Резервное копирование'); ?></a></li>
                                </ul>
                            </li>
                            <li class="dropdown <?php echo $menu_active_update . $menu_active_update_restore . $menu_active_system_about ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php _e('Справка'); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=system.about"><?php _e('О программе'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="http://faq.phpshop.ru" target="_blank"><?php _e('Учебник'); ?></a></li>
                                    <li><a href="https://help.phpshop.ru" target="_blank"><?php _e('Техподдержка'); ?></a></li>
                                    <li><a href="#" id="presentation-select"><?php _e('Обучение'); ?></a></li>
                                    <li><a href="http://idea.phpshop.ru" target="_blank"><?php _e('Предложить идею'); ?></a></li>
                                    <li class="divider"></li>
                                    <li class="dropdown-header"><?php _e('Дополнительно'); ?></li>
                                    <li><a href="https://www.phpshop.ru/loads/files/setup.exe" target="_blank"><?php _e('Утилиты'); ?> EasyControl</a></li>
                                    <li><a href="https://www.phpshop.ru/page/yandex-webmaster.html" target="_blank">SEO <?php _e('оптимизация'); ?></a></li>
                                    <li><a href="https://beget.com/p566" target="_blank"><?php _e('Хостинг сайта'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=update"><span class="glyphicon glyphicon-cloud-download"></span> <?php _e('Мастер обновления'); ?></a></li>

                                </ul>
                            </li>
                            <li class="divider"></li>
                            <li class="dropdown <?php echo $menu_active_users . $menu_active_users_jurnal . $menu_active_users_stoplist; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-user hidden-xs"></span> <span class="visible-xs"><?php _e('Администратор'); ?> <span class="caret"></span></span><span class="caret  hidden-xs"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li class="dropdown-header"><?php _e('Вошел как');
                                    echo ' ' . $_SESSION['logPHPSHOP'];
                                    ?></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=users&id=<?php echo $_SESSION['idPHPSHOP']; ?>"><?php _e('Профиль'); ?></a></li>
                                    <li><a href="?path=users"><?php _e('Все администраторы'); ?></a></li>
                                    <li><a href="?path=users.jurnal"><?php _e('Журнал авторизации'); ?></a></li>
                                    <li class="divider"></li>
                                    <li><a href="./?logout"><span class="glyphicon glyphicon-transfer"></span> <?php _e('Выйти'); ?></a></li>
                                </ul>
                            </li>
                            <li><a href="../../" title="<?php _e('Перейти в магазин'); ?>" class="home go2front hidden-xs" target="_blank"><span class="glyphicon glyphicon-share-alt"></span></a></li>
                        </ul>
                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
            <nav class="navbar navbar-inverse navbar-statick <?php echo $isFrame; ?>">
                <div>

                    <div class="navbar-header pull-left">
                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar2" aria-expanded="false" aria-controls="navbar">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div id="navbar2" class="collapse navbar-collapse">

                        <ul class="nav navbar-nav">
                            <li><a href="../../" title="Магазин" target="_blank" class="visible-xs"><?php _e('Магазин'); ?></a></li>
                            <li class="<?php echo $menu_active_intro; ?>"><a href="./admin.php" title="Стартовая панель" class="home"><span class="glyphicon glyphicon-home hidden-xs"></span><span class="visible-xs"><?php _e('Домой'); ?></span></a></li>
                            <li class="dropdown <?php echo $menu_active_order . $menu_active_payment . $menu_active_order_paymentlog . $menu_active_order_status . $menu_active_report_statorder . $menu_active_report_statuser . $menu_active_report_statpayment . $menu_active_report_statproduct; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php _e('Заказы'); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=order"><span><?php _e('Заказы'); ?></span><span class="dropdown-header"><?php _e('Просмотр и оформление заказов, распечатка счетов'); ?></span></a></li>
                                    <li><a href="?path=order.paymentlog"><?php _e('Электронные платежи'); ?><span class="dropdown-header"><?php _e('Просмотр журнала оплаты заказов платежными системами'); ?></span></a></li>
                                    <li><a href="?path=payment"><?php _e('Способы оплаты'); ?><span class="dropdown-header"><?php _e('Просмотр, добавление и редактирование способов оплаты заказов'); ?></span></a></li>
                                    <li><a href="?path=order.status"><?php _e('Статусы заказов'); ?><span class="dropdown-header"><?php _e('Просмотр, добавление и редактирование статусов заказов'); ?></span></a></li>
                                    <li><a href="?path=delivery"><?php _e('Доставка'); ?><span class="dropdown-header"><?php _e('Просмотр и редактирование доставки. Настройка полей для заполнения заказа'); ?></span></a></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=report.statorder"><span class="glyphicon glyphicon-stats"></span> <?php _e('Отчеты по продажам'); ?></a></li>
                                </ul>
                            </li>

                            <li class="dropdown <?php echo $menu_active_catalog . $menu_active_product . $menu_active_report_searchjurnal . $menu_active_report_searchreplace . $menu_active_sort; ?>" id="tour-product">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php _e('Товары'); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=catalog"><span><?php _e('Товары'); ?></span><span class="dropdown-header"><?php _e('Просмотр, добавление и редактирование товаров'); ?></span></a></li>
                                    <li><a href="?path=catalog&action=new"><span><?php _e('Каталоги'); ?></span><span class="dropdown-header"><?php _e('Просмотр, добавление и редактирование категорий товаров'); ?></span></a></li>
                                    <li><a href="?path=sort"><?php _e('Характеристики'); ?><span class="dropdown-header"><?php _e('Просмотр, добавление и редактирование дополнительных полей товаров'); ?></span></a></li>
                                    <li><a href="?path=sort.parent"><?php _e('Варианты подтипов'); ?><span class="dropdown-header"><?php _e('Просмотр, добавление и редактирование вариантов подтипов товаров'); ?></span></a></li>
                                    <li class="divider"></li>
                                    <li><a href="?path=report.searchjurnal"><span class="glyphicon glyphicon-sunglasses"></span> <?php _e('Журнал поиска товаров'); ?></a></li>
                                </ul>
                            </li>

                            <li class="dropdown <?php echo $menu_active_shopusers . $menu_active_shopusers_status . $menu_active_shopusers_notice . $menu_active_shopusers_comment . $menu_active_shopusers_messages; ?>">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><?php _e('Пользователи'); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=shopusers"><?php _e('Покупатели'); ?><span class="dropdown-header"><?php _e('Список зарегистрированных покупателей магазина'); ?></span></a></li>
                                    <li><a href="?path=shopusers.status"><?php _e('Статусы и скидки'); ?><span class="dropdown-header"><?php _e('Управление статусами и скидками пользователей магазина'); ?></span></a></li>
                                    <li><a href="?path=shopusers.notice"><?php _e('Уведомления'); ?><span class="dropdown-header"><?php _e('Заявки о поступлении товара на склад от пользователей магазина'); ?></span></a></li>
                                    <li><a href="?path=shopusers.comment"><?php _e('Комментарии'); ?><span class="dropdown-header"><?php _e('Список комментариев для товаров, оставленные пользователями'); ?></span></a></li>
                                    <li><a href="?path=shopusers.messages"><?php _e('Переписка'); ?><span class="dropdown-header"><?php _e('Переписка с покупателями магазина из личного кабинета'); ?></span></a></li>
                                </ul>
                            </li>

                            <li class="dropdown <?php echo $menu_active_menu . $menu_active_gbook . $menu_active_page_catalog . $menu_active_page . $menu_active_news . $menu_active_news_rss . $menu_active_photo_catalog; ?>">
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false"><?php _e('Веб-сайт'); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=page.catalog"><?php _e('Страницы'); ?><span class="dropdown-header"><?php _e('Создание и публикация страниц'); ?></span></a></li>
                                    <li><a href="?path=photo.catalog"><?php _e('Фотогалерея'); ?><span class="dropdown-header"><?php _e('Фотогалерея изображений на сайте'); ?></span></a></li>
                                    <li><a href="?path=menu"><?php _e('Текстовые блоки'); ?><span class="dropdown-header"><?php _e('Вывод текстовых блоков в блок-меню'); ?></span></a></li>
                                    <li><a href="?path=gbook"><?php _e('Отзывы'); ?><span class="dropdown-header"><?php _e('Отзывы пользователей о сайте'); ?></span></a></li>
                                    <li><a href="?path=news"><?php _e('Новости'); ?><span class="dropdown-header"><?php _e('Новостная лента сайта'); ?></span></a></li>
                                </ul>
                            </li>

                            <li class="dropdown <?php echo $menu_active_slider . $menu_active_links . $menu_active_banner . $menu_active_opros . $menu_active_metrica_traffic . $menu_active_metrica_sources_summary . $menu_active_metrica_sources_social . $menu_active_metrica_sources_sites . $menu_active_metrica_search_phrases . $menu_active_metrica_search_engines . $menu_active_metrica; ?>" >
                                <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-expanded="false"><?php _e('Маркетинг'); ?> <span class="caret"></span></a>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="?path=slider"><span><?php _e('Слайдер'); ?></span><span class="dropdown-header"><?php _e('Рекламный слайдер на главной странице'); ?></span></a></li>
                                    <li><a href="?path=news.sendmail"><?php _e('Рассылки'); ?><span class="dropdown-header"><?php _e('Создание email рассылок пользователям'); ?></span></a></li>

                                    <li><a href="?path=links"><span><?php _e('Ссылки'); ?></span><span class="dropdown-header"><?php _e('Обмен ссылками с сайтами'); ?></span></a></li>
                                    <li><a href="?path=banner"><?php _e('Баннеры'); ?><span class="dropdown-header"><?php _e('Вывод графических изображений'); ?></span></a></li>
                                    <li><a href="?path=opros"><?php _e('Опросы'); ?><span class="dropdown-header"><?php _e('Опросы для пользователей на сайте'); ?></span></a></li>                                    <li class="divider"></li>
                                    <li><a href="?path=metrica"><span class="glyphicon glyphicon-equalizer"></span> <?php _e('Статистика посещений'); ?></a></li>
                                </ul>
                            </li>
                        </ul>
                        <?php
// Быстрый поиск
                        switch ($PHPShopSystem->getSerilizeParam('admoption.search_enabled')) {
                            case 1:
                                $search_class = 'hidden';
                                $search_id = $search_name = $search_placeholder = $search_action = $search_value = null;
                                break;

                            case 3:
                                $search_class = 'hidden-xs search-product';
                                $search_placeholder = __('Искать в товарах...');
                                $search_target = '_self';
                                $search_name = 'where[name]';
                                $search_value = PHPShopSecurity::true_search($_GET['where']['name']);
                                break;

                            default:
                                $search_class = 'hidden-xs';
                                $search_placeholder = __('Искать в учебнике...');
                                $search_action = 'http://faq.phpshop.ru/search/';
                                $search_id = 'search';
                                $search_target = '_blank';
                                $search_name = 'words';
                                $search_value = null;
                        }
                        ?>
                        <form class="navbar-right <?php echo $search_class; ?>"  action="<?php echo $search_action; ?>" target="<?php echo $search_target; ?>">
                            <div class="input-group">
                                <input name="<?php echo $search_name; ?>" maxlength="50" value="<?php echo $search_value; ?>" id="<?php echo $search_id; ?>" class="form-control input-sm" placeholder="<?php echo $search_placeholder; ?>" required="" type="search"  data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="">
                                <input type="hidden" name="path" value="catalog">
                                <input type="hidden" name="from" value="header">
                                <span class="input-group-btn">
                                    <button class="btn btn-default btn-sm" type="submit"><span class="glyphicon glyphicon-search"></span></button>
                                </span>
                            </div>
                        </form>
                        <?php
                        // notification
                        $i_notif = 0;
                        if (is_array($notificationList))
                            foreach ($notificationList as $notification) {
                                if ($i_notif < 3) {
                                    include_once($_classPath . 'modules/' . $notification . '/admpanel/notification.php');
                                    $notification_function = 'notification' . ucfirst($notification);
                                    if (function_exists($notification_function)) {
                                        call_user_func($notification_function);
                                    }
                                }
                                $i_notif++;
                            }


                        if (!empty($_SESSION['update_check']))
                            echo '<a class="navbar-btn btn btn-sm btn-info navbar-right hidden-xs" href="?path=update" data-toggle="tooltip" data-placement="bottom" title="' . __('Доступно обновление') . '">Update <span class="badge">' . intval($_SESSION['update_check']) . '</span></a>';
                        ?>

                        <a class="navbar-btn btn btn-sm btn-warning navbar-right hidden-xs hidden-sm hide" href="?path=order&where[statusi]=0"><?php _e('Заказы'); ?> <span class="badge" id="orders-check"><?php echo $PHPShopBase->getNumRows('orders', "where statusi='0'"); ?></span>
                        </a>

                    </div><!-- /.navbar-collapse -->
                </div>
            </nav>
            <div class="clearfix"></div>
            <a id="temp-color" class="hide"></a><a id="temp-color-selected" class="hide"></a>

            <?php
            if (file_exists($loader_file)) {
                if (empty($_REQUEST['id']) and empty($_REQUEST['action'])) {

                    if ($PHPShopBase->Rule->CheckedRules($subpath[0], 'view')) {
                        if (function_exists($loader_function))
                            call_user_func($loader_function);
                        else
                            _e('Функция ') . $loader_function . __('() не найдена в файле ') . $loader_file;
                    }
                    else
                        $PHPShopBase->Rule->BadUserFormaWindow();
                }
                else
                    echo $interface;
            }
            else
                $PHPShopBase->Rule->BadUserFormaWindow();
            ?>
            <br>
        </div>

        <!-- Notification -->
        <div id="notification" class="success-notification hide">
            <div  class="alert alert-success alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <span class="notification-alert"> </span>
            </div>
        </div>
        <!--/ Notification -->

        <!-- Presentation -->
        <div id="presentation" class="hide">
            <div id="presentation-content">
                <div class="panel panel-default">
                    <div class="panel-heading "><span class="glyphicon glyphicon-film text-primary"></span> <b class="text-primary"><?php _e('Урок 1: Создание товара'); ?></b>
                        <a class="btn btn-primary btn-xs pull-right" href="?path=product&return=catalog&action=new&video"><span class="glyphicon glyphicon-play"></span> <?php _e('Старт'); ?></a></div>
                    <div class="panel-body ">
<?php _e('Обучающий урок по созданию нового товара, заполнения полей и сохранения результата'); ?>.
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><span class="glyphicon glyphicon-film text-primary"></span> <b class="text-primary"><?php _e('Урок 2: Создание каталога'); ?></b>
                        <a class="btn btn-primary btn-xs pull-right" href="?path=catalog&action=new&video"><span class="glyphicon glyphicon-play"></span> <?php _e('Старт'); ?></a></div>
                    <div class="panel-body ">
<?php _e('Обучающий урок по созданию нового каталога товара, заполнения полей и сохранения результата'); ?>.
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><span class="glyphicon glyphicon-film text-primary"></span> <b class="text-primary"><?php _e('Урок 3: Редактор шаблонов'); ?></b>
                        <a class="btn btn-primary btn-xs pull-right" href="?path=tpleditor&name=bootstrap&file=/main/index.tpl&mod=html&video"><span class="glyphicon glyphicon-play"></span> <?php _e('Старт'); ?></a></div>
                    <div class="panel-body">
<?php _e('Обучающий урок по редактированию шаблона дизайна, описание переменных шаблонизатора, управление редактором кода'); ?>.
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><span class="glyphicon glyphicon-film text-primary"></span> <b class="text-primary"><?php _e('Урок 4: Настройки'); ?></b><a class="btn btn-primary btn-xs pull-right" href="?path=system#1"><span class="glyphicon glyphicon-play"></span> <?php _e('Старт'); ?></a>
                    </div>
                    <div class="panel-body">
<?php _e('Выбрать общий шаблон дизайна магазина можно в <a href="?path=system#1">Настройках дизайна</a>. Изменить цветовую тему оформления панели управления можно в  <a href="?path=system#4">Настройках управления</a>'); ?>.
                    </div>
                </div>
                <div class="checkbox text-muted">
                    <label>
                        <input type="checkbox" <?php echo $presentation_checked; ?> id="presentation-check">  <?php _e('Показывать при входе в панель управления'); ?>
                    </label>
                </div>
            </div>
        </div>
        <?php
        if (isset($_GET['video'])) {
            echo '<script>var video=true;</script>';
        }

        if ($_GET['path'] == 'intro' and $presentation_checked == 'checked')
            echo '<script>var presentation_start=true;</script>';
        ?>

        <!--/ Presentation -->


        <!-- Modal select -->
        <div class="modal" id="selectModal" tabindex="-1" role="dialog" aria-labelledby="selectModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="form-horizontal" role="form" data-toggle="validator" id="modal-form" method="post" enctype="multipart/form-data">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                            <h4 class="modal-title" id="selectModalLabel">Заголовок</h4>
                        </div>
                        <div class="modal-body">

<?php if (!empty($selectModalBody)) echo $selectModalBody; ?>

                        </div>
                        <div class="modal-footer">

                            <!-- Progress -->
                            <div class="progress hidden">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="5" aria-valuemin="0" aria-valuemax="100" style="width: 5%">
                                    <span class="sr-only">45% Complete</span>
                                </div>
                            </div>   
                            <!--/ Progress -->

                            <button type="button" class="btn btn-default btn-sm pull-left hidden btn-delete"><span class="glyphicon glyphicon-trash"></span> <?php _e('Удалить'); ?></button>
                            <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><?php _e('Отменить'); ?></button>
                            <button type="submit" class="btn btn-primary btn-sm"><?php _e('Применить'); ?></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!--/ Modal select-->

        <!-- Modal filemanager -->
        <div class="modal bs-example-modal-lg" id="elfinderModal" tabindex="-1" role="dialog"  aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>

                        <span class="btn btn-default btn-sm pull-left glyphicon glyphicon-fullscreen" id="filemanagerwindow" data-toggle="tooltip" data-placement="bottom" title="Увеличить размер"></span>

                        <h4 class="modal-title"><?php _e('Найти файл'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <iframe class="elfinder-modal-content" frameborder="0" marginheight="0" marginwidth="0" scrolling="no" data-path="image" data-option="return=icon_new"></iframe>

                    </div>
                </div>
            </div>
        </div>
        <!--/ Modal filemanager -->

        <!-- Fixed mobile bar -->
        <div class="bar-padding-fix <?php echo $isMobile . $isFrame; ?>"> </div>
        <nav class="navbar navbar-statick navbar-fixed-bottom bar bar-tab visible-xs visible-sm <?php echo $isFrame; ?>" role="navigation">
            <a class="tab-item <?php echo $menu_active_intro; ?>" href="./admin.php">
                <span class="icon icon-home"></span>
                <span class="tab-label"><?php _e('Домой'); ?></span>
            </a>
            <a class="tab-item <?php echo $menu_active_order; ?>" href="?path=order" id="bar-cart">
                <span class="icon icon-download"></span> <span class="badge badge-positive hide" id="orders-mobile-check"><?php echo $PHPShopBase->getNumRows('orders', "where statusi='0'"); ?></span>
                <span class="tab-label"><?php _e('Заказы'); ?></span>
            </a>
            <a class="tab-item <?php echo $menu_active_catalog; ?>" href="?path=catalog">
                <span class="icon icon-compose"></span>
                <span class="tab-label"><?php _e('Цены'); ?></span>
            </a>
            <a class="tab-item <?php echo $menu_active_shopusers; ?>"  href="?path=shopusers">
                <span class="icon icon-person"></span>
                <span class="tab-label"><?php _e('Покупатели'); ?></span>
            </a>
            <a class="tab-item" href="./?logout">
                <span class="icon icon-share"></span>
                <span class="tab-label"><?php _e('Выход'); ?></span>
            </a>
        </nav>
        <!--/ Fixed mobile bar -->

        <!-- jQuery plugins -->
        <script src="./js/bootstrap.min.js" data-rocketoptimized="false" data-cfasync="false"></script>
        <script src="./js/jquery.dataTables.min.js" data-rocketoptimized="false" data-cfasync="false"></script>
        <script src="./js/dataTables.bootstrap.js" data-rocketoptimized="false" data-cfasync="false"></script>
        <script src="./js/phpshop.js" data-rocketoptimized="false" data-cfasync="false"></script>
        <script src="./js/jquery.cookie.js" data-rocketoptimized="false" data-cfasync="false"></script>
        <script src="./js/jquery.form.js" data-rocketoptimized="false" data-cfasync="false"></script>
        <script src="./js/bootstrap-select.min.js" data-rocketoptimized="false" data-cfasync="false"></script>
        <!--/ jQuery plugins -->

        <?php
        if (isset($_SESSION['chat']) and !$PHPShopSystem->ifSerilizeParam("admoption.chat_enabled"))
            echo ' <!-- Chat --> <link rel="stylesheet" href="https://cdn.envybox.io/widget/cbk.css">
<script type="text/javascript" src="https://cdn.envybox.io/widget/cbk.js?wcb_code=679f0a2e11f4ab299aa741fb8d211539" charset="UTF-8" async></script><!--/ Chat -->';
        ?>
    </body>
</html>
<?php
// Запись файла локализации
//writeLangFile();
?>