<?php

PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("page");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("category");


$TitlePage = __('Редактирование Категории') . ' #' . $_GET['id'];
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);

// Построение дерева категорий
function treegenerator($array, $i, $parent) {
    global $tree_array;
    $del = '¦&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree_select = $check = false;
    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator($tree_array[$k], $i + 1, $k);

            if ($k == $_GET['parent_to'])
                $selected = 'selected';
            else
                $selected = null;

            // Проверка зацикливания
            if ($k == $_GET['id'])
                $disabled = ' disabled ';
            else
                $disabled = null;

            if (empty($check['select'])) {
                $tree_select.='<option value="' . $k . '" ' . $selected . $disabled . '>' . $del . $v . '</option>';
                $i = 1;
            } else {
                $tree_select.='<option value="' . $k . '" ' . $selected . $disabled . '>' . $del . $v . '</option>';
            }

            $tree_select.=$check['select'];
        }
    }
    return array('select' => $tree_select);
}

/**
 * Экшен загрузки форм редактирования
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopSystem, $PHPShopBase;

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./js/jquery.tagsinput.min.js', './js/jquery.treegrid.js', './catalog/gui/catalog.gui.js', './js/bootstrap-treeview.min.js');
    $PHPShopGUI->addCSSFiles('./css/bootstrap-treeview.min.css', './css/jquery.tagsinput.css');

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['id'])));

    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    $PHPShopGUI->action_select['Предпросмотр'] = array(
        'name' => 'Предпросмотр',
        'url' => '../../shop/CID_' . $data['id'] . '.html',
        'action' => 'front' . $GLOBALS['isFrame'],
        'target' => '_blank'
    );


    $PHPShopGUI->action_select['Товары'] = array(
        'name' => 'Товары в каталоге',
        'url' => '?path=' . $_GET['path'] . '&cat=' . intval($_GET['id']),
        'class' => $GLOBALS['isFrame']
    );

    $PHPShopGUI->setActionPanel(__("Каталог") . ': ' . $data['name'] . ' [ID ' . $data['id'] . ']', array('Товары', 'Создать', 'Предпросмотр', '|', 'Удалить'), array('Сохранить', 'Сохранить и закрыть'));

    // Наименование
    $Tab_info = $PHPShopGUI->setField("Название", $PHPShopGUI->setInputText(false, 'name_new', $data['name'], '100%'));

    // Права менеджеров
    if ($PHPShopSystem->ifSerilizeParam('admoption.rule_enabled', 1) and !$PHPShopBase->Rule->CheckedRules('catalog', 'remove')) {
        $where = array('secure_groups' => " REGEXP 'i" . $_SESSION['idPHPSHOP'] . "i' or secure_groups = ''");
        $secure_groups = true;
    }
    else
        $where = $secure_groups = false;

    $PHPShopCategoryArray = new PHPShopCategoryArray($where);
    $CategoryArray = $PHPShopCategoryArray->getArray();
    $GLOBALS['count'] = count($CategoryArray);

    $CategoryArray[0]['name'] = '- ' . __('Корневой уровень') . ' -';
    $tree_array = array();

    foreach ($PHPShopCategoryArray->getKey('parent_to.id', true) as $k => $v) {
        foreach ($v as $cat) {
            $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
        }
        $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
        $tree_array[$k]['id'] = $k;
        if ($k == $data['parent_to'])
            $tree_array[$k]['selected'] = true;
    }

    $GLOBALS['tree_array'] = &$tree_array;
    $_GET['parent_to'] = $data['parent_to'];

    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-live-search="true" data-container=""  data-style="btn btn-default btn-sm" name="parent_to_new" data-width="100%"><option value="0">' . $CategoryArray[0]['name'] . '</option>';
    if ($k == $data['parent_to'])
        $selected = 'selected';
    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $k);


            if ($k == $data['parent_to'])
                $selected = 'selected';
            else
                $selected = null;

            // Проверка зацикливания
            if ($k == $_GET['id'])
                $disabled = ' disabled ';
            else
                $disabled = null;

            $tree_select.='<option value="' . $k . '"  ' . $selected . $disabled . '>' . $v . '</option>';
            $tree_select.=$check['select'];
        }
    $tree_select.='</select>';

    // Выбор каталога
    $Tab_info.= $PHPShopGUI->setField("Размещение", $tree_select);

    // Сетка
    $num_row_area = $PHPShopGUI->setRadio('num_row_new', 1, 1, $data['num_row'], false, false, false, false);
    $num_row_area.=$PHPShopGUI->setRadio('num_row_new', 2, 2, $data['num_row'], false, false, false, false);
    $num_row_area.=$PHPShopGUI->setRadio('num_row_new', 3, 3, $data['num_row'], false, false, false, false);
    $num_row_area.=$PHPShopGUI->setRadio('num_row_new', 4, 4, $data['num_row'], false, false, false, false);
    $Tab_info.=$PHPShopGUI->setField("Товаров в длину", $num_row_area, 'left');

    // вывод списком доступен только для корневых каталогов.
    if ($data['parent_to'] == 0)
        $vid = $PHPShopGUI->setCheckbox('vid_new', 1, 'Выводить подкаталоги списком в основном окне', $data['vid']);
    $vid .= $PHPShopGUI->setCheckbox('skin_enabled_new', 1, 'Скрыть каталог', $data['skin_enabled']);
    $Tab_info.=$PHPShopGUI->setField("Опции вывода", $vid);

    // Товаров на странице
    $Tab_info.=$PHPShopGUI->setLine() . $PHPShopGUI->setField("Товаров на странице", $PHPShopGUI->setInputText(false, 'num_cow_new', $data['num_cow'], '100',  __('шт.')), 'left');

    // Тип сортировки
    $order_by_value[] = array('по имени', 1, $data['order_by']);
    $order_by_value[] = array('по цене', 2, $data['order_by']);
    $order_by_value[] = array('по номеру', 3, $data['order_by']);
    $order_to_value[] = array('возрастанию', 1, $data['order_to']);
    $order_to_value[] = array('убыванию', 2, $data['order_to']);
    $Tab_info.=$PHPShopGUI->setField("Сортировка", $PHPShopGUI->setInputText(null, "num_new", $data['num'], 100, false, 'left') . '&nbsp' .
            $PHPShopGUI->setSelect('order_by_new', $order_by_value, 120) . $PHPShopGUI->setSelect('order_to_new', $order_to_value, 120), 'left');

    // Дополнительные каталоги
    $Tab_info.=$PHPShopGUI->setField('Дополнительные каталоги', $PHPShopGUI->setTextarea('dop_cat_new', $data['dop_cat'], true, false, false, 'Введите ID каталога'), 1, 'Подкаталоги одновременно выводятся в нескольких каталогах.');

    $Tab1 = $PHPShopGUI->setCollapse('Информация', $Tab_info);

    // Иконка
    $Tab_icon.=$PHPShopGUI->setField("Изображение", $PHPShopGUI->setIcon($data['icon'], "icon_new", false));
    $Tab1.= $PHPShopGUI->setCollapse('Иконка', $Tab_icon);

    // Редактор
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $editor = new Editor('content_new');
    $editor->Height = '450';
    $editor->Config['EditorAreaCSS'] = chr(47) . "phpshop" . chr(47) . "templates" . chr(47) . $PHPShopSystem->getValue('skin') . chr(47) . $PHPShopBase->getParam('css.default');
    $editor->ToolbarSet = 'Normal';
    $editor->Value = $data['content'];
    $Tab2 = $editor->AddGUI();

    // Заголовки
    $Tab7 = $PHPShopGUI->loadLib('tab_headers', $data);

    // Права
    $Tab9 = $PHPShopGUI->setCollapse('Каталог могут редактировать', $PHPShopGUI->loadLib('tab_secure', $data), 'in', false);

    // Добавление закладки характеристики если нет подкаталогов
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $subcategory_data = $PHPShopOrm->select(array('id'), array('parent_to' => '=' . intval($data['id'])), false, array('limit' => 1));

    if (!is_array($subcategory_data)) {

        // Кэш фильтра
        if ($PHPShopSystem->getSerilizeParam("admoption.filter_cache_enabled") == 1) {
            $cache = $PHPShopGUI->setCheckbox('reset_cache', 1, __('Очистить кэш характеристик фильтра отбора по параметрам'), false);
            $Tab8 = $PHPShopGUI->setCollapse(__('Кэширование характеристик'), $cache, 'in', false);
        }

        $Tab8.= $PHPShopGUI->setCollapse('Характеристики', $PHPShopGUI->loadLib('tab_sorts', $data), 'in', true);

        $Tab8 .= $PHPShopGUI->setCollapse('Варианты подтипов', tab_parent($data) . $PHPShopGUI->setHelp('Управление вариантами подтипов товаров находится в разделе <a href="?path=sort.parent" title="Перейти">Варианты подтипов</a>'), 'in', true);
    }
    else
        $Tab8 = $PHPShopGUI->setHelp('Характеристики доступны только в подкаталогах с товарами.');



    // Мультибаза
    $Tab9.=$PHPShopGUI->setCollapse('Показывать на витринах', $PHPShopGUI->loadLib('tab_multibase', $data));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Описание", $Tab2), array("Заголовки", $Tab7), array("Характеристики", $Tab8, true), array("Права", $Tab9, true));

    // Прогрессбар
    if ($GLOBALS['count'] > 500)
        $treebar = '<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
    <span class="sr-only">' . __("Загрузка") . '..</span>
  </div>
</div>';

    // Поиск категорий
    $search = '<div class="none" id="category-search" style="padding-bottom:5px;"><div class="input-group input-sm">
                <input type="input" class="form-control input-sm" type="search" id="input-category-search" placeholder="' . __('Искать в категориях...') . '" value="">
                 <span class="input-group-btn">
                  <a class="btn btn-default btn-sm" id="btn-search" type="submit"><span class="glyphicon glyphicon-search"></span></a>
                 </span>
            </div></div>';

    if (empty($GLOBALS['isFrame'])) {

        // Левый сайдбар
        $sidebarleft[] = array('title' => 'Категории', 'content' => $search . '<div id="tree">' . $treebar . '</div>', 'title-icon' => '<span class="glyphicon glyphicon-plus new" data-toggle="tooltip" data-placement="top" title="Добавить каталог"></span>&nbsp;<span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="Развернуть"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="Свернуть"></span>&nbsp;<span class="glyphicon glyphicon-search" id="show-category-search" data-toggle="tooltip" data-placement="top" title="Поиск"></span>');
        $PHPShopGUI->setSidebarLeft($sidebarleft, 3);
        $PHPShopGUI->sidebarLeftCell = 3;
    }


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.catalog.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.catalog.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.catalog.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path'] . '&cat=' . $_POST['rowID']);
}

/**
 * Экшен обновления
 * @return bool 
 */
