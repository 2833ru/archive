<?php

$TitlePage = __("Товары");
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('category');
PHPShopObj::loadClass('sort');

/**
 * Вывод товаров
 */
function actionStart() {
    global $PHPShopInterface, $PHPShopSystem, $TitlePage;

    $PHPShopCategoryArray = new PHPShopCategoryArray();
    $PHPShopCategoryArray->order = array('order' => 'num, name');
    $PHPShopCategoryArray->setArray();
    $CategoryArray = $PHPShopCategoryArray->getArray();
    $GLOBALS['count'] = count($CategoryArray);

    if (!empty($CategoryArray[$_GET['cat']]['name']))
        $catname = " / " . $CategoryArray[$_GET['cat']]['name'];
    else
        $catname = " / " . __('Новые товары');

    $PHPShopInterface->action_select['Редактировать выбранные'] = array(
        'name' => 'Редактировать выбранные',
        'action' => 'edit-select',
        'class' => 'disabled'
    );

    $PHPShopInterface->action_select['Настройка'] = array(
        'name' => 'Настройка полей',
        'action' => 'option enabled'
    );


    $PHPShopInterface->action_select['Поиск'] = array(
        'name' => '<span class=\'glyphicon glyphicon-search\'></span> Расширенный поиск',
        'action' => 'search enabled'
    );

    if (isset($_GET['cat']))
        $PHPShopInterface->action_select['Редактировать каталог'] = array(
            'name' => 'Редактировать каталог',
            'action' => 'enabled',
            'url' => '?path=' . $_GET['path'] . '&id=' . intval($_GET['cat'])
        );


    $PHPShopInterface->action_title['copy'] = 'Сделать копию';
    $PHPShopInterface->action_title['url'] = 'Открыть URL';

    $PHPShopInterface->action_button['Добавить товар'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="Добавить товар" data-cat="' . $_GET['cat'] . '"'
    );


    $PHPShopInterface->setActionPanel($TitlePage . $catname, array('Поиск', '|', 'Настройка', 'Редактировать каталог', 'Редактировать выбранные', 'CSV', '|', 'Удалить выбранные'), array('Добавить товар'));

    // Настройка полей
    if (!empty($_COOKIE['check_memory'])) {
        $memory = json_decode($_COOKIE['check_memory'], true);
    }
    if (!is_array($memory['catalog.option'])) {
        $memory['catalog.option']['icon'] = 1;
        $memory['catalog.option']['name'] = 1;
        $memory['catalog.option']['price'] = 1;
        $memory['catalog.option']['item'] = 1;
        $memory['catalog.option']['menu'] = 1;
        $memory['catalog.option']['status'] = 1;
        $memory['catalog.option']['label'] = 1;
        $memory['catalog.option']['uid'] = 0;
        $memory['catalog.option']['id'] = 0;
    }

    $PHPShopInterface->setCaption(
            array(null, "3%"), array("Иконка", "5%", array('sort' => 'none', 'view' => intval($memory['catalog.option']['icon']))), array("Название", "40%", array('view' => intval($memory['catalog.option']['name']))), array("ID", "10%", array('view' => intval($memory['catalog.option']['id']))), array("Артикул", "15%", array('view' => intval($memory['catalog.option']['uid']))), array("Цена", "15%", array('view' => intval($memory['catalog.option']['price']))), array("Кол-во", "10%", array('view' => intval($memory['catalog.option']['item']))), array("", "7%", array('view' => intval($memory['catalog.option']['menu']))), array("Статус" . "", "7%", array('align' => 'right', 'view' => intval($memory['catalog.option']['status'])))
    );

    $PHPShopInterface->addJSFiles('./js/jquery.treegrid.js', './catalog/gui/catalog.gui.js');


    if (isset($_GET['where']['category']) and $_GET['where']['category'] != $_GET['cat'])
        unset($_GET['cat']);

    $where = false;
    if (isset($_GET['cat'])) {

        if (!empty($_GET['cat']) or $_GET['sub'] == 'csv')
            $where = array('category' => '=' . intval($_GET['cat']));

        $limit = array('limit' => 3000);

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
            case(3): $order = array('order' => 'num' . $order_direction . ", items desc");
                break;
            default: $order = array('order' => 'num' . $order_direction . ", items desc");
                break;
        }
    } else {
        $limit = array('limit' => 300);
        $order = array('order' => 'id DESC');
    }

    // Расширенная сортировка
    if (is_array($_GET['order'])) {
        foreach ($_GET['order'] as $k => $v) {
            $order = array('order' => PHPShopSecurity::TotalClean($k) . ' ' . PHPShopSecurity::TotalClean($v));
        }
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
                    $where[PHPShopSecurity::TotalClean($k)] = " LIKE '%" . PHPShopSecurity::TotalClean($v) . "%'";
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
    $PHPShopOrm->mysql_error = false;
    $data = $PHPShopOrm->select(array('*'), $where, $order, $limit);
    if (is_array($data))
        foreach ($data as $row) {

            if (!empty($row['pic_small']))
                $icon = '<img src="' . $row['pic_small'] . '" onerror="imgerror(this)" class="media-object" lowsrc="./images/no_photo.gif">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            $PHPShopInterface->path = 'product';

            // Артикул
            if (!empty($row['uid']) and empty($memory['catalog.option']['uid']))
                $uid = '<div class="text-muted">Арт ' . $row['uid'] . '</div>';
            else
                $uid = null;


            if (!empty($memory['catalog.option']['label']) and (!empty($row['newtip']) or !empty($row['spec']) or !empty($row['sklad']) or isset($row['yml']))) {
                $uid.='<div class="text-muted">';

                // Новинка
                if (!empty($row['newtip']))
                    $uid.= '<a class="label label-info" title="Новинка" href="?path=catalog' . $postfix . '&where[newtip]=1">Н</a> ';

                // Спецпредложение
                if (!empty($row['spec']))
                    $uid.= '<a class="label label-warning" title="Спецпредложение" href="?path=catalog' . $postfix . '&where[spec]=1">С</a> ';

                // Под заказ
                if (!empty($row['sklad']))
                    $uid.= '<a class="label label-danger" title="Под заказ" href="?path=catalog' . $postfix . '&where[sklad]=1">О</a> ';

                // Яндекс Маркет
                if (empty($row['yml']))
                    $uid.= '<a class="label label-danger" title="Нет в Яндекс.Маркете" href="?path=catalog' . $postfix . '&where[yml]=0">Я</a> ';

                $uid.='</div>';
            }

            // Enabled
            if (empty($row['enabled']))
                $enabled = 'text-muted';
            else
                $enabled = null;


            $PHPShopInterface->setRow(
                    $row['id'], array('name' => $icon, 'link' => '?path=product&return=catalog&id=' . $row['id'], 'align' => 'left', 'view' => intval($memory['catalog.option']['icon'])), array('name' => $row['name'], 'link' => '?path=product&return=catalog&id=' . $row['id'], 'align' => 'left', 'addon' => $uid, 'class' => $enabled, 'view' => intval($memory['catalog.option']['name'])), array('name' => $row['id'], 'view' => intval($memory['catalog.option']['id'])), array('name' => $row['uid'], 'view' => intval($memory['catalog.option']['uid'])), array('name' => $row['price'], 'editable' => 'price_new', 'view' => intval($memory['catalog.option']['price'])), array('name' => $row['items'], 'align' => 'center', 'editable' => 'items_new', 'view' => intval($memory['catalog.option']['item'])), array('action' => array('edit', 'copy', 'url', '|', 'delete', 'id' => $row['id']), 'align' => 'center', 'view' => intval($memory['catalog.option']['menu'])), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл')), 'view' => intval($memory['catalog.option']['status']))
            );
        }

    // Левый сайдбар дерева категорий
    $CategoryArray[0]['name'] = 'Корень';
    $tree_array = array();
    foreach ($PHPShopCategoryArray->getKey('parent_to.id', true) as $k => $v) {
        foreach ($v as $cat) {
            $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
        }
        $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
        $tree_array[$k]['id'] = $k;
    }

    $GLOBALS['tree_array'] = &$tree_array;

    $PHPShopInterface->path = 'catalog';

    $tree = '<table class="tree table table-hover">';
    $sub = intval($_GET['sub']);
    $indent = null;
    if (!empty($sub)) {
        $tree.='<tr class="treegrid-active data-tree">
           <td><span class="glyphicon glyphicon-triangle-bottom"></span> <a href="#">' . substr($CategoryArray[$sub]['name'], 0, 27) . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $sub)) . '</span></td>
	</tr>';
        $indent = '<span class="treegrid-sub"></span>';

        $PHPShopCategory = new PHPShopCategory($sub);
        $parent = $PHPShopCategory->getParam('parent_to');

        $sidebarleft_title = '<a href="?path=catalog&sub=' . intval($parent) . '" class="data-row"><span class="glyphicon glyphicon-chevron-left"></span>' . __('Предыдущая') . '</a>';
    }
    else
        $sidebarleft_title = __('Категории');

    $cat_limit = $PHPShopSystem->getSerilizeParam('admoption.adm_cat_limit');
    if (empty($cat_limit))
        $cat_limit = 100;

    if (is_array($tree_array[$sub]['sub']))
        foreach ($tree_array[$sub]['sub'] as $k => $v) {

            // Лимит вывода каталогов
            if ($GLOBALS['count'] > $cat_limit) {
                $check = treegenerator($tree_array[$k], $k);
                $tree_save = 1;
            } else {
                $check = treegenerator_lite($tree_array[$k], $k);
                $tree_save = 1;
            }


            if (empty($check))
                $tree.='<tr class="treegrid-' . $k . ' data-tree">
		<td>' . $indent . '<a href="?path=catalog&cat=' . $k . '&sub=' . $sub . '" title="' . $v . '">' . substr($v, 0, 26) . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
	</tr>';
            else
                $tree.='<tr class="treegrid-' . $k . ' data-tree">
		<td>' . $indent . '<a href="#" class="treegrid-parent" data-parent="treegrid-' . $k . '" title="' . $v . '">' . substr($v, 0, 26) . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
	</tr>';
            $tree.=$check;
        }

    if (empty($sub)) {
        $tree.='
        <tr class="treegrid-1000000">
           <td><a href="#" class="treegrid-parent" data-parent="treegrid-1000000">Неопределенные товары</a></td>
	</tr>
        <tr class="treegrid-1000001 treegrid-parent-1000000 data-row">
           <td><a href="?path=catalog&cat=1000001"><span class="glyphicon glyphicon-download-alt"></span>Загруженные CRM</a></td>
	</tr>
       <tr class="treegrid-1000002 treegrid-parent-1000000 data-row">
           <td><a href="?path=catalog&cat=0&sub=csv"><span class="glyphicon glyphicon-download-alt"></span>Загруженные CSV</a></td>
	</tr>
         <tr class="treegrid-1000004 treegrid-parent-1000000 data-row">
           <td><a href="?path=catalog&cat=1000004"><span class="glyphicon glyphicon-trash"></span>Удаленные</a></td>
    </tr>';
    }
    $tree.='
</table>
    <script>
    var cat="' . intval($_GET['cat']) . '";
    var tree_save=' . $tree_save . ';
    </script>';





    $sidebarleft[] = array('title' => $sidebarleft_title, 'content' => $tree, 'title-icon' => '<span class="glyphicon glyphicon-plus new" data-toggle="tooltip" data-placement="top" title="Добавить каталог"></span>&nbsp;<span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="Развернуть все"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="Свернуть"></span>');
    $PHPShopInterface->setSidebarLeft($sidebarleft, 3);

    $PHPShopInterface->Compile(3);
}

