<?php

session_start();
$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass(array("base", "system", "admgui", "orm", "date", "xml", "security", "string", "parser", "mail", "lang"));
$subpath[0] = 'catalog';

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
$PHPShopBase->chekAdmin();

PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('category');
PHPShopObj::loadClass('sort');

// Системные настройки
$PHPShopSystem = new PHPShopSystem();
$PHPShopLang = new PHPShopLang(array('locale' => $_SESSION['lang'], 'path' => 'admin'));

// Редактор GUI
$PHPShopInterface = new PHPShopInterface();

/**
 * Вывод товаров
 */
// Настройка полей
if (!empty($_COOKIE['check_memory'])) {
    $memory = json_decode($_COOKIE['check_memory'], true);
}
if (!is_array($memory['catalog.option']) or count($memory['catalog.option']) < 3) {
    $memory['catalog.option']['icon'] = 1;
    $memory['catalog.option']['name'] = 1;
    $memory['catalog.option']['price'] = 1;
    $memory['catalog.option']['item'] = 1;
    $memory['catalog.option']['menu'] = 1;
    $memory['catalog.option']['status'] = 1;
    $memory['catalog.option']['label'] = 1;
    $memory['catalog.option']['uid'] = 0;
    $memory['catalog.option']['id'] = 0;
    $memory['catalog.option']['num'] = 0;
    $memory['catalog.option']['sort'] = 0;
}

// Характеристики
if (!empty($memory['catalog.option']['sort'])) {
    $PHPShopSortArray = new PHPShopSortArray();
    $PHPShopSort = $PHPShopSortArray->getArray();
    $PHPShopSortCategoryArray = new PHPShopSortCategoryArray();
    $PHPShopSortCategory = $PHPShopSortCategoryArray->getArray();
} else {
    $PHPShopSort = array();
    $PHPShopSortCategory = array();
}

if (isset($_GET['where']['category']))
    unset($_GET['cat']);

// Тип поиска
switch ($_GET['core']) {
    case 'reg':
        $core = 'REGEXP';
        break;
    case 'eq':
        $core = ' = ';
        break;
    default: $core = 'REGEXP';
}

// ID всегда eq
if (!empty($_GET['where']['id']))
    $core = ' = ';


$where = false;

if (isset($_GET['start']))
    $limit = array('limit' => $_GET['start'] . ',' . $_GET['length']);
else
    $limit = array('limit' => 300);

if (isset($_GET['cat']) or isset($_GET['sub'])) {

    if (!empty($_GET['cat']) or $_GET['sub'] == 'csv' or isset($_GET['sub'])) {
        $where['category'] = "=" . intval($_GET['cat']);
    }

    if ($_GET['sub'] === 'csv') {
        $where['category'] = "='0'";
    }

    // Направление сортировки из настроек каталога
    $PHPShopCategory = new PHPShopCategory(intval($_GET['cat']));
    switch ($PHPShopCategory->getParam('order_to')) {
        case(1): $order_direction = "";
            break;
        case(2): $order_direction = " desc";
            break;
        default: $order_direction = "";
            break;
    }
    switch ($PHPShopCategory->getParam('order_by')) {
        case(1): $order = array('order' => 'name' . $order_direction);
            break;
        case(2):
            $order = array('order' => 'price' . $order_direction);
            break;
        case(3): $order = array('order' => 'num' . $order_direction . ", datas desc");
            break;
        default: $order = array('order' => 'num' . $order_direction . ", datas desc");
            break;
    }
} else {

    $order = array('order' => 'datas DESC');
}

// Расширенная сортировка из JSON
if (is_array($_GET['order']) and ! empty($_SESSION['jsort'][$_GET['order']['0']['column']])) {
    $order = array('order' => $_SESSION['jsort'][$_GET['order']['0']['column']] . ' ' . $_GET['order']['0']['dir']);
}

// Расширенный поиск
if (is_array($_GET['where'])) {
    foreach ($_GET['where'] as $k => $v) {

        if (isset($v) and $v != '') {

            // Характеристики
            if (is_array($v)) {
                $vendor = null;
                foreach ($v as $kk => $vv) {
                    if ($kk == 0 and !empty($vv))
                        $vendor.="  LIKE '%" . PHPShopSecurity::TotalClean($vv) . "%'";
                    elseif (!empty($vv))
                        $vendor.=" and " . PHPShopSecurity::TotalClean($k) . " LIKE '%" . PHPShopSecurity::TotalClean($vv) . "%'";
                }

                if (!empty($vendor))
                    $where[PHPShopSecurity::TotalClean($k)] = $vendor;
            }
            else
                $where[PHPShopSecurity::TotalClean($k)] = " " . $core . " '" . PHPShopSecurity::TotalClean($v) . "'";
        }
    }
}

