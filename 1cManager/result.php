<?php

/**
 * Автономная синхронизация номенклатуры из 1С
 * @package PHPShopExchange
 * @author PHPShop Software
 * @version 2.5
 */
// Авторизация
include_once("login.php");
PHPShopObj::loadClass(array("readcsv", "product", "orm"));

$F_done = null;
$GetItemCreate = 0;
$GetItemUpdate = 0;
$GetCatalogCreate = 0;

// Номер ячейки характеристик для учета сдвига массива
$GLOBALS['option']['sort'] = 17;


// Персонализация
if (function_exists('mod_option')) {
    call_user_func_array('mod_option', array(&$GLOBALS['option']));
}

// Генератор характеристик v 2.0
function charsGenerator($category, $CsvToArray) {
    global $PHPShopBase;

    $return = null;

    // Отладка
    $debug = $_GET['debug'];

    // Нормализация нескольких значений
    for ($i = $GLOBALS['option']['sort']; $i < count($CsvToArray); $i = $i + 2) {

        $sort_name = trim($CsvToArray[$i]);
        $sort_value = trim($CsvToArray[$i + 1]);

        // Несколько значений
        if (strstr($sort_value, "&&")) {
            $sort_value_array = explode("&&", $sort_value);

            foreach ($sort_value_array as $value) {

                $CsvToArray[$i] = $sort_name;
                $CsvToArray[$i + 1] = $value;

                $CsvToArray[] = $sort_name;
                $CsvToArray[] = $value;
            }
        }
    }


    // Обработка 
    for ($i = $GLOBALS['option']['sort']; $i < count($CsvToArray); $i = $i + 2) {

        $sort_name = trim($CsvToArray[$i]);
        $sort_value = trim($CsvToArray[$i + 1]);
        
        if(empty($sort_name) or empty($sort_value)) 
            continue;

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
            if (empty($sort_name_present) and !empty($category)) {

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
                    $cat_set = $PHPShopOrm->insert(array('name_new' => 'Для каталога ' . $cat_name, 'category_new' => 0), '_new', __FUNCTION__, __LINE__);
                }


                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
                $PHPShopOrm->debug = $debug;
                if ($parent = $PHPShopOrm->insert(array('name_new' => $sort_name, 'category_new' => intval($cat_set)), '_new', __FUNCTION__, __LINE__)) {

                    // Создаем новое значение характеристики
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
                    $PHPShopOrm->debug = $debug;
                    $slave = $PHPShopOrm->insert(array('name_new' => $sort_value, 'category_new' => $parent), '_new', __FUNCTION__, __LINE__);

                    $return[$parent][] = intval($slave);
                    $cat_sort[] = $parent;

                    // Обновляем набор каталога товаров
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
                    $PHPShopOrm->debug = $debug;
                    $PHPShopOrm->update(array('sort_new' => serialize($cat_sort)), array('id' => '=' . intval($category)), '_new', __FUNCTION__, __LINE__);
                }
            }
            // Дописываем значение 
            else {

                // Получаем ИД существующей характеристики
                $PHPShopOrm = new PHPShopOrm();
                $PHPShopOrm->debug = $debug;
                $result = $PHPShopOrm->query('select a.id  from ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' AS a where a.name="' . $sort_name . '" ' . $where_in . ' limit 1', __FUNCTION__, __LINE__);
                if ($row = mysqli_fetch_array($result)) {
                    $parent = $row['id'];
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
                    $PHPShopOrm->debug = $debug;
                    $slave = $PHPShopOrm->insert(array('name_new' => $sort_value, 'category_new' => $parent), '_new', __FUNCTION__, __LINE__);

                    $return[$parent][] = intval($slave);
                }
            }
        }
    }

    return $return;
}

// Обработка каталогов
class ReadCsvCatalog extends PHPShopReadCsvNative {

    var $CsvContent;
    var $ReadCsvRow;
    var $TableName;
    var $CsvToArray;
    var $ItemCreate = 0;

