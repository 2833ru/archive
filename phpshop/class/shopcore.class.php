<?php

/**
 * Родительский класс ядра вывода товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopShopCore
 * @version 1.3
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
     * Конструктор
     */
    function PHPShopShopCore() {
        global $PHPShopValutaArray;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['products'];

        // Массив валют
        $this->Valuta = $PHPShopValutaArray->getArray();

        PHPShopObj::loadClass('product');
        parent::PHPShopCore();

        // Валюта товара
        $this->dengi = $this->PHPShopSystem->getParam('dengi');
        
        // HTML опции верстки
        $this->setHtmlOption(__CLASS__);
    }

    /**
     * Генерация SQL запроса для выборки
     * @param string $where параметр отбора
     * @return mixed
     */
    function query_filter($where = false) {
        global $SysValue;

        if (!empty($where))
            $where.=' and ';

        $sort = null;

        $this->set('productRriceOT', 0);
        $this->set('productRriceDO', 0);

        $v = @$SysValue['nav']['query']['v'];
        $s = PHPShopSecurity::TotalClean(@$SysValue['nav']['query']['s'], 1);
        $f = PHPShopSecurity::TotalClean(@$SysValue['nav']['query']['f'], 1);

        if ($this->PHPShopNav->isPageAll())
            $p = PHPShopSecurity::TotalClean($p, 1);

        // Сортировка по характеристикам
        if (is_array($v)) {
            foreach ($v as $key => $value) {
                if (PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($value)) {
                    $hash = $key . "-" . $value;
                    $sort.=" and vendor REGEXP 'i" . $hash . "i' ";
                }
            }
        }


        // Накрутка
        $percent = $this->PHPShopSystem->getValue('percent');

        // Сортировка принудительная пользователем
        switch ($f) {
            case(1): $order_direction = "";
                $this->set('productSortNext', 2);
                $this->set('productSortImg', 1);
                $this->set('productSortT', 1);
                break;
            case(2): $order_direction = " desc";
                $this->set('productSortNext', 1);
                $this->set('productSortImg', 2);
                $this->set('productSortT', 2);
                break;
            default: $order_direction = "";
                $this->set('productSortNext', 2);
                $this->set('productSortImg', 1);
                $this->set('productSortT', 1);
                break;
        }
        switch ($s) {
            case(1): $order = array('order' => 'name' . $order_direction);
                $this->set('productSortA', 'sortActiv');
                break;
            case(2): $order = array('order' => 'price' . $order_direction);
                $this->set('productSortB', 'sortActiv');
                break;
            case(3): $order = array('order' => 'num' . $order_direction);
                $this->set('productSortC', 'sortActiv');
                break;
            default:
                $order = array('order' => 'num' . $order_direction);
                $this->set('productSortC', 'sortActiv');
                break;
        }

        // Преобразуем массив условия сортировки в строку
        foreach ($order as $key => $val)
            $string = $key . ' by ' . $val;

        // Все страницы
        if ($this->PHPShopNav->isPageAll()) {
            $sql = "select * from " . $this->getValue('base.products') . " where (" . $where . " enabled='1' and parent_enabled='0') " . $sort . " " . $string . ' limit ' . $obj->max_item;
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

        $row = $this->select(array($name), array('id' => '=' . intval($currency)), false, array('limit' => 1), __FUNCTION__, array('base' => $this->getValue('base.currency'), 'cache' => 'true'));

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
     * @return array
     */
    function select($select, $where, $order = false, $option = array('limit' => 1), $function_name = false, $from = false) {

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
    function setPaginator($count, $sql = null) {

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

        // Сортировка
        $sort = '?';
        if (!empty($_GET['s']) and is_numeric($_GET['s']))
            $sort.='s=' . $_GET['s'] . '&';
        if (!empty($_GET['f']) and is_numeric($_GET['f']))
            $sort.='f=' . $_GET['f'] . '&';

        // Фильтры
        if (!empty($_GET['v']) and is_array($_GET['v']))
            foreach ($_GET['v'] as $key => $val) {
                if (is_numeric($key) and is_numeric($val))
                    $sort.='v[' . $key . ']=' . $val . '&';
            }

        $sort = substr($sort, 0, strlen($sort) - 1);

        // Всего страниц
        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $result = $this->PHPShopOrm->query("select COUNT('id') as count from " . $this->objBase . ' where ' . $SQL);
        $row = mysql_fetch_array($result);
        $this->num_page = $row['count'];

        $i = 1;
        $navigat = $delim;

        // Кол-во страниц в навигации
        $num = ceil($this->num_page / $this->num_row);

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

            while ($i <= $num) {
                if ($i > 1) {
                    $p_start = $this->num_row * ($i - 1) + 1;
                    $p_end = $p_start + $this->num_row;
                } else {
                    $p_start = $i;
                    $p_end = $this->num_row;
                }

                $this->set("paginPageRangeStart", $p_start);
                $this->set("paginPageRangeEnd", $p_end);
                $this->set("paginPageNumber", $i);
                $this->set("paginPageCurnet", $i);
                $this->set("paginPageCount", $i);


                if ($i != $this->page) {
                    if ($i == 1) {
                        $this->set("paginLink", substr($this->objPath, 0, strlen($this->objPath) - 1) . '.html' . $sort);
                        $navigat.= parseTemplateReturn($template_location . "paginator/paginator_one_link.tpl", $template_location_bool);
                    } else {
                        if ($i > ($this->page - $this->nav_len) and $i < ($this->page + $this->nav_len)) {
                            $this->set("paginLink", $this->objPath . $i . '.html' . $sort);
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
                $this->set("previousLink", substr($this->objPath, 0, strlen($this->objPath) - 1) . '.html' . $sort);
            else
                $this->set("previousLink", $this->objPath . ($p_do) . '.html' . $sort);


            // Убираем дубль первой страницы CID_X_0.html
            if ($p_to == 0)
                $this->set("nextLink", substr($this->objPath, 0, strlen($this->objPath) - 1) . '.html' . $sort);
            else
                $this->set("nextLink", $this->objPath . ($p_to) . '.html' . $sort);

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
     * Проверка изображений режима Multibase
     * @param string $img адрес изображения
     * @param bool $return возврат измененной страницы
     */
    function checkMultibase($img) {

        $base_host = $this->PHPShopSystem->getSerilizeParam('admoption.base_host');
        if ($this->PHPShopSystem->getSerilizeParam('admoption.base_enabled') == 1 and !empty($base_host)) {
            $source_img = eregi_replace("/UserFiles/", "http://" . $base_host . "/UserFiles/", $img);
            return $source_img;
        }
        else
            return $img;
    }

    /**
     * Проверка права каталога режима Multibase
     * @param int $category
     * @return boolean 
     */
    function errorMultibase($category) {


        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {

            if (empty($this->multi_cat)) {
                $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
                $PHPShopOrm->debug = $this->debug;
                $PHPShopOrm->cache = true;
                $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 100));
                if (is_array($data)) {
                    foreach ($data as $row) {
                        $this->multi_cat[] = $row['id'];
                    }
                }
            }

            if (!in_array($category, $this->multi_cat))
                return true;
        }
    }

    /**
     * Проверка дополнительных данных товара по складу
     * @param array $row масив данных по товару
     */
    function checkStore($row = array()) {

        // Единица измерения
        if (empty($row['ed_izm']))
            $row['ed_izm'] = $this->lang('product_on_sklad_i');
        $this->set('productEdIzm', $row['ed_izm']);

        // Показывать состояние склада
        if ($this->PHPShopSystem->getSerilizeParam('admoption.sklad_enabled') == 1 and $row['items'] > 0)
            $this->set('productSklad', $this->lang('product_on_sklad') . " " . $row['items'] . " " . $row['ed_izm']);
        else
            $this->set('productSklad', '');

        // Если товар на складе
        if (empty($row['sklad'])) {

            $this->set('Notice', '');
            $this->set('ComStartCart', '');
            $this->set('ComEndCart', '');
            $this->set('ComStartNotice', PHPShopText::comment('<'));
            $this->set('ComEndNotice', PHPShopText::comment('>'));

            // Если нет новой цены
            if (empty($row['price_n'])) {
                $this->set('productPrice', $this->price($row));
                $this->set('productPriceRub', '');
            }

            // Если есть новая цена
            else {
                $productPrice = $this->price($row);
                $productPriceNew = $this->price($row, true);
                $this->set('productPrice', $productPrice);
                $this->set('productPriceRub', PHPShopText::strike($productPriceNew . " " . $this->currency()));
            }
        }

        // Товар под заказ
        else {
            $this->set('productPrice', $this->price($row));
            $this->set('productPriceRub', $this->lang('sklad_mesage'));
            $this->set('ComStartNotice', null);
            $this->set('ComEndNotice', null);
            $this->set('ComStartCart', PHPShopText::comment('<'));
            $this->set('ComEndCart', PHPShopText::comment('>'));
            $this->set('productNotice', $this->lang('product_notice'));
        }

        // Если цены показывать только после авторизации
        if ($this->PHPShopSystem->getSerilizeParam('admoption.user_price_activate') == 1 and empty($_SESSION['UsersId'])) {
            $this->set('ComStartCart', PHPShopText::comment('<'));
            $this->set('ComEndCart', PHPShopText::comment('>'));
            $this->set('productPrice', null);
            $this->set('productValutaName', null);
        }


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
                        $tr.='<li class="'.$this->cell_type_class.'">' . $val . '</li>';
                        $item++;
                    }
                break;

            // Блоки
            case 'div':
                if (is_array($args))
                    foreach ($args as $key => $val) {
                        $tr.='<div class="'.$this->cell_type_class.'">' . $val . '</div>';
                        $item++;
                    }
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

                if (!empty($this->setka_footer))
                    $tr.='<tr><td ' . $this->grid_style . ' colspan="' . ($this->cell * 2) . '" height="1"><img height="1" src="images/spacer.gif"></td></tr>';
        }


        return $tr;
    }

    /**
     * Расчёт кол-ва столбцов товара с учётом возможного изменения пользователем через шаблон
     * @param Int $category ИД текущей категории
     * @param Int $num_row  кол-во колонок в категории по умолчанию
     */
    function calculateCell($category, $num_row) {
        if (isset($_REQUEST['gridChange']) AND $_REQUEST['gridChange'] > 0) {
            if ($_REQUEST['gridChange'] == 2 AND $num_row > 1) {
                $_SESSION['gridChange'][$category] = $num_row;
                return $num_row;
            } else {
                $_SESSION['gridChange'][$category] = 1;
                return 1;
            }
        } elseif (isset($_SESSION['gridChange'][$category]))
            return $_SESSION['gridChange'][$category];
        else
            return $num_row;
    }

    /**
     * Генератор сетки товаров
     * @param array $dataArray массив данных
     * @param int $cell разрадя сетки [1-5]
     * @return string
     */
    function product_grid($dataArray, $cell = 2) {

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
        $this->set('productInfo', $this->lang('product_info'));
        $this->set('productPriceMoney', $this->dengi);
        $this->set('catalog', $this->lang('catalog'));
        if ($this->PHPShopNav->getPage() > 0)
            $this->set('productPageThis', $this->PHPShopNav->getPage());
        else
            $this->set('productPageThis', 1);

        $d1 = $d2 = $d3 = $d4 = $d5 = $d6 = $d7 = null;
        if (is_array($dataArray)) {
            $total = count($dataArray);

            // Проверка разделителя сетки
            if ($total < $cell)
                $this->grid = false;

            foreach ($dataArray as $row) {

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

                // Опции склада
                $this->checkStore($row);

                // Опции товара
                //$this->option_select($row);
                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row);

                // Подключаем шаблон ячейки товара
                $dis = ParseTemplateReturn($this->getValue('templates.main_product_forma_' . $this->cell));


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

    /**
     * Сообщение об неизвестном методе
     */
    function __call($m, $a) {
        echo $this->message('Ошибка', '$' . __CLASS__ . '->' . $m . '() не определен в ' . __FILE__);
    }

}
?>