// Сквозные характеристики
if (!empty($_GET['sort'])) {
    $sort_array = explode(":", $_GET['sort']);
    $PHPShopSortSearch = new PHPShopSortSearch($sort_array[0]);

    if (is_array($PHPShopSortSearch->sort_array))
        foreach ($PHPShopSortSearch->sort_array as $k => $v) {
            if ($v == $sort_array[1])
                $where['vendor'] = " REGEXP 'i" . $PHPShopSortSearch->sort_category . '-' . $k . "i'";
        }
}

// Постфикс
if (!empty($_GET['cat']))
    $postfix = '&cat=' . intval($_GET['cat']);
else
    $postfix = null;


// Таблица с данными
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
$PHPShopOrm->debug = false;

// Быстрый поиск
if ($_GET['from'] == 'header') {
    $where['parent_enabled'] = "='0'";
    $where['parent_enabled'].= " and (name " . $where['name'] . " or uid " . $where['name'] . " or id " . $where['name'] . ")";
    unset($where['name']);
} else {

    // Убираем подтипы
    $where['parent_enabled'] = "='0'";
}

// Поиск размеров
if (!empty($_GET['parent'])) {
    $where['parent'] = "='" . $_GET['parent'] . "'";
    $where['parent_enabled'] = "='1'";
}

// Права менеджеров
if ($PHPShopSystem->ifSerilizeParam('admoption.rule_enabled', 1)) {
    $categoriesOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $PHPShopOrm->debug = false;
    $categories = $categoriesOrm->getList(array('id'), array('secure_groups' => " REGEXP 'i" . $_SESSION['idPHPSHOP'] . "i' or secure_groups = ''"));
    $categoryIds = array();
    foreach ($categories as $category) {
        $categoryIds[] = $category['id'];
    }

    if(count($categoryIds) > 0 && !isset($where['category'])) {
        $where['category'] = sprintf(' IN (%s)', implode(',', $categoryIds));
    }
}

// Поиск на странице JSON
if (!empty($_GET['search']['value'])) {
    $where['parent_enabled'] .= " and (name LIKE '%" . PHPShopString::utf8_win1251(PHPShopSecurity::TotalClean($_GET['search']['value'])) . "%' or uid LIKE '%" . PHPShopString::utf8_win1251(PHPShopSecurity::TotalClean($_GET['search']['value'])) . "%')";
}

