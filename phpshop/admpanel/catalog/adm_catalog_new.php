<?php

PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("page");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("category");


$TitlePage = __('Новый каталог');
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

            if ($k == $_GET['id'])
                $selected = 'selected';
            else
                $selected = null;

            if (empty($check['select'])) {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                $i = 1;
            } else {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
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

    // Начальные данные
    $data = array();
    //$data['name'] = __('Новый каталог');
    // ко-лво товара на странице каталога.. Ставим 0 для активации алгоритма автоматического расчёта сетки.
    $data['num_cow'] = 0;
    //$data['num_cow'] = $PHPShopSystem->getParam('num_row');
    $data['num_row'] = 3;
    $data['num'] = 1;

    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    $PHPShopGUI->action_select['Урок'] = array(
        'name' => 'Обучение',
        'action' => 'presentation',
        'icon' => 'glyphicon glyphicon-education'
    );

    $PHPShopGUI->setActionPanel(__("Новый каталог"), array('Урок'), array('Создать и редактировать'));

    // Наименование
    $Tab_info = $PHPShopGUI->setField(__("Название:"), $PHPShopGUI->setInputArg(array('name' => 'name_new', 'type' => 'text.requared', 'value' => $data['name'])));

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

    $CategoryArray[0]['name'] = '- Корневой уровень -';
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

            $tree_select.='<option value="' . $k . '"  ' . $selected . '>' . $v . '</option>';
            $tree_select.=$check['select'];
        }
    $tree_select.='</select>';

    // Выбор каталога
    $Tab_info.= $PHPShopGUI->setField(__("Размещение:"), $tree_select);

    // Сетка
    $num_row_area = $PHPShopGUI->setRadio('num_row_new', 1, 1, $data['num_row']);
    $num_row_area.=$PHPShopGUI->setRadio('num_row_new', 2, 2, $data['num_row']);
    $num_row_area.=$PHPShopGUI->setRadio('num_row_new', 3, 3, $data['num_row']);
    $num_row_area.=$PHPShopGUI->setRadio('num_row_new', 4, 4, $data['num_row']);
    $Tab_info.=$PHPShopGUI->setField(__("Товаров в длину:"), $num_row_area, 'left');

    // Вывод
    // вывод списком доступен только для корневых каталогов.
    if ($data['parent_to'] == 0)
        $vid = $PHPShopGUI->setCheckbox('vid_new', 1, __('Выводить подкаталоги списком в основном окне'), $data['vid']);
    $vid .= $PHPShopGUI->setCheckbox('skin_enabled_new', 1, __('Скрыть каталог'), $data['skin_enabled']);
    $Tab_info.=$PHPShopGUI->setField(__("Опции вывода:"), $vid);

    // Товаров на странице
    $Tab_info.=$PHPShopGUI->setLine() . $PHPShopGUI->setField(__("Товаров на странице:"), $PHPShopGUI->setInputText(false, 'num_cow_new', $data['num_cow'], '100', __('шт.')), 'left');

    // Тип сортировки
    $order_by_value[] = array('по имени', 1, $data['order_by']);
    $order_by_value[] = array('по цене', 2, $data['order_by']);
    $order_by_value[] = array('по номеру', 3, $data['order_by']);
    $order_to_value[] = array('возрастанию', 1, $data['order_to']);
    $order_to_value[] = array('убыванию', 2, $data['order_to']);
    $Tab_info.=$PHPShopGUI->setField(__("Сортировка:"), $PHPShopGUI->setInputText(null, "num_new", $data['num'], 100, false, 'left') . '&nbsp' .
            $PHPShopGUI->setSelect('order_by_new', $order_by_value, 120) . $PHPShopGUI->setSelect('order_to_new', $order_to_value, 120), 'left');

    // Дополнительные каталоги
    $Tab_info.=$PHPShopGUI->setField('Дополнительные каталоги:', $PHPShopGUI->setTextarea('dop_cat_new', $data['dop_cat'], false, false, false, __('Введите ID каталога')), 1, 'Подкаталоги одновременно выводятся в нескольких каталогах.');

    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), $Tab_info);

    // Иконка
    $Tab_icon.=$PHPShopGUI->setField(__("Изображение"), $PHPShopGUI->setIcon($data['icon'], "icon_new", false));


    $Tab1.= $PHPShopGUI->setCollapse(__('Иконка'), $Tab_icon);

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

    // Безопасноть
    $Tab8 = $PHPShopGUI->setCollapse(__('Редактирование'), $PHPShopGUI->loadLib('tab_secure', $data));

    //Мультибаза
    $Tab8.=$PHPShopGUI->setCollapse(__('Мультибаза'), $PHPShopGUI->loadLib('tab_multibase', $data));

    // Добавление закладки характеристики если нет подкаталогов
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $subcategory_data = $PHPShopOrm->select(array('id'), array('parent_to' => '=' . intval($data['id'])), false, array('limit' => 2));
    if (!is_array($subcategory_data))
        $Tab9 = $PHPShopGUI->setCollapse(__('Характеристики'), $PHPShopGUI->loadLib('tab_sorts', $data));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Основное"), $Tab1), array(__("Описание"), $Tab2), array(__("Заголовки"), $Tab7), array(__("Характеристики"), $Tab9), array(__("Права"), $Tab8));

    // Прогрессбар
    if ($GLOBALS['count'] > 500)
        $treebar = '<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 45%">
    <span class="sr-only">Загрузка..</span>
  </div>