// Построение дерева категорий > 100
function treegenerator($array, $parent) {
    global $PHPShopInterface, $tree_array;
    $tree = $check = false;
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            if (!is_array($tree_array[$k]['sub']))
                $tree.='<tr class="treegrid-' . $k . ' treegrid-parent-' . $parent . ' data-tree" style="display:none">
		<td><a href="?path=catalog&cat=' . $k . '" title="' . $v . '">' . substr($v, 0, 30) . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
	</tr>';
            else {

                $tree.='<tr class="treegrid-' . $k . ' treegrid-parent-' . $parent . ' data-tree" style="display:none">
		<td><span class="glyphicon glyphicon-triangle-right"></span> <a href="?path=catalog&sub=' . $k . '" data-parent="treegrid-' . $k . '" title="' . $v . '">' . substr($v, 0, 30) . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
            </tr>';
            }

            $tree.=$check;
        }
    }
    return $tree;
}

// Построение дерева категорий < 100
function treegenerator_lite($array, $parent) {
    global $PHPShopInterface, $tree_array;
    $tree = $check = false;
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {
            $check = treegenerator_lite($tree_array[$k], $k);

            if (empty($check))
                $tree.='<tr class="treegrid-' . $k . ' treegrid-parent-' . $parent . ' data-tree">
		<td><a href="?path=catalog&cat=' . $k . '">' . $v . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
	</tr>';
            else
                $tree.='<tr class="treegrid-' . $k . ' treegrid-parent-' . $parent . ' data-tree">
		<td><a href="#" class="treegrid-parent" data-parent="treegrid-' . $k . '">' . $v . '</a><span class="pull-right">' . $PHPShopInterface->setDropdownAction(array('edit', 'delete', 'id' => $k)) . '</span></td>
	</tr>';

            $tree.=$check;
        }
    }
    return $tree;
}

?>