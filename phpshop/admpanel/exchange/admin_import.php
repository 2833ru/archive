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
    'descrip' => 'Meta description',
    'keywords' => 'Meta keywords',
    "prod_seo_name" => 'SEO ссылка',
    'num_row' => 'Товаров в длину',
    'num_cow' => 'Товаров на странице',
    'count' => 'Содержит товаров',
    'cat_seo_name' => 'SEO ссылка каталога',
    'sum' => 'Сумма',
    'servers' => 'Витрины',
    'items1' => 'Склад 2',
    'items2' => 'Склад 3',
    'items3' => 'Склад 4',
    'items4' => 'Склад 5',
    'vendor' => '@Характеристика',
    'data_adres' => 'Адрес',
    'color' => 'Код цвета',
    'parent2' => 'Цвет',
    'rate' => 'Рейтинг',
    'productday' => 'Товар дня',
    'hit' => 'Хит',
    'sendmail' => 'Подписка на рассылку',
    'statusi' => 'Статус заказа',
    'country' => 'Страна',
    'state' => 'Область',
    'index' => 'Индекс',
    'house' => 'Дом',
    'porch' => 'Подъезд',
    'door_phone' => 'Домофон',
    'flat' => 'Квартира',
    'delivtime' => 'Время доставки',
    'org_name' => 'Организация',
    'org_inn' => 'ИНН',
    'org_kpp' => 'КПП',
    'org_yur_adres' => 'Юридический адрес',
    'dop_info' => 'Комментарий пользоватея',
    'tracking' => 'Код отслеживания',
    'path' => 'Путь каталога',
    'length' => 'Длина',
    'width' => 'Ширина',
    'height' => 'Высота',
    'moysklad_product_id' => 'МойСклад Id',
    'bonus' => 'Бонус'
);

if ($GLOBALS['PHPShopBase']->codBase == 'utf-8')
    unset($key_name);

// Стоп лист
$key_stop = array('password', 'wishlist', 'sort', 'yml_bid_array', 'status', 'files', 'datas', 'price_search', 'vid', 'name_rambler', 'servers', 'skin', 'skin_enabled', 'secure_groups', 'icon_description', 'title_enabled', 'title_shablon', 'descrip_shablon', 'descrip_enabled', 'productsgroup_check', 'productsgroup_product', 'keywords_enabled', 'keywords_shablon', 'rate_count', 'sort_cache', 'sort_cache_created_at', 'parent_title', 'menu', 'order_by', 'order_to', 'org_ras', 'org_bank', 'org_kor', 'org_bik', 'org_city', 'admin', 'org_fakt_adres');

if(empty($subpath[2]))
    $subpath[2]=null;