</div>';

    // Поиск категорий
    $search = '<div class="none" id="category-search" style="padding-bottom:5px;"><div class="input-group input-sm">
                <input type="input" class="form-control input-sm" type="search" id="input-category-search" placeholder="' . __('Искать в категориях...') . '" value="">
                 <span class="input-group-btn">
                  <a class="btn btn-default btn-sm" id="btn-search" type="submit"><span class="glyphicon glyphicon-search"></span></a>
                 </span>
            </div></div>';

    // Левый сайдбар
    $sidebarleft[] = array('title' => 'Категории', 'content' => $search . '<div id="tree">' . $treebar . '</div>', 'title-icon' => '<span class="glyphicon glyphicon-plus new" data-toggle="tooltip" data-placement="top" title="Добавить каталог"></span>&nbsp;<span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="Развернуть"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="Свернуть"></span>&nbsp;<span class="glyphicon glyphicon-search" id="show-category-search" data-toggle="tooltip" data-placement="top" title="Поиск"></span>');

    $PHPShopGUI->setSidebarLeft($sidebarleft, 3);
    $PHPShopGUI->sidebarLeftCell = 3;



    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.catalog.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен обновления
 * @return bool 
 */
function actionInsert() {
    global $PHPShopModules, $PHPShopOrm, $PHPShopBase;

    if (empty($_POST['vid_new']))
        $_POST['vid_new'] = 0;

    if (empty($_POST['yml_new']))
        $_POST['yml_new'] = 0;

    // Характеристики
    $_POST['sort_new'] = serialize($_POST['sort_new']);

    // Мультибаза
    $_POST['servers_new'] = "";
    if (is_array($_POST['servers']))
        foreach ($_POST['servers'] as $v)
            if ($v != 'null' and !strstr($v, ','))
                $_POST['servers_new'].="i" . $v . "i";

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

    $_POST['icon_new'] = iconAdd();

    // Доп каталоги
    if (!empty($_POST['dop_cat_new']) and substr($_POST['dop_cat_new'], 1) != '#') {
        $_POST['dop_cat_new'] = '#' . $_POST['dop_cat_new'] . '#';
    }

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    header('Location: ?path=' . $_GET['path'] . '&id=' . $action);
    return $action;
}

// Добавление изображения 
function iconAdd() {

    // Папка сохранения
    $path = '/UserFiles/Image/';

    // Копируем от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg', 'jpeg','svg'))) {
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


    if (!empty($file)) {
        return $file;
    }
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>