    function __construct($file) {
        $this->CsvContent = $this->read($file);
        $this->TableName = $GLOBALS['SysValue']['base']['categories'];
    }

    function read($file) {

        if (file_exists($file)) {
            $fp = @fopen($file, "r");
            $i = 0;
            if ($this->title_clean)
                $i = 0;
            else
                $i = 1;
            while (($data = @fgetcsv($fp, $this->size, $this->delim)) !== FALSE) {
                if ($i > 0)
                    $this->CsvToArray[$data[0]] = $data;
                $i++;
            }
            fclose($fp);
        }
        else
            echo ("Не могу прочитать файл " . $file);
    }

    // Создаем новую запись
    function CreateCatalog($id) {
        global $link_db;
        $CsvToArray = $this->CsvToArray[$id];
        if (is_array($CsvToArray)) {
            $sql = "INSERT INTO " . $this->TableName . " SET
     id = '" . trim($CsvToArray[0]) . "',
     name = '" . addslashes($CsvToArray[1]) . "', 
     parent_to = '" . trim($CsvToArray[2]) . "' ";

            // Отладка
            if (isset($_GET['debug'])) {
                echo $sql . PHP_EOL;
                $result = mysqli_query($link_db, $sql) or die(mysqli_error($link_db)) . PHP_EOL;
            }
            else
                $result = mysqli_query($link_db, $sql);

            if ($result)
                $this->ItemCreate++;
        }
    }

    function GetItemCreate() {
        return $this->ItemCreate;
    }

    // Вложенная проверка дерева каталогов
    function ChekTree($id) {
        $row = $this->CsvToArray;
        $parent = $row[$id][2];
        $CheckId = parent::CheckId($id);
        if (empty($CheckId))
            $this->CreateCatalog($id);
        if ($parent != 0) {
            $CheckIdParent = parent::CheckId($parent);
            if (empty($CheckIdParent))
                $this->ChekTree($parent);
        }
    }

}

// Обработка товаров
class ReadCsv1C extends PHPShopReadCsvNative {

    var $CsvContent;
    var $ReadCsvRow;
    var $TableName;
    var $Sklad_status;
    var $ObjCatalog, $ObjSystem;
    var $ItemCreate = 0;
    var $ItemUpdate = 0;
    var $ImageSrc = "jpg";

    function __construct($CsvContentFile, $ObjCatalog, $ObjSystem) {
        $this->ImagePath = $GLOBALS['SysValue']['dir']['dir'] . "/UserFiles/Image/";
        //$this->CsvContent = $CsvContent;
        $this->TableName = $GLOBALS['SysValue']['base']['products'];
        $this->TableNameFoto = $GLOBALS['SysValue']['base']['foto'];
        $this->Sklad_status = $ObjSystem->getSerilizeParam("admoption.sklad_status");
        $this->ObjCatalog = $ObjCatalog;
        $this->ObjSystem = $ObjSystem;
        $this->GetIdValuta = PHPShopValuta::getAll(true);
        parent::__construct($CsvContentFile);
        $this->DoUpdatebase();
    }

    // Кол-во созданных товаров
    function GetItemCreate() {
        return $this->ItemCreate;
    }

    // Кол-во измененных товаров
    function GetItemUpdate() {
        return $this->ItemUpdate;
    }

    // Кол-во созданных каталогов
    function GetCatalogCreate() {
        if ($this->ObjCatalog)
            $num = $this->ObjCatalog->GetItemCreate();
        else
            $num = 0;
        return $num;
    }

    // Путь к картинке
    function ImagePlus($img) {
        if (!empty($img))
            return $this->ImagePath . $img;
    }

    // Создаем каталоги
    function DoUpdatebase() {
        $CsvToArray = $this->CsvToArray;
        if (is_array($CsvToArray)) {
            foreach ($CsvToArray as $v) {
                $this->UpdateBaseCatalog($v[15]);
                $this->UpdateBase($v);
            }
        }
    }