switch ($subpath[2]) {
    case 'catalog':
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $key_base = array('id');
        break;
    case 'user':
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
        $key_base = array('id', 'login');
        array_push($key_stop, 'tel_code', 'adres', 'inn', 'kpp', 'company', 'mail', 'token', 'token_time');
        break;
    case 'order':
        PHPShopObj::loadClass('order');
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $key_base = array('id', 'uid');
        array_push($key_stop, 'orders', 'user');
        $key_name['uid'] = __('№ Заказа');
        $TitlePage .= ' ' . __('заказов');
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
        } else
            $sort_array[] = $sort;

        if (is_array($sort_array))
            foreach ($sort_array as $sort_list) {

                if (strstr($sort_list, $sortsdelim)) {

                    $sort_list_array = explode($sortsdelim, $sort_list, 2);
                    $sort_name = PHPShopSecurity::TotalClean($sort_list_array[0]);
                    $sort_value = PHPShopSecurity::TotalClean($sort_list_array[1]);

                    // Получить ИД набора характеристик в каталоге
                    $PHPShopOrm = new PHPShopOrm();
                    $PHPShopOrm->debug = $debug;
                    $result_1 = $PHPShopOrm->query('select sort,name from ' . $GLOBALS['SysValue']['base']['categories'] . ' where id="' . $category . '"  limit 1', __FUNCTION__, __LINE__);
                    $row_1 = mysqli_fetch_array($result_1);

                    $cat_sort = unserialize($row_1['sort']);

                    $cat_name = $row_1['name'];

                    // Отсутствует в базе
                    if (is_array($cat_sort))
                        $where_in = ' and a.id IN (' . @implode(",", $cat_sort) . ') ';
                    else
                        $where_in = null;

                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
                    $PHPShopOrm->debug = $debug;

                    $result_2 = $PHPShopOrm->query('select a.id as parent, b.id from ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' AS a 
        JOIN ' . $GLOBALS['SysValue']['base']['sort'] . ' AS b ON a.id = b.category where a.name="' . $sort_name . '" and b.name="' . $sort_value . '" ' . $where_in . ' limit 1', __FUNCTION__, __LINE__);
                    $row_2 = mysqli_fetch_array($result_2);

                    // Присутствует в  базе
                    if (!empty($where_in) and isset($row_2['id'])) {
                        $return[$row_2['parent']][] = $row_2['id'];
                    }
                    // Отсутствует в базе
                    else {

                        // Проверка характеристики
                        if (!empty($where_in))
                            $sort_name_present = $PHPShopBase->getNumRows('sort_categories', 'as a where a.name="' . $sort_name . '" ' . $where_in . ' limit 1');

                        // Создаем новую характеристику
                        if (empty($sort_name_present) and ! empty($category)) {

                            // Есть
                            if (!empty($cat_sort[0])) {
                                $PHPShopOrm = new PHPShopOrm();
                                $PHPShopOrm->debug = $debug;

                                $result_3 = $PHPShopOrm->query('select category from ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' where id="' . intval($cat_sort[0]) . '"  limit 1', __FUNCTION__, __LINE__);
                                $row_3 = mysqli_fetch_array($result_3);
                                $cat_set = $row_3['category'];
                            }
                            // Нет, создать новый набор
                            else {

                                // Создание набора характеристик
                                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
                                $PHPShopOrm->debug = $debug;
                                $cat_set = $PHPShopOrm->insert(array('name_new' => __('Для каталога') . ' ' . $cat_name, 'category_new' => 0), '_new', __FUNCTION__, __LINE__);
                            }

                            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
                            $PHPShopOrm->debug = $debug;

                            if (!empty($sort_name))
                                if ($parent = $PHPShopOrm->insert(array('name_new' => $sort_name, 'category_new' => $cat_set), '_new', __FUNCTION__, __LINE__)) {

                                    // Создаем новое значение характеристики
                                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
                                    $PHPShopOrm->debug = $debug;
                                    $slave = $PHPShopOrm->insert(array('name_new' => $sort_value, 'category_new' => $parent), '_new', __FUNCTION__, __LINE__);

                                    $return[$parent][] = $slave;
                                    $cat_sort[] = $parent;

                                    // Обновляем набор каталога товаров
                                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
                                    $PHPShopOrm->debug = $debug;
                                    $PHPShopOrm->update(array('sort_new' => serialize($cat_sort)), array('id' => '=' . $category), '_new', __FUNCTION__, __LINE__);
                                }
                        }
                        // Дописываем значение 
                        elseif(!empty($sort_value)) {

                            // Получаем ИД существующей характеристики
                            $PHPShopOrm = new PHPShopOrm();
                            $PHPShopOrm->debug = $debug;
                            $result = $PHPShopOrm->query('select a.id  from ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' AS a where a.name="' . $sort_name . '" ' . $where_in . ' limit 1', __FUNCTION__, __LINE__);
                            if ($row = mysqli_fetch_array($result)) {
                                $parent = $row['id'];
                                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
                                $PHPShopOrm->debug = $debug;
                                $slave = $PHPShopOrm->insert(array('name_new' => $sort_value, 'category_new' => $parent), '_new', __FUNCTION__, __LINE__);

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
    global $PHPShopOrm, $PHPShopBase, $csv_load_option, $key_name, $csv_load_count, $subpath, $PHPShopSystem, $csv_load, $csv_load_totale;

    // Кодировка UTF-8
    if ($_POST['export_code'] == 'utf' and is_array($data)) {
        foreach ($data as $k => $v)
            $data[$k] = PHPShopString::utf8_win1251($v);
    }

    require_once $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/lib/thumb/phpthumb.php';
    $width_kratko = $PHPShopSystem->getSerilizeParam('admoption.width_kratko');
    $img_tw = $PHPShopSystem->getSerilizeParam('admoption.img_tw');
    $img_th = $PHPShopSystem->getSerilizeParam('admoption.img_th');

    if (is_array($data)) {

        $key_name_true = array_flip($key_name);

        // Имена полей
        if (empty($csv_load_option)) {
            $select = false;

            // Сопоставление полей
            if (is_array($_POST['select_action'])) {

                foreach ($_POST['select_action'] as $k => $name) {

                    if (!empty($name))
                        $select = true;

                    if (substr($name, 0, 1) == '@')
                        $_POST['select_action'][$k] = '@' . $data[$k];
                }
            }

            if ($select)
                $csv_load_option = $_POST['select_action'];
            else
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

                // Поля кириллические
                if (!empty($key_name_true[$cols_name])) {
                    $row[$key_name_true[$cols_name]] = $data[$k];
                }
                // Поля характеристики в колонках
                elseif (substr($cols_name, 0, 1) == '@') {
                    $row[$cols_name] = $data[$k];
                    $sort_name = substr($cols_name, 1, (strlen($cols_name) - 1));

                    // Несколько значений
                    if (strstr($data[$k], ',')) {
                        $sort_array = explode(',', $data[$k]);
                    } else
                        $sort_array[] = $data[$k];

                    if (is_array($sort_array)) {
                        foreach ($sort_array as $v)
                            $row['vendor_array'] .= $sort_name . $_POST['export_sortsdelim'] . $v . $_POST['export_sortdelim'];
                    }

                    unset($row[$cols_name]);
                    unset($sort_array);
                }
                // Остальные
                else
                    $row[strtolower($cols_name)] = $data[$k];
            }

            // Телефон пользователя
            if (!empty($row['data_adres'])) {

                $row['enabled'] = 1;

                $tel['main'] = 0;
                $tel['list'][0]['tel_new'] = $row['data_adres'];
                $row['data_adres'] = serialize($tel);
            }

            // Путь каталога
            if (isset($row['path'])) {
                if (empty($row['category'])) {
                    $search = $row['path'];
                    $category = new PHPShopCategory(0);
                    $category->getChildrenCategories(100, ['id', 'parent_to', 'name'], false, $search);

                    while (count($category->search) != $category->found) {
                        $PHPShopOrmCat = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
                        $PHPShopOrmCat->debug = false;
                        $category->search_id = $PHPShopOrmCat->insert(array('name_new' => $category->search[$category->found], 'parent_to_new' => $category->search_id));
                        $category->found++;
                    }

                    $row['category'] = $category->search_id;
                }
            }

            // Коррекция флага подтипа
            if (isset($row['parent']) and $row['parent'] == '')
                unset($row['parent']);

            // Характеристики
            if (!empty($row['vendor_array'])) {
                $row['vendor'] = null;
                $vendor_array = sort_encode($row['vendor_array'], $row['category']);

                if (is_array($vendor_array)) {
                    $row['vendor_array'] = serialize($vendor_array);
                    foreach ($vendor_array as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $p) {
                                $row['vendor'] .= "i" . $k . "-" . $p . "i";
                            }
                        } else
                            $row['vendor'] .= "i" . $k . "-" . $v . "i";
                    }
                } else
                    $row['vendor_array'] = null;
            }

            // Полный путь к изображениями
            if (!empty($_POST['export_imgpath']) and isset($_POST['export_imgpath'])) {
                if (!empty($row['pic_small']))
                    $row['pic_small'] = '/UserFiles/Image/' . $row['pic_small'];
            }

            // Дополнительные изображения
            if (!empty($_POST['export_imgdelim']) and strstr($row['pic_big'], $_POST['export_imgdelim'])) {
                $data_img = explode($_POST['export_imgdelim'], $row['pic_big']);
            } elseif(!empty($row['pic_big']))
                $data_img[] = $row['pic_big'];

            if (!empty($data_img) and is_array($data_img)) {

                // Получение ID товара по артикулу
                if (empty($row['id']) and ! empty($row['uid'])) {
                    $PHPShopOrmProd = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $data_prod = $PHPShopOrmProd->getOne(array('id'), array('uid' => '="' . $row['uid'] . '"'));
                    $row['id'] = $data_prod['id'];
                }

                foreach ($data_img as $k => $img) {
                    if (!empty($img)) {

                        // Главное изображение
                        if ($k == 0) {
                            if (isset($_POST['export_imgpath']) and ! empty($img))
                                $row['pic_big'] = '/UserFiles/Image/' . $img;
                            elseif (!empty($img))
                                $row['pic_big'] = $img;
                        }

                        // Полный путь к изображениям
                        if (isset($_POST['export_imgpath']))
                            $img = '/UserFiles/Image/' . $img;

                        // Проверка существования изображения
                        $PHPShopOrmImg = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
                        $PHPShopOrmImg->debug = false;
                        $check = $PHPShopOrmImg->select(array('name'), array('name' => '="' . $img . '"', 'parent' => '=' . intval($row['id'])), false, array('limit' => 1));

                        // Создаем новую
                        if (!is_array($check)) {

                            // Запись в фотогалерее
                            $PHPShopOrmImg->insert(array('parent_new' => intval($row['id']), 'name_new' => $img, 'num_new' => $k));

                            $file = $_SERVER['DOCUMENT_ROOT'] . $img;
                            $name = str_replace(array(".png", ".jpg", ".jpeg", ".gif"), array("s.png", "s.jpg", "s.jpeg", "s.gif"), $file);

                            if (!file_exists($name) and file_exists($file)) {

                                // Генерация тубнейла 
                                if (isset($_POST['export_imgproc'])) {
                                    $thumb = new PHPThumb($file);
                                    $thumb->setOptions(array('jpegQuality' => $width_kratko));
                                    $thumb->resize($img_tw, $img_th);
                                    $thumb->save($name);
                                } else
                                    copy($file, $name);
                            }
                        }
                    }
                }
            }
            // Полный путь к изображениями
            else if (isset($_POST['export_imgpath']) and ! empty($row['pic_big']))
                $row['pic_big'] = '/UserFiles/Image/' . $row['pic_big'];

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
                if (empty($subpath[2]) and ! empty($_POST['export_uniq']) and ! empty($row['uid'])) {
                    $uniq = $PHPShopBase->getNumRows('products', "where uid = '" . $row['uid'] . "'");
                } else
                    $uniq = 0;

                // Проверки пустого имени
                if (isset($row['name']) and empty($row['name']))
                    $uniq = true;

                if (empty($uniq)) {

                    if (isset($row['price'])) {
                        $row['price'] = str_replace(',', '.', $row['price']);
                    }
                    if (isset($row['price_n'])) {
                        $row['price_n'] = str_replace(',', '.', $row['price_n']);
                    }
                    if (isset($row['price2'])) {
                        $row['price2'] = str_replace(',', '.', $row['price2']);
                    }
                    if (isset($row['price3'])) {
                        $row['price3'] = str_replace(',', '.', $row['price3']);
                    }
                    if (isset($row['price4'])) {
                        $row['price4'] = str_replace(',', '.', $row['price4']);
                    }
                    if (isset($row['price5'])) {
                        $row['price5'] = str_replace(',', '.', $row['price5']);
                    }

                    $insertID = $PHPShopOrm->insert($row, '');
                    if (is_numeric($insertID)) {

                        $PHPShopOrm->clean();

                        // Обновляем ID в фотогалереи нового товара
                        if ($PHPShopOrmImg)
                            $PHPShopOrmImg->update(array('parent_new' => $insertID), array('parent' => '=0'));

                        // Счетчик
                        $csv_load_count++;
                    }
                }
            }
            // Обновление данных
            else {

                // Настраиваемый ключ
                if (!empty($_POST['export_key'])) {
                    $where = array($_POST['export_key'] => '="' . $row[$_POST['export_key']] . '"');
                    unset($row[$_POST['export_key']]);
                } else {

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

                        // Обновляем ID в фотогалереи товара по артикулу
                        if (!empty($where['uid']) and is_array($data_img) and $PHPShopOrmImg) {

                            $PHPShopOrmProduct = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                            $data_product = $PHPShopOrmProduct->select(array('id'), array('uid' => $where['uid']), false, array('limit' => 1));
                            $PHPShopOrmImg->update(array('parent_new' => $data_product['id']), array('parent' => '=0'));
                        }

                        // Счетчик
                        $count = $PHPShopOrm->get_affected_rows();

                        $csv_load_count += $count;
                        $csv_load_totale++;

                        // Отчет
                        if (!empty($count))
                            $csv_load[] = $row;
                    }
                }
            }
        }
    }
}

// Функция обновления
function actionSave() {
    global $PHPShopGUI, $PHPShopSystem, $key_name, $key_name, $result_message, $csv_load_count, $subpath, $csv_load, $csv_load_totale;


    // Выбрать настройку
    if ($_POST['exchanges'] != 'new') {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);

        // Изменить имя настройки
        if (!empty($_POST['exchanges_new'])) {
            $PHPShopOrm->update(array('name_new' => $_POST['exchanges_new']), array('id' => '=' . intval($_POST['exchanges'])));
        }

        $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['exchanges'])), false, array("limit" => 1));
        if (is_array($data)) {
            unset($_POST);
            $_POST = unserialize($data['option']);
            $exchanges_name = $data['name'];
            unset($_POST['exchanges_new']);
        }
    }

    // Удалить настройки
    if (!empty($_POST['exchanges_remove']) and is_array($_POST['exchanges_remove'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
        foreach ($_POST['exchanges_remove'] as $v)
            $data = $PHPShopOrm->delete(array('id' => '=' . intval($v)));
    }

    // Раздел из памяти настроек
    if (!empty($_POST['subpath']))
        $subpath[2] = $_POST['subpath'];

    switch ($subpath[2]) {
        case 'catalog':
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
            break;
        case 'user':
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
            break;
        case 'order':
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
            break;
        default: $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
            break;
    }

    $delim = $_POST['export_delim'];

    // Настройка нулевого склада
    $GLOBALS['admoption_sklad_status'] = $PHPShopSystem->getSerilizeParam('admoption.sklad_status');

    // Память разделителей характеристик
    $memory = json_decode($_COOKIE['check_memory'], true);
    unset($memory[$_GET['path']]);
    $memory[$_GET['path']]['export_sortdelim'] = @$_POST['export_sortdelim'];
    $memory[$_GET['path']]['export_sortsdelim'] = @$_POST['export_sortsdelim'];
    $memory[$_GET['path']]['export_imgdelim'] = @$_POST['export_imgdelim'];
    $memory[$_GET['path']]['export_imgpath'] = @$_POST['export_imgpath'];
    $memory[$_GET['path']]['export_uniq'] = @$_POST['export_uniq'];
    $memory[$_GET['path']]['export_action'] = @$_POST['export_action'];
    $memory[$_GET['path']]['export_delim'] = @$_POST['export_delim'];
    $memory[$_GET['path']]['export_imgproc'] = @$_POST['export_imgproc'];

    if (is_array($memory))
        setcookie("check_memory", json_encode($memory), time() + 3600000, $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/admpanel/');

    // Копируем csv от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if ($_FILES['file']['ext'] == "csv") {
            if (@move_uploaded_file($_FILES['file']['tmp_name'], "csv/" . $_FILES['file']['name'])) {
                $csv_file = "csv/" . $_FILES['file']['name'];
                $csv_file_name = $_FILES['file']['name'];
            } else
                $result_message = $PHPShopGUI->setAlert(__('Ошибка сохранения файла') . ' <strong>' . $csv_file_name . '</strong> в phpshop/admpanel/csv', 'danger');
        }
    }

    // Читаем csv из URL
    elseif (!empty($_POST['furl'])) {

        // Google
        $path = parse_url($_POST['furl']);
        if ($path['host'] == 'docs.google.com') {
            $a_path = explode("/", $path['path']);
            if (is_array($a_path)) {
                $id = $a_path[3];

                if ($id == 'e') {
                    $id = $a_path[4];
                    $csv_file = $_POST['furl'];
                } else
                    $csv_file = 'https://docs.google.com/spreadsheets/d/' . $id . '/export?format=csv&' . $path['fragment'];

                $csv_file_name = 'Google Таблиц ' . $_POST['exchanges_new'] . $exchanges_name;
                $_POST['export_code'] = 'utf';
                $delim = ',';
            }
        }
        // Url
        else {
            $csv_file = $_POST['furl'];
            $path_parts = pathinfo($csv_file);
            $csv_file_name = $path_parts['basename'];
        }
    }

    // Читаем csv из файлового менеджера
    elseif (!empty($_POST['lfile'])) {
        $csv_file = $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $_POST['lfile'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }
    // Автоматизация
    elseif (!empty($_POST['csv_file'])) {
        $csv_file = $_POST['csv_file'];
        $path_parts = pathinfo($csv_file);
        $csv_file_name = $path_parts['basename'];
    }


    // Обработка csv
    if (!empty($csv_file)) {
        PHPShopObj::loadClass('file');

        // Автоматизация
        if (!empty($_POST['bot'])) {

            $limit = intval($_POST['line_limit']);

            if (empty($_POST['end']))
                $_POST['end'] = intval($_POST['line_limit']);

            $end = $_POST['end'];

            if (isset($_POST['total']) and $_POST['end'] > $_POST['total'])
                $end = $_POST['total'];

            if (empty($_POST['start']))
                $_POST['start'] = 0;

            $result = PHPShopFile::readCsvGenerators($csv_file, 'csv_update', $delim, array($_POST['start'], $_POST['end']));
            if ($result) {

                // Строк в файле
                if (empty($_POST['total'])) {
                    $total = 0;
                    $f = fopen($csv_file, 'r');
                    while (!feof($f)) {
                        $total ++;
                        fgets($f);
                    }
                    fclose($f);
                } else
                    $total = $_POST['total'];

                $bar = round($_POST['line_limit'] * 100 / $total);

                // Конец
                if ($end > $total) {
                    $end = $total;
                    $bar = 100;
                    $bar_class = null;
                } else {
                    $bar_class = "active";
                }


                $total_min = round(floatval((($total - $csv_load_count) / $_POST['line_limit']) * $_POST['time_limit']), 1);
                $action = true;
                $result_message = $PHPShopGUI->setAlert('<div id="bot_result">' . __('Файл') . ' <strong>' . $csv_file_name . '</strong> ' . __('загружен. Обработано ') . $end . __(' из ') . $total . __(' строк. Изменено') . ' <b id="total-update">' . intval($csv_load_count + 1) . '</b> ' . __('записей. Интервал ') . $_POST['time_limit'] . __(' мин. Осталось') . ' <b id="total-min">' . $total_min . '</b> ' . __('мин') . '.</div>
<div class="progress bot-progress">
  <div class="progress-bar progress-bar-striped  progress-bar-success ' . $bar_class . '" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: ' . $bar . '%"> ' . $bar . '% 
  </div>
</div>');
                $json_message = __('Файл') . ' <strong>' . $csv_file_name . '</strong> ' . __('загружен. Обработано ') . $end . __(' из ') . $total . __(' строк. Изменено') . ' <b id="total-update">' . intval($csv_load_count) . '</b> ' . __('записей. Интервал ') . $_POST['time_limit'] . __(' мин. Осталось') . ' <b id="total-min">' . $total_min . '</b> ' . __('мин') . '.';
                $result_message .= $PHPShopGUI->setInput("hidden", "csv_file", $csv_file);
                $result_message .= $PHPShopGUI->setInput("hidden", "total", $total);
            } else
                $result_message = $PHPShopGUI->setAlert(__('Нет прав на запись файла') . ' ' . $csv_file, 'danger');
        }
        else {

            $result = PHPShopFile::readCsv($csv_file, 'csv_update', $delim);

            if ($result) {

                if (empty($csv_load_count))
                    $result_message = $PHPShopGUI->setAlert(__('Файл') . ' <strong>' . $csv_file_name . '</strong> ' . __('загружен. Обработано ' . $csv_load_totale . ' строк. Изменено') . ' <strong>' . intval($csv_load_count) . '</strong> ' . __('записей') . '.', 'warning');
                else {

                    // Файл результа
                    $result_csv = './csv/result_' . date("d_m_y_His") . '.csv';
                    PHPShopFile::writeCsv($result_csv, $csv_load);

                    $result_message = $PHPShopGUI->setAlert(__('Файл') . ' <strong>' . $csv_file_name . '</strong> ' . __('загружен. Обработано ' . $csv_load_totale . ' строк. Изменено') . ' <strong>' . intval($csv_load_count) . '</strong> ' . __('записей') . '. ' . __('Отчет по обновленным позициям ') . ' <a href="' . $result_csv . '" target="_blank">CSV</a>.');
                }
            } else
                $result_message = $PHPShopGUI->setAlert(__('Нет прав на запись файла') . ' ' . $csv_file, 'danger');
        }
    }

    // Сохранение настройки
    if ($_POST['exchanges'] == 'new' and ! empty($_POST['exchanges_new'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
        $PHPShopOrm->insert(array('name_new' => $_POST['exchanges_new'], 'option_new' => serialize($_POST), 'type_new' => 'import'));
    }

    // Автоматизация
    if (!empty($_POST['ajax'])) {

        if ($total > $end) {

            $bar = round($_POST['end'] * 100 / $total);

            return array("success" => $action, "bar" => $bar, "count" => $csv_load_count, "result" => PHPShopString::win_utf8($json_message), 'limit' => $limit);
        } else
            return array("success" => 'done', "count" => $csv_load_count, "result" => PHPShopString::win_utf8($json_message), 'limit' => $limit);
    }
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm, $key_name, $subpath, $key_base, $key_stop, $result_message;

    $PHPShopGUI->action_button['Импорт'] = array(
        'name' => __('Выполнить'),
        'action' => 'saveID',
        'class' => 'btn btn-primary btn-sm navbar-btn',
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-save'
    );

    $list = null;
    $PHPShopOrm->clean();
    $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1));
    $select_value[] = array('Не выбрано', false, false);

    if (is_array($data)) {

        // Путь каталога
        if (empty($subpath[2])) {
            $data['path'] = null;
        }

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

                $list .= '<div class="pull-left" style="width:190px;min-height: 19px;"><kbd class="' . $kbd_class . '">' . ucfirst($name) . '</kbd></div>';
                $help = 'data-subtext="<span class=\'glyphicon glyphicon-flag text-success\'></span>"';
            }
            elseif (!in_array($key, $key_stop)) {
                $list .= '<div class="pull-left" style="width:190px;min-height: 19px;">' . ucfirst($name) . '</div>';
                $help = null;
            }

            if (!in_array($key, $key_stop)) {
                $select_value[] = array(ucfirst($name), ucfirst($name), false, $help);

                // Ключ обнвления
                if ($key != 'id' and $key != 'uid' and $key != 'vendor' and $key != 'vendor_array')
                    $key_value[] = array(ucfirst($name), $key, false);
            }
        }
    } else
        $list = '<span class="text-warning hidden-xs">' . __('Недостаточно данных для создания карты полей. Создайте одну запись в нужном разделе в ручном режиме для начала работы') . '.</span>';

    // Размер названия поля
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./exchange/gui/exchange.gui.js');
    $PHPShopGUI->_CODE = $result_message;

    // Товары
    if (empty($subpath[2])) {
        $class = false;
        $TitlePage .= ' ' . __('товаров');
        $data['path'] = null;
    }

    // Каталоги
    elseif ($subpath[2] == 'catalog') {
        $class = 'hide';
        $TitlePage .= ' ' . __('каталогов');
    }

    // Пользователи
    elseif ($subpath[2] == 'user') {
        $class = 'hide';
        $TitlePage .= ' ' . __('пользователей');
    }

    // Пользователи
    elseif ($subpath[2] == 'order') {
        $class = 'hide';
    }

    $PHPShopGUI->_CODE .= '<p class="text-muted hidden-xs">' . __('Ниже приведен список полей, которые может содержать ваш файл. Одно из выделенных полей являются обязательными. Если вы импортируете данные, содержащие специальные символы (запятые, точки с запятыми и т.д.), соответствующие поля должны быть заключены в кавычки') . '.</p>';
    $PHPShopGUI->_CODE .= '<div class="panel panel-default"><div class="panel-body">' . $list . '</div></div>';
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
        $export_sortdelim = @$memory[$_GET['path']]['export_sortdelim'];
        $export_sortsdelim = @$memory[$_GET['path']]['export_sortsdelim'];
        $export_imgvalue = @$memory[$_GET['path']]['export_imgdelim'];
    }
    else {
        $memory=$export_sortdelim=$export_sortsdelim=$export_imgvalue=$exchanges_remove_value=null;
    }

    $delim_value[] = array('Точка с запятой', ';', @$memory[$_GET['path']]['export_delim']);
    $delim_value[] = array('Запятая', ',', @$memory[$_GET['path']]['export_delim']);

    $action_value[] = array('Обновление', 'update', @$memory[$_GET['path']]['export_action']);
    $action_value[] = array('Создание', 'insert', @$memory[$_GET['path']]['export_action']);

    $delim_sortvalue[] = array('#', '#', $export_sortdelim);
    $delim_sortvalue[] = array('@', '@', $export_sortdelim);
    $delim_sortvalue[] = array('$', '$', $export_sortdelim);

    $delim_sort[] = array('/', '/', $export_sortsdelim);
    $delim_sort[] = array('\\', '\\', $export_sortsdelim);
    $delim_sort[] = array('-', '-', $export_sortsdelim);
    $delim_sort[] = array('&', '&', $export_sortsdelim);

    $delim_imgvalue[] = array(__('Выключить'), 0, $export_imgvalue);
    $delim_imgvalue[] = array(__('Запятая'), ',', $export_imgvalue);
    $delim_imgvalue[] = array('#', '#', $export_imgvalue);
    $delim_imgvalue[] = array(__('пробел'), ' ', $export_imgvalue);

    $code_value[] = array('ANSI', 'ansi', 'selected');
    $code_value[] = array('UTF-8', 'utf', '');

    $key_value[] = array('Id или Артикул', 0, 'selected');

    // Закладка 1
    $Tab1 = $PHPShopGUI->setField("Файл", $PHPShopGUI->setFile()).
            $PHPShopGUI->setField('Действие', $PHPShopGUI->setSelect('export_action', $action_value, 150, true)) .
            $PHPShopGUI->setField('CSV-разделитель', $PHPShopGUI->setSelect('export_delim', $delim_value, 150, true)) .
            $PHPShopGUI->setField('Разделитель для характеристик', $PHPShopGUI->setSelect('export_sortdelim', $delim_sortvalue, 150), false, false, $class) .
            $PHPShopGUI->setField('Разделитель значений характеристик', $PHPShopGUI->setSelect('export_sortsdelim', $delim_sort, 150), false, false, $class) .
            $PHPShopGUI->setField('Полный путь для изображений', $PHPShopGUI->setCheckbox('export_imgpath', 1, null, @$memory[$_GET['path']]['export_imgpath']), 1, 'Добавляет к изображениям папку /UserFiles/Image/', $class) .
            $PHPShopGUI->setField('Обработка изображений', $PHPShopGUI->setCheckbox('export_imgproc', 1, null, @$memory[$_GET['path']]['export_imgproc']), 1, 'Создание тумбнейла и ватермарка', $class) .
            $PHPShopGUI->setField('Разделитель для изображений', $PHPShopGUI->setSelect('export_imgdelim', $delim_imgvalue, 150), 1, 'Дополнительные изображения', $class) .
            $PHPShopGUI->setField('Кодировка текста', $PHPShopGUI->setSelect('export_code', $code_value, 150)) .
            $PHPShopGUI->setField('Ключ обновления', $PHPShopGUI->setSelect('export_key', $key_value, 150, false, false, true), 1, 'Изменение ключа обновления может привести к порче данных', $class) .
            $PHPShopGUI->setField('Проверка уникальности', $PHPShopGUI->setCheckbox('export_uniq', 1, null, @$memory[$_GET['path']]['export_uniq']), 1, 'Исключает дублирование данных при создании');

    // Закладка 2
    $Tab2 = $PHPShopGUI->setField(array('Колонка 1', 'Колонка 2'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка 3', 'Колонка 4'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка 5', 'Колонка 6'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка 7', 'Колонка 8'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка 9', 'Колонка 10'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка 11', 'Колонка 12'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка 13', 'Колонка 14'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка 15', 'Колонка 16'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка 17', 'Колонка 18'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка 19', 'Колонка 20'), array($PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true), $PHPShopGUI->setSelect('select_action[]', $select_value, 150, true,false,true)), array(array(3, 2), array(2, 2)));

    // Закладка 3
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
    $data = $PHPShopOrm->select(array('*'), array('type' => '="import"'), array('order' => 'id DESC'), array("limit" => "1000"));
    $exchanges_value[] = array(__('Создать новую настройку'), 'new');
    if (is_array($data)){
        foreach ($data as $row) {
            $exchanges_value[] = array($row['name'], $row['id']);
            $exchanges_remove_value[] = array($row['name'], $row['id']);
        }
    }else $exchanges_remove_value=null;

    $Tab3 = $PHPShopGUI->setField('Выбрать настройку', $PHPShopGUI->setSelect('exchanges', $exchanges_value, 300, false));
    $Tab3 .= $PHPShopGUI->setField('Сохранить настройку', $PHPShopGUI->setInputArg(array('type' => 'text', 'placeholder' => 'Имя настройки', 'size' => '300', 'name' => 'exchanges_new', 'class' => 'vendor_add')));
    
    if(is_array($exchanges_remove_value))
    $Tab3 .= $PHPShopGUI->setField('Удалить настройки', $PHPShopGUI->setSelect('exchanges_remove[]', $exchanges_remove_value, 300, false, false, false, false, 1, true));

    // Закладка 4
    if (empty($_POST['time_limit']))
        $_POST['time_limit'] = 1;

    if (empty($_POST['line_limit']))
        $_POST['line_limit'] = 500;
    
    if(empty($_POST['bot']))
        $_POST['bot']=null;

    $Tab4 = $PHPShopGUI->setField('Лимит строк', $PHPShopGUI->setInputText(null, 'line_limit', $_POST['line_limit'], 150), 1, 'Задается хостингом');
    $Tab4 .= $PHPShopGUI->setField('Временной интервал', $PHPShopGUI->setInputText(null, 'time_limit', $_POST['time_limit'], 150, __('минут')), 1, 'Задается хостингом');
    $Tab4 .= $PHPShopGUI->setField("Помощник", $PHPShopGUI->setCheckbox('bot', 1, __('Умная загрузка для соблюдения правила ограничений на хостинге'), $_POST['bot'], false, false));

    $Tab1 = $PHPShopGUI->setCollapse('Настройки',$Tab1);
    $Tab2 = $PHPShopGUI->setCollapse('Подсказка',$PHPShopGUI->setHelp('Если вы загружаете файл, который скачали в меню "База" &rarr; "Экспорт базы", и он содержит штатные заголовки столбцов – сопоставление полей делать <b>не нужно</b>. Если это сторонний прайс со своими названиями колонок, сделайте <b>Cопоставление полей</b>.')).
            $PHPShopGUI->setCollapse('Сопоставление полей',$Tab2);
    
    $Tab3 = $PHPShopGUI->setCollapse('Сохраненные настройки',$Tab3);
    $Tab4 = $PHPShopGUI->setCollapse('Автоматизация',$Tab4);
    
    $PHPShopGUI->tab_return = true;
    $PHPShopGUI->setTab(array('Настройки', $Tab1, true), array('Сопоставление полей', $Tab2, true), array('Сохраненные настройки', $Tab3, true), array('Автоматизация', $Tab4, true));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", true, "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.exchange.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.exchange.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $help = '<p class="text-muted data-row">' . __('Для импорта данных нужно скачать') . ' <a href="?path=exchange.export"><span class="glyphicon glyphicon-share-alt"></span>' . __('Пример файла') . '</a>' . __(', выбрав нужные вам поля. Далее добавьте или измените нужную информацию, не нарушая структуру, и выберите меню') . ' <em> ' . __('"Импорт данных"') . '</em></p>';

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