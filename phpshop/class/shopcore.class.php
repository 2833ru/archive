<?php

/**
 * Родительский класс ядра вывода товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopShopCore
 * @version 1.4
 * @package PHPShopClass
 */
class PHPShopShopCore extends PHPShopCore {

    /**
     * Фото-заглушка при отсутствии фото товара
     * @var string
     */
    var $no_photo = 'images/shop/no_photo.gif';

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;

    /**
     * Кэширование, рекомендуется [true]
     * @var bool
     */
    var $cache = true;

    /**
     * Форматирование элементов кэша
     * @var array
     */
    var $cache_format = array('content', 'yml_bid_array');

    /**
     * Генерация рамок в сетки товаров
     * @var bool
     */
    var $grid = true;

    /**
     * Лимит вывода записей на 1 странице, рекомендуется 100-300
     * @var int
     */
    var $max_item = 100;

    /**
     * Память параметов выполнения функций и модулей. При проектировании модулей и хуков следует отключить память [false]
     * @var bool
     */
    var $memory = true;
    var $multi_cat = array();

    /**
     * Тип верстки таблиц товаров [default | li | div]
     * @var string  
     */
    var $cell_type = 'default';

    /**
     * Класс элемента товара
     * @var string 
     */
    var $cell_type_class = 'product-block';

    /**
     * Максимальная и минимальная цена
     */
    var $price_min = 0;
    var $price_max = 0;

    /**
     * Конструктор
     */
    function __construct() {
        global $PHPShopValutaArray;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['products'];

        // Массив валют
        $this->Valuta = $PHPShopValutaArray->getArray();

        PHPShopObj::loadClass('product');
        parent::__construct();

        // Валюта товара
        $this->dengi = $this->PHPShopSystem->getParam('dengi');
        $this->currency = $this->currency();

        // Настройки
        $this->parent_price_enabled = $this->PHPShopSystem->getSerilizeParam('admoption.parent_price_enabled');
        $this->user_price_activate = $this->PHPShopSystem->getSerilizeParam('admoption.user_price_activate');
        $this->sklad_enabled = $this->PHPShopSystem->getSerilizeParam('admoption.sklad_enabled');
        $this->sklad_status = $this->PHPShopSystem->getSerilizeParam('admoption.sklad_status');
        $this->format = intval($this->PHPShopSystem->getSerilizeParam("admoption.price_znak"));

        // HTML опции верстки
        $this->setHtmlOption(__CLASS__);
    }

    /**
     * Поддержка старого конструктора
     */
    function __call($name, $arguments) {
        if ($name == __CLASS__) {
            self::__construct();
        }
    }

