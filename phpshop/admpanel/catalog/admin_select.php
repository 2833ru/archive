<?php

$TitlePage = __("Обновить товары");
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('category');

$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

// Определение визульного вывода поля
function getKeyView($val) {

    if (strpos($val['Type'], "(")) {
        $a = explode("(", $val['Type']);
        $b = $a[0];
    }
    else
        $b = $val['Type'];
    $key_view = array(
        'varchar' => array('type' => 'text', 'name' => $val['Field'] . '_new'),
        'text' => array('type' => 'textarea', 'height' => 150, 'name' => $val['Field'] . '_new'),
        'int' => array('type' => 'text', 'size' => 100, 'name' => $val['Field'] . '_new'),
        'float' => array('type' => 'text', 'size' => 200, 'name' => $val['Field'] . '_new'),
        'enum' => array('type' => 'checkbox', 'name' => $val['Field'] . '_new', 'value' => 1, 'caption' => 'Вкл.'),
    );

    if (!empty($key_view[$b]))
        return $key_view[$b];
    else
        return array('type' => 'text', 'name' => $val['Field'] . '_new');
}

// Описания полей
$key_name = array(
    'id' => 'Id',
    'name' => '<b>Наименование</b>',
    'uid' => 'Артикул',
    'price' => '<b>Цена 1</b>',
    'price2' => 'Цена 2',
    'price3' => 'Цена 3',
    'price4' => 'Цена 4',
    'price5' => 'Цена 5',
    'price_n' => 'Старая цена',
    'sklad' => 'Под заказ',
    'newtip' => 'Новинка',
    'spec' => 'Спецпредложение',
    'items' => '<b>Склад</b>',
    'weight' => 'Вес',
    'num' => 'Приоритет',
    'enabled' => '<b>Вывод</b>',
    'content' => 'Подробное описание',
    'description' => 'Краткое описание',
    'pic_small' => 'Маленькое изображение',
    'pic_big' => 'Большое изображение',
    'category' => 'Категория',
    'yml' => 'Яндекс.Маркет',
    'icon' => 'Иконка',
    'parent_to' => 'Родитель',
    'category' => 'Каталог',
    'title' => 'Meta Title',
    'login' => 'Логин',
    'tel' => 'Телефон',
    'datas' => 'Дата',
    'cumulative_discount' => 'Накопительная скидка',
    'seller' => 'Статус загрузки в 1С',
    'statusi' => 'Статус состояния заказа',
    'fio' => 'Ф.И.О',
    'city' => 'Город',
    'street' => 'Улица',
    'orders' => 'Сумма',
    'odnotip' => 'Сопутствующие товары (IDS)',
    'page' => 'Страницы',
    'parent' => 'Подчиненные товары (IDS)',
    'dop_cat' => 'Дополнительные каталоги ',
    'ed_izm' => 'Единица измерения',
    'baseinputvaluta' => 'Валюта (ID)',
    'p_enabled' => 'Яндекс.Маркет под заказ',
    'rate' => 'Рейтинг',
    'rate_count' => 'Голоса в рейтинге',
    'descrip' => 'Meta description',
    'keywords' => 'Meta keywords',
    'parent_enabled' => 'Подтип товара',
    'price_search' => 'Цена для поиска',
    'prod_seo_name' => 'SEO ссылка',
    'vendor_array' => 'Характеристики'
);

// Стоп лист
$key_stop = array('id', 'password', 'wishlist',  'datas', 'data_adres', 'sort', 'yml_bid_array', 'vendor', 'status', 'files', 'user', 'title_enabled', 'descrip_enabled', 'title_shablon', 'descrip_shablon', 'title_shablon', 'keywords_enabled', 'keywords_shablon');

/**
 * Редактировать с выбранными Шаг 1
 */
