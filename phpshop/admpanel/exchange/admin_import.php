<?php

$TitlePage = __("Импорт данных");

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
    'yml' => 'Яндекс.Маркет',
    'icon' => 'Иконка',
    'parent_to' => 'Родитель',
    'category' => 'Каталог',
    'title' => 'Заголовок',
    'login' => 'Логин',
    'tel' => 'Телефон',
    'cumulative_discount' => 'Накопительная скидка',
    'seller' => 'Статус загрузки в 1С',
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
    'data_adres' => 'Адрес',
    'descrip' => 'Meta description',
    'keywords' => 'Meta keywords',
    "prod_seo_name" => 'SEO ссылка',
    'num_row' => 'Товаров в длину',
    'num_cow' => 'Товаров на странице',
    'count' => 'Содержит товаров',
    'cat_seo_name' => 'SEO ссылка каталога',
    'sum' => 'Сумма'
);


// Стоп лист
$key_stop = array('password', 'wishlist', 'data_adres', 'sort', 'yml_bid_array', 'vendor', 'status', 'files', 'datas', 'price_search', 'vid', 'name_rambler', 'servers', 'skin', 'skin_enabled', 'secure_groups', 'icon_description');

switch ($subpath[2]) {
    case 'catalog':
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $key_base = array('id');
        break;
    case 'user':
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
        $key_base = array('id', 'login');
        array_push($key_stop, 'tel_code', 'adres', 'inn', 'kpp', 'company');
        break;
    case 'order':
        PHPShopObj::loadClass('order');
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $key_base = array('id', 'uid');
        $key_name['uid'] = '№ Заказа';
        $TitlePage.=' заказов';
        break;
    default: $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $key_base = array('id', 'uid');
        break;
}