    /**
     * Генерация SQL запроса для выборки
     * @param string $where параметр отбора
     * @return mixed
     */
    function query_filter($where = false) {

        if (!empty($where))
            $where.=' and ';

        $sort = null;

        $this->set('productRriceOT', 0);
        $this->set('productRriceDO', 0);

        $v = @$_GET['v'];
        $s = intval(@$_GET['s']);
        $f = intval(@$_GET['f']);

        if ($this->PHPShopNav->isPageAll())
            $p = PHPShopSecurity::TotalClean($p, 1);

        // Сортировка по характеристикам
        if (is_array($v)) {

            $sort.=" and (";
            foreach ($v as $key => $val) {
                if (PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($val)) {
                    $hash = $key . "-" . $val;
                    $sort.=" vendor REGEXP 'i" . $hash . "i' or";
                }
            }
            $sort = substr($sort, 0, strlen($sort) - 2);
            $sort.=") ";
        }


        // Накрутка
        $percent = $this->PHPShopSystem->getValue('percent');

        // Форма вывода
        switch ($_GET['gridChange']) {
            case 1:
                $this->set('gridSetAactive', 'active');
                break;
            case 2:
                $this->set('gridSetBactive', 'active');
                break;
            default: $this->set('gridSetBactive', 'active');
        }

        // Сортировка принудительная пользователем
        switch ($f) {
            case(1): $order_direction = "";
                $this->set('productSortNext', 2);
                $this->set('productSortImg', 1);
                $this->set('productSortT', 1);
                $this->set('fSetBactive', 'active');
                break;
            case(2): $order_direction = " desc";
                $this->set('productSortNext', 1);
                $this->set('productSortImg', 2);
                $this->set('productSortT', 2);
                $this->set('fSetAactive', 'active');
                break;
            default: $order_direction = "";
                $this->set('productSortNext', 2);
                $this->set('productSortImg', 1);
                $this->set('productSortT', 1);
                $this->set('fSetBactive', 'active');
                break;
        }
        switch ($s) {
            case(1): $order = array('order' => 'name' . $order_direction);
                $this->set('productSortA', 'sortActiv');
                $this->set('sSetBactive', 'active');
                break;
            case(2): $order = array('order' => 'price' . $order_direction);
                $this->set('productSortB', 'sortActiv');
                $this->set('sSetAactive', 'active');
                break;
            default:
                $order = array('order' => 'num' . $order_direction);
                $this->set('productSortC', 'sortActiv');
                $this->set('sSetCactive', 'active');
                break;
        }

        // Преобразуем массив условия сортировки в строку
        foreach ($order as $key => $val)
            $string = $key . ' by ' . $val;

        // Все страницы
        if ($this->PHPShopNav->isPageAll()) {
            $sql = "select * from " . $this->getValue('base.products') . " where (" . $where . " enabled='1' and parent_enabled='0') " . $sort . " " . $string . ' limit ' . $this->max_item;
        }

        // Поиск по цене
        elseif (isset($_POST['priceSearch']) or !empty($sort)) {

            if (!empty($_POST['priceOT']) or !empty($_POST['priceDO'])) {
                $priceOT = intval($_POST['priceOT']);
                $priceDO = intval($_POST['priceDO']);

                $this->set('productRriceOT', $priceOT);
                $this->set('productRriceDO', $priceDO);

                // Бесконечность
                if ($priceDO == 0)
                    $priceDO = 1000000000;

                if (empty($priceOT))
                    $priceOT = 0;

                // Цена с учетом выбранной валюты
                $priceOT/=$this->currency('kurs');
                $priceDO/=$this->currency('kurs');

                // Условие поиска по цене
                $price_sort = "and price >= " . ($priceOT / (100 + $percent) * 100) . " AND price <= " . ($priceDO / (100 + $percent) * 100);
            }

            $sql = "select * from " . $this->getValue('base.products') . " where " . $where . " enabled='1' and parent_enabled='0' " . $price_sort . " " . $sort . $string . ' limit 0,' . $this->max_item;
        }
        else {
            // Возвращаем массив параметра сортировки результата
            return $order;
        }

        // Возвращаем SQL сложный запрос
        return $sql;
    }

    /**
     * Валюта
     * @param string $name имя поля в таблице валют для выдачи
     * @return string
     */
    function currency($name = 'code') {

        if (isset($_SESSION['valuta']))
            $currency = $_SESSION['valuta'];
        else
            $currency = $this->dengi;

        $row = $this->select(array('*'), array('id' => '=' . intval($currency)), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.currency'), 'cache' => 'true'));

        if ($name == 'code' and ($row['iso'] == 'RUR' or $row['iso'] == "RUB"))
            return 'p';

        return $row[$name];
    }

    /**
     * Выборка из БД
     * @param array $select массив условий выборки
     * @param array $where массив условий выборки
     * @param array $order массив условий выборки
     * @param array $option массив условий выборки
     * @param string $function_name имя функции для отладки
     * @param array $from массив опций
     * @param array $mysql_error блокировка ошибок
     * @return array
     */
    function select($select, $where, $order = false, $option = array('limit' => 1), $function_name = false, $from = false, $mysql_error = true) {

        if (is_array($from)) {
            $base = @$from['base'];
            $cache = @$from['cache'];
            if (!empty($from['cache_format']))
                $cache_format = $from['cache_format'];
        } else {
            $base = $this->objBase;
            $cache = $this->cache;
            $cache_format = $this->cache_format;
        }

        $PHPShopOrm = new PHPShopOrm($base);
        $PHPShopOrm->objBase = $base;
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->cache = $cache;
        $PHPShopOrm->mysql_error = $mysql_error;
        $PHPShopOrm->cache_format = $cache_format;
        $result = $PHPShopOrm->select($select, $where, $order, $option, __CLASS__, $function_name);

        return $result;
    }

    /**
     * Стоимость товара
     * @param array $row массив данных товара
     * @param bool $newprice изменилась цена
     * @return float
     */
    function price($row, $newprice = false) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, $newprice);
            if ($hook) {
                return $hook;
            }
            else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        // Если есть новая цена
        if (empty($newprice)) {
            $price = $row['price'];
        } else {
            $price = $row['price_n'];
            $row['price2'] = $row['price3'] = $row['price4'] = $row['price5'] = null;
        }