function actionUpdate() {
    global $PHPShopModules, $PHPShopBase;


    if (empty($_POST['vid_new']))
        $_POST['vid_new'] = 0;

    if (empty($_POST['skin_enabled_new']))
        $_POST['skin_enabled_new'] = 0;

    // Характеристики
    $_POST['sort_new'] = serialize($_POST['sort_new']);


    // Проверка прав редактирования
    if ($PHPShopBase->Rule->CheckedRules('catalog', 'rule')) {

        $secure = null;
        if (is_array($_POST['secure_groups_new']))
            foreach ($_POST['secure_groups_new'] as $crid => $value) {
                $secure.='i' . $crid . 'i';
                if (!empty($_POST['secure_groups_new']['all'])) {
                    $secure = '';
                    break;
                }
            }

        $_POST['secure_groups_new'] = $secure;
    }

    // Мультибаза
    $_POST['servers_new'] = "";
    if (is_array($_POST['servers']))
        foreach ($_POST['servers'] as $v)
            if ($v != 'null' and !strstr($v, ','))
                $_POST['servers_new'].="i" . $v . "i";


    // Доп каталоги
    if (!empty($_POST['dop_cat_new']) and substr($_POST['dop_cat_new'], 1) != '#') {
        $_POST['dop_cat_new'] = '#' . $_POST['dop_cat_new'] . '#';
    }

    $_POST['icon_new'] = iconAdd();

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    $PHPShopOrm->clean();

    return array('success' => $action);
}

// Добавление изображения 
function iconAdd() {

    // Папка сохранения
    $path = '/UserFiles/Image/';

    // Копируем от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg', 'jpeg', 'svg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'])) {
                $file = $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // Читаем файл из URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST['icon_new'];
    }

    // Читаем файл из файлового менеджера
    elseif (!empty($_POST['icon_new'])) {
        $file = $_POST['icon_new'];
    }

    if (empty($file))
        $file = '';

    return $file;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->delete(array('id' => '=' . intval($_POST['rowID'])));

    // Переносим подкатегории с удалённого каталога во временную папку
    $PHPShopOrm->clean();

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $PHPShopOrm->update(array("category" => "1000004", "enabled" => '0'), array("category" => "=" . $_POST['rowID']), false);

    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>