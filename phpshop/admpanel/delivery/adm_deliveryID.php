<?php

PHPShopObj::loadClass('delivery');


$TitlePage = __('Редактирование Доставки #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);

// Построение дерева категорий
function treegenerator($array, $i, $parent) {
    global $tree_array;
    $del = '¦&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree = $tree_select = $check = false;

    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {
            $del = str_repeat($del, $i);
            $check = treegenerator($tree_array[$k], $i + 1, $k);


            if ($k == $_GET['parent_to'])
                $selected = 'selected';
            else
                $selected = null;

            if (empty($check['select'])) {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                $i = 1;
            } else {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                //$i++;
            }


            $tree.='<tr class="treegrid-' . $k . ' treegrid-parent-' . $parent . ' data-tree">
		<td><a href="?path=delivery&cat=' . $k . '">' . $v . '</a></td>
                    </tr>';

            $tree_select.=$check['select'];
            $tree.=$check['tree'];
        }
    }
    return array('select' => $tree_select, 'tree' => $tree);
}

/**
 * Экшен загрузки форм редактирования
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopSystem;

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./js/jquery.treegrid.js','./delivery/gui/delivery.gui.js');

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['id'])));

    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    if (!empty($data['is_folder']))
        $catalog = true;
    else
        $catalog = false;

    $PHPShopGUI->setActionPanel(__("Доставка") . ' / ' . $data['city'], array('Создать','|','Удалить', ), array('Сохранить', 'Сохранить и закрыть'));

    // Наименование
    $Tab_info = $PHPShopGUI->setField(__("Название:"), $PHPShopGUI->setInputText(false, 'city_new', $data['city'], '100%'));


    $PHPShopCategoryArray = new PHPShopDeliveryArray(array('is_folder' => "='1'"));
    $CategoryArray = $PHPShopCategoryArray->getArray();

    $CategoryArray[0]['city'] = '- Корневой уровень -';
    $tree_array = array();

    foreach ($PHPShopCategoryArray->getKey('PID.id', true) as $k => $v) {
        foreach ($v as $cat) {
            $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['city'];
        }
        $tree_array[$k]['name'] = $CategoryArray[$k]['city'];
        $tree_array[$k]['id'] = $k;
        if ($k == $data['PID'])
            $tree_array[$k]['selected'] = true;
    }



    $GLOBALS['tree_array'] = &$tree_array;
    $_GET['parent_to'] = $data['PID'];

    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-container=".sidebarcontainer"  data-style="btn btn-default btn-sm" name="PID_new"><option value="0">' . $CategoryArray[0]['city'] . '</option>';
    $tree = '<table class="tree table table-hover">';
    if ($k == $data['PID'])
        $selected = 'selected';
    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $k);

            $tree.='<tr class="treegrid-' . $k . ' data-tree">
		<td><a href="?path=delivery&cat=' . $k . '">' . $v . '</a></td>
                    </tr>';

            if ($k == $data['PID'])
                $selected = 'selected';
            else
                $selected = null;

            $tree_select.='<option value="' . $k . '"  ' . $selected . '>' . $v . '</option>';

            $tree_select.=$check['select'];
            $tree.=$check['tree'];
        }
    $tree_select.='</select>';
    $tree.='</table>';


    // Выбор каталога
    $Tab_info.= $PHPShopGUI->setField(__("Размещение:"), $tree_select);

    // Вывод
    $Tab_info.=$PHPShopGUI->setField(__("Вывод:"), $PHPShopGUI->setCheckbox('enabled_new', 1, "Активный статус", $data['enabled']) . $PHPShopGUI->setCheckbox('flag_new', 1, "Доставка по умолчанию", $data['flag']));

    // Цены
    $Tab_price = $PHPShopGUI->setField(__("Стоимость:"), $PHPShopGUI->setInputText(false, 'price_new', $data['price'], '150', $PHPShopSystem->getDefaultValutaCode()));

    $Tab_price.=$PHPShopGUI->setField(__("Бесплатная доставка свыше:"), $PHPShopGUI->setInputText(false, 'price_null_new', $data['price_null'], '150', $PHPShopSystem->getDefaultValutaCode()) . $PHPShopGUI->setCheckbox('price_null_enabled_new', 1, "Учитывать", $data['price_null_enabled']));

    // Такса
    $Tab_price.=$PHPShopGUI->setField(__("Такса за каждые 0.5 кг веса"), $PHPShopGUI->setInputText(false, 'taxa_new', $data['taxa'], '150', $PHPShopSystem->getDefaultValutaCode()) . $PHPShopGUI->setHelp('Используется для задания дополнительной тарификации (например, для "Почта России").<br>Каждые дополнительные 0.5 кг свыше базовых 0.5 кг будут стоить указанную сумму.'));


    // Тип сортировки
    $Tab_info.=$PHPShopGUI->setField(__("Приоритет:"), $PHPShopGUI->setInputText('№', "num_new", $data['num'], 150));

    // Настройка выбора городов из БД
    $city_select_value[] = array('Не использовать', 0, $data['city_select']);
    $city_select_value[] = array('Только Регионы и города РФ', 1, $data['city_select']);
    $city_select_value[] = array('Все страны мира', 2, $data['city_select']);

    if (!$catalog)
        $Tab_info.=$PHPShopGUI->setField(__("Помощь подбора стран, регионов и городов:"), $PHPShopGUI->setSelect('city_select_new', $city_select_value));

    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), $Tab_info);

    // Иконка
    $Tab1.=$PHPShopGUI->setField(__("Изображение"), $PHPShopGUI->setIcon($data['icon'], "icon_new", false));

    // Цены
    if (!$catalog)
        $Tab1.= $PHPShopGUI->setCollapse(__('Цены'), $Tab_price);

    // Дополнительные поля
    if (!$catalog)
        $Tab2 = $PHPShopGUI->loadLib('tab_option', $data);


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    if (!$catalog)
        $PHPShopGUI->setTab(array(__("Основное"), $Tab1), array(__("Адреса пользователя"), $Tab2));
    else
        $PHPShopGUI->setTab(array(__("Основное"), $Tab1));


    // Левый сайдбар
    $sidebarleft[] = array('title' => 'Категории', 'content' => $tree, 'title-icon' => '<span class="glyphicon glyphicon-plus newcat" data-toggle="tooltip" data-placement="top" title="Добавить каталог"></span>&nbsp;<span class="glyphicon glyphicon-chevron-down" data-toggle="tooltip" data-placement="top" title="Развернуть"></span>&nbsp;<span class="glyphicon glyphicon-chevron-up" data-toggle="tooltip" data-placement="top" title="Свернуть"></span>');
    
        $help = '<p class="text-muted">У каждого типа доставки можно настроить обязательные и дополнительные поля для заполнения заказа в закладке управления доставкой <kbd>Адреса пользователя</kbd></p>';

    $sidebarleft[] = array('title' => 'Подсказка', 'content' => $help);
    
    $PHPShopGUI->setSidebarLeft($sidebarleft, 3);
    $PHPShopGUI->sidebarLeftCell = 3;



    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.delivery.delete") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.delivery.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.delivery.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    $result = actionUpdate();
    
      if (isset($_REQUEST['ajax'])) {
      exit(json_encode(array("success" => $result)));
      }
      else header('Location: ?path=' . $_GET['path'].'&cat='.$_POST['PID_new']); 
}

/**
 * Экшен обновления
 * @return bool
 */
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;


    if (is_array($_POST['data_fields'])){
        
        if(is_array($_POST[data_fields][enabled]))
        foreach($_POST[data_fields][enabled] as $k=>$v){
            $_POST[data_fields][enabled][$k] = array_map("urldecode", $v);
        }
        
        
        $_POST['data_fields_new'] = serialize($_POST['data_fields']);
    }

    // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('flag_new', 'enabled_new', 'price_null_enabled_new');

    $_POST['icon_new'] = iconAdd('icon_new');

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $PHPShopOrm->debug = false;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));

    return array("success" => $action);
}

// Добавление изображения 
function iconAdd($name = 'icon_new') {

    // Папка сохранения
    $path = '/UserFiles/Image/';

    // Копируем от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'])) {
                $file = $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // Читаем файл из URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST[$name];
    }

    // Читаем файл из файлового менеджера
    elseif (!empty($_POST[$name])) {
        $file = $_POST[$name];
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


    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>