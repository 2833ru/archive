<?php

PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("page");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("category");


$TitlePage = __('Редактирование Товара #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

// Построение дерева категорий
function treegenerator($array, $i, $curent) {
    global $tree_array;
    $del = '¦&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree_select = $check = false;

    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator($tree_array[$k], $i + 1, $curent);

            if ($k == $curent)
                $selected = 'selected';
            else
                $selected = null;

            if (empty($check['select'])) {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                $i = 1;
            } else {
                $tree_select.='<option value="' . $k . '" ' . $selected . ' disabled>' . $del . $v . '</option>';
                //$i++;
            }

            $tree_select.=$check['select'];
        }
    }
    return array('select' => $tree_select);
}

function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopBase, $PHPShopSystem, $CategoryArray;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    // Редирект на родителя подтипа
    if ($data['parent_enabled'] == 1 and empty($_GET['view'])) {
        $data_parent = $PHPShopOrm->select(array('id'), array('parent' => "='" . $data['id'] . "' or parent LIKE '," . $data['id'] . "' or  parent LIKE '" . $data['id'] . ",'",'parent_enabled'=>"='0'"),false,array('limit'=>1));
        if (!empty($data_parent['id']))
            header('Location: ?path=' . $_GET['path'] . '&id=' . $data_parent['id'] . '&tab=6&return=' . $_GET['return']);
    }

    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['return']);
    }

    // Имя товара
    if (strlen($data['name']) > 47)
        $title_name = substr($data['name'], 0, 47) . '...';
    else
        $title_name = $data['name'];

    $PHPShopGUI->action_select['Предпросмотр'] = array(
        'name' => 'Предпросмотр',
        'url' => '../../shop/UID_' . $data['id'] . '.html',
        'action' => 'front',
        'target' => '_blank',
        'class' => $GLOBALS['isFrame']
    );

    $PHPShopGUI->setActionPanel(__("Товар") . ": " . $title_name . ' [ID ' . $data['id'] . ']', array('Сделать копию', 'Предпросмотр', '|', 'Удалить'), array('Сохранить', 'Сохранить и закрыть'));

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./js/jquery.tagsinput.min.js', './catalog/gui/catalog.gui.js', './js/jquery.waypoints.min.js', './product/gui/product.gui.js', './js/bootstrap-colorpicker.min.js');
    $PHPShopGUI->addCSSFiles('./css/jquery.tagsinput.css', './css/bootstrap-colorpicker.min.css');

    // Права менеджеров
    if ($PHPShopSystem->ifSerilizeParam('admoption.rule_enabled', 1) and !$PHPShopBase->Rule->CheckedRules('catalog', 'remove')) {
        $where = array('secure_groups' => " REGEXP 'i" . $_SESSION['idPHPSHOP'] . "i' or secure_groups = ''");
        $secure_groups = true;
    }
    else
        $where = $secure_groups = false;

    $PHPShopCategoryArray = new PHPShopCategoryArray($where);
    $CategoryArray = $PHPShopCategoryArray->getArray();

    $CategoryArray[0]['name'] = '- Выбрать каталог -';
    $tree_array = array();

    foreach ($PHPShopCategoryArray->getKey('parent_to.id', true) as $k => $v) {
        foreach ($v as $cat) {
            $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
        }
        $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
        $tree_array[$k]['id'] = $k;
    }

    $tree_array[0]['sub'][1000000] = 'Неопределенные товары';
    $tree_array[1000000]['name'] = 'Неопределенные товары';
    $tree_array[1000000]['id'] = 1000000;
    $tree_array[1000000]['sub'][1000001] = 'Загруженные 1C';
    $tree_array[1000000]['sub'][1000002] = 'Загруженные CSV';
    $tree_array[1000002]['id'] = 0;

    $GLOBALS['tree_array'] = &$tree_array;

    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-live-search="true" data-container=""  data-style="btn btn-default btn-sm" name="category_new"  data-width="100%"><option value="0">' . $CategoryArray[0]['name'] . '</option>';

    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $data['category']);

            if ($k == $data['category'])
                $selected = 'selected';
            else
                $selected = null;

            if (empty($tree_array[$k]))
                $disabled = null;
            else
                $disabled = ' disabled';

            $tree_select.='<option value="' . $k . '" ' . $selected . $disabled . '>' . $v . '</option>';

            $tree_select.=$check['select'];
        }
    $tree_select.='</select>';

    // Выбор каталога
    $Tab_info = $PHPShopGUI->setField(__("Размещение:"), $tree_select, 1, 'Вывод в каталоге ID ' . $data['category'] . '. Изменено ' . PHPShopDate::get($data['datas'], true));

    // Наименование
    $Tab_info.=$PHPShopGUI->setField("Название:", $PHPShopGUI->setInputText(null, 'name_new', $data['name']));

    // Артикул
    $Tab_info.=$PHPShopGUI->setField('Артикул:', $PHPShopGUI->setInputText(null, 'uid_new', $data['uid'], 250));

    // Иконка
    $PHPShopOrmImg = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
    $data_pic = $PHPShopOrmImg->select(array('*'), array('parent' => '=' . intval($data['id'])), array('order' => 'num,id'), array('limit' => 1));
    if (is_array($data_pic)) {
        $icon_server = true;
        $icon_title = false;
    } else {
        $icon_server = false;
        $icon_title = 'Главное изображение товара создается автоматически при загрузке через закладку Изображение. Но вы можете загрузить главное фото отдельно здесь.';
    }

    $Tab_info.=$PHPShopGUI->setField(__("Изображение"), $PHPShopGUI->setIcon($data['pic_big'], "pic_big_new", true, array('load' => false, 'server' => true, 'url' => false, 'view' => $icon_server)), 1, $icon_title);

    if (empty($icon_server))
        $Tab_info.=$PHPShopGUI->setField(__("Превью"), $PHPShopGUI->setFile($data['pic_small'], "pic_small_new", array('load' => false, 'server' => 'image', 'url' => false, 'view' => $icon_server)), 1, 'Превью изображения товара создается автоматически при загрузке через закладку Изображение. Но вы можете загрузить превью отдельно здесь.');
    else
        $Tab_info.=$PHPShopGUI->setFile($data['pic_small'], "pic_small_new", array('load' => false, 'server' => 'image', 'url' => false, 'view' => $icon_server));

    // Склад
    if (empty($data['ed_izm']))
        $ed_izm = 'шт.';
    else
        $ed_izm = $data['ed_izm'];

    $Tab_info.=$PHPShopGUI->setField('Склад:', $PHPShopGUI->setInputText(false, 'items_new', $data['items'], 100, $ed_izm), 'left');

    // Вес
    $Tab_info.=$PHPShopGUI->setField('Вес:', $PHPShopGUI->setInputText(false, 'weight_new', $data['weight'], 100, 'гр.'), 'left');

    // Единица измерения
    if (empty($data['ed_izm']))
        $data['ed_izm'] = 'шт.';
    $Tab_info.=$PHPShopGUI->setField('Единица измерения:', $PHPShopGUI->setInputText(false, 'ed_izm_new', $data['ed_izm'], 100));

    // Рекомендуемые товары
    $Tab_info.=$PHPShopGUI->setField('Рекомендуемые товары для совместной продажи:', $PHPShopGUI->setTextarea('odnotip_new', $data['odnotip'], false, false, false, __('Укажите ID товаров или воспользуйтесь <a href="#" data-target="#odnotip_new"  class="btn btn-sm btn-default tag-search"><span class="glyphicon glyphicon-search"></span> поиском товаров</a>')));

    // Дополнительные каталоги
    $Tab_info.=$PHPShopGUI->setField('Дополнительные каталоги:', $PHPShopGUI->setTextarea('dop_cat_new', $data['dop_cat'], false, false, false, __('Введите ID каталога')), 1, 'Товары одновременно выводятся в нескольких каталогах.');

    // Опции вывода
    $Tab_info.=$PHPShopGUI->setField('Опции вывода:', $PHPShopGUI->setCheckbox('enabled_new', 1, 'Вывод в каталоге', $data['enabled']) .
            $PHPShopGUI->setCheckbox('spec_new', 1, 'Спецпредложение', $data['spec']) . $PHPShopGUI->setCheckbox('newtip_new', 1, 'Новинка', $data['newtip']));
    $Tab_info.=$PHPShopGUI->setField('Сортировка:', $PHPShopGUI->setInputText('№', 'num_new', $data['num'], 150));

    if ($_GET['view'] == 'option')
        $Tab_info.=$PHPShopGUI->setField('Связи', $PHPShopGUI->setRadio('parent_enabled_new', 0, __('Обычный товар'), $data['parent_enabled']) . $PHPShopGUI->setRadio('parent_enabled_new', 1, __('Подтип товара'), $data['parent_enabled']));

    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), $Tab_info);

    // Валюты
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    $valuta_area = null;
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $check = 'checked';
                $valuta_def_name = $val['code'];
            }
            else
                $check = false;
            $valuta_area.=$PHPShopGUI->setRadio('baseinputvaluta_new', $val['id'], $val['name'], $check, false, false, array('code' => $val['code']));
        }

    // Цены
    $Tab_price.=$PHPShopGUI->setField('Цена 1:', $PHPShopGUI->setInputText(null, 'price_new', $data['price'], 150, $valuta_def_name));
    $Tab_price.=$PHPShopGUI->setField('Цена 2:', $PHPShopGUI->setInputText(null, 'price2_new', $data['price2'], 150, $valuta_def_name));
    $Tab_price.=$PHPShopGUI->setField('Цена 3:', $PHPShopGUI->setInputText(null, 'price3_new', $data['price3'], 150, $valuta_def_name));
    $Tab_price.=$PHPShopGUI->setField('Цена 4:', $PHPShopGUI->setInputText(null, 'price4_new', $data['price4'], 150, $valuta_def_name));
    $Tab_price.=$PHPShopGUI->setField('Цена 5:', $PHPShopGUI->setInputText(null, 'price5_new', $data['price5'], 150, $valuta_def_name));
    $Tab_price.=$PHPShopGUI->setField(__('Старая цена'), $PHPShopGUI->setInputText(null, 'price_n_new', $data['price_n'], 150, $valuta_def_name));
    $Tab_price.=$PHPShopGUI->setField('Под заказ:', $PHPShopGUI->setCheckbox('sklad_new', 1, 'Под заказ', $data['sklad']));

    // Валюта
    $Tab_price.=$PHPShopGUI->setField(__('Валюта:'), $valuta_area);

    $Tab1.=$PHPShopGUI->setCollapse(__('Цены'), $Tab_price, 'in', true, true, array('type' => 'price'));

    // YML
    $data['yml_bid_array'] = unserialize($data['yml_bid_array']);
    $Tab_yml = $PHPShopGUI->setField('<a href="/yml/" target="_blank" title="Открыть файл">YML</a>', $PHPShopGUI->setCheckbox('yml_new', 1, __('Вывод в Яндекс Маркете'), $data['yml']) . '<br>' .
            $PHPShopGUI->setRadio('p_enabled_new', 1, __('В наличии'), $data['p_enabled']) .
            $PHPShopGUI->setRadio('p_enabled_new', 0, __('Уведомить (Под заказ)'), $data['p_enabled'])
    );

    // BID
    $Tab_yml.=$PHPShopGUI->setField(__('Ставка BID'), $PHPShopGUI->setInputText(null, 'yml_bid_array[bid]', $data['yml_bid_array']['bid'], 100));
    $Tab_yml.=$PHPShopGUI->setField(__('Ставка CBID'), $PHPShopGUI->setInputText(null, 'yml_bid_array[cbid]', $data['yml_bid_array']['cbid'], 100));
    $Tab1.=$PHPShopGUI->setCollapse(__('Яндекс Маркет'), $Tab_yml, false);

    // Подтипы
    /*
      $Tab_option = $PHPShopGUI->setField(__('Связи'), $PHPShopGUI->setRadio('parent_enabled_new', 0, __('Обычный товар'), $data['parent_enabled']) .
      $PHPShopGUI->setRadio('parent_enabled_new', 1, __('Добавочная опция для ведущего товара'), $data['parent_enabled']));

      $Tab_option.=$PHPShopGUI->setField(__('ID подтипов'), $PHPShopGUI->setTextarea('parent_new', $data['parent'], "none", false, false, __('Укажите ID товаров или воспользуйтесь <a href="#"  data-target="#parent_new" class="btn btn-sm btn-default tag-search"><span class="glyphicon glyphicon-search"></span> поиском товаров</a>')));

      // Подтипы
      $Tab1.=$PHPShopGUI->setCollapse(__('Подтипы'), $Tab_option, false); */

    // Редактор краткого описания
    $Tab2 = $PHPShopGUI->loadLib('tab_description', $data);

    // Редактор подробного описания
    $Tab3 = $PHPShopGUI->loadLib('tab_content', $data);

    // Статьи
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
    $data_page = $PHPShopOrm->select(array('*'), false, array('order' => 'name'), array('limit' => 100));

    if (strstr($data['page'], ',')) {
        $data['page'] = explode(",", $data['page']);
    }
    else
        $data['page'] = array($data['page']);

    $value = array();
    if (is_array($data_page))
        foreach ($data_page as $val) {
            if (is_numeric(array_search($val['link'], $data['page']))) {
                $check = 'selected';
            }
            else
                $check = false;

            $value[] = array($val['name'], $val['link'], $check);
        }

    // Статьи
    $Tab_docs = $PHPShopGUI->setCollapse(__('Статьи'), $PHPShopGUI->setSelect('page_new[]', $value, '50%', false, false, false, '90%', 30, true));

    // Файлы
    $Tab_docs.= $PHPShopGUI->setCollapse(__('Файлы'), $PHPShopGUI->loadLib('tab_files', $data));

    // Фотогалерея
    $Tab6 = $PHPShopGUI->loadLib('tab_img', $data);

    // Характеристики
    $Tab_sorts = $PHPShopGUI->loadLib('tab_sorts', $data);

    // Заголовки
    $Tab_header = $PHPShopGUI->loadLib('tab_headers', $data);

    // Подтипы
    $Tab_option = $PHPShopGUI->loadLib('tab_option', $data);

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Основное"), $Tab1), array(__("Изображение"), $Tab6), array(__("Описание"), $Tab2), array(__("Подробно"), $Tab3), array(__("Документы"), $Tab_docs), array(__("Характеристики"), $Tab_sorts), array(__("Подтипы"), $Tab_option, true), array(__("Заголовки"), $Tab_header));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.catalog.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.catalog.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.catalog.edit");

    $_GET['path'] = 'catalog';

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

    header('Location: ?path=' . $_GET['return'] . '&cat=' . $_POST['category_new']);
}

