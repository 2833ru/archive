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
    'bonus' => 'Бонус',
    'price_purch' => 'Закупочная цена'
);

if ($GLOBALS['PHPShopBase']->codBase == 'utf-8')
    unset($key_name);

// Стоп лист
$key_stop = array('password', 'wishlist', 'sort', 'yml_bid_array', 'status', 'files', 'datas', 'price_search', 'vid', 'name_rambler', 'servers', 'skin', 'skin_enabled', 'secure_groups', 'icon_description', 'title_enabled', 'title_shablon', 'descrip_shablon', 'descrip_enabled', 'productsgroup_check', 'productsgroup_product', 'keywords_enabled', 'keywords_shablon', 'rate_count', 'sort_cache', 'sort_cache_created_at', 'parent_title', 'menu', 'order_by', 'order_to', 'org_ras', 'org_bank', 'org_kor', 'org_bik', 'org_city', 'admin', 'org_fakt_adres');

if (empty($subpath[2]))
    $subpath[2] = null;

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

// Загрузка изображения по ссылке 
function downloadFile($url, $path) {

    $newfname = $path;
    $url = iconv("windows-1251", "utf-8//IGNORE", $url);

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    $file = @fopen($url, 'rb', false, stream_context_create($arrContextOptions));
    if ($file) {
        $newf = fopen($newfname, 'wb');
        if ($newf) {
            while (!feof($file)) {
                fwrite($newf, fread($file, 1024 * 8), 1024 * 8);
            }
        }
    }
    if ($file) {
        fclose($file);
    }
    if ($newf) {
        fclose($newf);
        return true;
    }
}

// Временная категория
function setCategory() {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
    $row = $PHPShopOrm->getOne(array('id'), array('name' => '="Загрузка CSV ' . PHPShopDate::get() . '"'));

    if (empty($row['id'])) {
        $result = $PHPShopOrm->insert(array('name_new' => 'Загрузка CSV ' . PHPShopDate::get(), 'skin_enabled_new' => 1));
        return $result;
    } else
        return $row['id'];
}

function sort_encode($sort, $category) {

    $return = [];
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
                    
                    $return += (new sortCheck($sort_name,$sort_value,$category,$debug))->result();
                }
            }
    }

    return $return;
}

