<?php

/**
 * Обмен по CommerceML
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 1.5
 */
class CommerceMLLoader {

    private static $session_name = "CommerceMLLoader";
    private static $upload1c = 'upload/';
    var $result_path = 'sklad/';
    var $exchange_path = '';
    var $cleanup_import_directory = true;

    public function __construct() {
        global $PHPShopSystem;

        // Параметры обмена
        $this->exchange_zip = $PHPShopSystem->getSerilizeParam("1c_option.exchange_zip");
        $this->exchange_key = $PHPShopSystem->getSerilizeParam("1c_option.exchange_key");
        $this->exchange_create = $PHPShopSystem->getSerilizeParam("1c_option.exchange_create");
        $this->exchange_create_category = $PHPShopSystem->getSerilizeParam("1c_option.exchange_create_category");
        $this->exchange_load_status = $PHPShopSystem->getSerilizeParam('1c_option.1c_load_status');
        $this->exchange_auth_path = $PHPShopSystem->getSerilizeParam("1c_option.exchange_auth_path");
        $this->exchange_auth = $PHPShopSystem->getSerilizeParam("1c_option.exchange_auth");
        $this->exchange_image_path = "/UserFiles/Image/";
        $this->image_result_path = $PHPShopSystem->getSerilizeParam('admoption.image_result_path');
        $this->exchange_log = $PHPShopSystem->getSerilizeParam("1c_option.exchange_log");
        $this->exchange_image = $PHPShopSystem->getSerilizeParam("1c_option.exchange_image");

        // Параметры ресайзинга
        $this->img_tw = $PHPShopSystem->getSerilizeParam('admoption.img_tw');
        $this->img_th = $PHPShopSystem->getSerilizeParam('admoption.img_th');
        $this->width_kratko = $PHPShopSystem->getSerilizeParam('admoption.width_kratko');
        $this->img_w = $PHPShopSystem->getSerilizeParam('admoption.img_w');
        $this->img_h = $PHPShopSystem->getSerilizeParam('admoption.img_h');
        $this->image_save_source = $PHPShopSystem->getSerilizeParam('admoption.image_save_source');
    }

