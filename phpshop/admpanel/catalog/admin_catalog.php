<?php

$TitlePage = __("Товары");
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('category');
PHPShopObj::loadClass('sort');
unset($_SESSION['jsort']);

/**
 * Вывод товаров
 */
function actionStart() {
    global $PHPShopInterface, $TitlePage, $PHPShopSystem, $PHPShopBase;

    // Права менеджеров
    if ($PHPShopSystem->ifSerilizeParam('admoption.rule_enabled', 1) and !$PHPShopBase->Rule->CheckedRules('catalog', 'remove')) {
        $where = array('secure_groups' => " REGEXP 'i" . $_SESSION['idPHPSHOP'] . "i' or secure_groups = ''");
        $secure_groups = true;
    }
    else
        $where = $secure_groups = false;
    
    $where['id']='='.intval($_GET['cat']);
    
    $PHPShopCategoryArray = new PHPShopCategoryArray($where);
    $PHPShopCategoryArray->order = array('order' => 'num, name');
    $CategoryArray = $PHPShopCategoryArray->getArray();
    
    if (!empty($CategoryArray[$_GET['cat']]['name']))
        $catname = '  &rarr;  <span id="catname">' . $CategoryArray[$_GET['cat']]['name'].'</span>';
    elseif (!empty($CategoryArray[$_GET['sub']]['name']))
        $catname = '  &rarr;  <span id="catname">' . $CategoryArray[$_GET['sub']]['name'].'</span>';
    else
        $catname = '  &rarr;  <span id="catname">' . __('Новые товары').'</span>';

    // Права менеджеров
    if ($secure_groups and isset($_GET['cat']) and empty($CategoryArray[$_GET['cat']]['name'])) {
        $catname = " /  <span class='text-danger'><span class='glyphicon glyphicon-lock'></span> " . __('Доступ закрыт') . '</span>';
        $_GET['where']['disabled'] = true;
    }


    if (!empty($_GET['cat']))
        $PHPShopInterface->action_select['Предпросмотр'] = array(
            'name' => 'Предпросмотр',
            'url' => '../../shop/CID_' . $_GET['cat'] . '.html',
            'action' => 'front enabled',
            'target' => '_blank'
        );

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
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="' . __('Добавить товар') . '" data-cat="' . $_GET['cat'] . '"'
    );

    $PHPShopInterface->setActionPanel($TitlePage . $catname, array('Поиск', '|', 'Предпросмотр', 'Настройка', 'Редактировать каталог', 'Редактировать выбранные', 'CSV', '|', 'Удалить выбранные'), array('Добавить товар'));

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
        $memory['catalog.option']['num'] = 0;
        $memory['catalog.option']['sort'] = 0;
    }

    $PHPShopInterface->setCaption(
            array(null, "3%"), array("Иконка", "5%", array('sort' => 'none', 'view' => intval($memory['catalog.option']['icon']))), array("Название", "40%", array('view' => intval($memory['catalog.option']['name']))), array("№", "10%", array('view' => intval($memory['catalog.option']['num']))), array("ID", "10%", array('view' => intval($memory['catalog.option']['id']))), array("Артикул", "15%", array('view' => intval($memory['catalog.option']['uid']))), array("Цена", "15%", array('view' => intval($memory['catalog.option']['price']))), array("Кол-во", "10%", array('view' => intval($memory['catalog.option']['item']))), array("", "7%", array('view' => intval($memory['catalog.option']['menu']))), array("Характеристики", "30%", array('view' => intval($memory['catalog.option']['sort']))), array("Статус", "7%", array('align' => 'right', 'view' => intval($memory['catalog.option']['status'])))
    );

    $PHPShopInterface->addJSFiles('./catalog/gui/catalog.gui.js', './js/bootstrap-treeview.min.js');
    $PHPShopInterface->addCSSFiles('./css/bootstrap-treeview.min.css');
    $PHPShopInterface->path = 'catalog';

    // Прогрессбар
    $treebar = '<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
    <span class="sr-only">' . __('Загрузка') . '..</span>
  </div>
</div>';

    // Поиск категорий
    $search = '<div class="none" id="category-search" style="padding-bottom:5px;"><div class="input-group input-sm">
                <input type="input" class="form-control input-sm" type="search" id="input-category-search" placeholder="' . __('Искать в категориях...') . '" value="">
                 <span class="input-group-btn">
                  <a class="btn btn-default btn-sm" id="btn-search" type="submit"><span class="glyphicon glyphicon-search"></span></a>
                 </span>
            </div></div>';

    $sidebarleft[] = array('title' => __('Категории'), 'content' => $search . '<div id="tree">' . $treebar . '</div>', 'title-icon' => '<div class="hidden-xs"><span class="glyphicon glyphicon-plus new" data-toggle="tooltip" data-placement="top" title="' . __('Добавить каталог') . '"></span>&nbsp;<span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="' . __('Развернуть все') . '"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="' . __('Свернуть') . '"></span>&nbsp;<span class="glyphicon glyphicon-search" id="show-category-search" data-toggle="tooltip" data-placement="top" title="' . __('Поиск') . '"></span></div>');

    $PHPShopInterface->setSidebarLeft($sidebarleft, 3);

    $PHPShopInterface->Compile(3);
}

// Обработка событий
$PHPShopGUI->getAction();
?>