        return PHPShopProductFunction::GetPriceValuta($row['id'], array($price, $row['price2'], $row['price3'], $row['price4'], $row['price5']), $row['baseinputvaluta']);
    }

    /**
     * Генерация пагинатора
     * @param int $count количество товаров на странице
     * @param string $sql SQL запрос в виде строки для сложных выборок (применение AND и OR в одном условии, начмная от WHERE)
     */
    function setPaginator($count = null, $sql = null) {

        // Перехват модуля в начале функции
        if ($this->setHook(__CLASS__, __FUNCTION__, array('count' => $count, 'sql' => $sql), 'START'))
            return true;

        // проверяем наличие шаблонов пагинации в папке шаблона
        // если отсутствуют, то используем шаблоны из lib
        $type = $this->memory_get(__CLASS__ . '.' . __FUNCTION__);
        if (!$type) {
            if (!PHPShopParser::checkFile("paginator/paginator_one_link.tpl")) {
                $type = "lib";
            } else {
                $type = "templates";
            }

            $this->memory_set(__CLASS__ . '.' . __FUNCTION__, $type);
        }

        if ($type == "lib") {
            $template_location = "./phpshop/lib/templates/";
            $template_location_bool = true;
        }

        // Кол-во данных
        $this->count = $count;
        $SQL = null;

        // Выборка по параметрам WHERE
        $nWhere = 1;
        if (is_array($this->where)) {
            foreach ($this->where as $pole => $value) {
                $SQL.=$pole . $value;
                if ($nWhere < count($this->where))
                    $SQL.=$this->PHPShopOrm->Option['where'];
                $nWhere++;
            }
        }
        else
            $SQL = $sql;


        $sort = '?';

        // Фильтры
        if (!empty($_GET['v']) and is_array($_GET['v']))
            foreach ($_GET['v'] as $key => $val) {

                if (is_array($val)) {

                    foreach ($val as $v)
                        $sort.='v[' . $key . '][]=' . $v . '&';
                } else if (is_numeric($key) and is_numeric($val))
                    $sort.='v[' . $key . ']=' . $val . '&';
            }

        // Сортировка
        if (!empty($_GET['s']) and is_numeric($_GET['s']))
            $sort.='s=' . $_GET['s'] . '&';
        if (!empty($_GET['f']) and is_numeric($_GET['f']))
            $sort.='f=' . $_GET['f'] . '&';


        $sort = substr($sort, 0, strlen($sort) - 1);

        // Всего страниц
        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $result = $this->PHPShopOrm->query("select COUNT('id') as count from " . $this->objBase . ' where ' . $SQL);
        $row = mysqli_fetch_array($result);
        $this->num_page = $row['count'];

        $i = 1;
        $navigat = $delim;

        // Кол-во страниц в навигации
        $num = ceil($this->num_page / $this->num_row);
        $this->max_page = $num;

        // 404 ошибка при ошибочной пагинации
        if ($this->page > $this->num_page and $this->page != 'ALL') {
            return $this->setError404();
        }

        if ($num > 1) {
            if ($this->page >= $num) {
                $p_to = $i - 1;
                $p_do = $this->page - 1;
            } else {
                $p_to = $this->page + 1;
                $p_do = 1;
            }


            if ($this->page != 'ALL')
                $this->set("paginPageCurnet", $this->page);
            else
                $this->set("paginPageCurnet", '-');
            $this->set("paginPageCount", $num);

            while ($i <= $num) {
                if ($i > 1) {
                    $p_start = $this->num_row * ($i - 1) + 1;
                    $p_end = $p_start + $this->num_row - 1;
                } else {
                    $p_start = $i;
                    $p_end = $this->num_row;
                }

                $this->set("paginPageRangeStart", $p_start);
                $this->set("paginPageRangeEnd", $p_end);
                $this->set("paginPageNumber", $i);

                if ($i != $this->page) {
                    if ($i == 1) {
                        $this->set("paginLink", $GLOBALS['SysValue']['dir']['dir'] . substr($this->objPath, 0, strlen($this->objPath) - 1) . '.html' . $sort);
                        $navigat.= parseTemplateReturn($template_location . "paginator/paginator_one_link.tpl", $template_location_bool);
                    } else {
                        if ($i > ($this->page - $this->nav_len) and $i < ($this->page + $this->nav_len)) {
                            $this->set("paginLink", $GLOBALS['SysValue']['dir']['dir'] . $this->objPath . $i . '.html' . $sort);
                            $navigat.= parseTemplateReturn($template_location . "paginator/paginator_one_link.tpl", $template_location_bool);
                        } else if ($i - ($this->page + $this->nav_len) < 3 and (($this->page - $this->nav_len) - $i) < 3) {
                            $navigat.= parseTemplateReturn($template_location . "paginator/paginator_one_more.tpl", $template_location_bool);
                        }
                    }
                }
                else
                    $navigat.= parseTemplateReturn($template_location . "paginator/paginator_one_selected.tpl", $template_location_bool);

                $i++;
            }

            $this->set("pageNow", $this->getValue('lang.page_now'));
            $this->set("navBack", $this->lang('nav_back'));
            $this->set("navNext", $this->lang('nav_forw'));
            $this->set("navigation", $navigat);


            // Убираем дубль первой страницы CID_X_1.html
            if ($p_do == 1)
                $this->set("previousLink", $GLOBALS['SysValue']['dir']['dir'] . substr($this->objPath, 0, strlen($this->objPath) - 1) . '.html' . $sort);
            else
                $this->set("previousLink", $GLOBALS['SysValue']['dir']['dir'] . $this->objPath . ($p_do) . '.html' . $sort);


            // Убираем дубль первой страницы CID_X_0.html
            if ($p_to == 0 or strtoupper($this->page) == 'ALL')
                $this->set("nextLink", $GLOBALS['SysValue']['dir']['dir'] . substr($this->objPath, 0, strlen($this->objPath) - 1) . '.html' . $sort);
            else
                $this->set("nextLink", $GLOBALS['SysValue']['dir']['dir'] . $this->objPath . ($p_to) . '.html' . $sort);

            // Добавлем ссылку показать все
            if (strtoupper($this->page) == 'ALL')
                $this->set("allPages", parseTemplateReturn($template_location . "paginator/paginator_all_pages_link_selected.tpl", $template_location_bool));
            else {
                $this->set("allPagesLink", $this->objPath . 'ALL.html' . $sort);
                $this->set("allPages", parseTemplateReturn($template_location . "paginator/paginator_all_pages_link.tpl", $template_location_bool));
            }

            // Назначаем переменную шаблонизатора
            $nav = parseTemplateReturn($template_location . "paginator/paginator_main.tpl", $template_location_bool);
            $this->set('productPageNav', $nav);

            // Перехват модуля в конце функции
            $this->setHook(__CLASS__, __FUNCTION__, $nav, 'END');
        }
    }

    /**
     * Проверка изображений режима Multibase [отключено]
     * @param string $img адрес изображения
     * @param bool $return возврат измененной страницы
     */
    function checkMultibase($img) {
        /*
          $base_host = $this->PHPShopSystem->getSerilizeParam('admoption.base_host');
          if ($this->PHPShopSystem->getSerilizeParam('admoption.base_enabled') == 1 and !empty($base_host)) {
          $source_img = str_replace("/UserFiles/", "http://" . $base_host . "/UserFiles/", $img);
          return $source_img;
          }
          else
          return $img; */

        return $img;
    }

     /**
     * Проверка прав каталога режима Multibase
     * @return string 
     */
    function queryMultibase() {
        global $queryMultibase;

        // Мультибаза
        if (defined("HostID")) {

            // Память
            if (!empty($queryMultibase))
                return $queryMultibase;

            $multi_cat = array();
            $multi_dop_cat = null;

            $where['servers'] = " REGEXP 'i" . HostID . "i'";
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
            $PHPShopOrm->debug = $this->debug;
            $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 100), __CLASS__, __FUNCTION__);
            if (is_array($data)) {
                foreach ($data as $row) {
                    $multi_cat[] = $row['id'];
                    $multi_dop_cat.=" or dop_cat REGEXP '#" . $row['id'] . "#'";
                }
            }

            $queryMultibase = $multi_select = ' and ( category IN (' . @implode(',', $multi_cat) . ')' . $multi_dop_cat . ')';

            return $multi_select;
        }
    }

    /**
     * Проверка права каталога режима Multibase
     * @param int $category ID каталога
     * @param string $dop_cat #ID# дополнительных каталогов
     * @return boolean 
     */
    function errorMultibase($category, $dop_cat=null) {

        if (defined("HostID")) {

            if (empty($this->multi_cat)) {

                // Добавочные каталоги
                if (!empty($dop_cat)) {

                    $dop_cat_array = explode("#", $dop_cat);
                    $dop_cat_array_true = array();

                    if (is_array($dop_cat_array))
                        foreach ($dop_cat_array as $v)
                            if (!empty($v))
                                $dop_cat_array_true[] = $v;

                    $where['id'] = ' IN ("' . @implode('","', $dop_cat_array_true) . '")';
                }

                $where['servers'] = " REGEXP 'i" . HostID . "i'";

                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->cache = false;
                $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 1000));

                if (is_array($data)) {
                    foreach ($data as $row) {
                        $this->multi_cat[] = $row['id'];
                    }
                }
            }

            // Добавочные каталоги
            if (count($dop_cat_array_true) > 0) {

                // Категория
                $this->category = $dop_cat_array_true[0];
                $this->PHPShopCategory = new PHPShopCategory($this->category);
                $this->category_name = $this->PHPShopCategory->getName();

                return false;
            } else if (!in_array($category, $this->multi_cat))
                return true;
        }
    }

    /**
     * Проверка дополнительных данных товара по складу
     * @param array $row масив данных по товару
     */
    function checkStore($row = array()) {

        // Валюта
        $this->set('productValutaName', $this->currency);

        // Единица измерения
        if (empty($row['ed_izm']))
            $row['ed_izm'] = $this->lang('product_on_sklad_i');
        $this->set('productEdIzm', $row['ed_izm']);

        // Показывать состояние склада
        if ($this->sklad_enabled == 1 and $row['items'] > 0)
            $this->set('productSklad', $this->lang('product_on_sklad') . " " . $row['items'] . " " . $row['ed_izm']);
        else
            $this->set('productSklad', '');

        // Цена
        $price = $this->price($row);

        // Расчет минимальной и максимальной цены
        if ($price > $this->price_max)
            $this->price_max = $price;

        if (empty($this->price_min))
            $this->price_min = $price;

        if ($price < $this->price_min)
            $this->price_min = $price;

        // Форматирование
        $price = number_format($price, $this->format, '.', ' ');

        // Если товар на складе
        if (empty($row['sklad'])) {

            $this->set('Notice', '');
            $this->set('ComStartCart', '');
            $this->set('ComEndCart', '');
            $this->set('ComStartNotice', PHPShopText::comment('<'));
            $this->set('ComEndNotice', PHPShopText::comment('>'));
            $this->set('elementCartHide', null);
            $this->set('elementNoticeHide', 'hide hidden');

            // Если нет новой цены
            if (empty($row['price_n'])) {

                $this->set('productPrice', $price);
                $this->set('productPriceRub', '');
            }

            // Если есть новая цена
            else {
                $productPrice = $price;
                $productPriceNew = $this->price($row, true);
                $this->set('productPrice', $productPrice);
                $this->set('productPriceRub', PHPShopText::strike($productPriceNew . " " . $this->currency, $this->format));
            }
        }

        // Товар под заказ
        else {
            $this->set('productPrice', $price);
            $this->set('productPriceRub', $this->lang('sklad_mesage'));
            $this->set('ComStartNotice', '');
            $this->set('ComEndNotice', '');
            $this->set('elementCartHide', 'hide hidden');
            $this->set('ComStartCart', PHPShopText::comment('<'));
            $this->set('ComEndCart', PHPShopText::comment('>'));
            $this->set('productNotice', $this->lang('product_notice'));
            $this->set('elementNoticeHide', null);
            $this->set('elementCartOptionHide', 'hide hidden');
        }

        // Если цены показывать только после авторизации
        if ($this->user_price_activate == 1 and empty($_SESSION['UsersId'])) {
            $this->set('ComStartCart', PHPShopText::comment('<'));
            $this->set('ComEndCart', PHPShopText::comment('>'));
            $this->set('productPrice', null);
            $this->set('productPriceRub', null);
            $this->set('productValutaName', null);
        }

        // Проверка на нулевую цену 
        if (empty($row['price'])) {
            $this->set('ComStartCart', PHPShopText::comment('<'));
            $this->set('ComEndCart', PHPShopText::comment('>'));


            $this->set('elementCartHide', 'hide hidden');

            $this->set('productPrice', null);
            $this->set('productPriceRub', null);
            $this->set('productValutaName', null);
        }

        // Проверка подтипа
        if (!empty($row['parent'])) {
            $this->set('elementCartHide', 'hide hidden');
            $this->set('ComStartCart', PHPShopText::comment('<'));
            $this->set('ComEndCart', PHPShopText::comment('>'));

            if (empty($row['sklad']))
                $this->set('elementCartOptionHide', null);
        }
        else
            $this->set('elementCartOptionHide', 'hide hidden');

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $row);
            if ($hook) {
                return $hook;
            }
            else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }
    }

    /**
     * Форма ячеек с товарами
     * @return string
     */
    function setCell($d1, $d2 = null, $d3 = null, $d4 = null, $d5 = null, $d6 = null, $d7 = null) {

        // Оформление разделителя ячеек
        if ($this->grid)
            $this->grid_style = 'class="setka"';
        else
            $this->grid_style = '';

        $this->separator = null;

        $Arg = func_get_args();
        $item = 1;

        foreach ($Arg as $key => $value)
            if ($key < $this->cell)
                $args[] = $value;

        $num = count($args);

        // Расчет CSS стилей табличной сетки товара
        switch ($num) {
            // Сетка в 1 ячейку
            case 1:
                $panel = array('panel_l panel_1_1');
                break;

            // Сетка в 2 ячейки
            case 2:
                $panel = array('panel_l panel_2_1', 'panel_r panel_2_2');
                break;

            // Сетка в 3 ячейки
            case 3:
                $panel = array('panel_l panel_3_1', 'panel_r panel_3_2', 'panel_l panel_3_2');
                break;

            // Сетка в 4 ячейки
            case 4:
                $panel = array('panel_l panel_4_1', 'panel_r panel_4_2', 'panel_l panel_4_3', 'panel_l panel_4_4');
                break;

            // Сетка в 5 ячейки
            case 5:
                $panel = array('panel_l panel_5_1', 'panel_r panel_5_2', 'panel_l panel_5_3', 'panel_l panel_5_4', 'panel_l panel_5_5');
                break;

            default: $panel = array('panel_l', 'panel_r', 'panel_l', 'panel_r', 'panel_l', 'panel_r', 'panel_l');
        }

        switch ($this->cell_type) {

            // Списки
            case 'li':
                if (is_array($args))
                    foreach ($args as $key => $val) {
                        $tr.='<li class="' . $this->cell_type_class . '">' . $val . '</li>';
                        $item++;
                    }
                break;

            // Блоки
            case 'div':
                if (is_array($args))
                    foreach ($args as $key => $val) {
                        $tr.='<div class="' . $this->cell_type_class . '">' . $val . '</div>';
                        $item++;
                    }
                break;

            // Bootstrap
            case 'bootstrap':
                $tr = '<div class="row">';
                if (is_array($args))
                    foreach ($args as $key => $val) {
                        $tr.=$val;
                        $item++;
                    }
                $tr.='</div>';
                break;

            // Табличная
            default:

                $tr = '<tr>';
                if (is_array($args))
                    foreach ($args as $key => $val) {
                        $tr.='<td class="' . $panel[$key] . '" valign="top">' . $val . '</td>';

                        if ($item < $num and $num == $this->cell)
                            $tr.='<td ' . $this->grid_style . '><img src="images/spacer.gif" width="1"></td>';

                        $item++;
                    }
                $tr.='</tr>';


                $this->separator = '<tr><td ' . $this->grid_style . ' colspan="' . ($this->cell * 2) . '" height="1"><img height="1" src="images/spacer.gif"></td></tr>';

                if (!empty($this->setka_footer)) {
                    $tr.=$this->separator;
                }
        }


        return $tr;
    }

    /**
     * Расчёт кол-ва столбцов товара с учётом возможного изменения пользователем через шаблон
     * @param Int $category ИД текущей категории
     * @param Int $num_row  кол-во колонок в категории по умолчанию
     */
    function calculateCell($category, $num_row) {

        if (!empty($_REQUEST['gridChange'])) {
            if ($_REQUEST['gridChange'] == 2 AND $num_row > 1) {
                $_SESSION['gridChange'][$category] = $num_row;
                $this->set("gridChange2", "btn-primary");
                return $num_row;
            } elseif ($_REQUEST['gridChange'] == 2) {
                $_SESSION['gridChange'][$category] = 2;
                $this->set("gridChange2", "btn-primary");
                return 2;
            } else {

                $_SESSION['gridChange'][$category] = 1;
                $this->set("gridChange", "btn-primary");
                return 1;
            }
        } elseif (isset($_SESSION['gridChange'][$category])) {
            if ($_SESSION['gridChange'][$category] > 1)
                $this->set("gridChange2", "btn-primary");
            else
                $this->set("gridChange", "btn-primary");
            return $_SESSION['gridChange'][$category];
        }
        if ($num_row > 1)
            $this->set("gridChange2", "btn-primary");
        else
            $this->set("gridChange", "btn-primary");
        return $num_row;
    }

    /**
     * Вывод подтипов товаров
     * @param array $row массив значений
     */
    function parent($row) {

    // Перехват модуля в начале функции
    if($this->setHook(__CLASS__, __FUNCTION__, $row, 'START'))
        return true;

    $this->select_value = array();
    $row['parent'] = PHPShopSecurity::CleanOut($row['parent']);

    if (!empty($row['parent'])) {
        $parent = explode(",", $row['parent']);

        // Убираем добавление в корзину главного товара
        $this->set('ComStartCart', '<!--');
        $this->set('ComEndCart', '-->');

        // Собираем массив товаров
        if (is_array($parent))
            foreach ($parent as $value) {
                if (PHPShopProductFunction::true_parent($value))
                    $Product[$value] = $this->select(array('*'), array('uid' => '="' . $value . '"', 'enabled' => "='1'", 'sklad' => "!='1'"), false, false, __FUNCTION__);
                else
                    $Product[intval($value)] = $this->select(array('*'), array('id' => '=' . intval($value), 'enabled' => "='1'", 'sklad' => "!='1'"), false, false, __FUNCTION__);
            }

        // Цена главного товара
        if (!empty($row['price']) and empty($row['priceSklad']) and (!empty($row['items']) or (empty($row['items']) and $this->sklad_status == 1))) {
            $this->select_value[] = array($row['name'] . " -  (" . $this->price($row) . "
                    " . $this->currency . ')', $row['id'], false);
        } else {
            $this->set('ComStartNotice', PHPShopText::comment('<'));
            $this->set('ComEndNotice', PHPShopText::comment('>'));
        }

        // Выпадающий список товаров
        if (is_array($Product))
            foreach ($Product as $p) {
                if (!empty($p)) {

                    // Если товар на складе
                    if (empty($p['priceSklad']) and (!empty($p['items']) or (empty($p['items']) and $this->sklad_status == 1))) {
                        $price = $this->price($p);
                        $this->select_value[] = array($p['name'] . ' -  (' . $price . ' ' . $this->currency . ')', $p['id'], false);
                    }
                }
            }

        if (count($this->select_value) > 0) {
            $this->set('parentList', PHPShopText::select('parentId', $this->select_value, "; max-width:300px;"));
            $this->set('productParentList', ParseTemplateReturn("product/product_odnotip_product_parent.tpl"));
        }

        $this->set('productPrice', '');
        $this->set('productPriceRub', '');
        $this->set('productValutaName', '');

        // Перехват модуля в конце функции
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
    }
}

/**
 * Генератор сетки товаров
 * @param array $dataArray массив данных
 * @param int $cell разрадя сетки [1-5]
 * @return string
 */
function product_grid($dataArray, $cell = 2, $template = false) {

    if (empty($cell))
        $cell = 2;
    $this->cell = $cell;
    $this->setka_footer = true;

    $table = null;
    $j = 1;
    $item = 1;
    $lastmodified = 0;

    // Локализация
    $this->set('productSale', $this->lang('product_sale'));
    $this->set('productSaleReady', $this->lang('productSaleReady'));
    $this->set('productInfo', $this->lang('product_info'));
    $this->set('productPriceMoney', $this->dengi);
    $this->set('catalog', $this->lang('catalog'));
    if ($this->PHPShopNav->getPage() > 0)
        $this->set('productPageThis', $this->PHPShopNav->getPage());
    elseif ($this->PHPShopNav->getPage() != 'ALL')
        $this->set('productPageThis', 1);

    $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
    if (is_array($dataArray)) {
        $total = count($dataArray);

        // Проверка разделителя сетки
        if ($total < $cell)
            $this->grid = false;

        foreach ($dataArray as $row) {

            // Опции склада
            $this->checkStore($row);

            // Название
            $this->set('productName', $row['name']);

            // Артикул
            $this->set('productArt', $row['uid']);

            // Краткое описание
            $this->set('productDes', Parser($row['description']));

            // Вес
            $this->set('productWeight', $row['weight']);

            // Максимальная дата изменения
            if ($row['datas'] > $lastmodified)
                $lastmodified = $row['datas'];

            // Маленькая картинка
            $this->set('productImg', $this->checkMultibase($row['pic_small']));

            // Пустая картинка, заглушка
            if (empty($row['pic_small']))
                $this->set('productImg', $this->no_photo);

            // Большая картинка
            $this->set('productImgBigFoto', $this->checkMultibase($row['pic_big']));

            // Ид товара
            $this->set('productUid', $row['id']);

            // Подключение функции вывода средней оценки товара из отзывов пользователей
            $this->doLoadFunction(__CLASS__, 'comment_rate', array("row" => $row, "type" => "CID"), 'shop');

            // Опции товара
            //$this->option_select($row);
            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $row);

            if (empty($template))
                $template = $this->getValue('templates.main_product_forma_' . $this->cell);

            // Подключаем шаблон ячейки товара
            $dis = ParseTemplateReturn($template);


            // Убераем последний разделитель в сетке
            if ($item == $total)
                $this->setka_footer = false;

            $cell_name = 'd' . $j;
            $$cell_name = $dis;

            if ($j == $this->cell) {
                $table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
                $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
                $j = 0;
            } elseif ($item == $total) {
                $table.=$this->setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
            }

            $j++;
            $item++;
        }
    }

    $this->lastmodified = $lastmodified;
    return $table;
}

}

?>