function actionSelect() {
    global $PHPShopGUI, $key_name, $key_stop;

    // Выбранные товары
    if (!empty($_POST['select'])) {
        unset($_SESSION['select']['product']);
        if (is_array($_POST['select'])) {
            foreach ($_POST['select'] as $k => $v)
                if (!empty($v))
                    $select[intval($k)] = intval($v);
            $_SESSION['select']['product'] = $select;
        }
    }

    // Наборы
    $command[] = array('Прайс-лист', 1, false);
    $command[] = array('База Excel', 2, false);

    $PHPShopGUI->_CODE.= '<p class="text-muted">Вы можете редактировать одновременно несколько записей. Выберите записи из списка выше, отметьте галочкой поля, которые нужно отредактировать, и нажмите на кнопку "Редактировать выбранные".</p><p class="text-muted"><a href="#" id="select-all">Выбрать все</a> | <a href="#" id="select-none">Снять выделение со всех</a></p>';

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1));

    if (is_array($data))
        foreach ($data as $key => $val) {

            if ((!in_array($key, $key_stop))) {
                if (!empty($key_name[$key])) {
                    $name = $key_name[$key];
                    $select = 0;
                } else {
                    $name = $key;
                    $select = 0;
                }

                // Память выбранных полей
                if (!empty($_COOKIE['check_memory'])) {
                    $memory = json_decode($_COOKIE['check_memory'], true);
                    if (is_array($memory[$_GET['path']])) {
                        if ($memory[$_GET['path']][$key] == 1)
                            $select = 1;
                        else
                            $select = 0;
                    }
                }

                $PHPShopGUI->_CODE.='<div class="pull-left" style="width:200px;>' . $PHPShopGUI->setCheckBox($key, 1, ucfirst($name), $select) . '</div>';
            }
        }

    exit($PHPShopGUI->_CODE . '<p class="clearfix"> </p>');
}

// Добавление полей товара в выбор
function actionSelectEdit() {

    unset($_SESSION['select_col']);
    if (!empty($_POST['select_col'])) {
        $_SESSION['select_col'] = $_POST['select_col'];
    }
    return array("success" => true);
}

/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopOrm;

    if (is_array($_SESSION['select']['product'])) {
        $val = array_values($_SESSION['select']['product']);
        $where = array('id' => ' IN (' . implode(',', $val) . ')');
    }
    else
        $where = null;

    $PHPShopOrm->debug = false;


    // Добавление характеристик
    if (is_array($_POST['vendor_array_add'])) {
        foreach ($_POST['vendor_array_add'] as $k => $val) {

            if (!empty($val)) {
                $PHPShopOrmSort = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
                $result = $PHPShopOrmSort->insert(array('name_new' => $val, 'category_new' => $k));
                if (!empty($result))
                    $_POST['vendor_array_new'][$k][] = $result;
            }
            else
                unset($_POST['vendor_array_add'][$k]);
        }
    }

    // Изменение характеристик
    if (!empty($_POST['vendor_array_new'])) {
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
    }


    // Память выбранных полей
    if (is_array($_POST)) {
        $memory = json_decode($_COOKIE['check_memory'], true);
        unset($memory[$_GET['path']]);
        foreach ($_POST as $k => $v) {
            if (strstr($k, '_new'))
                $memory[$_GET['path']][str_replace('_new', '', $k)] = 1;
        }
        if (is_array($memory))
            setcookie("check_memory", json_encode($memory), time() + 3600000, '/phpshop/admpanel/');
    }

    if ($PHPShopOrm->update($_POST, $where)) {
        header('Location: ?path=catalog&cat=' . $_GET['cat']);
    }
    else
        return true;
}

// Построение дерева категорий
function treegenerator($array, $i, $parent) {
    global $tree_array;
    $del = '¦&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree = $tree_select = $check = false;
    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

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
		<td><a href="?path=catalog&id=' . $k . '">' . $v . '</a></td>
                    </tr>';

            $tree_select.=$check['select'];
            $tree.=$check['tree'];
        }
    }
    return array('select' => $tree_select, 'tree' => $tree);
}