    // Создаем каталоги
    function UpdateBaseCatalog($category) {
        if ($this->ObjCatalog)
            $this->ObjCatalog->ChekTree($category);
    }

    // Проверка кол-ва фото
    function GetNumFoto($id) {
        global $link_db;
        $sql = "select id from " . $this->TableNameFoto . " where parent=$id";
        $result = mysqli_query($link_db, $sql);
        return @mysqli_num_rows($result);
    }

    // Получения Ид товара по артикулу
    function getIdForImages($uid) {
        global $link_db;
        $sql = "select id from " . $this->TableName . " where uid='$uid' limit 1";
        $result = mysqli_query($link_db, $sql);
        $row = mysqli_fetch_array($result);
        return $row['id'];
    }

    // Обновление данных
    function UpdateBase($CsvToArray) {
        global $link_db;

        // Есть ли товары в базе
        if ($_REQUEST['create'] == "true")
            $CheckBase = parent::CheckUid($CsvToArray[0]);
        else
            $CheckBase = true;

        // Обновляем
        if (!empty($CheckBase)) {

            $sql = "UPDATE " . $this->TableName . " SET ";

            // Персонализация
            if (function_exists('mod_update')) {
                $sql.=call_user_func_array('mod_update', array(&$CsvToArray, __CLASS__, __FUNCTION__));
            }

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_name") == 1 and !empty($CsvToArray[1]))
                $sql.="name='" . addslashes($CsvToArray[1]) . "', "; // название

            if ($this->ObjSystem->getSerilizeParam('1c_option.update_content') == 1 and !empty($CsvToArray[4]))
                $sql.="content='" . addslashes($CsvToArray[4]) . "', "; // краткое описание

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_description") == 1 and !empty($CsvToArray[2]))
                $sql.="description='" . addslashes($CsvToArray[2]) . "', "; // подробное описание

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and !empty($CsvToArray[15]))
                $sql.="category='" . trim($CsvToArray[15]) . "', "; // категория

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_price") == 1 and !empty($CsvToArray[7]))
                $sql.="price='" . @$CsvToArray[7] . "', "; // цена 1


            // Склад
            if ($this->ObjSystem->getSerilizeParam("1c_option.update_item") == 1) {
                switch ($this->Sklad_status) {

                    // Товар ставится под заказ
                    case(3):
                        if ($CsvToArray[6] < 1)
                            $sql.="sklad='1', enabled='1', p_enabled='0', ";
                        else
                            $sql.="sklad='0', enabled='1', p_enabled='1', ";
                        break;

                    // Товар убирается с продаж
                    case(2):
                        if ($CsvToArray[6] < 1)
                            $sql.="enabled='0', p_enabled='0', ";
                        else
                            $sql.="enabled='1', sklad='0', p_enabled='1', ";
                        break;

                    // Не исползуется
                    default: $sql.="";
                }
            }
            else {
                $sklad = 0;
                $enabled = 1;
            }

            if (!empty($CsvToArray[3])) {
                $sql.="pic_small='" . $this->ImagePlus($CsvToArray[3]) . "_1s." . $this->ImageSrc . "',";
                $sql.="pic_big='" . $this->ImagePlus($CsvToArray[3]) . "_1." . $this->ImageSrc . "',";
            }

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_price") == 1) {

                $sql.="price2='" . @$CsvToArray[8] . "', "; // цена 2
                $sql.="price3='" . @$CsvToArray[9] . "', "; // цена 3
                $sql.="price4='" . @$CsvToArray[10] . "', "; // цена 4
                $sql.="price5='" . @$CsvToArray[11] . "', "; // цена 5
            }

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_item") == 1)
                $sql.="items='" . @$CsvToArray[6] . "', "; // склад

            if (PHPShopProductFunction::true_parent($CsvToArray[0])) {
                $sql.="parent_enabled='1', ";
            } else {
                $sql.="parent_enabled='0', ";
            }