    private function checkauth() {
        global $_classPath;

        // Авторизация по ссылке
        if ($this->exchange_auth == 1 and $this->exchange_auth_path != "" and $_SERVER['PHP_SELF'] == $GLOBALS['SysValue']['dir']['dir'] . '/1cManager/' . $this->exchange_auth_path . '.php') {

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']);
            $data = $PHPShopOrm->select(array('token'), array('enabled' => "='1'", 'token' => '!=""'), false, array('limit' => 1));
            $_SESSION['token'] = $data['token'];

            return true;
        }

        if ($this->exchange_auth == 0 and ! empty($_SERVER['PHP_AUTH_USER']) && !empty($_SERVER['PHP_AUTH_PW'])) {

            include($_classPath . "lib/phpass/passwordhash.php");
            $hasher = new PasswordHash(8, false);

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['users']);
            $data = $PHPShopOrm->select(array('login,password'), array('enabled' => "='1'"), false, array('limit' => 100));

            if (is_array($data))
                foreach ($data as $row) {
                    if ($_SERVER['PHP_AUTH_USER'] == $row['login']) {
                        if ($hasher->CheckPassword($_SERVER['PHP_AUTH_PW'], $row['password'])) {

                            $_SESSION['login'] = $data['login'];
                            $_SESSION['password'] = $data['password'];

                            return true;
                        }
                    }
                }
        }
    }

    private function crc16($str) {
        $data = trim($str);

        // Внешний код
        if (!is_numeric($data)) {
            $crc = 0xFFFF;
            for ($i = 0; $i < strlen($data); $i++) {
                $x = (($crc >> 8) ^ ord($data[$i])) & 0xFF;
                $x ^= $x >> 4;
                $crc = (($crc << 8) ^ ($x << 12) ^ ($x << 5) ^ $x) & 0xFFFF;
            }
        } else
            $crc = $data;

        return $crc;
    }

    public function exchange($type, $mode) {
        session_name(self::$session_name);
        session_start();
        $upload_path = dirname(__FILE__) . $this->exchange_path . '/';
        header("Content-Type: text/plain");
        $response = 'failure';
        if (empty($type) || empty($mode)) {
            $response .= "\nEmpty command type or mode.";
        }
        if (($mode != 'checkauth') && (!isset($_COOKIE[self::$session_name]) || ($_COOKIE[self::$session_name] != $_SESSION['fixed_session_id']))) {
            $response .= "\nUnauthorized.";
        } else
            switch ($mode) {
                case 'checkauth': // Authorization query
                    if ($this->checkauth()) {
                        $response = "success";
                        $response .= "\n" . session_name();
                        $response .= "\n" . session_id();
                        $response .= "\n" . self::sessid_get();
                        $response .= "\ntimestamp=" . time();
                    } else {
                        $response .= "\nAccess denied.";
                    }
                    break;
                case 'init': // Initialize query
                    if (!is_dir($upload_path . self::$upload1c)) {
                        mkdir($upload_path . self::$upload1c, 0777, true);
                    } elseif ($this->cleanup_import_directory) {
                        self::cleanup_import_directory($upload_path . self::$upload1c);
                    }
                    $response = "zip=" . ((intval($this->exchange_zip) == 1) ? 'yes' : 'no');
                    $response .= "\nfile_limit=" . self::parse_size(ini_get("upload_max_filesize"));
					
					// Поддержка CML 2.04
                    //$response .= "\n" . self::sessid_get();
                    //$response .= "\nversion=2.04";
                    break;
                case 'file': // Upload files from 1C
                    $filepath = $upload_path . self::$upload1c . $_GET['filename'];

                    if ($this->data != '') {
                        $data = $this->data;
                        $data_length = 100000;
                    } else {
                        $data = file_get_contents("php://input");
                        $data_length = $_SERVER['CONTENT_LENGTH'];
                    }
                    if (isset($data) && $data !== false) {

                        if (dirname($_GET['filename']) != '.') {
                            @mkdir($upload_path . self::$upload1c . '/' . dirname($_GET['filename']), 0777, true);
                        }
                        $file = fopen($filepath, "w+");
                        if ($file) {
                            $bytes_writed = fwrite($file, $data);
                            if ($bytes_writed == $data_length or $this->data != '') {
                                if (mime_content_type($filepath) == 'application/zip') {
                                    $zip = new ZipArchive();
                                    $zip->open($filepath);
                                    $zip->extractTo($upload_path . self::$upload1c);
                                    $zip->close();
                                }
                                $response = "success";
                            }
                            fclose($file);
                        }
                    }
                    break;
                case 'import': // Processing data
                    if (file_exists($upload_path . 'completed.lock')) {
                        unlink($upload_path . 'completed.lock');
                    }
                    if (file_exists($upload_path . self::$upload1c . $_GET['filename'])) {
                        $move_path = 'goods/';
                        if (preg_match('/^import(.*)\.xml$/', $_GET['filename'])) {
                            $import_xml = simplexml_load_file($upload_path . self::$upload1c . $_GET['filename']);
                            if (isset($import_xml->Классификатор->Группы)) {
                                $move_path = 'goods/';
                            } elseif (isset($import_xml->Каталог->Товары)) {
                                $move_path = 'goods/';
                            } elseif (isset($import_xml->Классификатор->Свойства)) {
                                $move_path = 'goods/';
                            }
                        } elseif (preg_match('/^offers(.*)\.xml$/', $_GET['filename'])) {

                            $import_xml = simplexml_load_file($upload_path . self::$upload1c . $_GET['filename']);
                            if (isset($import_xml->ПакетПредложений->Предложения)) {
                                $move_path = 'goods/';
                            } elseif (isset($import_xml->Классификатор)) {
                                $move_path = 'goods/';
                            }
                        } else {
                            $move_path = 'goods/';
                        }
                        if (!is_dir($upload_path . $move_path)) {
                            @mkdir($upload_path . $move_path, 0777, true);
                        }
                        preg_match_all('/^(offers|import|prices|rests)(?:.*)(\.xml)$/', $_GET['filename'], $new_name_parts);
                        $new_name = count($new_name_parts) ? $new_name_parts[1][0] . $new_name_parts[2][0] : $_GET['filename'];
                        rename($upload_path . self::$upload1c . $_GET['filename'], $upload_path . $move_path . $new_name);

                        // Парсер 
                        $this->parser($import_xml);

                        $response = "success";
                    } else {
                        $response = "failure";
                    }
                    break;
                case 'complete':
                    $complete_file = fopen($upload_path . 'completed.lock', 'w');
                    fputs($complete_file, time());
                    fclose($complete_file);
                    $response = "success";
                    break;

                case 'query':// Вырузка

                    PHPShopObj::loadClass(array("cml", "order"));
                    $PHPShopCommerceML = new PHPShopCommerceML();

                    switch ($type) {

                        case 'sale': // Вырузка заказов
                            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
                            $where['seller'] = "!='1'";

                            if ($this->exchange_load_status > 0)
                                $where['statusi'] = '=' . intval($this->exchange_load_status);

                            $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id desc'), array('limit' => 10));

                            header("Content-Type: text/xml;charset=windows-1251");
                            $response = $PHPShopCommerceML->getOrders($data);

                            if (is_array($data)) {

                                // Смена флага загрузки
                                if (is_array($PHPShopCommerceML->update_status))
                                    foreach ($PHPShopCommerceML->update_status as $id) {
                                        $PHPShopOrm->update(array('seller_new' => '1'), array('id' => '=' . $id));
                                    }
                            }

                            break;

                        case 'get_catalog': // Выгрузка товаров
                            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

                            $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id desc'), array('limit' => 100000));

                            header("Content-Type: text/xml;charset=windows-1251");
                            $response = $PHPShopCommerceML->getProducts($data);
                            if (empty($response))
                                $response = "success";

                            break;
                    }

                    break;
                default:
                    $response = "failure";
            }

        // Лог
        $this->log($type, $mode, $response);

        return $response . "\n";
    }

    private static function sessid_get($varname = 'sessid') {
        $sessid = null;
        if (!is_array($_SESSION) || !isset($_SESSION['fixed_session_id'])) {
            $_SESSION["fixed_session_id"] = session_id();
        } else {
            $sessid = $_SESSION["fixed_session_id"];
        }
        return $varname . "=" . $sessid;
    }

    private static function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

    private static function cleanup_import_directory($path) {
        $elements = scandir($path);
        foreach ($elements as $element) {
            if (in_array($element, array('.', '..')))
                continue;
            if (is_dir($path . '/' . $element)) {
                if (@!rmdir($path . '/' . $element)) {
                    self::cleanup_import_directory($path . '/' . $element);
                    @rmdir($path . '/' . $element);
                }
            } else {
                @unlink($path . '/' . $element);
            }
        }
    }

    // Смена кодировки
    private function array2iconv(&$value) {
        $value = iconv("UTF-8", "CP1251", $value);
    }

    // Каталоги
    private function parser_category($parent, $item) {

        $this->category_array[] = array($this->crc16((string) $item->Ид[0]), (string) $item->Наименование[0], (string) $parent);

        if (isset($item->Группы[0]))
            foreach ($item->Группы[0] as $items) {

                $this->category_array[] = array($this->crc16((string) $items->Ид[0]), (string) $items->Наименование[0], $this->crc16((string) $item->Ид[0]));

                if (isset($items->Группы[0]))
                    $this->parser_category($this->crc16((string) $items->Ид[0]), $items);
            }
    }

    private function writeCsv($file, $csv, $error = false) {
        $fp = @fopen($file, "w+");
        if ($fp) {
            foreach ($csv as $value) {
                fputcsv($fp, $value, ';', '"');
            }
            fclose($fp);
        } elseif ($error)
            echo 'No file ' . $file;
    }

    private function parser($xml) {
        global $parent_array, $sort_array, $properties_array;

        if ($xml) {

            // Создание папки
            $date = date("j-m-Y-H-i-s");
            if (!is_dir($this->result_path . $date)) {
                mkdir($this->result_path . $date, 0777, true);
            }

            $properties = null;
            $this->product_array[] = array("Артикул", "Наименование", "Краткое описание", "Имя картинки", "Подробное описание", "Кол-во картинок", "Остаток", "Цена1", "Цена2", "Цена3", "Цена4", "Цена5", "Вес", "Ед.измерения", "ISO", "Category ID", "Parent", "Характеристика", "Значение");


            // import.xml
            if (isset($xml->Классификатор->Группы) or $_GET['filename'] == 'import.xml') {

                $this->category_array[0] = array('CatalogID', 'Name', 'Parent');

                // Категории    
                foreach ($xml->Классификатор->Группы[0] as $item) {

                    $this->parser_category(0, $item);
                }

                // Свойства    
                foreach ($xml->Классификатор->Свойства[0] as $item) {

                    $properties_array[(string) $item->Ид[0]] = (string) $item->Наименование[0];
                }

				// Загрузка дополнительных полей справочника из МойСклад
                /*
                $file = 'http://priceexport.sklad24.online';
                $handle = fopen($file, "r");
                $i = 0;
                while ($data = fgetcsv($handle, 0, ';')) {
                    if (empty($i))
                        $csv_name = $data;
                    else
                        $csv_data[$data[0]] = $data;
                    $i++;
                }*/

                // Запись в файл
                if (count($this->category_array) > 1) {

                    // Очистка дублей
                    foreach ($this->category_array as $k => $val) {
                        if ($val[0] == $val[2])
                            unset($this->category_array[$k]);
                    }

                    array_walk_recursive($this->category_array, 'self::array2iconv');

                    $this->writeCsv('sklad/' . $date . '/tree.csv', $this->category_array, true);
                }

                // Товары 
                foreach ($xml->Каталог->Товары[0] as $item) {
                    // Тест
                    /*
                      if ($item->Ид[0] == '1'){
                      $item->Артикул[0] = 'test3434343';
                      $sort_array['1']= $sort_array['1#62'];
                      } */

                    // Краткое описание
                    if (isset($item->ЗначенияРеквизитов[0])) {
                        foreach ($item->ЗначенияРеквизитов[0] as $req) {

                            if ($req->Наименование[0] == 'Полное наименование') {
                                $description = (string) $req->Значение[0];
                            }
                        }
                    } else
                        $description = null;

                    // Подробное описание
                    if (isset($item->Описание)) {
                        $content = (string) $item->Описание;
                    } else
                        $content = null;

                    // Свойства  -  Характеристики
                    $properties = [];
                    if (isset($item->ЗначенияСвойств[0])) {
                        foreach ($item->ЗначенияСвойств[0] as $req) {

                            $properties[] = [$properties_array[(string) $req->Ид[0]], (string) $req->Значение[0]];
                        }
                    }

                    // Подтипы
                    $parent = null;

                    // Картинка
                    $image_count = 0;
                    $image = null;

                    // Категория
                    if (isset($item->Группы))
                        $category = $this->crc16((string) $item->Группы[0]->Ид);
                    else
                        $category = 0;

                    if (isset($item->Картинка) and ! empty($this->exchange_image)) {

                        if (!is_array((array) $item->Картинка))
                            (array) $item->Картинка[] = (string) $item->Картинка;

                        foreach ((array) $item->Картинка as $i => $img) {

                            $new_name = 'img' . $this->crc16((string) $item->Ид[0]) . '_' . ($i + 1) . '.jpg';
                            $new_name_s = 'img' . $this->crc16((string) $item->Ид[0]) . '_' . ($i + 1) . 's.jpg';
                            $new_name_big = 'img' . $this->crc16((string) $item->Ид[0]) . '_' . ($i + 1) . '_big.jpg';

                            // Тубнейл
                            $thumb = new PHPThumb(dirname(__FILE__) . $this->exchange_path . '/' . self::$upload1c . $img);
                            $thumb->setOptions(array('jpegQuality' => $this->width_kratko));
                            $thumb->resize($this->img_tw, $this->img_th);
                            $thumb->save($_SERVER['DOCUMENT_ROOT'] . $this->exchange_image_path . $this->image_result_path . $new_name_s);

                            // Основное
                            $thumb = new PHPThumb(dirname(__FILE__) . $this->exchange_path . '/' . self::$upload1c . $img);
                            $thumb->setOptions(array('jpegQuality' => $this->width_kratko));
                            $thumb->resize($this->img_w, $this->img_h);
                            $thumb->save($_SERVER['DOCUMENT_ROOT'] . $this->exchange_image_path . $this->image_result_path . $new_name);

                            // Исходное
                            if (!empty($this->image_save_source))
                                copy(dirname(__FILE__) . $this->exchange_path . '/' . self::$upload1c . $img, $this->exchange_image_path . $this->image_result_path . $new_name_big);

                            //copy(dirname(__FILE__) . $this->exchange_path . '/' . self::$upload1c . $img, $this->exchange_image_path . $new_name_s);
                            //copy(dirname(__FILE__) . $this->exchange_path . '/' . self::$upload1c . $img, $this->exchange_image_path . $new_name_big);
                            //rename(dirname(__FILE__) . $this->exchange_path . '/' . self::$upload1c . $img, '..' . $this->exchange_image_path . $this->image_result_path . $new_name);
                            $image = $this->image_result_path . 'img' . $this->crc16((string) $item->Ид[0]);
                            $image_count++;
                        }
                    }


                    // Артикул
                    if (!empty((string) $item->Артикул[0]) and $this->exchange_key == 'uid') {

                        $this->product_array[(string) $item->Артикул[0]] = array((string) $item->Артикул[0], (string) $item->Наименование[0], $description, $image, $content, $image_count, "", "", "", "", "", "", "", "", "", $category, $parent);

                        // Характеристики
                        if (is_array($sort_array[(string) $item->Ид[0]])) {

                            foreach ($sort_array[(string) $item->Ид[0]] as $sort)
                                $this->product_array[(string) $item->Артикул[0]][] = $sort;
                        }

                        // Свойства
                        if (is_array($properties))
                            foreach ($properties as $val)
                                if (is_array($val))
                                    foreach ($val as $value)
                                        $this->product_array[(string) $item->Артикул[0]][] = $value;
                    }

                    // Внешний код
                    elseif (!empty((string) $item->Ид[0]) and $this->exchange_key == 'external') {

                        // Подтипы 18#141
                        if (strstr((string) $item->Ид[0], '#')) {
                            $p = explode("#", (string) $item->Ид[0]);
                            $item->Ид[0] = $p[1];
                        }
                        $this->product_array[(string) $item->Ид[0]] = array((string) $item->Ид[0], (string) $item->Наименование[0], $description, $image, $content, $image_count, "", "", "", "", "", "", "", "", "", $category, $parent);

                        // Свойства
                        if (is_array($properties))
                            foreach ($properties as $val)
                                if (is_array($val))
                                    foreach ($val as $value)
                                        $this->product_array[(string) $item->Ид[0]][] = $value;
                    }
                }


                // Запись в файл
                if (count($this->product_array) > 1) {

                    array_walk_recursive($this->product_array, 'self::array2iconv');

                    $this->writeCsv('sklad/' . $date . '/upload_0.csv', $this->product_array, true);

                    // Выполнение
                    $this->load($date, true);
                }
            }

            // offers.xml
            else if (isset($xml->ПакетПредложений->Предложения) or $_GET['filename'] = 'offers.xml') {

                $parent_check = false;
                foreach ($xml->ПакетПредложений->Предложения->Предложение as $item) {

                    // Тест
                    //if ($item->Ид[0] == '1#62')
                    //$item->Артикул[0] = 'test3434343';
                    // Дополнительные склады 10/A#20/B
                    if (isset($item->Склад)) {

                        foreach ($item->Склад as $items) {
                            $warehouses[(string) $items[ИдСклада]] = (int) $items[КоличествоНаСкладе];
                        }

                        if (is_array($warehouses)) {
                            $warehouse = null;

                            foreach ($warehouses as $k => $v)
                                $warehouse .= $v . '/' . $k . '#';

                            $warehouse = substr($warehouse, 0, strlen($warehouse) - 1);
                        }
                    } else
                        $warehouse = (int) $item->Количество[0];

                    // Подтипы 18#141
                    if (strstr((string) $item->Ид[0], '#')) {

                        // Имя подтипа
                        $pattern = '/\((.+?)\)/';
                        preg_match_all($pattern, (string) $item->Наименование[0], $matches);

                        $parent = $matches[1][0];

                        if (empty($parent))
                            $parent = true;

                        if (empty($parent_check)) {

                            // Поиск подтипов
                            foreach ($xml->ПакетПредложений->Предложения->Предложение as $item) {

                                // Подтипы 18#141
                                if (strstr((string) $item->Ид[0], '#')) {
                                    $p = explode("#", (string) $item->Ид[0]);
                                    $parent_array[$p[0]] .= $p[1] . ',';
                                }
                            }
                            $parent_check = true;
                        }
                    }

                    // Главный товар с подтипами
                    else {

                        $parent = $parent_array[(string) $item->Ид[0]];
                        if (!empty($parent))
                            $parent = substr($parent, 0, strlen($parent) - 1);
                    }

                    // Характеристики
                    if (isset($item->ХарактеристикиТовара)) {

                        foreach ($item->ХарактеристикиТовара->ХарактеристикаТовара as $sorts) {
                            $sort_array[(string) $item->Ид[0]][] = (string) $sorts->Наименование;
                            $sort_array[(string) $item->Ид[0]][] = (string) $sorts->Значение;
                        }
                    }



                    // Артикул
                    if (!empty((string) $item->Артикул[0]) and $this->exchange_key == 'uid') {
                        $this->product_array[(string) $item->Артикул[0]] = array((string) $item->Артикул[0], (string) $item->Наименование[0], null, null, null, null, $warehouse, (string) $item->Цены->Цена[0]->ЦенаЗаЕдиницу[0], (string) $item->Цены->Цена[1]->ЦенаЗаЕдиницу[0], (string) $item->Цены->Цена[2]->ЦенаЗаЕдиницу[0], (string) $item->Цены->Цена[3]->ЦенаЗаЕдиницу[0], (string) $item->Цены->Цена[4]->ЦенаЗаЕдиницу[0], "", "", (string) $item->Цены->Цена[0]->Валюта[0], null, $parent);
                    }

                    // Внешний код
                    elseif (!empty((string) $item->Ид[0]) and $this->exchange_key == 'external') {

                        // Подтипы 18#141
                        if (strstr((string) $item->Ид[0], '#')) {
                            $p = explode("#", (string) $item->Ид[0]);
                            $item->Ид[0] = $p[1];
                        }

                        $this->product_array[(string) $item->Ид[0]] = array((string) $item->Ид[0], (string) $item->Наименование[0], null, null, null, null, $warehouse, (string) $item->Цены->Цена[0]->ЦенаЗаЕдиницу[0], (string) $item->Цены->Цена[1]->ЦенаЗаЕдиницу[0], (string) $item->Цены->Цена[2]->ЦенаЗаЕдиницу[0], (string) $item->Цены->Цена[3]->ЦенаЗаЕдиницу[0], (string) $item->Цены->Цена[4]->ЦенаЗаЕдиницу[0], "", "", (string) $item->Цены->Цена[0]->Валюта[0], null, $parent);
                    }
                }

                // Запись в файл
                if (count($this->product_array) > 1) {
                    array_walk_recursive($this->product_array, 'self::array2iconv');
                    $this->writeCsv('sklad/' . $date . '/upload_0.csv', $this->product_array, true);


                    // Выполнение
                    $this->load($date, false);

                    // Характеристики
                    if (is_array($sort_array)) {

                        $_GET['mode'] = 'file';
                        $this->exchange($_GET['type'], $_GET['mode']);

                        $_GET['mode'] = 'import';
                        $_GET['filename'] = 'import.xml';
                        $this->exchange($_GET['type'], $_GET['mode']);
                    }
                }
            }
        } else {
            echo "Ошибка загрузки XML\n";
            foreach (libxml_get_errors() as $error) {
                echo "\t", $error->message;
            }
        }
    }

    private function encode($pas) {
        $encode = null;
        for ($i = 0; $i < (strlen($pas)); $i++)
            $encode .= ord($pas[$i]) . "O";

        $encode = str_replace(11, "I", $encode);
        return $encode . "I10O";
    }

    private function load($date, $create_category = true) {
        $protocol = 'http://';
        if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $protocol = 'https://';
        }

        if ($this->exchange_create_category == 1 and ! empty($create_category))
            $create_category = 'true';

        if ($this->exchange_create == 1)
            $create = 'true';

        if (!empty($_SESSION['token']))
            $token = '&token=' . $_SESSION['token'];
        else
            $token = null;

        $url = $protocol . $_SERVER['SERVER_NAME'] . '/1cManager/result.php?date=' . $date . '&files=all&log=' . $_SERVER['PHP_AUTH_USER'] . '&pas=' . $this->encode($_SERVER['PHP_AUTH_PW']) . '&create=' . $create . '&create_category=' . $create_category . $token;

        $сurl = curl_init();
        curl_setopt_array($сurl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $action = curl_exec($сurl);
        curl_close($сurl);
        return $action;
    }

    private function log($type, $mode, $response) {

        if (!empty($this->exchange_log)) {
            $file = './log/cml_' . date("d_m_y") . '.log';

            if (isset($_GET['filename']))
                $filename .= '&filename=' . $_GET['filename'];
            else
                $filename = null;

            $content = '
==== ' . date('d-m-y H:i:s') . '=====
IN: ' . $_SERVER['PHP_SELF'] . '?type=' . $type . '&mod=' . $mode . $filename . '
OUT: ' . $response . '
' . $this->error;

            $fp = fopen($file, "a+");
            if ($fp) {
                fputs($fp, $content);
                fclose($fp);
            }
        }
    }

}

$_classPath = "../phpshop/";
include($_classPath . "class/obj.class.php");
include($_classPath . 'lib/thumb/phpthumb.php');
PHPShopObj::loadClass(array("base", "system", "array", "valuta"));

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
$PHPShopSystem = new PHPShopSystem();
$CommerceMLLoader = new CommerceMLLoader();
echo $CommerceMLLoader->exchange($_GET['type'], $_GET['mode']);
?>