// Выбор каталога
function viewCatalog() {

    $PHPShopCategoryArray = new PHPShopCategoryArray();
    $CategoryArray = $PHPShopCategoryArray->getArray();

    $CategoryArray[0]['name'] = '- Кореневой уровень -';
    $tree_array = array();

    foreach ($PHPShopCategoryArray->getKey('parent_to.id', true) as $k => $v) {
        foreach ($v as $cat) {
            $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
        }
        $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
        $tree_array[$k]['id'] = $k;
    }


    $GLOBALS['tree_array'] = &$tree_array;

    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-container=".sidebarcontainer"  data-style="btn btn-default btn-sm" name="category_new">';

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

    return $tree_select;
}

/**
 * Редактировать с выбранными Шаг 2
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $key_name, $key_stop;

    $PHPShopGUI->setActionPanel(__("Обновить Товары"), false, array('Сохранить и закрыть'));
    $PHPShopGUI->addJSFiles('./catalog/gui/catalog.gui.js');
    $PHPShopGUI->field_col = 2;
    $select_error = null;

    $PHPShopGUI->_CODE.= $PHPShopGUI->setHelp('Вы можете редактировать одновременно несколько записей. Выберите записи из списка товаров, отметьте галочкой товары, которые нужно отредактировать, и нажмите на кнопку "Редактировать выбранные".<hr>', false);

    $PHPShopOrm->sql = 'show fields  from ' . $GLOBALS['SysValue']['base']['products'];
    $select = array_values($_SESSION['select_col']);
    $data = $PHPShopOrm->select();
    if (is_array($data))
        foreach ($data as $val) {

            if (in_array($val['Field'], $select) and !in_array($val['Field'], $key_stop)) {

                // Каталоги
                if ($val['Field'] == 'category') {
                    $PHPShopGUI->_CODE.=$PHPShopGUI->setField(__("Размещение:"), viewCatalog());
                }
                // Характеристики
                elseif ($val['Field'] == 'vendor_array') {
                    if (!empty($_GET['cat'])) {
                        PHPShopObj::loadClass("sort");
                        $PHPShopSort = new PHPShopSort($_GET['cat'], false, false, 'sorttemplate', false, false, true);
                        $PHPShopGUI->_CODE.=$PHPShopSort->disp;
                    } else {
                        //$PHPShopGUI->_CODE.=$PHPShopGUI->setField(__('Характеристики'),'<p class="text-muted"></p>');
                        $select_error = 'Редактировать характеристики можно только у товаров из общей категории: <a href="?path=catalog"><span class="glyphicon glyphicon-share-alt"></span> Выбрать</a>';
                    }
                } elseif (!empty($key_name[$val['Field']])) {
                    $name = $key_name[$val['Field']];
                    $PHPShopGUI->_CODE.=$PHPShopGUI->setField(ucfirst($name), $PHPShopGUI->setInputArg(getKeyView($val)));
                } else {
                    $name = $val['Field'];
                    $PHPShopGUI->_CODE.=$PHPShopGUI->setField(ucfirst($name), $PHPShopGUI->setInputArg(getKeyView($val)));
                }
            }
        }


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.catalog.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.catalog.edit");


    // Выбранные данные
    $select_action_path = 'product';

    if (is_array($_SESSION['select'][$select_action_path])) {
        foreach ($_SESSION['select'][$select_action_path] as $val)
            $select_message = '<span class="label label-default">' . count($_SESSION['select']['product']) . '</span> товаров выбрано<hr><a href="#" class="back"><span class="glyphicon glyphicon-ok"></span> Изменить интервал</a>';
    }
    else
        $select_message = '<p class="text-muted">Вы можете выбрать конкретные объекты для экспорта. По умолчанию будут экспортированы все позиции.: <a href="?path=catalog"><span class="glyphicon glyphicon-share-alt"></span> Выбрать</a></p>';

    $sidebarleft[] = array('title' => 'Подсказка', 'content' => $select_message);

    // Ошибки
    if (!empty($select_error))
        $sidebarleft[] = array('title' => 'Ошибка', 'content' => $select_error, 'class' => 'text-danger');


    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);

    $PHPShopGUI->Compile(2);
    return true;
}

/**
 * Шаблон вывода характеристик
 */
