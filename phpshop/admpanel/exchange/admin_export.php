<?php

$TitlePage = __("Экспорт данных");
PHPShopObj::loadClass('sort');
PHPShopObj::loadClass('array');

// Описания полей
$key_name = array(
    'id' => 'Id',
    'name' => 'Наименование',
    'uid' => 'Артикул',
    'price' => 'Цена 1',
    'price2' => 'Цена 2',
    'price3' => 'Цена 3',
    'price4' => 'Цена 4',
    'price5' => 'Цена 5',
    'price_n' => 'Старая цена',
    'sklad' => 'Под заказ',
    'newtip' => 'Новинка',
    'spec' => 'Спецпредложение',
    'items' => 'Склад',
    'weight' => 'Вес',
    'num' => 'Приоритет',
    'enabled' => 'Вывод',
    'content' => 'Подробное описание',
    'description' => 'Краткое описание',
    'pic_small' => 'Маленькое изображение',
    'pic_big' => 'Большое изображение',
    'category' => 'Категория',
    'yml' => 'Яндекс.Маркет',
    'icon' => 'Иконка',
    'parent_to' => 'Родитель',
    'category' => 'Каталог',
    'title' => 'Заголовок',
    'login' => 'Логин',
    'tel' => 'Телефон',
    'datas' => 'Дата',
    'cumulative_discount' => 'Накопительная скидка',
    'seller' => 'Статус загрузки в 1С',
    'statusi' => 'Статус состояния заказа',
    'fio' => 'Ф.И.О',
    'city' => 'Город',
    'street' => 'Улица',
    'odnotip' => 'Сопутствующие товары',
    'page' => 'Страницы',
    'parent' => 'Подчиненные товары',
    'dop_cat' => 'Дополнительные каталоги',
    'ed_izm' => 'Единица измерения',
    'baseinputvaluta' => 'Валюта',
    'vendor_array' => 'Характеристики',
    'p_enabled' => 'Наличие в Яндекс.Маркет',
    'parent_enabled' => 'Подтип',
    'rate' => 'Рейтинг',
    'rate_count' => 'Голоса в рейтинге',
    'descrip' => 'Meta description',
    'keywords' => 'Meta keywords',
    'parent_enabled' => 'Подтип товара',
    'price_search' => 'Цена для поиска',
    'index' => 'Индекс',
    'fio' => 'ФИО',
    'tel' => 'Телефон',
    'street' => 'Улица',
    'house' => 'Дом',
    'porch' => 'Подъезд',
    'door_phone' => 'Домофон',
    'flat' => 'Квартира',
    'delivtime' => 'Время доставки',
    'door_phone' => 'Домофон',
    'tel' => 'Телефон',
    'house' => 'Дом',
    'porch' => 'Подъезд',
    'org_name' => 'Компания',
    'org_inn' => 'ИНН',
    'org_kpp' => 'КПП',
    'org_yur_adres' => 'Юр. адрес',
    'org_fakt_adres' => 'Факт. адрес',
    'org_ras' => 'Р/С',
    'org_bank' => 'Банк',
    'org_kor' => 'К/С',
    'org_bik' => 'БИК',
    'org_city' => 'Город',
    'dop_info' => 'Примечание покупателя',
    'status' => 'Примечание менеджера',
    'seller' => 'Загружено в CRM',
    'country' => 'Страна',
    'statusi' => 'Статус заказа',
    'status' => 'Статус пользователя',
    'state' => 'Регион/штат',
    'city' => 'Город',
    'sum' => 'Сумма',
    'user' => 'ID Пользователя',
    'orders' => 'Корзина',
    "prod_seo_name" => 'SEO ссылка',
    'num_row' => 'Товаров в длину',
    'num_cow' => 'Товаров на странице',
    'count' => 'Содержит товаров',
    'cat_seo_name'=>'SEO ссылка каталога',
    'sum'=>'Сумма',
    'servers'=>'Витрины'
    
   
);