function sort_encode($sort, $category) {
    global $PHPShopBase;

    $return = null;
    $delim = $_POST['export_sortdelim'];
    $sortsdelim = $_POST['export_sortsdelim'];
    $debug = false;
    if (!empty($sort)) {

        if (strstr($sort, $delim)) {
            $sort_array = explode($delim, $sort);
        }
        else
            $sort_array[] = $sort;

        if (is_array($sort_array))
            foreach ($sort_array as $sort_list) {

                if (strstr($sort_list, $sortsdelim)) {

                    $sort_list_array = explode($sortsdelim, $sort_list, 2);
                    $sort_name = $sort_list_array[0];
                    $sort_value = $sort_list_array[1];

                    // Получить ИД набора характеристик в каталоге
                    $PHPShopOrm = new PHPShopOrm();
                    $PHPShopOrm->debug = $debug;
                    $result_1 = $PHPShopOrm->query('select sort,name from ' . $GLOBALS['SysValue']['base']['categories'] . ' where id="' . $category . '"  limit 1',__FUNCTION__,__LINE__);
                    $row_1 = mysqli_fetch_array($result_1);

                    $cat_sort = unserialize($row_1['sort']);
                    
                    $cat_name = $row_1['name'];
                    
                    // Отсутствует в базе
                    if (is_array($cat_sort))
                        $where_in=' and a.id IN (' . @implode(",", $cat_sort) . ') ';
                    else $where_in=null;

                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
                    $PHPShopOrm->debug = $debug;

                    $result_2 = $PHPShopOrm->query('select a.id as parent, b.id from ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' AS a 
        JOIN ' . $GLOBALS['SysValue']['base']['sort'] . ' AS b ON a.id = b.category where a.name="' . $sort_name . '" and b.name="' . $sort_value . '" '.$where_in.' limit 1',__FUNCTION__,__LINE__);
                    $row_2 = mysqli_fetch_array($result_2);

                    // Присутствует в  базе
                    if (!empty($where_in) and isset($row_2['id'])) {
                        $return[$row_2['parent']][] = $row_2['id'];
                    }
                    // Отсутствует в базе
                    else {
                        
                        
                        // Проверка характеристики
                        if(!empty($where_in))
                        $sort_name_present = $PHPShopBase->getNumRows('sort_categories', 'as a where a.name="' . $sort_name . '" '.$where_in.' limit 1');

                        // Создаем новую характеристику
                        if (empty($sort_name_present) and !empty($category)) {

                            // Есть
                            if (!empty($cat_sort[0])) {
                                $PHPShopOrm = new PHPShopOrm();
                                $PHPShopOrm->debug = $debug;

                                $result_3 = $PHPShopOrm->query('select category from ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' where id="' . intval($cat_sort[0]) . '"  limit 1',__FUNCTION__,__LINE__);
                                $row_3 = mysqli_fetch_array($result_3);
                                $cat_set = $row_3['category'];
                            }
                            // Нет, создать новый набор
                            else {

                                // Создание набора характеристик
                                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
                                $PHPShopOrm->debug = $debug;
                                $cat_set = $PHPShopOrm->insert(array('name_new' => __('Для каталога').' ' . $cat_name, 'category_new' => 0),'_new',__FUNCTION__,__LINE__);
                            }


                            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
                            $PHPShopOrm->debug = $debug;
                            if ($parent = $PHPShopOrm->insert(array('name_new' => $sort_name, 'category_new' => $cat_set),'_new',__FUNCTION__,__LINE__)) {

                                // Создаем новое значение характеристики
                                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
                                $PHPShopOrm->debug = $debug;
                                $slave = $PHPShopOrm->insert(array('name_new' => $sort_value, 'category_new' => $parent),'_new',__FUNCTION__,__LINE__);

                                $return[$parent][] = $slave;
                                $cat_sort[] = $parent;

                                // Обновляем набор каталога товаров
                                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
                                $PHPShopOrm->debug = $debug;
                                $PHPShopOrm->update(array('sort_new' => serialize($cat_sort)), array('id' => '=' . $category),'_new',__FUNCTION__,__LINE__);
                            }
                        }
                        // Дописываем значение 
                        else {

                            // Получаем ИД существующей характеристики
                            $PHPShopOrm = new PHPShopOrm();
                            $PHPShopOrm->debug = $debug;
                            $result = $PHPShopOrm->query('select a.id  from ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' AS a where a.name="' . $sort_name . '" '.$where_in.' limit 1',__FUNCTION__,__LINE__);
                            if ($row = mysqli_fetch_array($result)) {
                                $parent = $row['id'];
                                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
                                $PHPShopOrm->debug = $debug;
                                $slave = $PHPShopOrm->insert(array('name_new' => $sort_value, 'category_new' => $parent),'_new',__FUNCTION__,__LINE__);

                                $return[$parent][] = $slave;
                            }
                        }
                    }
                }
            }
    }

    return $return;
}

// Обработка строки CSV
function csv_update($data) {
    global $PHPShopOrm, $PHPShopBase, $csv_load_option, $key_name, $csv_load_count, $subpath;

    if (is_array($data)) {

        $key_name_true = array_flip($key_name);

        // Имена полей
        if (empty($csv_load_option)) {
            $csv_load_option = $data;
        }
        // Значения
        else {

            // Простановка полей
            foreach ($csv_load_option as $k => $cols_name) {

                // base64
                if (substr($data[$k], 0, 7) == 'base64-') {

                    // Пользователи
                    if ($subpath[2] == 'user') {
                        $array = array();
                        $array['main'] = 0;
                        $array['list'][] = json_decode(base64_decode(substr($data[$k], 7, strlen($data[$k]) - 7)), true);
                        array_walk_recursive($array, 'array2iconv');

                        $data[$k] = serialize($array);
                    }
                }

                if (!empty($key_name_true[$cols_name]))
                    $row[$key_name_true[$cols_name]] = $data[$k];
                else
                    $row[$cols_name] = $data[$k];
            }

            // Характеристики
            if (!empty($row['vendor_array'])) {
                $row['vendor'] = null;
                $vendor_array = sort_encode($row['vendor_array'], $row['category']);

                if (is_array($vendor_array)) {
                    $row['vendor_array'] = serialize($vendor_array);
                    foreach ($vendor_array as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $p) {
                                $row['vendor'].="i" . $k . "-" . $p . "i";
                            }
                        }
                        else
                            $row['vendor'].="i" . $k . "-" . $v . "i";
                    }
                }
                else
                    $row['vendor_array'] = null;
            }

            // Полный путь к изображениями
            if (isset($_POST['export_imgpath'])) {
                if (!empty($row['pic_small']))
                    $row['pic_small'] = '/UserFiles/Image/' . $row['pic_small'];
                if (!empty($row['pic_big']))
                    $row['pic_big'] = '/UserFiles/Image/' . $row['pic_big'];
            }

            // Создание данных
            if ($_POST['export_action'] == 'insert') {

                $PHPShopOrm->debug = false;
                $PHPShopOrm->mysql_error = false;

                // Списывание со склада
                if (isset($row['items'])) {
                    switch ($GLOBALS['admoption_sklad_status']) {

                        case(3):
                            if ($row['items'] < 1) {
                                $row['sklad'] = 1;
                            } else {
                                $row['sklad'] = 0;
                            }
                            break;

                        case(2):
                            if ($row['items'] < 1) {
                                $row['enabled'] = 0;
                            } else {
                                $row['enabled'] = 1;
                            }
                            break;

                        default:
                            break;
                    }
                }

                // Дата создания
                $row['datas'] = time();

                // Проверка уникальности товаров
                if (empty($subpath[2]) and !empty($_POST['export_uniq']) and !empty($row['uid'])) {
                    $uniq = $PHPShopBase->getNumRows('products', "where uid = '" . $row['uid'] . "'");
                }
                else
                    $uniq = 0;

                if (empty($uniq))
                    if (is_numeric($PHPShopOrm->insert($row, ''))) {

                        $PHPShopOrm->clean();

                        // Счетчик
                        $csv_load_count++;
                    }
            }
            // Обновление данных
            else {
                // Обновление по ID
                if (isset($row['id'])) {
                    $where = array('id' => '="' . intval($row['id']) . '"');
                    unset($row['id']);
                }

                // Обновление по артикулу
                elseif (isset($row['uid'])) {
                    $where = array('uid' => '="' . $row['uid'] . '"');
                    unset($row['uid']);
                }

                // Обновление по логину
                elseif (isset($row['login'])) {
                    $where = array('login' => '="' . $row['login'] . '"');
                    unset($row['login']);
                }

                // Ошибка
                else {
                    unset($row);
                    return false;
                }

                // Списывание со склада
                if (isset($row['items'])) {
                    switch ($GLOBALS['admoption_sklad_status']) {

                        case(3):
                            if ($row['items'] < 1) {
                                $row['sklad'] = 1;
                            } else {
                                $row['sklad'] = 0;
                            }
                            break;

                        case(2):
                            if ($row['items'] < 1) {
                                $row['enabled'] = 0;
                            } else {
                                $row['enabled'] = 1;
                            }
                            break;

                        default:
                            break;
                    }
                }

                // Дата обновления
                $row['datas'] = time();

                if (!empty($where)) {
                    $PHPShopOrm->debug = false;
                    if ($PHPShopOrm->update($row, $where, '') === true) {

                        // Счетчик
                        $csv_load_count++;
                    }
                }
            }
        }
    }
}

// Функция обновления
function actionSave() {
    global $PHPShopGUI, $PHPShopSystem, $key_name, $key_name, $result_message, $csv_load_count;

    $delim = $_POST['export_delim'];

    // Настройка нулевого склада
    $GLOBALS['admoption_sklad_status'] = $PHPShopSystem->getSerilizeParam('admoption.sklad_status');

    // Память разделителей характеристик
    $memory = json_decode($_COOKIE['check_memory'], true);
    unset($memory[$_GET['path']]);
    $memory[$_GET['path']]['export_sortdelim'] = $_POST['export_sortdelim'];
    $memory[$_GET['path']]['export_sortsdelim'] = $_POST['export_sortsdelim'];

    if (is_array($memory))
        setcookie("check_memory", json_encode($memory), time() + 3600000, $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/admpanel/');


    // Копируем csv от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if ($_FILES['file']['ext'] == "csv") {
            if (move_uploaded_file($_FILES['file']['tmp_name'], "csv/" . $_FILES['file']['name'])) {
                $csv_file = "csv/" . $_FILES['file']['name'];
                $csv_file_name = $_FILES['file']['name'];
            }
            else
                $result_message = $PHPShopGUI->setAlert(__('Ошибка сохранения файла').' <strong>' . $csv_file_name . '</strong> в phpshop/admpanel/csv', 'danger');
        }
    }

    // Читаем csv из URL
    elseif (!empty($_POST['furl'])) {
        $csv_file = $_POST['furl'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }

    // Читаем csv из файлового менеджера
    elseif (!empty($_POST['lfile'])) {
        $csv_file = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $_POST['lfile'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }

    // Обработка csv
    if (!empty($csv_file)) {

        PHPShopObj::loadClass('file');
        PHPShopFile::readCsv($csv_file, 'csv_update', $delim);

        if (empty($csv_load_count))
            $result_message = $PHPShopGUI->setAlert(__('Файл').' <strong>' . $csv_file_name . '</strong> '.__('загружен. Обработано').' <strong>' . intval($csv_load_count) . '</strong> '.__('строк. Не найден ключ обновления <kbd>Id</kbd> или <kbd>Артикул</kbd>'), 'warning');
        else
            $result_message = $PHPShopGUI->setAlert(__('Файл').' <strong>' . $csv_file_name . '</strong> '.__('загружен. Обработано').' <strong>' . intval($csv_load_count) . '</strong> '.__('строк.'));
    }
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm, $key_name, $subpath, $key_base, $key_stop, $result_message;

    $PHPShopGUI->action_button['Импорт'] = array(
        'name' => 'Выполнить',
        'action' => 'saveID',
        'class' => 'btn btn-primary btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-save'
    );

    $list = null;
    $PHPShopOrm->clean();
    $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1));
    if (is_array($data)) {
        foreach ($data as $key => $val) {

            if (!empty($key_name[$key]))
                $name = $key_name[$key];
            else
                $name = $key;

            if (@in_array($key, $key_base)) {
                if ($key == 'id')
                    $kbd_class = 'enabled';
                else
                    $kbd_class = null;

                $list.='<div class="pull-left" style="width:200px;"><kbd class="' . $kbd_class . '">' . ucfirst($name) . '</kbd></div>';
            }
            elseif (!in_array($key, $key_stop))
                $list.='<div class="pull-left" style="width:200px">' . ucfirst($name) . '</div>';
        }
    }
    else
        $list = '<span class="text-warning hidden-xs">'.__('Недостаточно данных для создания карты полей. Создайте одну запись в нужном разделе в ручном режиме для начала работы').'.</span>';

    // Размер названия поля
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./exchange/gui/exchange.gui.js');
    $PHPShopGUI->_CODE = $result_message;

    // Товары
    if (empty($subpath[2])) {
        $class = false;
        $TitlePage.=' товаров';
    }

    // Каталоги
    elseif ($subpath[2] == 'catalog') {
        $class = 'hide';
        $TitlePage.=' каталогов';
    }

    // Пользователи
    elseif ($subpath[2] == 'user') {
        $class = 'hide';
        $TitlePage.=' пользователей';
    }

    // Пользователи
    elseif ($subpath[2] == 'order') {
        $class = 'hide';
    }

    $PHPShopGUI->_CODE.= '<p class="text-muted hidden-xs">'.__('Ниже приведен список полей, которые может содержать ваш файл. Одно из выделенных полей являются обязательными. Если вы импортируете данные, содержащие специальные символы (запятые, точки с запятыми и т.д.), соответствующие поля должны быть заключены в кавычки').'.</p>';
    $PHPShopGUI->_CODE.= '<div class="panel panel-default"><div class="panel-body">' . $list . '</div></div>';
    $PHPShopGUI->setActionPanel($TitlePage, false, array('Импорт'));

    // Память полей
    if (!empty($_POST['export_sortdelim']))
        $export_sortdelim = $_POST['export_sortdelim'];
    else
        $export_sortdelim = '#';

    if (!empty($_POST['export_sortsdelim']))
        $export_sortdelim = $_POST['export_sortsdelim'];
    else
        $export_sortsdelim = '/';

    if (!empty($_COOKIE['check_memory'])) {
        $memory = json_decode($_COOKIE['check_memory'], true);
        $export_sortdelim = $memory[$_GET['path']]['export_sortdelim'];
        $export_sortsdelim = $memory[$_GET['path']]['export_sortsdelim'];
    }

    $delim_value[] = array('Точка с запятой', ';', 'selected');
    $delim_value[] = array('Запятая', ',', '');

    $action_value[] = array('Обновление', 'update', 'selected');
    $action_value[] = array('Создание', 'insert', '');

    $delim_sortvalue[] = array('#', '#', $export_sortdelim);
    $delim_sortvalue[] = array('@', '@', $export_sortdelim);
    $delim_sortvalue[] = array('$', '$', $export_sortdelim);
    $delim_sortvalue[] = array('|', '|', $export_sortdelim);

    $delim_sort[] = array('/', '/', $export_sortsdelim);
    $delim_sort[] = array('\\', '\\', $export_sortsdelim);
    $delim_sort[] = array('-', '-', $export_sortsdelim);
    $delim_sort[] = array('&', '&', $export_sortsdelim);

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройки', $PHPShopGUI->setField('Действие', $PHPShopGUI->setSelect('export_action', $action_value, 150,true)) .
            $PHPShopGUI->setField('CSV-разделитель', $PHPShopGUI->setSelect('export_delim', $delim_value, 150,true)) .
            $PHPShopGUI->setField('Разделитель характеристик', $PHPShopGUI->setSelect('export_sortdelim', $delim_sortvalue, 150), false, false, $class) .
            $PHPShopGUI->setField('Разделитель значений характеристик', $PHPShopGUI->setSelect('export_sortsdelim', $delim_sort, 150), false, false, $class) .
            $PHPShopGUI->setField('Полный путь для изображений', $PHPShopGUI->setCheckbox('export_imgpath', 1, 'Включить', 0), 1, 'Добавляет к изображениям папку /UserFiles/Image/') .
            $PHPShopGUI->setField('Проверка уникальности', $PHPShopGUI->setCheckbox('export_uniq', 1, 'Включить', 0, 'disabled'), 1, 'Исключает дублирование данных при создании') .
            $PHPShopGUI->setField("Файл", $PHPShopGUI->setFile())
    );

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.exchange.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.exchange.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $help = '<p class="text-muted data-row">'.__('Для экспорта файла нужно скачать <a href="?path=exchange.export"><span class="glyphicon glyphicon-share-alt"></span>Пример файла</a>, выбрав нужные вам поля. Далее давьте/измените нужную информацию, не нарушая структуру и выберите меню <em>"Импорт данных"</em>').'.</p>';

    $sidebarleft[] = array('title' => 'Тип данных', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './exchange/'));
    $sidebarleft[] = array('title' => 'Подсказка', 'content' => $help, 'class' => 'hidden-xs');

    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // Футер
    $PHPShopGUI->Compile(2);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();
?>