/**
 * Экшен обновления
 * @return bool
 */
function actionUpdate() {
    global $PHPShopModules, $PHPShopSystem, $PHPShopOrm;

    // Списывание со склада
    if (isset($_POST['items_new'])) {
        switch ($PHPShopSystem->getSerilizeParam('admoption.sklad_status')) {

            case(3):
                if ($_POST['items_new'] < 1) {
                    $_POST['sklad_new'] = 1;
                    //$_POST['enabled_new'] = 1;
                } else {
                    $_POST['sklad_new'] = 0;
                    $_POST['enabled_new'] = 1;
                }
                break;

            case(2):
                if ($_POST['items_new'] < 1) {
                    $_POST['enabled_new'] = 0;
                    //$_POST['sklad_new'] = 0;
                } else {
                    $_POST['enabled_new'] = 1;
                    //$_POST['sklad_new'] = 0;
                }
                break;

            default:
                break;
        }
    }

    // Дата измененения
    $_POST['datas_new'] = time();

    if (empty($_POST['ajax'])) {
        $_POST['yml_bid_array_new'] = serialize($_POST['yml_bid_array']);

        // Добавление характеристик
        if (is_array($_POST['vendor_array_add'])) {
            foreach ($_POST['vendor_array_add'] as $k => $val) {

                $sort_array = $result = null;

                if (!empty($val)) {

                    if (strstr($val, '#')) {
                        $sort_array = explode('#', $val);
                    }
                    else
                        $sort_array[] = $val;

                    if (is_array($sort_array))
                        foreach ($sort_array as $val_sort) {

                            $PHPShopOrmSort = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);

                            // Проверка уникальности
                            $checkName = $PHPShopOrmSort->select(array('id'), array('name' => '="' . trim($val_sort) . '"', 'category' => '=' . intval($k)), false, array('limit' => 1));

                            // Нет характеристики, создаем новую
                            if (empty($checkName['id'])) {
                                $PHPShopOrmSort->clean();

                                $result = $PHPShopOrmSort->insert(array('name_new' => trim($val_sort), 'category_new' => intval($k)));
                                if (!empty($result))
                                    $_POST['vendor_array_new'][$k][] = $result;
                            }
                            // Есть, назначем Из базы
                            else {
                                $_POST['vendor_array_new'][$k][] = $checkName['id'];
                            }
                        }
                }
                else
                    unset($_POST['vendor_array_add'][$k]);
            }
        }

        // Изменение характеристик
        $_POST['vendor_new'] = null;
        if (is_array($_POST['vendor_array_new']))
            foreach ($_POST['vendor_array_new'] as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $key => $p) {
                        $_POST['vendor_new'].="i" . $k . "-" . $p . "i";
                        if (empty($p))
                            unset($_POST['vendor_array_new'][$k][$key]);
                    }
                }
                else
                    $_POST['vendor_new'].="i" . $k . "-" . $v . "i";
            }

        $_POST['vendor_array_new'] = serialize($_POST['vendor_array_new']);

        // Статьи
        if (isset($_POST['editID'])) {
            if (is_array($_POST['page_new']))
                $_POST['page_new'] = array_pop($_POST['page_new']);
        }
        else
            $_POST['page_new'] = @implode(',', $_POST['page_new']);

        // Файлы
        if (isset($_POST['editID'])) {
            if (is_array($_POST['files_new'])) {
                foreach ($_POST['files_new'] as $k => $files)
                    $files_new[$k] = @array_map("urldecode", $files);

                $_POST['files_new'] = serialize($files_new);
            }
        }
        else
            $_POST['files_new'] = serialize($_POST['files_new']);

        // Доп каталоги
        if (!empty($_POST['dop_cat_new']) and substr($_POST['dop_cat_new'], 1) != '#') {
            $_POST['dop_cat_new'] = '#' . $_POST['dop_cat_new'] . '#';
        }

        // Конвертер цвета
        if (!empty($_POST['parent2_new']) and empty($_POST['color_new']))
            $_POST['color_new'] = PHPShopString::getColor($_POST['parent2_new']);

        // Корректировка пустых значений
        $PHPShopOrm->updateZeroVars('newtip_new', 'enabled_new', 'spec_new', 'yml_new', 'sklad_new', 'pic_small_new', 'pic_big_new');
    }

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    // Добавление изображения в фотогалерею
    $insert = fotoAdd();
    if (empty($_POST['pic_small_new']) and !empty($insert['pic_small_new']))
        $_POST['pic_small_new'] = $insert['pic_small_new'];
    if (empty($_POST['pic_big_new']) and !empty($insert['name_new']))
        $_POST['pic_big_new'] = $insert['name_new'];

    // Права пользователя
    $_POST['user_new'] = $_SESSION['idPHPSHOP'];



    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    $PHPShopOrm->clean();

    return array('success' => $action, 'enabled' => $_POST['enabled_new'], 'sklad' => $_POST['sklad_new'], 'id' => $_POST['rowID']);
}