// Стоп лист
$key_stop = array('password', 'wishlist', 'sort', 'yml_bid_array', 'vendor', 'files','vid','name_rambler','skin','skin_enabled','secure_groups','icon_description');


switch ($subpath[2]) {
    case 'catalog':
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $key_base = array('id', 'name', 'icon', 'parent_to');
        break;
    case 'user':
        PHPShopObj::loadClass('user');
        $PHPShopUserStatusArray = new PHPShopUserStatusArray();
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
        $key_base = array('id', 'login', 'name', 'tel');
        array_push($key_stop, 'tel_code', 'adres', 'inn', 'kpp', 'company', 'data_adres');
        break;
    case 'order':
        PHPShopObj::loadClass('order');
        $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $key_base = array('id', 'uid', 'fio', 'tel', 'datas');
        $key_name['uid'] = '№ Заказа';
        $TitlePage.=' заказов';
        break;
    default: $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $key_base = array('id', 'name', 'uid', 'category', 'price', 'newtip', 'spec', 'items', 'enabled');
        array_push($key_stop, 'user', 'title_enabled', 'descrip_enabled', 'title_shablon', 'descrip_shablon', 'title_shablon', 'keywords_enabled', 'keywords_shablon');
        break;
}

// Память полей
if (!empty($_COOKIE['check_memory'])) {
    $memory = json_decode($_COOKIE['check_memory'], true);
    if (is_array($memory[$_GET['path']])) {
        $key_base = array_keys($memory[$_GET['path']]);
    }
}

// Добавление товар в выбор
function actionSelect() {
    global $subpath;
    unset($_SESSION['select']);


    // Выбранные товары
    if (!empty($_POST['select'])) {
        if (is_array($_POST['select'])) {
            foreach ($_POST['select'] as $k => $v)
                if (!empty($v))
                    $select[intval($k)] = intval($v);
            $_SESSION['select'][$subpath[2]] = $select;
        }
    }

    return array("success" => true);
}

// Проверка ключей для implode
function implodeCheck(&$value) {
    $value = intval($value);
}

// Проверка полей
function patternCheck(&$value) {
    $value = "`" . $value . "`";
}

// Разбор сериализованных полей
function serializeSelect($str, $cols_name = false) {
    $delim = $_POST['export_delim'];
    $sortdelim = $_POST['export_sortdelim'];
    $array_line = $csv_line = null;
    $cols_array = unserialize($str);

    if (is_array($cols_array)) {

        // Заголовки
        $key = array_keys($cols_array);
        array_walk_recursive($key, 'implodeCheck');
        $idcat_list = implode(',', $key);


        if (!empty($idcat_list)) {
            $where = array('id' => ' IN (' . $idcat_list . ')');

            $PHPShopSortCategoryArray = new PHPShopSortCategoryArray($where);
            $data = $PHPShopSortCategoryArray->getArray();
        }

        if (is_array($data)) {

            foreach ($cols_array as $k => $v) {
                if (is_array($v)) {

                    // Значения
                    $val = array_values($v);
                    array_walk_recursive($val, 'implodeCheck');
                    $id_list = implode(',', $val);
                    if (!empty($id_list)) {
                        $where = array('id' => ' IN (' . $id_list . ')');
                        $PHPShopSortArray = new PHPShopSortArray($where);
                        $data_v = $PHPShopSortArray->getArray();
                    }

                    foreach ($v as $a_v)
                        $array_line.=$data[$k]['name'] . '/' . $data_v[$a_v]['name'] . $sortdelim;
                }
            }
            $csv_line.='"' . substr($array_line, 0, (strlen($array_line) - 1)) . '"' . $delim;
        }
    }
    else
        $csv_line = '""' . $delim;

    return $csv_line;
}