            // Подчиненные товары
            if (strstr($CsvToArray[16], "@")) {
                $parent_array = explode("@", $CsvToArray[16]);
                $sql.="parent='" . $parent_array[0] . "', parent2='" . $parent_array[1] . "',";
            }
            else
                $sql.="parent='" . $CsvToArray[16] . "', ";

            // вес
            if (!empty($CsvToArray[12]))
                $sql.="weight='" . $CsvToArray[12] . "', ";

            // Валюта
            if (!empty($CsvToArray[14]))
                $sql.="baseinputvaluta='" . $this->GetIdValuta[$CsvToArray[14]] . "', ";

            $sql.="datas='" . date("U") . "' "; // дата изменения

            $sql.=" where uid='" . $CsvToArray[0] . "'";

            // Отладка
            if (isset($_GET['debug'])) {
                echo $sql . PHP_EOL;
                $result = mysqli_query($link_db, $sql) or die(mysqli_error($link_db)) . PHP_EOL;
            }
            else
                $result = mysqli_query($link_db, $sql);

            if ($result)
                $this->ItemUpdate++;

            // Добавляем картинки в галерею
            if (!empty($CsvToArray[3])) {
                $last_id = $this->getIdForImages($CsvToArray[0]);
                $ready_num_img = $this->GetNumFoto($last_id);
                $img_num = $CsvToArray[5] - $ready_num_img;
                $img_num = +$ready_num_img;
                while ($img_num < $CsvToArray[5]) {
                    $ImgName = $this->ImagePlus($CsvToArray[3]) . "_" . ($img_num + 1) . "." . $this->ImageSrc;
                    $sql = "INSERT INTO " . $this->TableNameFoto . " VALUES ('',$last_id,'$ImgName','$img_num','')";
                    $result = mysqli_query($link_db, $sql);
                    $img_num++;
                }
            }