$PHPShopOrm->mysql_error = false;
$sklad_enabled = $PHPShopSystem->getSerilizeParam('admoption.sklad_enabled');
$data = $PHPShopOrm->select(array('*'), $where, $order, $limit);
if (is_array($data))
    foreach ($data as $row) {

        if (!empty($row['pic_small']))
            $icon = '<img src="' . $row['pic_small'] . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="media-object">';
        else
            $icon = '<img class="media-object" src="./images/no_photo.gif">';

        $PHPShopInterface->path = 'product&return=catalog';

        // Артикул
        if (!empty($row['uid']) and empty($memory['catalog.option']['uid']))
            $uid = '<div class="text-muted">' . __('Арт') . ' ' . $row['uid'] . '</div>';
        else
            $uid = null;

        if (!empty($memory['catalog.option']['label']) and ( !empty($row['newtip']) or ! empty($row['spec']) or ! empty($row['sklad']) or isset($row['yml']))) {
            $uid .= '<div class="text-muted">';

            // Новинка
            if (!empty($row['newtip']))
                $uid .= '<a class="label label-info" title="' . __('Новинка') . '" href="?path=catalog' . $postfix . '&where[newtip]=1">' . __('Н') . '</a> ';

            // Спецпредложение
            if (!empty($row['spec']))
                $uid .= '<a class="label label-warning" title="' . __('Спецпредложение') . '" href="?path=catalog' . $postfix . '&where[spec]=1">' . __('С') . '</a> ';

            // Под заказ
            if (!empty($row['sklad']))
                $uid .= '<a class="label label-danger" title="' . __('Под заказ') . '" href="?path=catalog' . $postfix . '&where[sklad]=1">' . __('О') . '</a> ';

            // Яндекс Маркет
            if (empty($row['yml']))
                $uid .= '<a class="label label-danger" title="' . __('Нет в Яндекс.Маркете') . '" href="?path=catalog' . $postfix . '&where[yml]=0">' . __('Я') . '</a> ';

            // Яндекс Маркет
            if ($row['cpa'] == 1 and ! empty($row['yml']))
                $uid .= '<a class="label label-info" title="' . __('Яндекс.Маркете CPA') . '" href="?path=catalog' . $postfix . '&where[cpa]=1">' . __('CPA') . '</a> ';

            // Подтип
            if (strstr($row['parent'], ','))
                $uid .= '<a class="label label-default" title="' . __('Подтипы') . '" href="?path=catalog' . $postfix . '&where[parent]=,">' . __('П') . '</a> ';

            $uid .= '</div>';
        }

        // Enabled
        if (empty($row['enabled']))
            $enabled = 'text-muted';
        else
            $enabled = null;

        if ($row['items'] < 0 and $sklad_enabled)
            $row['items'] = 0;

        // Характеристики
        $sort_list = null;
        $sort = unserialize($row['vendor_array']);
        if (is_array($sort))
            foreach ($sort as $scat => $sorts) {
                if (is_array($PHPShopSortCategory[$scat])) {
                    if (is_array($sorts))
                        foreach ($sorts as $s)
                            $sort_list .= '<a href="?path=sort&id=' . $scat . '" class="text-muted">' . $PHPShopSort[$s]['name'] . '</a>, ';
                }
            }

        $sort_list = substr($sort_list, 0, strlen($sort_list) - 2);

        $PHPShopInterface->setRow(
                $row['id'], array('name' => $icon, 'link' => '?path=product&return=catalog.' . $row['category'] . '&id=' . $row['id'], 'align' => 'left', 'view' => intval($memory['catalog.option']['icon'])), array('name' => $row['name'], 'sort' => 'name', 'link' => '?path=product&return=catalog.' . $row['category'] . '&id=' . $row['id'], 'align' => 'left', 'addon' => $uid, 'class' => $enabled, 'view' => intval($memory['catalog.option']['name'])), array('name' => $row['num'], 'sort' => 'num', 'align' => 'center', 'editable' => 'num_new', 'view' => intval($memory['catalog.option']['num'])), array('name' => $row['id'], 'sort' => 'id', 'view' => intval($memory['catalog.option']['id'])), array('name' => $row['uid'], 'sort' => 'uid', 'view' => intval($memory['catalog.option']['uid'])), array('name' => $row['price'], 'sort' => 'price', 'editable' => 'price_new', 'view' => intval($memory['catalog.option']['price'])), array('name' => $row['items'], 'sort' => 'items', 'align' => 'center', 'editable' => 'items_new', 'view' => intval($memory['catalog.option']['item'])), array('name' => $row['items1'], 'sort' => 'items1', 'align' => 'center', 'editable' => 'items1_new', 'view' => intval($memory['catalog.option']['items1'])), array('name' => $row['items2'], 'sort' => 'items2', 'align' => 'center', 'editable' => 'items2_new', 'view' => intval($memory['catalog.option']['items2'])), array('name' => $row['items3'], 'sort' => 'items3', 'align' => 'center', 'editable' => 'items3_new', 'view' => intval($memory['catalog.option']['items3'])), array('action' => array('edit', 'copy', 'url', '|', 'delete', 'id' => $row['id']), 'align' => 'center', 'view' => intval($memory['catalog.option']['menu'])), array('name' => $sort_list . "", 'view' => intval($memory['catalog.option']['sort'])), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл')), 'sort' => 'enabled', 'view' => intval($memory['catalog.option']['status']))
        );
    }

$total = $PHPShopOrm->select(array("COUNT('id') as count"), $where, $order);

if (!empty($_GET['cat']))
    $catname = $PHPShopCategory->getName();
elseif (isset($_GET['where']))
    $catname = __('Поиск');
else
    $catname = __('Новые товары');

$PHPShopInterface->_AJAX["catname"] = PHPShopString::win_utf8($catname);

if (!empty($total['count'])) {
    $PHPShopInterface->_AJAX["recordsFiltered"] = $total['count'];
} else {
    $PHPShopInterface->_AJAX["data"] = array();
    $PHPShopInterface->_AJAX["recordsFiltered"] = 0;
}

$_SESSION['jsort'] = $PHPShopInterface->_AJAX["sort"];
unset($PHPShopInterface->_AJAX["sort"]);

header("Content-Type: application/json");
exit(json_encode($PHPShopInterface->_AJAX));
?>