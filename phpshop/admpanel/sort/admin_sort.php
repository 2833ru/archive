<?php

PHPShopObj::loadClass('sort');

$TitlePage = __("Характеристики");

$PHPShopSortCategoryArray = new PHPShopSortCategoryArray(array('category' => '=0'));
$SortCategoryArray = $PHPShopSortCategoryArray->getArray();

/**
 * Вывод товаров
 */
function actionStart() {
    global $PHPShopInterface, $TitlePage, $SortCategoryArray, $help;

    $PHPShopInterface->action_button['Добавить характеристику'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="Добавить характеристику" data-cat="' . $_GET['cat'] . '"'
    );

    $PHPShopInterface->action_select['Добавить группу'] = array(
        'name' => 'Добавить группу',
        'action' => 'enabled',
        'url' => '?path=' . $_GET['path'] . '&action=new&type=sub'
    );


    if (isset($_GET['cat']))
        $PHPShopInterface->action_select['Редактировать группу'] = array(
            'name' => 'Редактировать группу',
            'action' => 'enabled',
            'url' => '?path=' . $_GET['path'] . '&type=sub&id=' . intval($_GET['cat'])
        );

    if (!empty($_GET['cat']))
        $TitlePage.=': ' . $SortCategoryArray[$_GET['cat']]['name'];

    $PHPShopInterface->setActionPanel($TitlePage, array('Редактировать группу','Добавить группу', '|', 'Удалить выбранные'), array('Добавить характеристику'));
    $PHPShopInterface->setCaption(array(null, "1%"), array("Название", "50%"), array("", "10%"), array("Бренд" . "", "10%", array('align' => 'center')), array("Опция" . "", "10%", array('align' => 'center')), array("Фильтр" . "", "10%", array('align' => 'center')));

    $where = array('category' => '!=0');
    if (!empty($_GET['cat'])) {
        $where = array('category' => '=' . intval($_GET['cat']));
    }

    $PHPShopInterface->addJSFiles('./js/jquery.treegrid.js','./sort/gui/sort.gui.js');

    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
    //$PHPShopOrm->Option['where'] = ' or ';
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            // Фильтр
            if (!empty($row['filtr']))
                $filtr = '<span class="glyphicon glyphicon-ok"><span class="hide">1</span></span>';
            else
                $filtr = '<span class="hide">0</span>';

            // Опция
            if (!empty($row['goodoption']))
                $goodoption = '<span class="glyphicon glyphicon-ok"><span class="hide">1</span></span>';
            else
                $goodoption = '<span class="hide">0</span>';

            // Бренд
            if (!empty($row['brand']))
                $brand = '<span class="glyphicon glyphicon-ok"><span class="hide">1</span></span>';
            else
                $brand = '<span class="hide">0</span>';

            // Описание
            if (!empty($row['description']))
                $row['name'].=$PHPShopInterface->setHelpIcon($row['description']);


            $PHPShopInterface->path = 'sort';
            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=sort&id=' . $row['id'], 'align' => 'left'), array('action' => array('edit', '|', 'delete', 'id' => $row['id']), 'align' => 'center'), array('name' => $brand, 'align' => 'center'), array('name' => $goodoption, 'align' => 'center'), array('name' => $filtr, 'align' => 'center')
            );
        }



    $sidebarleft[] = array('title' => 'Группы', 'content' => $PHPShopInterface->loadLib('tab_menu_sort', false, './sort/'), 'title-icon' => '<span class="glyphicon glyphicon-plus newsub" data-toggle="tooltip" data-placement="top" title="Добавить группу"></span>');
    $sidebarleft[] = array('title' => 'Подсказка', 'content' => $help, 'class' => 'hidden-xs');
    $PHPShopInterface->setSidebarLeft($sidebarleft, 3);

    $PHPShopInterface->Compile(3);
}

?>