// Функция обновления
function actionSave() {
    global $PHPShopOrm, $key_name, $subpath, $PHPShopOrderStatusArray, $PHPShopUserStatusArray;

    $PHPShopOrm->debug = false;
    $PHPShopOrm->mysql_error = false;
    $delim = $_POST['export_delim'];
    $csv = null;
    $gz = $_POST['export_gzip'];
    $pattern_cols = $_POST['pattern_cols'];
    if (!is_array($pattern_cols))
        $pattern_cols = array('id', 'name', 'price');
    else {
        array_walk($pattern_cols, 'patternCheck');
    }

    // Экспорт только выбранных
    $select_action_path = $subpath[2];
    if (empty($select_action_path))
        $select_action_path = 'product';

    if (is_array($_SESSION['select'][$select_action_path])) {
        $val = array_values($_SESSION['select'][$select_action_path]);
        $where = array('id' => ' IN (' . implode(',', $val) . ')');
    }
    else
        $where = null;

    // Память выбранных полей
    if (is_array($_POST['pattern_cols'])) {
        $memory = json_decode($_COOKIE['check_memory'], true);
        unset($memory[$_GET['path']]);
        foreach ($_POST['pattern_cols'] as $k => $v) {
            $memory[$_GET['path']][$v] = 1;
        }
        if (is_array($memory))
            setcookie("check_memory", json_encode($memory), time() + 3600000, $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/admpanel/');
    }

    $data = $PHPShopOrm->select($pattern_cols, $where, array('order' => 'id desc'), array('limit' => $_POST['export_limit']));

    foreach ($_POST['pattern_cols'] as $cols_name) {

        if (!empty($key_name[$cols_name]))
            $name = $key_name[$cols_name];
        else
            $name = $cols_name;

        $csv.='"' . $name . '"' . $delim;
    }

    $csv = substr($csv, 0, (strlen($csv) - 1)) . "\n";

    if (is_array($data)) {
        foreach ($data as $row) {
            $csv_line = null;
            foreach ($_POST['pattern_cols'] as $cols_name) {
                if ($cols_name == 'datas')
                    $csv_line.=PHPShopDate::get($row[$cols_name]) . $delim;

                // Корзина
                elseif ($cols_name == 'orders') {
                    $order = unserialize($row['orders']);
                    $csv_line.='"';
                    if (is_array($order['Cart']['cart']))
                        foreach ($order['Cart']['cart'] as $k => $v) {
                            $csv_line.= '[' . $v['name'] . '(' . $v['num'] . '*' . $v['price'] . ')]';
                        }
                    $csv_line.='"' . $delim;
                }

                // Статус заказа
                elseif ($cols_name == 'statusi') {
                    $csv_line.='"' . $PHPShopOrderStatusArray->getParam($row['statusi'] . '.name') . '"' . $delim;
                }

                // Статус пользователя
                elseif ($cols_name == 'status') {
                    $csv_line.='"' . $PHPShopUserStatusArray->getParam($row['status'] . '.name') . '"' . $delim;
                }

                // Сериализованное значение
                elseif (PHPShopString::is_serialized($row[$cols_name])) {
                    $csv_line.=serializeSelect($row[$cols_name], $cols_name);
                } else {

                    // Проверка старых заказов < 4.0
                    if ($cols_name == 'fio' and (empty($row['fio'])) and empty($row['tel'])) {
                        $orders = unserialize($row['orders']);
                        if (is_array($orders["Person"])) {
                            $row['fio'] = $orders["Person"]['name_person'] . ' ' . $orders["Person"]['mail'];
                            $row['tel'] = $orders["Person"]['tel_code'] . ' ' . $orders["Person"]['tel_name'];
                            $row['street'] = $orders["Person"]['adr_name'];
                            $row['org_name'] = $orders["Person"]['org_name'];
                            $row['org_inn'] = $orders["Person"]['org_inn'];
                            $row['org_kpp'] = $orders["Person"]['org_kpp'];
                            $row['user'] = $orders["Person"]['mail'];
                        }
                    }

                    $csv_line.='"' . PHPShopSecurity::CleanOut($row[$cols_name]) . '"' . $delim;
                }
            }

            $csv.=substr($csv_line, 0, (strlen($csv_line) - 1)) . "\n";
        }
    }


    $sorce = "./csv/export_" . $subpath[2] . "_" . date("d_m_y_His") . ".csv";
    PHPShopFile::write($sorce, $csv);

    if ($gz) {
        PHPShopFile::gzcompressfile($sorce);
        header("Location: " . $sorce . '.gz');
    }
    else
        header("Location: " . $sorce);
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm, $key_name, $subpath, $key_base, $key_stop;

    $PHPShopGUI->action_button['Экспорт'] = array(
        'name' => 'Экспорт',
        'action' => 'saveID',
        'class' => 'btn  btn-primary btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-open'
    );


    $sel_left = $sel_right = null;

    $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1));
    if (is_array($data))
        foreach ($data as $key => $val) {

            if (!empty($key_name[$key]))
                $name = $key_name[$key];
            else
                $name = $key;

            if (@in_array($key, $key_base))
                $sel_left.='<option value="' . $key . '" selected class="">' . ucfirst($name) . '</option>';
            elseif (!in_array($key, $key_stop))
                $sel_right.='<option value="' . $key . '" class="">' . ucfirst($name) . '</option>';
        }


    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./exchange/gui/exchange.gui.js');

    // Товары
    if (empty($subpath[2])) {
        $class = false;
        $select_action = ' товаров';
        $TitlePage.=$select_action;
        $select_path = 'catalog';
        $PHPShopGUI->_CODE = '<p></p><p class="text-muted">'.__('Ниже приведен список полей, которые могут быть экспортированы. Выделенные поля являются обязательными для последующей загрузки файла, остальные поля можно добавить или убрать из блока доступных полей по желанию.</p><p><kbd>Id</kbd> или <kbd>Артикул</kbd>').'</p>';
    }

    // Каталоги
    elseif ($subpath[2] == 'catalog') {

        $class = 'hide';
        $select_action = ' каталогов';
        $TitlePage.=$select_action;
        $PHPShopGUI->_CODE = '<p></p><p class="text-muted">'.__('Ниже приведен список полей, которые могут быть экспортированы. Выделенные поля являются обязательными для последующей загрузки файла, остальные поля можно добавить или убрать из блока доступных полей по желанию').'.</p><p><kbd>Id</kbd></p>';
    }

    // Пользователи
    elseif ($subpath[2] == 'user') {
        $class = 'hide';
        $select_path = 'shopusers';
        $select_action = ' пользователей';
        $TitlePage.=$select_action;
        $PHPShopGUI->_CODE = '<p></p><p class="text-muted">'.__('Ниже приведен список полей, которые могут быть экспортированы. Выделенные поля являются обязательными для последующей загрузки файла, остальные поля можно добавить или убрать из блока доступных полей по желанию').'.</p><p><kbd>Id</kbd> или <kbd>Логин</kbd></p>';
    }

    // Заказы
    elseif ($subpath[2] == 'order') {
        $class = 'hide';
        $select_path = $subpath[2];
        $PHPShopGUI->_CODE = '<p></p><p class="text-muted">'.__('Ниже приведен список полей, которые могут быть экспортированы. Выделенные поля являются обязательными для последующей загрузки файла, остальные поля можно добавить или убрать из блока доступных полей по желанию').'.</p><p><kbd>Id</kbd></p>';
    }


    $PHPShopGUI->_CODE.= '
    <table width="100%">
        <tr>
        <td class="text-center" width="48%"><label for="pattern_default">'.__('Экспортируемые поля').'</label></td>
        <td> </td>
        <td class="text-center"><label for="pattern_more">'.__('Доступные поля').'</label></td>
        </tr>
        <tr>
        <td>
        <select id="pattern_default" style="height:200px" name="pattern_cols[]" multiple class="form-control">
             ' . $sel_left . '                                 
        </select>
        </td>
        <td class="text-center"><a class="btn btn-default btn-sm" href="#" id="send-default" data-toggle="tooltip" data-placement="top" title="'.__('Добавить поле').'"><span class="glyphicon glyphicon-chevron-left"></span></a><br><br>
        <a class="btn btn-default btn-sm" id="send-more" href="#" data-toggle="tooltip" data-placement="top" title="'.__('Убрать поле').'"><span class="glyphicon glyphicon-chevron-right"></span></a><br><br>