// Обработка строки CSV
function csv_update($data) {
    global $PHPShopOrm, $PHPShopBase, $csv_load_option, $key_name, $csv_load_count, $subpath, $PHPShopSystem, $csv_load, $csv_load_totale, $img_load;

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
                    
                    // Автоматизация
                    if(!empty($_POST['bot'])){
                        $_POST['select_action'][$k]= PHPShopString::utf8_win1251($name,true);
                    }

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
                    if (strstr($data[$k], $_POST['export_sortsdelim'])) {
                        $sort_array = explode($_POST['export_sortsdelim'], $data[$k]);
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

                // Временная категория
                if (empty($row['category'])) {
                    $row['category'] = setCategory();
                }

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
            if (!strstr($row['pic_big'], '/UserFiles/Image/') and ! strstr($row['pic_big'], 'http'))
                $_POST['export_imgpath'] = true;
            else
                $_POST['export_imgpath'] = false;


            if (!empty($_POST['export_imgpath'])) {
                if (!empty($row['pic_small']))
                    $row['pic_small'] = '/UserFiles/Image/' . $row['pic_small'];
            }

            // Разделитель для изображений
            if (empty($_POST['export_imgdelim'])) {
                $imgdelim = [' ', ',', ';', '#'];
                foreach ($imgdelim as $delim) {
                    if (strstr($row['pic_big'], $delim)) {
                        $_POST['export_imgdelim'] = $delim;
                    }
                }
            }

            // Дополнительные изображения
            if (!empty($_POST['export_imgdelim']) and strstr($row['pic_big'], $_POST['export_imgdelim'])) {
                $data_img = explode($_POST['export_imgdelim'], $row['pic_big']);
            } elseif (!empty($row['pic_big']))
                $data_img[] = $row['pic_big'];

            if (!empty($data_img) and is_array($data_img)) {

                // Получение ID товара по артикулу при обновлении
                if ($_POST['export_action'] == 'update' and empty($row['id']) and ! empty($row['uid'])) {
                    $PHPShopOrmProd = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $data_prod = $PHPShopOrmProd->getOne(array('id'), array('uid' => '="' . $row['uid'] . '"'));
                    $row['id'] = $data_prod['id'];
                }

                // Папка картинок
                $path = $PHPShopSystem->getSerilizeParam('admoption.image_result_path');
                if (!empty($path))
                    $path = $path . '/';

                foreach ($data_img as $k => $img) {
                    if (!empty($img)) {

                        // Полный путь к изображениям
                        if (!empty($_POST['export_imgpath']))
                            $img = '/UserFiles/Image/' . $img;

                        // Загрузка изображений по ссылке
                        if (isset($_POST['export_imgload']) and strstr($img, 'http')) {

                            $path_parts = pathinfo($img);
                            $path_parts['basename'] = PHPShopFile::toLatin($path_parts['basename']);

                            // Файл загружен
                            if (downloadFile($img, $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . '/UserFiles/Image/' . $path . $path_parts['basename']))
                                $img_load++;
                            else
                                continue;

                            // Новое имя
                            $img = $GLOBALS['dir']['dir'] . '/UserFiles/Image/' . $path . $path_parts['basename'];
                        }

                        // Проверка существования изображения
                        $PHPShopOrmImg = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
                        $PHPShopOrmImg->debug = false;
                        $check = $PHPShopOrmImg->select(array('name'), array('name' => '="' . $img . '"', 'parent' => '=' . intval($row['id'])), false, array('limit' => 1));

                        // Создаем новую
                        if (!is_array($check)) {

                            // Запись в фотогалерее
                            $PHPShopOrmImg->insert(array('parent_new' => intval($row['id']), 'name_new' => $img, 'num_new' => $k));

                            $file = $_SERVER['DOCUMENT_ROOT'] . $img;
                            $name = str_replace(array(".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF"), array("s.png", "s.jpg", "s.jpeg", "s.gif", "s.png", "s.jpg", "s.jpeg", "s.gif"), $file);

                            if (!file_exists($name) and file_exists($file)) {

                                // Генерация тубнейла 
                                if (!empty($_POST['export_imgproc'])) {
                                    $thumb = new PHPThumb($file);
                                    $thumb->setOptions(array('jpegQuality' => $width_kratko));
                                    $thumb->resize($img_tw, $img_th);
                                    $thumb->save($name);
                                } else
                                    copy($file, $name);
                            }

                            // Главное изображение
                            if ($k == 0 and ! empty($file)) {

                                $row['pic_big'] = $img;

                                // Главное превью
                                if (empty($row['pic_small']) or isset($_POST['export_imgload']) or isset($_POST['export_imgproc']))
                                    $row['pic_small'] = str_replace(array(".png", ".jpg", ".jpeg", ".gif", ".PNG", ".JPG", ".JPEG", ".GIF"), array("s.png", "s.jpg", "s.jpeg", "s.gif", "s.png", "s.jpg", "s.jpeg", "s.gif"), $img);
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
                
                // Проверка SEO имени каталога
                if($subpath[2] == 'catalog' and !empty($row['name'])){
                    $uniq_cat_data = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->getOne(['*'],['name'=>'="'.$row['name'].'"']);
                    
                    // Есть одноименный каталог
                    if(!empty($uniq_cat_data['name'])){
                        $parent_cat_data = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->getOne(['*'],['id'=>'="'.$uniq_cat_data['parent_to'].'"']);
                        $row['cat_seo_name'] = PHPShopString::toLatin($row['name']);
                        $row['cat_seo_name'] = PHPShopString::toLatin($parent_cat_data['name']).'-'.PHPShopString::toLatin($row['name']);
                    }
                    else $row['cat_seo_name'] = PHPShopString::toLatin($row['name']);
                    
                }

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
                        $csv_load_totale++;

                        // Отчет
                        $GLOBALS['csv_load'][] = $row;
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
                    if (!empty($row['id'])) {
                        $where = array('id' => '="' . intval($row['id']) . '"');
                        unset($row['id']);
                    }

                    // Обновление по артикулу
                    elseif (!empty($row['uid'])) {
                        $where = array('uid' => '="' . $row['uid'] . '"');
                        unset($row['uid']);
                    }

                    // Обновление по логину
                    elseif (!empty($row['login'])) {
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
                            $GLOBALS['csv_load'][] = $row;
                    }
                }
            }
        }
    }
}

// Функция обновления
function actionSave() {
    global $PHPShopGUI, $PHPShopSystem, $key_name, $key_name, $result_message, $csv_load_count, $subpath, $csv_load, $csv_load_totale, $img_load;

    // Выбрать настройку
    if ($_POST['exchanges'] != 'new') {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);

        // Изменить имя настройки
        if (!empty($_POST['exchanges_new'])) {
            $PHPShopOrm->update(array('name_new' => $_POST['exchanges_new']), array('id' => '=' . intval($_POST['exchanges'])));
        }

        // Настройки для Cron
        if (!empty($_POST['exchanges_cron'])) {
            $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['exchanges'])), false, array("limit" => 1));
            if (is_array($data)) {
                unset($_POST);
                $_POST = unserialize($data['option']);
                $exchanges_name = $data['name'];
                unset($_POST['exchanges_new']);
            }
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

    // Память настроек
    $memory[$_GET['path']]['export_sortdelim'] = @$_POST['export_sortdelim'];
    $memory[$_GET['path']]['export_sortsdelim'] = @$_POST['export_sortsdelim'];
    $memory[$_GET['path']]['export_imgdelim'] = @$_POST['export_imgdelim'];
    $memory[$_GET['path']]['export_imgpath'] = @$_POST['export_imgpath'];
    $memory[$_GET['path']]['export_uniq'] = @$_POST['export_uniq'];
    $memory[$_GET['path']]['export_action'] = @$_POST['export_action'];
    $memory[$_GET['path']]['export_delim'] = @$_POST['export_delim'];
    $memory[$_GET['path']]['export_imgproc'] = @$_POST['export_imgproc'];
    $memory[$_GET['path']]['export_imgload'] = @$_POST['export_imgload'];

    // Копируем csv от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if ($_FILES['file']['ext'] == "csv") {
            if (@move_uploaded_file($_FILES['file']['tmp_name'], "csv/" . PHPShopString::toLatin($_FILES['file']['name']).'.'.$_FILES['file']['ext'])) {
                $csv_file_name = PHPShopString::toLatin($_FILES['file']['name']).'.'.$_FILES['file']['ext'];
                $csv_file = "csv/" . $csv_file_name;
                $_POST['lfile'] = $GLOBALS['dir']['dir'] . "/phpshop/admpanel/csv/" . $csv_file_name;
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

            // Первая загрузка
            if (empty($_POST['total'])) {

                // Строк в файле
                $total = 0;
                $handle = fopen($csv_file, "r");
                while ($data = fgetcsv($handle, 0, $delim)) {
                    $total++;
                }

                $bar = 0;
                $end = 0;
                $csv_load_count = 0;
                $bar_class = "active";

                if ($_POST['export_action'] == 'insert')
                    $do = 'Создано';
                else
                    $do = 'Изменено';

                $total_min = round($total / $_POST['line_limit'] * $_POST['time_limit']);

                $result_message = $PHPShopGUI->setAlert('<div id="bot_result">' . __('Файл') . ' <strong>' . $csv_file_name . '</strong> ' . __('загружен. Обработано ') . $end . __(' из ') . $total . __(' строк. ' . $do) . ' <b id="total-update">' . intval($csv_load_count) . '</b> ' . __('записей.') . '</div>
<div class="progress bot-progress">
  <div class="progress-bar progress-bar-striped  progress-bar-success ' . $bar_class . '" role="progressbar" aria-valuenow="" aria-valuemin="0" aria-valuemax="100" style="width: ' . $bar . '%"> ' . $bar . '% 
  </div>
</div>','success load-result',true,false,false);
                $result_message .= $PHPShopGUI->setAlert('<b>Пожалуйста, не закрывайте окно до полной загрузки товаров</b><br>
Вы можете продолжить работу с другими разделами сайта, открывая меню в новой вкладке (нажмите <kbd>CTRL</kbd> и кликните на раздел).', 'info load-info',true,false,false);
                $result_message .= $PHPShopGUI->setInput("hidden", "csv_file", $csv_file);
                $result_message .= $PHPShopGUI->setInput("hidden", "total", $total);
                $result_message .= $PHPShopGUI->setInput("hidden", "stop", 0);
            } else {

                $result = PHPShopFile::readCsvGenerators($csv_file, 'csv_update', $delim, array($_POST['start'], $_POST['end']));
                if ($result) {

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

                    if ($_POST['export_action'] == 'insert')
                        $lang_do = 'Создано';
                    else
                        $lang_do = 'Изменено';

                    if ($csv_load_count < 0)
                        $csv_load_count = 0;

                    $total_min = round(($total - $csv_load_count) / $_POST['line_limit'] * $_POST['time_limit']);
                    $action = true;
                    $json_message = __('Файл') . ' <strong>' . $csv_file_name . '</strong> ' . __('загружен. Обработано ') . $end . __(' из ') . $total . __(' строк. ' . $lang_do) . ' <b id="total-update">' . intval($csv_load_count) . '</b> ' . __('записей.');

                    // Файл результа
                    if ($_POST['line_limit'] >= 10) {
                        $result_csv = './csv/result_' . date("d_m_y_His") . '.csv';
                        PHPShopFile::writeCsv($result_csv, $GLOBALS['csv_load']);
                    }

                    // Данные для журнала
                    $csv_load_totale = $_POST['start'] . '-' . $_POST['end'];
                } else
                    $result_message = $PHPShopGUI->setAlert(__('Нет прав на запись файла') . ' ' . $csv_file, 'danger');
            }
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

                    if ($_POST['export_action'] == 'insert') {
                        $lang_do = 'Создано';
                        $lang_do2 = 'созданным';
                    } else {
                        $lang_do = 'Изменено';
                        $lang_do2 = 'обновленным';
                    }

                    $result_message = $PHPShopGUI->setAlert(__('Файл') . ' <strong>' . $csv_file_name . '</strong> ' . __('загружен. Обработано ' . $csv_load_totale . ' строк. ' . $lang_do) . ' <strong>' . intval($csv_load_count) . '</strong> ' . __('записей') . '. ' . __('Отчет по ' . $lang_do2 . ' позициям ') . ' <a href="' . $result_csv . '" target="_blank">CSV</a>.');
                }
            } else {
                $result = 0;
                $result_message = $PHPShopGUI->setAlert(__('Нет прав на запись файла') . ' ' . $csv_file, 'danger');
            }
        }
    }

    // Сохранение настройки
    if ($_POST['exchanges'] == 'new' and ! empty($_POST['exchanges_new'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
        $PHPShopOrm->insert(array('name_new' => $_POST['exchanges_new'], 'option_new' => serialize($_POST), 'type_new' => 'import'));
    }

    if (!empty($_POST['bot']) and (empty($_POST['total']) or $_POST['line_limit'] < 10))
        $log_off = true;

    // Журнал загрузок
    if (empty($log_off)) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges_log']);
        $PHPShopOrm->insert(array('date_new' => time(), 'file_new' => $csv_file, 'status_new' => $result, 'info_new' => serialize([$csv_load_totale, $lang_do, (int) $csv_load_count, $result_csv, (int) $img_load]), 'option_new' => serialize($_POST)));
    }

    // Автоматизация
    if (!empty($_POST['ajax'])) {

        if ($total > $end) {

            $bar = round($_POST['end'] * 100 / $total);

            return array("success" => $action, "bar" => $bar, "count" => $csv_load_count, "result" => PHPShopString::win_utf8($json_message), 'limit' => $limit,'action'=>PHPShopString::win_utf8(mb_strtolower($lang_do,$GLOBALS['PHPShopBase']->codBase)));
        } else
            return array("success" => 'done', "count" => $csv_load_count, "result" => PHPShopString::win_utf8($json_message), 'limit' => $limit,'action'=>PHPShopString::win_utf8(mb_strtolower($lang_do,$GLOBALS['PHPShopBase']->codBase)));
    }
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm, $key_name, $subpath, $key_base, $key_stop, $result_message;

    // Выбрать настройку
    if (!empty($_GET['exchanges'])) {

        $PHPShopOrmExchanges = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
        $data_exchanges = $PHPShopOrmExchanges->select(array('*'), array('id' => '=' . intval($_GET['exchanges'])), false, array("limit" => 1));

        if (is_array($data_exchanges)) {
            $_POST = unserialize($data_exchanges['option']);
            $exchanges_name = ": " . $data_exchanges['name'];
        }
    }

    if (!empty($_POST['lfile'])) {
        $memory[$_GET['path']]['export_sortdelim'] = @$_POST['export_sortdelim'];
        $memory[$_GET['path']]['export_sortsdelim'] = @$_POST['export_sortsdelim'];
        $memory[$_GET['path']]['export_imgdelim'] = @$_POST['export_imgdelim'];
        $memory[$_GET['path']]['export_imgpath'] = @$_POST['export_imgpath'];
        $memory[$_GET['path']]['export_imgload'] = @$_POST['export_imgload'];
        $memory[$_GET['path']]['export_uniq'] = @$_POST['export_uniq'];
        $memory[$_GET['path']]['export_action'] = @$_POST['export_action'];
        $memory[$_GET['path']]['export_delim'] = @$_POST['export_delim'];
        $memory[$_GET['path']]['export_imgproc'] = @$_POST['export_imgproc'];
        $memory[$_GET['path']]['export_code'] = @$_POST['export_code'];
        $memory[$_GET['path']]['bot'] = @$_POST['bot'];

        $export_sortdelim = @$memory[$_GET['path']]['export_sortdelim'];
        $export_sortsdelim = @$memory[$_GET['path']]['export_sortsdelim'];
        $export_imgvalue = @$memory[$_GET['path']]['export_imgdelim'];
        $export_code = $memory[$_GET['path']]['export_code'];
    }
    // Настройки по умолчанию
    else {
        $memory[$_GET['path']]['export_imgload'] = 1;
        $memory[$_GET['path']]['export_imgproc'] = 1;

        $_POST['line_limit'] = 1;

        if ($_GET['path'] == 'exchange.import')
            $_POST['bot'] = 1;
        
        if($subpath[2] == 'catalog')
            $memory[$_GET['path']]['export_action']='insert';
    }


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

    // Пустая база
    if (!is_array($data)) {
        $PHPShopOrm->insert(array('name_new' => 'Тестовый товар'));
        $PHPShopOrm->clean();
        $data = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1));
        $PHPShopOrm->delete(array('name' => '="Тестовый товар"'));
        
       if(empty($subpath[2]))
         $memory[$_GET['path']]['export_action']='insert';
    }

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

    $PHPShopGUI->setActionPanel($TitlePage . $exchanges_name, false, array('Импорт'));

    $delim_value[] = array('Точка с запятой', ';', @$memory[$_GET['path']]['export_delim']);
    $delim_value[] = array('Запятая', ',', @$memory[$_GET['path']]['export_delim']);

    $action_value[] = array('Обновление', 'update', @$memory[$_GET['path']]['export_action']);
    $action_value[] = array('Создание', 'insert', @$memory[$_GET['path']]['export_action']);

    $delim_sortvalue[] = array('#', '#', $export_sortdelim);
    $delim_sortvalue[] = array('@', '@', $export_sortdelim);
    $delim_sortvalue[] = array('$', '$', $export_sortdelim);
    $delim_sortvalue[] = array(__('Колонка'), '-', $export_sortdelim);

    $delim_sort[] = array('/', '/', $export_sortsdelim);
    $delim_sort[] = array('\\', '\\', $export_sortsdelim);
    $delim_sort[] = array('-', '-', $export_sortsdelim);
    $delim_sort[] = array('&', '&', $export_sortsdelim);
    $delim_sort[] = array(';', ';', $export_sortsdelim);
    $delim_sort[] = array(',', ',', $export_sortsdelim);

    $delim_imgvalue[] = array(__('Автоматический'), 0, $export_imgvalue);
    $delim_imgvalue[] = array(__('Запятая'), ',', $export_imgvalue);
    $delim_imgvalue[] = array(__('Точка с запятой'), ';', $export_imgvalue);
    $delim_imgvalue[] = array('#', '#', $export_imgvalue);
    $delim_imgvalue[] = array(__('Пробел'), ' ', $export_imgvalue);

    $code_value[] = array('ANSI', 'ansi', $export_code);
    $code_value[] = array('UTF-8', 'utf', $export_code);

    $key_value[] = array('Id или Артикул', 0, 'selected');

    // Закладка 1
    $Tab1 = $PHPShopGUI->setField("Файл", $PHPShopGUI->setFile($_POST['lfile'])) .
            $PHPShopGUI->setField('Действие', $PHPShopGUI->setSelect('export_action', $action_value, 150, true)) .
            $PHPShopGUI->setField('CSV-разделитель', $PHPShopGUI->setSelect('export_delim', $delim_value, 150, true)) .
            $PHPShopGUI->setField('Разделитель для характеристик', $PHPShopGUI->setSelect('export_sortdelim', $delim_sortvalue, 150), false, false, $class) .
            $PHPShopGUI->setField('Разделитель значений характеристик', $PHPShopGUI->setSelect('export_sortsdelim', $delim_sort, 150), false, false, $class) .
            $PHPShopGUI->setField('Обработка изображений', $PHPShopGUI->setCheckbox('export_imgproc', 1, null, @$memory[$_GET['path']]['export_imgproc']), 1, 'Создание тумбнейла и ватермарка', $class) .
            $PHPShopGUI->setField('Загрузка изображений', $PHPShopGUI->setCheckbox('export_imgload', 1, null, @$memory[$_GET['path']]['export_imgload']), 1, 'Загрузка изображений на сервер по ссылке', $class) .
            $PHPShopGUI->setField('Разделитель для изображений', $PHPShopGUI->setSelect('export_imgdelim', $delim_imgvalue, 150), 1, 'Дополнительные изображения', $class) .
            $PHPShopGUI->setField('Кодировка текста', $PHPShopGUI->setSelect('export_code', $code_value, 150)) .
            $PHPShopGUI->setField('Ключ обновления', $PHPShopGUI->setSelect('export_key', $key_value, 150, false, false, true), 1, 'Изменение ключа обновления может привести к порче данных', $class) .
            $PHPShopGUI->setField('Проверка уникальности', $PHPShopGUI->setCheckbox('export_uniq', 1, null, @$memory[$_GET['path']]['export_uniq']), 1, 'Исключает дублирование данных при создании', $class);

    // Память
    if (is_array($_POST['select_action'])) {
        foreach ($_POST['select_action'] as $x => $p)
            if (is_array($select_value)) {
                $select_value_pre = [];
                foreach ($select_value as $k => $v) {

                    if ($v[0] == $p or ( strstr($v[0], '@') and strstr($p, '@')))
                        $v[2] = 'selected';
                    else
                        $v[2] = null;

                    $select_value_pre[] = [$v[0], $v[1], $v[2], $v[3]];
                }
                ${'select_value' . ($x + 1)} = $select_value_pre;
            }
    }else {
        $n = 1;
        while ($n < 21) {
            ${'select_value' . ($n)} = $select_value;
            $n++;
        }
    }

    // Закладка 2
    $Tab2 = $PHPShopGUI->setField(array('Колонка A', 'Колонка B'), array($PHPShopGUI->setSelect('select_action[]', $select_value1, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value2, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка C', 'Колонка D'), array($PHPShopGUI->setSelect('select_action[]', $select_value3, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value4, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка E', 'Колонка F'), array($PHPShopGUI->setSelect('select_action[]', $select_value5, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value6, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка G', 'Колонка H'), array($PHPShopGUI->setSelect('select_action[]', $select_value7, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value8, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка I', 'Колонка J'), array($PHPShopGUI->setSelect('select_action[]', $select_value9, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value10, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка K', 'Колонка L'), array($PHPShopGUI->setSelect('select_action[]', $select_value11, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value12, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка M', 'Колонка N'), array($PHPShopGUI->setSelect('select_action[]', $select_value13, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value14, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка O', 'Колонка P'), array($PHPShopGUI->setSelect('select_action[]', $select_value15, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value16, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка Q', 'Колонка R'), array($PHPShopGUI->setSelect('select_action[]', $select_value17, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value18, 150, true, false, true)), array(array(3, 2), array(2, 2)));
    $Tab2 .= $PHPShopGUI->setField(array('Колонка S', 'Колонка T'), array($PHPShopGUI->setSelect('select_action[]', $select_value19, 150, true, false, true), $PHPShopGUI->setSelect('select_action[]', $select_value20, 150, true, false, true)), array(array(3, 2), array(2, 2)));

    // Закладка 3
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['exchanges']);
    $data = $PHPShopOrm->select(array('*'), array('type' => '="import"'), array('order' => 'id DESC'), array("limit" => "1000"));
    $exchanges_value[] = array(__('Создать новую настройку'), 'new');
    if (is_array($data)) {
        foreach ($data as $row) {
            $exchanges_value[] = array($row['name'], $row['id'], $_REQUEST['exchanges']);
            $exchanges_remove_value[] = array($row['name'], $row['id']);
        }
    } else
        $exchanges_remove_value = null;

    $Tab3 = $PHPShopGUI->setField('Выбрать настройку', $PHPShopGUI->setSelect('exchanges', $exchanges_value, 300, false));
    $Tab3 .= $PHPShopGUI->setField('Сохранить настройку', $PHPShopGUI->setInputArg(array('type' => 'text', 'placeholder' => 'Имя настройки', 'size' => '300', 'name' => 'exchanges_new', 'class' => 'vendor_add')));

    if (is_array($exchanges_remove_value))
        $Tab3 .= $PHPShopGUI->setField('Удалить настройки', $PHPShopGUI->setSelect('exchanges_remove[]', $exchanges_remove_value, 300, false, false, false, false, 1, true));

    // Закладка 4
    if (empty($_POST['time_limit']))
        $_POST['time_limit'] = 10;

    if (empty($_POST['line_limit']))
        $_POST['line_limit'] = 50;

    if (empty($_POST['bot']))
        $_POST['bot'] = null;

    $Tab4 = $PHPShopGUI->setField('Лимит строк', $PHPShopGUI->setInputText(null, 'line_limit', $_POST['line_limit'], 150), 1, 'Зависит от скорости хостинга');
    //$Tab4 .= $PHPShopGUI->setField('Временной интервал', $PHPShopGUI->setInputText(null, 'time_limit', $_POST['time_limit'], 150, __('секунд')), 1, 'Зависит от скорости хостинга');
    //$Tab4 .= $PHPShopGUI->setInput("hidden", "line_limit", $_POST['line_limit']);
    $Tab4 .= $PHPShopGUI->setField("Помощник", $PHPShopGUI->setCheckbox('bot', 1, __('Умная загрузка для соблюдения правила ограничений на хостинге'), @$_POST['bot'], false, false));

    $Tab1 = $PHPShopGUI->setCollapse('Настройки', $Tab1);
    $Tab2 = $PHPShopGUI->setCollapse('Подсказка', $PHPShopGUI->setHelp('Если вы загружаете файл, который скачали в меню "База" &rarr; "Экспорт базы", и он содержит <a name="import-col-name" href="#">штатные заголовки столбцов</a> – сопоставление полей делать <b>не нужно</b>. Если это сторонний прайс со своими названиями колонок, сделайте <b>Cопоставление полей</b>.<div style="margin-top:10px" id="import-col-name" class="none panel panel-default"><div class="panel-body">' . $list . '</div></div>')) .
            $PHPShopGUI->setCollapse('Сопоставление полей', $Tab2);

    $Tab3 = $PHPShopGUI->setCollapse('Сохраненные настройки', $Tab3);
    $Tab4 = $PHPShopGUI->setCollapse('Автоматизация', $Tab4);

    $Tab5 = $PHPShopGUI->loadLib('tab_log', $data, 'exchange/');
    if (!empty($Tab5))
        $Tab5_status = false;
    else
        $Tab5_status = true;

    $PHPShopGUI->tab_return = true;
    $PHPShopGUI->setTab(array('Настройки', $Tab1, true), array('Сопоставление полей', $Tab2, true), array('Сохраненные настройки', $Tab3, true), array('Автоматизация', $Tab4, true), array('История импортов', $Tab5, true, $Tab5_status));

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

// Обработка характеристик
class sortCheck {

    var $debug = false;

    function __construct($name, $value, $category,$debug=false) {
        
        $this->debug = $debug;

        $this->debug('Дано характеристика "' . $name . '" = "' . $value . '" в каталоге с ID=' . $category);

        // Проверка имени характеристики 
        $check_name = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->getOne(['*'], ['name' => '="' . $name . '"']);
        if ($check_name) {

            $this->debug('Есть характеристика "' . $name . '" c ID=' . $check_name['id'] . ' и CATEGORY=' . $check_name['category']);

            // Проверка значения характеристики
            $check_value = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort']))->getOne(['*'], ['name' => '="' . $value . '"']);
            if ($check_value) {
                $this->debug('Есть значение характеристики "' . $name . '" = "' . $value . '" c ID=' . $check_value['id']);

                // Проверка категории набора характеристики
                $check_category = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->getOne(['*'], ['id' => '="' . $category . '"']);
                $sort = unserialize($check_category['sort']);

                if (is_array($sort) and in_array($check_name['id'], $sort)) {
                    $this->debug('Есть набор характеристики "' . $name . '" = "' . $value . '" c ID=' . $check_value['id'] . ' в каталоге ' . $check_category['name'] . '" с ID=' . $category);
                } else {
                    $sort_categories = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->getOne(['*'], ['id' => '=' . $check_name['category']]);
                    $this->debug('Нет набор характеристики "' . $sort_categories['name'] . '" c ID=' . $check_name['category'] . ' в каталоге ' . $check_category['name'] . '" с ID=' . $category);

                    // Добавление в категорию набора характеристики
                    $sort[] = $check_name['id'];
                    (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->update(['sort_new' => serialize($sort)], ['id' => '=' . $category]);
                    $this->debug('Набор характеристик "' . $sort_categories['name'] . '" c ID=' . $check_name['category'] . ' добавлен в каталог "' . $check_category['name'] . '" с ID=' . $category);

                    $result[$check_name['id']][] = $check_value['id'];
                }
                $result[$check_name['id']][] = $check_value['id'];
            } else {
                $this->debug('Нет значения характеристики "' . $name . '" = "' . $value . '"');

                // Создание нового значения характеристики
                $new_value_id = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort']))->insert(['name_new' => $value, 'category_new' => $check_name['id'],'sort_seo_name_new'=>str_replace("_", "-",PHPShopString::toLatin($value))]);

                $this->debug('Создание нового значения характеристики "' . $name . '" = "' . $value . '" c ID=' . $new_value_id);
                $result[$check_name['id']][] = $new_value_id;
            }
        } else {

            $this->debug('Нет характеристики "' . $name . '"');

            // Проверка категории набора характеристики
            $check_category = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->getOne(['*'], ['id' => '="' . $category . '"']);
            $sort = unserialize($check_category['sort']);

            // У каталога есть характеристики
            if (is_array($sort)) {

                // Проверка значения характеристики
                foreach ($sort as $val) {
                    $check_value = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->getOne(['*'], ['id' => '=' . $val]);
                    if (!empty($check_value['category'])) {
                        $sort_categories = $check_value['category'];
                        continue;
                    }
                }

                $this->debug('Выбран набор характеристик c ID=' . $sort_categories);
            }
            // У каталога нет набора характеристик
            else {

                // Создание нового набора характеристик
                $new_sort_categories_name = $check_category['name'];
                $new_sort_categories = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->insert(['name_new' => $new_sort_categories_name, 'category_new' => 0]);
                $sort_categories = $new_sort_categories;
                $this->debug('Создание нового набор характеристик "' . $new_sort_categories_name . '" c ID=' . $sort_categories . ' ');
            }

            // Создание новой характеристики 
            $new_name_id = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']))->insert(['name_new' => $name, 'category_new' => $sort_categories]);
            $this->debug('Создание новой характеристики "' . $name . '" c ID=' . $new_name_id . ' в группе характеристик ID=' . $sort_categories);

            // Создание нового значения характеристики
            $new_value_id = (new PHPShopOrm($GLOBALS['SysValue']['base']['sort']))->insert(['name_new' => $value, 'category_new' => $new_name_id,'sort_seo_name_new'=>str_replace("_", "-",PHPShopString::toLatin($value))]);
            $this->debug('Создание нового значения характеристики "' . $name . '" = "' . $value . '" c ID=' . $new_value_id);

            // Добавление в категорию характеристики
            $sort[] = $new_name_id;
            (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))->update(['sort_new' => serialize($sort)], ['id' => '=' . $category]);
            $this->debug('Характеристика "' . $name . '" c ID=' . $new_name_id . ' добавлен в каталог "' . $check_category['name'] . '" с ID=' . $category);

            $result[$new_name_id][] = $new_value_id;
        }

        $this->result = $result;
    }

    // Отладка
    function debug($str) {
        if ($this->debug)
            echo $str . PHP_EOL . '<br>';
    }
    
    // Результат
    function result(){
        return $this->result;
    }
}

// Обработка событий
$PHPShopGUI->getAction();
?>