            // Обновляем характеристики
            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and $this->ObjSystem->getSerilizeParam("1c_option.update_sort") == 1 and !empty($CsvToArray[$GLOBALS['option']['sort']])) {

                // Генератор характеристик
                $resCharsArray = charsGenerator($CsvToArray[15], $CsvToArray);

                if (is_array($resCharsArray)) {
                    $vendor = null;
                    $resSerialized = serialize($resCharsArray);
                    foreach ($resCharsArray as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $o => $p) {
                                $vendor.="i" . $k . "-" . $p . "i";
                            }
                        } else {
                            $vendor.="i" . $k . "-" . $v . "i";
                        }
                    }


                    $sql = "UPDATE " . $this->TableName . " SET ";
                    $sql.="vendor='" . $vendor . "', ";
                    $sql.="vendor_array='" . $resSerialized . "' ";
                    $sql.=" where uid='" . $CsvToArray[0] . "'";

                    // Отладка
                    if (isset($_GET['debug'])) {
                        echo $sql . PHP_EOL;
                        $result = mysqli_query($link_db, $sql) or die(mysqli_error($link_db)) . PHP_EOL;
                    }
                    else
                        $result = mysqli_query($link_db, $sql);
                }
            }
        }
        // Создаем новый товар
        else {

            // Склад
            if ($this->ObjSystem->getSerilizeParam("1c_option.update_item") == 1) {
                switch ($this->Sklad_status) {

                    // Товар ставится под заказ
                    case(3):
                        if ($CsvToArray[6] < 1) {
                            $sklad = 1;
                            $enabled = 1;
                            $p_enabled = 0;
                        } else {
                            $sklad = 0;
                            $enabled = $p_enabled = 1;
                        }
                        break;

                    // Товар убирается с продаж
                    case(2):
                        if ($CsvToArray[6] < 1)
                            $enabled = $p_enabled = 0;
                        else
                            $enabled = $p_enabled = 1;
                        break;

                    // Не исползуется
                    default:
                        $sklad = 0;
                        $enabled = $p_enabled = 1;
                        break;
                }
            }
            else {
                $sklad = 0;
                $enabled = $p_enabled = 1;
            }

            // Добавляем характеристики
            $vendor = $vendor_array = null;

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and $this->ObjSystem->getSerilizeParam("1c_option.update_sort") == 1 and !empty($CsvToArray[$GLOBALS['option']['sort']])) {

                // Генератор характеристик
                $resCharsArray = charsGenerator($CsvToArray[15], $CsvToArray);
                if (is_array($resCharsArray)) {
                    $resSerialized = serialize($resCharsArray);
                    foreach ($resCharsArray as $k => $v) {
                        if (is_array($v)) {
                            foreach ($v as $o => $p) {
                                $vendor.="i" . $k . "-" . $p . "i";
                            }
                        } else {
                            $vendor.="i" . $k . "-" . $v . "i";
                        }
                    }
                }
                $vendor_array = serialize($resCharsArray);
            }

            $sql = "INSERT INTO " . $this->TableName . " SET ";

            // Персонализация
            if (function_exists('mod_insert')) {
                $sql.=call_user_func_array('mod_insert', array(&$CsvToArray, __CLASS__, __FUNCTION__));
            }

            // Родительская категория
            if ($this->ObjSystem->getSerilizeParam("1c_option.update_category") == 1 and !empty($CsvToArray[15]))
                $sql.="category='" . trim($CsvToArray[15]) . "',";
            else
                $sql.="category='1000001',";

            $sql.="name='" . addslashes(trim($CsvToArray[1])) . "',
            description='" . addslashes($CsvToArray[2]) . "',
            content='" . addslashes($CsvToArray[4]) . "',
            price='" . $CsvToArray[7] . "',
            sklad='" . $sklad . "',
            p_enabled='" . $p_enabled . "',
            enabled='" . $enabled . "',
            uid='" . $CsvToArray[0] . "',
            yml='1',
            datas='" . time() . "',
            vendor='" . $vendor . "',
            vendor_array='" . $vendor_array . "',";

            if (!empty($CsvToArray[3]))
                $sql.="pic_small='" . $this->ImagePlus($CsvToArray[3]) . "_1s." . $this->ImageSrc . "',
            pic_big='" . $this->ImagePlus($CsvToArray[3]) . "_1." . $this->ImageSrc . "',";


            if (PHPShopProductFunction::true_parent($CsvToArray[0])) {
                $sql.="parent_enabled='1', ";
            } else {
                $sql.="parent_enabled='0', ";
            }

            // Подчиненные товары v 2.1
            if (strstr($CsvToArray[16], "@")) {
                $parent_array = explode("@", $CsvToArray[16]);
                $sql.="parent='" . $parent_array[0] . "', parent2='" . $parent_array[1] . "',";
            }
            else
                $sql.="parent='" . $CsvToArray[16] . "', ";

            // Подчиненные товары v 2.0
            //$sql.="parent='" . $CsvToArray[16] . "', ";
            // Подчиненные товары v 1.5
            /*
              if (is_numeric($CsvToArray[16]) and $CsvToArray[16] == 1) {
              $sql.="parent_enabled='1', ";
              } else {
              $sql.="parent_enabled='0', ";
              $sql.="parent='" . $CsvToArray[16] . "', ";
              } */

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_item") == 1)
                $sql.="items='" . $CsvToArray[6] . "',";

            if ($this->ObjSystem->getSerilizeParam("1c_option.update_price") == 1) {
                $sql.="weight='" . $CsvToArray[12] . "',
            price2='" . $CsvToArray[8] . "',
            price3='" . $CsvToArray[9] . "',
            price4='" . $CsvToArray[10] . "',
            price5='" . $CsvToArray[11] . "',";
            }

            $sql.="baseinputvaluta='" . $this->GetIdValuta[$CsvToArray[14]] . "',
            ed_izm='" . $CsvToArray[13] . "'";

            // Отладка
            if (isset($_GET['debug'])) {
                echo $sql . PHP_EOL;
                $result = mysqli_query($link_db, $sql) or die(mysqli_error($link_db)) . PHP_EOL;
            }
            else
                $result = mysqli_query($link_db, $sql);

            $this->ItemCreate++;

            // Добавляем картинки в галерею
            $img_num = 1;
            if (!empty($CsvToArray[3])) {
                //$last_id=$this->getIdForImages($CsvToArray[0]);
                $last_id = mysqli_insert_id($link_db);
                while ($img_num <= $CsvToArray[5]) {
                    $ImgName = $this->ImagePlus($CsvToArray[3]) . "_" . $img_num . "." . $this->ImageSrc;
                    $sql = "INSERT INTO " . $this->TableNameFoto . " VALUES ('',$last_id,'$ImgName','$img_num','')";
                    $result = mysqli_query($link_db, $sql);
                    $img_num++;
                }
            }
        }
    }

}