// Добавление изображения в фотогалерею
function fotoAdd() {
    global $PHPShopSystem;
    require_once $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/lib/thumb/phpthumb.php';

    // Параметры ресайзинга
    $img_tw = $PHPShopSystem->getSerilizeParam('admoption.img_tw');
    $img_th = $PHPShopSystem->getSerilizeParam('admoption.img_th');
    $img_w = $PHPShopSystem->getSerilizeParam('admoption.img_w');
    $img_h = $PHPShopSystem->getSerilizeParam('admoption.img_h');
    $img_tw = empty($img_tw) ? 150 : $img_tw;
    $img_th = empty($img_th) ? 150 : $img_th;
    $img_w = empty($img_w) ? 300 : $img_w;
    $img_h = empty($img_h) ? 300 : $img_h;

    $img_adaptive = $PHPShopSystem->getSerilizeParam('admoption.image_adaptive_resize');
    $image_save_source = $PHPShopSystem->getSerilizeParam('admoption.image_save_source');
    $width_kratko = $PHPShopSystem->getSerilizeParam('admoption.width_kratko');
    $width_podrobno = $PHPShopSystem->getSerilizeParam('admoption.width_podrobno');

    // Папка сохранения
    $path = $GLOBALS['SysValue']['dir']['dir'] . '/UserFiles/Image/' . $PHPShopSystem->getSerilizeParam('admoption.image_result_path');

    // Соль
    $RName = substr(abs(crc32(time())), 0, 5);

    // Копируем от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg', 'jpeg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $path . $_FILES['file']['name'])) {
                $file = $_SERVER['DOCUMENT_ROOT'] . $path . $_FILES['file']['name'];
                $file_name = $_FILES['file']['name'];
                $path_parts = pathinfo($file);
                $tmp_file = $_SERVER['DOCUMENT_ROOT'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // Читаем файл из URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST['img_new'];
        $path_parts = pathinfo($file);
        $file_name = $path_parts['basename'];
    }

    // Читаем файл из файлового менеджера
    elseif (!empty($_POST['img_new'])) {
        $file = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $_POST['img_new'];
        $path_parts = pathinfo($file);
        $file_name = $path_parts['basename'];
    }

    if (!empty($file)) {

        // Маленькое изображение (тумбнейл)
        $thumb = new PHPThumb($file);
        $thumb->setOptions(array('jpegQuality' => $width_kratko));

        // Адаптивность
        if (!empty($img_adaptive))
            $thumb->adaptiveResize($img_tw, $img_th);
        else
            $thumb->resize($img_tw, $img_th);

        // Исходное название
        if ($PHPShopSystem->ifSerilizeParam('admoption.image_save_name')) {
            $name_s = $path_parts['filename'] . 's.' . strtolower($thumb->getFormat());
            $name = $path_parts['filename'] . '.' . strtolower($thumb->getFormat());
            $name_big = $path_parts['filename'] . '_big.' . strtolower($thumb->getFormat());

            if (!empty($image_save_source)) {
                $file_big = $_SERVER['DOCUMENT_ROOT'] . $path . $name_big;
                @copy($file, $file_big);
            }
        } else {
            $name_s = 'img' . $_POST['rowID'] . '_' . $RName . 's.' . strtolower($thumb->getFormat());
            $name = 'img' . $_POST['rowID'] . '_' . $RName . '.' . strtolower($thumb->getFormat());
            $name_big = 'img' . $_POST['rowID'] . '_' . $RName . '_big.' . strtolower($thumb->getFormat());
        }


        $thumb->save($_SERVER['DOCUMENT_ROOT'] . $path . $name_s);

        // Большое изображение
        $thumb = new PHPThumb($file);
        $thumb->setOptions(array('jpegQuality' => $width_podrobno));

        // Адаптивность
        if (!empty($img_adaptive))
            $thumb->adaptiveResize($img_w, $img_h);
        else
            $thumb->resize($img_w, $img_h);



        $watermark = $PHPShopSystem->getSerilizeParam('admoption.watermark_image');
        $watermark_text = $PHPShopSystem->getSerilizeParam('admoption.watermark_text');

        // Ватермарк
        if ($PHPShopSystem->ifSerilizeParam('admoption.watermark_big_enabled')) {

            // Image
            if (!empty($watermark) and file_exists($_SERVER['DOCUMENT_ROOT'] . $watermark))
                $thumb->createWatermark($_SERVER['DOCUMENT_ROOT'] . $watermark, $PHPShopSystem->getSerilizeParam('admoption.watermark_right'), $PHPShopSystem->getSerilizeParam('admoption.watermark_bottom'));
            // Text
            elseif (!empty($watermark_text))
                $thumb->createWatermarkText($watermark_text, $PHPShopSystem->getSerilizeParam('admoption.watermark_text_size'), $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/lib/font/' . $PHPShopSystem->getSerilizeParam('admoption.watermark_text_font') . '.ttf', $PHPShopSystem->getSerilizeParam('admoption.watermark_right'), $PHPShopSystem->getSerilizeParam('admoption.watermark_bottom'), $PHPShopSystem->getSerilizeParam('admoption.watermark_text_color'), $PHPShopSystem->getSerilizeParam('admoption.watermark_text_alpha'), 0,$PHPShopSystem->getSerilizeParam('admoption.watermark_center_enabled'));
        }

        $thumb->save($_SERVER['DOCUMENT_ROOT'] . $path . $name);

        // Исходное изображение
        if (!empty($image_save_source)) {

            if (!$PHPShopSystem->ifSerilizeParam('admoption.image_save_name')) {
                $file_big = $_SERVER['DOCUMENT_ROOT'] . $path . $name_big;
                @copy($file, $file_big);
            }

            // Ватермарк
            if ($PHPShopSystem->ifSerilizeParam('admoption.watermark_source_enabled')) {

                $thumb = new PHPThumb($file_big);
                $thumb->setOptions(array('jpegQuality' => $width_podrobno));
                $thumb->setWorkingImage($thumb->getOldImage());

                // Image
                if (!empty($watermark) and file_exists($_SERVER['DOCUMENT_ROOT'] . $watermark))
                    $thumb->createWatermark($_SERVER['DOCUMENT_ROOT'] . $watermark, $PHPShopSystem->getSerilizeParam('admoption.watermark_right'), $PHPShopSystem->getSerilizeParam('admoption.watermark_bottom'),$PHPShopSystem->getSerilizeParam('admoption.watermark_center_enabled'));
                // Text
                elseif (!empty($watermark_text))
                    $thumb->createWatermarkText($watermark_text, $PHPShopSystem->getSerilizeParam('admoption.watermark_text_size'), $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/lib/font/' . $PHPShopSystem->getSerilizeParam('admoption.watermark_text_font') . '.ttf', $PHPShopSystem->getSerilizeParam('admoption.watermark_right'), $PHPShopSystem->getSerilizeParam('admoption.watermark_bottom'), $PHPShopSystem->getSerilizeParam('admoption.watermark_text_color'), $PHPShopSystem->getSerilizeParam('admoption.watermark_text_alpha'), 0,$PHPShopSystem->getSerilizeParam('admoption.watermark_center_enabled'));

                $thumb->save($file_big);
            }
        }

        if (!$PHPShopSystem->ifSerilizeParam('admoption.image_save_name') and !empty($tmp_file))
            unlink($tmp_file);

        // Добавление в таблицу фотогалереи
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
        $insert['parent_new'] = $_POST['rowID'];
        $insert['name_new'] = $path . $name;
        $insert['pic_small_new'] = $path . $name_s;
        //$insert['info_new'] = $_POST['info_img_new'][0];
        $PHPShopOrm->insert($insert);
        return $insert;
    }
}

// Удаление фотогалереи
function fotoDelete($where = null) {

    if (!is_array($where))
        $where = array('parent' => '=' . intval($_POST['rowID']));

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
    $data = $PHPShopOrm->select(array('*'), $where, false, array('limit' => 100));
    if (is_array($data)) {
        foreach ($data as $row) {
            $name = $row['name'];
            $pathinfo = pathinfo($name);
            $oldWD = getcwd();
            $dirWhereRenameeIs = $_SERVER['DOCUMENT_ROOT'] . $pathinfo['dirname'];
            $oldFilename = $pathinfo['basename'];

            @chdir($dirWhereRenameeIs);
            @unlink($oldFilename);
            $oldFilename_s = str_replace(".", "s.", $oldFilename);
            @unlink($oldFilename_s);
            $oldFilename_big = str_replace(".", "_big.", $oldFilename);
            @unlink($oldFilename_big);
            @chdir($oldWD);
        }
        $PHPShopOrm->clean();
        return $PHPShopOrm->delete($where);
    }
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    // Удаление подипов
    $data = $PHPShopOrm->select(array('parent'), array('id' => '=' . intval($_POST['rowID'])));
    $PHPShopOrm->clean();

    $action = $PHPShopOrm->delete(array('id' => '=' . intval($_POST['rowID'])));

    // Удаление фотогалереи
    if ($action)
        fotoDelete();

    // Удаление подтипов
    if ($action and !empty($data['parent'])) {
        $parent = @explode(",", $data['parent']);
        $PHPShopOrm->mysql_error = false;
        $PHPShopOrm->delete(array('uid' => ' IN ("' . @implode('","', $parent) . '")'));
    }


    return array("success" => $action);
}

/**
 * Редактировать опцию
 */
function actionOptionEdit() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['id'])));

    if (empty($data['name'])) {
        $data['name'] = $_REQUEST['parent_name'] . ' ' . $data['parent'] . ' ' . $data['parent2'];
    }

    $PHPShopGUI->field_col = 2;

    // Конвертер цвета
    if (!empty($data['parent2']) and empty($data['color']))
        $data['color'] = PHPShopString::getColor($data['parent2']);

    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Размер', $PHPShopGUI->setInputArg(array('name' => 'parent_new', 'type' => 'text', 'value' => $data['parent'])));
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField(array('Цвет', 'Код'), array($PHPShopGUI->setInputArg(array('name' => 'parent2_new', 'type' => 'text', 'value' => $data['parent2'])), $PHPShopGUI->setInputColor('color_new', $data['color'], 110)), array(array(2, 6), array(1, 2)));
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Название', $PHPShopGUI->setInputArg(array('name' => 'name_new', 'type' => 'text.required', 'value' => $data['name'])) . $PHPShopGUI->setHelp(__('Полное <a href="?path=product&return=catalog.' . $data['category'] . '&id=' . $_REQUEST['id'] . '&view=option">название товара</a>, попадающего в корзину')));
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Артикул', $PHPShopGUI->setInputArg(array('name' => 'uid_new', 'type' => 'text', 'value' => $data['uid'])));

    // Склад
    if (empty($data['ed_izm']))
        $ed_izm = 'шт.';
    else
        $ed_izm = $data['ed_izm'];

    // Склад и вес
    $PHPShopGUI->_CODE.=$PHPShopGUI->setField(array('Склад', 'Вес'), array($PHPShopGUI->setInputText(false, 'items_new', $data['items'], 150, $ed_izm), $PHPShopGUI->setInputText(false, 'weight_new', $data['weight'], 150, 'гр.')), array(array(2, 4), array(2, 4)));

    // Сортировка и вывод
    $PHPShopGUI->_CODE.=$PHPShopGUI->setField(array('Сортировка', 'Вывод'), array($PHPShopGUI->setInputArg(array('name' => 'num_new', 'type' => 'text', 'value' => $data['num'], 'size' => 150)), $PHPShopGUI->setCheckbox('enabled_new', 1, 'Вывод в товаре', $data['enabled'])), array(array(2, 4), array(2, 4)));

    // Валюты
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    $valuta_area = null;
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $check = 'checked';
                $valuta_def_name = $val['code'];
            }
            else
                $check = false;
            $valuta_area.=$PHPShopGUI->setRadio('baseinputvaluta_new', $val['id'], $val['name'], $check);
        }


    // Цены
    $PHPShopGUI->_CODE.=$PHPShopGUI->setField(array('Цена 1', 'Цена 2'), array($PHPShopGUI->setInputText(null, 'price_new', $data['price'], 150, $valuta_def_name), $PHPShopGUI->setInputText(null, 'price2_new', $data['price2'], 150, $valuta_def_name)), array(array(2, 4), array(2, 4)));


    // Валюта
    $PHPShopGUI->_CODE.=$PHPShopGUI->setField(__('Валюта:'), $valuta_area);

    $PHPShopGUI->_CODE.=$PHPShopGUI->setInputArg(array('name' => 'rowID', 'type' => 'hidden', 'value' => $_REQUEST['id']));
    $PHPShopGUI->_CODE.=$PHPShopGUI->setInputArg(array('name' => 'parentID', 'type' => 'hidden', 'value' => $_REQUEST['parentID']));


    $PHPShopGUI->_CODE.=$PHPShopGUI->setField(__("Изображение"), $PHPShopGUI->setIcon($data['pic_small'], "pic_small_new", true, array('load' => true, 'server' => true, 'url' => false, 'view' => false)));

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    exit($PHPShopGUI->_CODE . '<p class="clearfix"> </p>');
}

/**
 * Редакировать файл
 */
function actionFileEdit() {
    global $PHPShopGUI, $PHPShopModules;


    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Название', $PHPShopGUI->setInputArg(array('name' => 'modal_file_name', 'type' => 'text.required', 'value' => urldecode($_GET['name']))));
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Файл', $PHPShopGUI->setFile($_GET['file'], 'lfile', array('server' => true)));
    $PHPShopGUI->_CODE.=$PHPShopGUI->setInputArg(array('name' => 'selectID', 'type' => 'hidden', 'class' => 'data-id', 'value' => $_POST['selectID']));
    $PHPShopGUI->_CODE.=$PHPShopGUI->setInputArg(array('name' => 'fileCount', 'type' => 'hidden', 'class' => 'data-count', 'value' => $_POST['fileCount']));


    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    exit($PHPShopGUI->_CODE . '<p class="clearfix"> </p>');
}

// Функция удаления изображения
function actionImgDelete() {
    global $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = fotoDelete(array('id' => '=' . $_POST['rowID']));

    return array("success" => $action);
}

// Функция редактирования изображения
function actionImgEdit() {
    global $PHPShopModules;

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . intval($_POST['rowID'])));

    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>