<a class="btn btn-default btn-sm" id="send-all" href="#" data-toggle="tooltip" data-placement="top" title="'.__('Выбрать все поля').'"><span class="glyphicon glyphicon-backward"></span></a><br><br>
<a class="btn btn-default btn-sm" id="remove-all" href="#" data-toggle="tooltip" data-placement="top" title="'.__('Удалить все поля').'"><span class="glyphicon glyphicon-forward"></span></a></td>
        <td width="48%">
        <select id="pattern_more" style="height:200px" multiple class="form-control">
             ' . $sel_right . '                                    
        </select>
</td>
        </tr>
   </table>';

    $PHPShopGUI->setActionPanel($TitlePage, false, array('Экспорт'));

    $delim_value[] = array('Точка с запятой', ';', 'selected');
    $delim_value[] = array('Запятая', ',', '');

    $delim_sortvalue[] = array('#', '#', 'selected');
    $delim_sortvalue[] = array('@', '@', '');
    $delim_sortvalue[] = array('$', '$', '');
    $delim_sortvalue[] = array('|', '|', '');

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройки', $PHPShopGUI->setField('CSV-разделитель', $PHPShopGUI->setSelect('export_delim', $delim_value, 150,true)) .
            $PHPShopGUI->setField('Разделитель для характеристик', $PHPShopGUI->setSelect('export_sortdelim', $delim_sortvalue, 150), false, false, $class) .
            $PHPShopGUI->setField('GZIP сжатие', $PHPShopGUI->setCheckbox('export_gzip', 1, 'Включить', 0), 1, 'Сокращает размер создаваемого файла') .
            $PHPShopGUI->setField('Лимит строк', $PHPShopGUI->setInputText(null, 'export_limit', '0,10000', 150), 1, 'Запись c 1 по 10000')
    );

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.exchange.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.exchange.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    // Выбранные данные
    $select_action_path = $subpath[2];
    if (empty($select_action_path))
        $select_action_path = 'product';
    if (is_array($_SESSION['select'][$select_action_path])) {

        if (!empty($_GET['return']))
            $select_path = $_GET['return'];

        foreach ($_SESSION['select'][$select_action_path] as $val)
            $select_message = '<span class="label label-default">' . count($_SESSION['select'][$select_action_path]) . '</span> ' . $select_action . ' '.__('выбрано').'<hr><a href="?path=' . $select_path . '""><span class="glyphicon glyphicon-ok"></span> '.__('Изменить интервал').'</a><br><a href="#" class="text-danger select-remove"><span class="glyphicon glyphicon-remove"></span> '.__('Удалить диапазон').'</a>';
    }
    else
        $select_message = '<p class="text-muted">'.__('Вы можете выбрать конкретные объекты для экспорта, отметив их галочками и выбрав в меню <span class="glyphicon glyphicon-cog"></span><span class="caret"></span> <em>"Экспортировать выбранные"</em>. По умолчанию будут экспортированы все позиции').'. <a href="?path=' . $select_path . '"><span class="glyphicon glyphicon-share-alt"></span> '.__('Выбрать').'</a></p>';

    $sidebarleft[] = array('title' => 'Тип данных', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './exchange/'));

    if (!empty($select_path))
        $sidebarleft[] = array('title' => 'Подсказка', 'content' => $select_message, 'class' => 'hidden-xs');

    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // Футер
    $PHPShopGUI->Compile(2);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();
?>