// форматирование даты
if (preg_match("/[^(0-9)|(\-)]/", $_GET['date']))
    $date = "";
else
    $date = $_GET['date'];

// Проверка режима Lite для ShopBuilder
if (empty($GLOBALS['option']['shopbuilder'])) {
    $path = "sklad";
    $dir = $path . "/" . $date;
} else {
    $path = "../phpshop/templates/1cManager/sklad";
    $dir = $path . "/" . $date;
}

// Читаем категории
if ($_GET['create_category'] == "true")
    $ReadCsvCatalog = new ReadCsvCatalog($dir . "/tree.csv");
else
    $ReadCsvCatalog = false;

// Подключаем настройки магазина
$PS = new PHPShopSystem();

// Смотрим папку
if ($_GET['files'] == "all" and is_dir($dir))
    if (@$dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {

            if ($file != "." and $file != ".." and $file != "tree.csv")
                $list_file[] = $file;
        }
        closedir($dh);
    }
if (is_file("./" . $dir . "/" . $_GET['files'])) {
    $list_file[] = $_GET['files'];
}

// Тестирование
if (isset($error)) {
    if (is_array($list_file))
        $list_file[$error] = "";
}


if (is_array($list_file))
    foreach ($list_file as $val) {

        // Включаем таймер
        $time = explode(' ', microtime());
        $start_time = $time[1] + $time[0];

        $fp = $dir . "/" . $val;
        if (file_exists($fp) and $val != 'tree.csv') {

            // Читаем файл
            $ReadCsv = new ReadCsv1C($fp, $ReadCsvCatalog, $PS);
            $F_done.=$val . ";";

            $GetCatalogCreate+=$ReadCsv->GetCatalogCreate();
            $GetItemCreate+=$ReadCsv->GetItemCreate();
            $GetItemUpdate+=$ReadCsv->GetItemUpdate();

            // Персонализация
            if (function_exists('mod_end_load')) {
                call_user_func_array('mod_end_load', array($ReadCsv, __CLASS__, __FUNCTION__));
            }

            // Результат
            if ($_GET['files'] != "all")
                echo $date . ";" . $F_done . "
" . $GetItemCreate . ";" . $GetItemUpdate . ";" . $GetCatalogCreate . ";";

            // Выключаем таймер
            $time = explode(' ', microtime());
            $seconds = ($time[1] + $time[0] - $start_time);
            $seconds = substr($seconds, 0, 6);

            // Журнал
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name12']);
            $PHPShopOrm->insert(array('datas_new' => time(), 'p_name_new' => $date, 'f_name_new' => $val, 'time_new' => $seconds));
        }
    }
else
    exit("Не могу прочитать файл " . $dir . "/" . $val);

if ($_GET['files'] == "all")
    echo $date . ";" . $F_done . "
" . $GetItemCreate . ";" . $GetItemUpdate . ";" . $GetCatalogCreate . ";";
?>