function sorttemplate($value, $n, $title, $vendor) {
    global $PHPShopGUI;
    $i = 1;
    //$value_new[0]=array(__('Нет данных'),false, 'none');
    if (is_array($value)) {
        sort($value);
        foreach ($value as $p) {
            $sel = null;
            if (is_array($vendor[$n])) {
                foreach ($vendor[$n] as $value) {

                    if ($value == $p[1])
                        $sel = "selected";
                }
            }elseif ($vendor[$n] == $p[1])
                $sel = "selected";

            $value_new[$i] = array($p[0], $p[1], $sel);
            $i++;
        }
    }

    $value = $PHPShopGUI->setSelect('vendor_array_new[' . $n . '][]', $value_new, 300, null, false, $search = true, false, $size = 1, $multiple = true);

    $disp = $PHPShopGUI->setField($title, $value) .
            $PHPShopGUI->setField(null, $PHPShopGUI->setInputArg(array('type' => 'text', 'placeholder' => __('Ввести другое'), 'size' => '300', 'name' => 'vendor_array_add[' . $n . ']')));

    return $disp;
}

/**
 * Настройка полей - 1 шаг
 */
function actionOption() {
    global $PHPShopInterface;

    // Память выбранных полей
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
    }
    
            $message = '<p class="text-muted">Вы можете изменить перечень полей в таблице отображения товаров в категориях.</p>';

    $searchforma =$message.
            $PHPShopInterface->setCheckbox('icon', 1, __('Иконка'), $memory['catalog.option']['icon']) .
            $PHPShopInterface->setCheckbox('name', 1, __('Название'), $memory['catalog.option']['name']) .
            $PHPShopInterface->setCheckbox('uid', 1, __('Артикул'), $memory['catalog.option']['uid']) .
            $PHPShopInterface->setCheckbox('id', 1, __('ID'), $memory['catalog.option']['id']) .
            $PHPShopInterface->setCheckbox('price', 1, __('Цена'), $memory['catalog.option']['price']) .
            $PHPShopInterface->setCheckbox('item', 1, __('Количество'), $memory['catalog.option']['item']) . '<br>' .
            $PHPShopInterface->setCheckbox('menu', 1, __('Экшен меню'), $memory['catalog.option']['menu']) .
            $PHPShopInterface->setCheckbox('status', 1, __('Статус'), $memory['catalog.option']['status']) .
            $PHPShopInterface->setCheckbox('num', 1, __('Сортировка'), $memory['catalog.option']['num']) .
            $PHPShopInterface->setCheckbox('label', 1, __('Лейблы спецпредложений'), $memory['catalog.option']['label']);

    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => 'catalog'));
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'cat', 'value' => $_REQUEST['cat']));

    $searchforma.='<p class="clearfix"> </p>';


    $PHPShopInterface->_CODE.=$searchforma;

    exit($PHPShopInterface->getContent() . '<p class="clearfix"> </p>');
}

/**
 * Настройка полей - 2 шаг
 */
function actionOptionSave() {

    // Память выбранных полей
    if (is_array($_POST['option'])) {

        $memory = json_decode($_COOKIE['check_memory'], true);
        unset($memory['catalog.option']);
        foreach ($_POST['option'] as $k => $v) {
            $memory['catalog.option'][$k] = $v;
        }
        if (is_array($memory))
            setcookie("check_memory", json_encode($memory), time() + 3600000*6, '/phpshop/admpanel/');
    }

    return array('success' => true);
}

// Обработка событий
$PHPShopGUI->getAction();
?>