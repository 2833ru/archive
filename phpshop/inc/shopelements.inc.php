<?php

/**
 * Элемент подбора по брендам
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopBrandsElement extends PHPShopElements {

    /**
     * @var int  Кол-во брендов
     */
    var $limitOnLine = 5;
    var $firstClassName = 'span-first-child';

    /**
     * Конструктор
     */
    function PHPShopBrandsElement() {
        $this->debug = false;
        parent::PHPShopElements();
    }

    /**
     * Вывод
     * @return string
     */
    function index() {
        global $SysValue;
        // Массив имен характеристик
        $PHPShopOrm = new PHPShopOrm($SysValue['base']['table_name20']);
        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->mysql_error = false;
        $result = $PHPShopOrm->query("select * from " . $SysValue['base']['table_name20'] . " where (brand='1' and goodoption!='1') order by num");
        while (@$row = mysql_fetch_assoc($result)) {
            $arrayVendor[$row['id']] = $row;
        }
        if (is_array($arrayVendor))
            foreach ($arrayVendor as $k => $v) {
                if (is_numeric($k))
                    $sortValue.=' category=' . $k . ' OR';
            }
        $sortValue = substr($sortValue, 0, strlen($sortValue) - 2);

        if (!empty($sortValue)) {
            // Массив значений 
            $i = 0;
            $result = $PHPShopOrm->query("select distinct name, id, icon, category from " . $SysValue['base']['table_name21'] . " where $sortValue group by name");
            while (@$row = mysql_fetch_array($result)) {
                @$arrayVendorValue[$row['category']]['name'].= ", " . $row['name'];
                if ($arrayVendor[$row['category']]['brand']) {
                    if ($i % $this->limitOnLine == 0) {
                        $this->set('brandFirstClass', $this->firstClassName);
                    } else {
                        $this->set('brandFirstClass', '');
                    }
                    $i++;

                    $this->set('brandIcon', $row['icon']);
                    $this->set('brandName', $row['name']);
                    $desc = '';
                    if ($row['page']) {
                        $PHPShopOrm->clean();
                        $res = $PHPShopOrm->query("select content from " . $SysValue['base']['page'] . " where link = '$row[page]' LIMIT 1");
                        $page = mysql_fetch_array($res);
                        $desc = $page['content'];
                    }

                    $this->set('brandPageLink', $GLOBALS['SysValue']['dir']['dir'].'/selection/?v[' . $row['category'] . ']=' . $row['id']);
                    $this->set('brandDescr', $desc);

                    $this->set('brandsList', ParseTemplateReturn('brands/top_brands_one.tpl'), true);
                }
            }
        }
        if ($this->get('brandsList'))
            return ParseTemplateReturn('brands/top_brands_main.tpl');
    }

}


/**
 * Элемент характеристик товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSortElements
 * @version 1.3
 * @package PHPShopElements
 */
class PHPShopSortElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function PHPShopSortElement() {
        parent::PHPShopElements();
    }

    /**
     * Вывод списка характеристики для отбора
     * @param string $var имя переменной в шаблонизаторе
     * @param int $n ИД характеристики для вывода значений
     * @param string $title заголовок блока
     * @param string $target цель формы [/selection/  |  /selectioncat/]
     */
    function brand($var, $n, $title, $target = '/selection/') {

        // ИД характеристики для вывода значений
        $this->n = $n;

        // Подгружаем библиотеку
        PHPShopObj::loadClass('sort');

        $PHPShopSort = new PHPShopSort();
        $value = $PHPShopSort->value($n, $title);
        $forma = PHPShopText::p(PHPShopText::form($value . PHPShopText::button('OK', 'SortSelect.submit()'), 'SortSelect', 'get', $target, false, 'ok'));
        $this->set('leftMenuContent', $forma);
        $this->set('leftMenuName', $title);

        // Подключаем шаблон
        $dis = $this->parseTemplate($this->getValue('templates.left_menu'));

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $value);

        // Назначаем переменную шаблона
        $this->set($var, $dis);
    }

}

/**
 * Элемент оформления вывода товаров в колонку
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopProductIconElements
 * @version 1.4
 * @package PHPShopElements
 */
class PHPShopProductIconElements extends PHPShopProductElements {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;

    /**
     * Память событий
     * @var bool
     */
    var $memory = true;

    /**
     * шаблон товара
     * @var string 
     */
    var $template = 'main_spec_forma_icon';

    /**
     * ограничение на вывод
     * @var string 
     */
    var $limitspec;

    /**
     * сетка товара [1-5]
     * @var int 
     */
    var $cell;

    /**
     * Констурктор
     */
    function PHPShopProductIconElements() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopProductElements();

        // HTML опции верстки
        $this->setHtmlOption(__CLASS__);
    }

    /**
     * Элемент "Спецпредложения-Новинки" для всех страниц
     * @param bool $force параметр отображения для подробного описания товара
     * @param int $category Ид категории для выборки
     * @param int $cell сетка товара [1-5]
     * @param int $limit ограничение на вывод
     * @return string
     */
    function specMainIcon($force = false, $category = null, $cell = null, $limit = null, $line = false) {

        $this->limitspec = $limit;

        
        if (!empty($cell))
            $this->cell = $cell;

        elseif (empty($this->cell))
            $this->cell = 1;


        switch ($GLOBALS['SysValue']['nav']['nav']) {

            // Раздел списка товаров
            case "CID":

                if (!empty($category))
                    $where['category'] = '=' . $category;

                elseif (PHPShopSecurity::true_num($this->PHPShopNav->getId())) {

                    $category = $this->PHPShopNav->getId();
                    if (!$this->memory_get('product_enabled.' . $category, true))
                        $where['category'] = '=' . $category;
                }
                break;

            // Раздел подробного описания
            case "UID":
                if (empty($force))
                    return false;
                else
                    $where['category'] = '=' . $category;

                $where['id'] = '!=' . $this->PHPShopNav->getId();
                break;
        }


        // Кол-во товаров на странице
        if (empty($this->limitspec))
            $this->limitspec = $this->PHPShopSystem->getParam('new_num');

        if (!$this->limitspec)
            $this->limitspec = $this->num_row;

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;


        // Завершение если отключен вывод
        if (empty($this->limitspec))
            return false;

        // Случаные товары для больших баз
        //$where['id']=$this->setramdom($limit);
        // Параметры выборки учета товара в новинках и наличия
        $where['newtip'] = "='1'";
        $where['enabled'] = "='1'";
        $where['parent_enabled'] = "='0'";

        // Проверка на единичную выборку
        if ($limit == 1) {
            $array_pop = true;
            $limit++;
        }

        // Память режима выборки новинок из каталогов
        $memory_spec = $this->memory_get('product_spec.' . $category);

        // Выборка новинок
        if ($memory_spec != 2 and $memory_spec != 3)
            $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limitspec), __FUNCTION__);

        // Проверка на единичную выборку
        if (!empty($array_pop) and is_array($this->dataArray)) {
            array_pop($this->dataArray);
        }

        if (!empty($this->dataArray) and is_array($this->dataArray)) {
            $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
            $this->set('specMainTitle', $this->lang('newprod'));

            // Заносим в память
            $this->memory_set('product_spec.' . $category, 1);
        } else {
            // Выборка спецпредложение
            unset($where['newtip']);
            $where['spec'] = "='1'";

            if ($memory_spec != 1 and $memory_spec != 3)
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limitspec), __FUNCTION__);

            // Проверка на единичную выборку
            if (!empty($array_pop) and is_array($this->dataArray)) {
                array_pop($this->dataArray);
            }

            if (!empty($this->dataArray) and is_array($this->dataArray)) {
                $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
                $this->set('specMainTitle', $this->lang('specprod'));

                // Заносим в память
                $this->memory_set('product_spec.' . $category, 2);
            } else {
                // Выборка последних добавленных товаров
                unset($where['id']);
                unset($where['spec']);
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => $this->limitspec), __FUNCTION__);

                // Проверка на единичную выборку
                if (!empty($array_pop) and is_array($this->dataArray)) {
                    array_pop($this->dataArray);
                }

                if (is_array($this->dataArray)) {
                    $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
                    $this->set('specMainTitle', $this->lang('newprod'));

                    // Заносим в память
                    $this->memory_set('product_spec.' . $category, 3);
                }
            }
        }

        // Собираем и возвращаем таблицу с товарами
        return $this->compile();
    }

    /**
     * Элемент простой формы вывода товаров (заготовка)
     * @param array $row массив данных товаров
     * @param int $cell разрядность сетки [1|2|3|4|5]
     * @param string $template шаблон вывода
     * @param bool $line наличие разделителя между сетками
     * @return string
     */
    function seamply_forma($row, $cell = false, $template = 'main_spec_forma_icon', $line = false) {

        // Количество ячеек для вывода товара
        if (empty($cell))
            $this->cell = $this->PHPShopSystem->getParam('num_vitrina');
        else $this->cell=$cell;


        $this->set('productInfo', $this->lang('productInfo'));

        // Добавляем в дизайн ячейки с товарами
        $this->product_grid($row, $this->cell, $template, $line);

        // Собираем и возвращаем таблицу с товарами
        return $this->compile();
    }

    /**
     * Форма ячеек с товарами
     * @return string
     */
    function setCell($d1, $d2 = null, $d3 = null, $d4 = null, $d5 = null, $d6 = null, $d7 = null) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $Arg = func_get_args();
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
            if ($hook) {
                return $hook;
            }
            else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
    }

    /**
     * Сбор данных по товарам в таблицу
     * @return string
     */
    function compile() {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook) {
            return $hook;
        }

        return parent::compile();
    }

}

/**
 * Элемент оформления вывода товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopProductIndexElements
 * @version 1.4
 * @package PHPShopElements
 */
class PHPShopProductIndexElements extends PHPShopProductElements {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;

    /**
     * Сетка товара
     * @var int
     */
    var $cell;

    /**
     * Память событий
     * @var bool
     */
    var $memory = false;

    /**
     * шаблон товара
     * @var string 
     */
    var $template = '';

    /**
     * Констурктор
     */
    function PHPShopProductIndexElements() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopProductElements();

        // HTML опции верстки
        $this->setHtmlOption(__CLASS__);
    }

    /**
     * Шаблон компактного вывода "Сейчас покупают"
     * @param array $row массив данных
     * @return string
     */
    function template_nowbuy($row) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row);
        if ($hook)
            return $hook;

        return PHPShopText::li($row['name'], 'shop/UID_' . $row['id'] . '.html');
    }

    /**
     * Элемент "сейчас покупают" для главной страницы
     * @return string
     */
    function nowBuy() {

        // Проверка запуска главной страницы
        if ($this->PHPShopNav->index()) {
            $i = 1;
            $this->limitpos = 10; // Количество выводимых позиций
            $this->limitorders = 10; // Количество запрашиваемых заказов
            $disp = $li = null;
            $enabled = $this->PHPShopSystem->getSerilizeParam('admoption.nowbuy_enabled');
            $sort = null;

            // Перехват модуля
            $hook = $this->setHook(__CLASS__, __FUNCTION__);
            if ($hook)
                return $hook;

            // Количество ячеек
            if (empty($this->cell))
                $this->cell = $this->PHPShopSystem->getValue('num_vitrina');

            if (!empty($enabled)) {

                // Последние заказы
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
                $PHPShopOrm->debug = $this->debug;
                $data = $PHPShopOrm->select(array('orders'), false, array('order' => 'id desc'), array('limit' => $this->limitorders));

                if (is_array($data)) {
                    foreach ($data as $row) {
                        $order = unserialize($row['orders']);
                        $cart = $order['Cart']['cart'];
                        if (is_array($cart))
                            foreach ($cart as $good) {
                                if ($i > $this->limitpos)
                                    break;
                                // Проверка подчиненного товара
                                if(!empty($good['parent'])) 
                                    $good['id']=$good['parent'];
                                
                                $sort.=' id=' . intval($good['id']) . ' OR';
                            }
                    }
                    $sort = substr($sort, 0, strlen($sort) - 2);

                    // Если есть товары
                    if (!empty($sort)) {
                        $PHPShopOrm = new PHPShopOrm();
                        $PHPShopOrm->debug = $this->debug;
                        $PHPShopOrm->sql = "select * from " . $this->objBase . " where (" . $sort . ") and enabled='1' LIMIT 0," . $this->limitpos;
                        $PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
                        $dataArray = $PHPShopOrm->select();
                        if (is_array($dataArray)) {

                            // Товары таблицей
                            if ($enabled == 2) {

                                // Количество ячеек для вывода товара
                                if (empty($this->cell))
                                    $this->cell = $this->PHPShopSystem->getParam('num_vitrina');
                                $this->set('productInfo', $this->lang('productInfo'));

                                // Добавляем в дизайн ячейки с товарами
                                $this->product_grid($dataArray, $this->cell, $this->template);

                                // Собираем и возвращаем таблицу с товарами
                                $disp = $this->compile();
                            }
                            // Товары списком
                            else {
                                foreach ($dataArray as $row) {
                                    $li.=$this->template_nowbuy($row);
                                    $i++;
                                }

                                $disp = PHPShopText::ol($li);
                            }

                            return $disp;
                        }
                    }
                }
            }
        }
    }

    /**
     * Проверка прав каталога режима Multibase
     * @param int $category
     * @return boolean 
     */
    function randMultibase() {

        $multi_cat = null;

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {

            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
            $where['parent_to'] = " > 0";
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
            $PHPShopOrm->debug = $this->debug;
            $PHPShopOrm->cache = true;
            $data = $PHPShopOrm->select(array('id'), $where, false, array('limit' => 1), __CLASS__, __FUNCTION__);
            if (is_array($data)) {
                foreach ($data as $row) {
                    $multi_cat = '=' . $row['id'];
                }
            }

            return $multi_cat;
        }
    }

    /**
     * Элемент "Спецпредложения" на главную страницу
     * @return string
     */
    function specMain() {

        // Проверка запуска главной страницы
        if ($this->PHPShopNav->index()) {


            // Количество ячеек для вывода товара
            if (empty($this->cell))
                $this->cell = $this->PHPShopSystem->getParam('num_vitrina');

            // Кол-во товаров на странице
            $this->limit = $this->PHPShopSystem->getParam('spec_num');

            if (!$this->limit)
                $this->limit = $this->num_row;

            // Завершение если отключен вывод
            if ($this->limit < 1)
                return false;

            // Перехват модуля
            $hook = $this->setHook(__CLASS__, __FUNCTION__);
            if ($hook)
                return $hook;

            $this->set('productInfo', $this->lang('productInfo'));

            // Случайные товары
            $where['id'] = $this->setramdom($this->limit);

            // Параметры выборки учета товара в спецпредложении и наличия
            $where['spec'] = "='1'";
            $where['enabled'] = "='1'";

            $randMultibase = $this->randMultibase();
            if (!empty($randMultibase))
                $where['category'] = $randMultibase;


            // Выборка
            if ($this->limit > 1)
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limit), __FUNCTION__);
            else
                $this->dataArray[] = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limit), __FUNCTION__);

            // Вторая попытка вывести спецпредложения, оптимизатор RAND выключен
            $count = count($this->dataArray);
            if ($count < $this->limit) {
                unset($where['id']);
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limit), __FUNCTION__);
            }


            // Добавляем в дизайн ячейки с товарами
            $this->product_grid($this->dataArray, $this->cell, $this->template);

            // Собираем и возвращаем таблицу с товарами
            return $this->compile();
        }
    }

    /**
     * Форма ячеек с товарами
     * @return string
     */
    function setCell($d1, $d2 = null, $d3 = null, $d4 = null, $d5 = null, $d6 = null, $d7 = null) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $Arg = func_get_args();
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
            if ($hook) {
                return $hook;
            }
            else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d6, $d7);
    }

    /**
     * Сбор данных по товарам в таблицу
     * @return string
     */
    function compile() {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook) {
            return $hook;
        }

        return parent::compile();
    }

}

/**
 * Элемент оформления дерева категорий товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopShopCatalogElement
 * @version 1.4
 * @package PHPShopElements
 */
class PHPShopShopCatalogElement extends PHPShopProductElements {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;
    var $cache = true;

    /**
     * Массив полей для очистки в кэше для оптимизации кэша. Вырезаем описание каталога и YML настройки.
     * @var array
     */
    var $cache_format = array('content', 'yml_bid_array');
    var $memory = true;

    /**
     * Сортировка корневых каталогов [num|name]
     * @var string 
     */
    var $root_order = 'num, name';

    /**
     * Проверять на единичные каталоги. [false] - для больших каталогов, сокращает запросы к БД
     * @var bool
     */
    var $chek_catalog = true;
    var $grid = true;

    /**
     * Лимит символов в описании каталога для расчета иконки каталога в элементе leftCatalTable
     * @var int
     */
    var $cat_description_limit = 200;

    /**
     * Конструктор
     */
    function PHPShopShopCatalogElement() {
        $this->objBase = $GLOBALS['SysValue']['base']['categories'];
        parent::PHPShopElements();

        // HTML опции верстки
        $this->setHtmlOption(__CLASS__);
    }

    /**
     * Шаблон вывода категорий каталогов с иконками
     * @param array $val массив данных
     * @return string
     */
    function template_cat_table($val) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $val);
        if ($hook)
            return $hook;

        return PHPShopText::a('/shop/CID_' . $val['id'] . '.html', $val['name'], $val['name']) . ' | ';
    }

    /**
     * Форма ячеек для leftCatalTable
     * @return string
     */
    function setCell($d1, $d2 = null, $d3 = null, $d4 = null, $d5 = null) {

        // Перехват модуля, занесение в память наличия модуля для оптимизации
        if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
            $Arg = func_get_args();
            $hook = $this->setHook(__CLASS__, __FUNCTION__, $Arg);
            if ($hook) {
                return $hook;
            }
            else
                $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }

        return parent::setCell($d1, $d2, $d3, $d4, $d5, $d5);
    }

    /**
     * Таблица категорий с иконками
     * @return string
     */
    function leftCatalTable() {

        // Выполнение только в Index
        if ($this->PHPShopNav->index()) {

            $dis = null;
            $podcatalog = null;

            // Количество ячеек
            if (empty($this->cell))
                $this->cell = $this->PHPShopSystem->getValue('num_vitrina');

            $table = null;
            $j = 1;
            $item = 1;

            // Перехват модуля
            $hook = $this->setHook(__CLASS__, __FUNCTION__, null, 'START');
            if ($hook)
                return $hook;

            if (is_array($this->data))
                foreach ($this->data as $row) {
                    $dis = null;
                    $podcatalog = null;
                    $this->set('catalogId', $row['id']);
                    $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $_SESSION['skin'] . chr(47));
                    $this->set('catalogTitle', $row['name']);
                    $this->set('catalogName', $row['name']);
                    if (empty($row['icon']))
                        $row['icon'] = $this->no_photo;
                    $this->set('catalogIcon', $row['icon']);

                    // Проверка на наличие иконки в описании категории
                    if (stristr($row['content'], 'img') and strlen($row['content']) < $this->cat_description_limit)
                        $this->set('catalogContent', $row['content']);
                    else
                        $this->set('catalogContent', null);

                    // Обход массива категорий из кэша, список подкаталогов
                    if (is_array($GLOBALS['Cache'][$this->objBase]))
                        foreach ($GLOBALS['Cache'][$this->objBase] as $val) {
                            if ($val['parent_to'] == $row['id'])
                                $podcatalog.=$this->template_cat_table($val);
                        }

                    $this->set('catalogPodcatalog', $podcatalog);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    // Подключаем шаблон
                    $dis.= ParseTemplateReturn("catalog/catalog_table_forma.tpl");

                    // Ячейки с каталогами (1-5)
                    if ($j < $this->cell) {
                        $cell_name = 'd' . $j;
                        $$cell_name = $dis;
                        $j++;
                        if ($item == count($this->data)) {
                            $table.=$this->setCell($d1, @$d2, @$d3, @$d4, @$d5);
                        }
                    } else {
                        $cell_name = 'd' . $j;
                        $$cell_name = $dis;
                        $table.=$this->setCell($d1, @$d2, @$d3, @$d4, @$d5);
                        $d1 = $d2 = $d3 = $d4 = $d5 = null;
                        $j = 1;
                    }
                    $item++;
                }

            $this->product_grid = $table;
            return $this->compile();
        }
    }

    /**
     * Вывод навигации каталогов
     * @param array $replace массив замены стилей
     * @param array $where массив параметров выборки, используется для вывода определенного каталога
     * PHPShopShopCatalogElement::leftCatal(false,$where['id']=1);
     * @return string
     */
    function leftCatal($replace = null, $where = null) {
        $dis = null;
        $i = 0;

        $this->set('thisCat', $this->PHPShopNav->getId());

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $where, 'START');
        if ($hook)
            return $hook;

        // Параметр выборки
        if (empty($where))
            $where['parent_to'] = '=0';

        // Не выводить скрытые каталоги
        $where['skin_enabled '] = "!='1'";

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $this->data = $PHPShopOrm->select(array('*'), $where, array('order' => $this->root_order), array("limit" => 100), __CLASS__, __FUNCTION__);
        if (is_array($this->data))
            foreach ($this->data as $row) {

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // Определяем переменные
                $this->set('catalogId', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));
                $this->set('catalogPodcatalog', $this->subcatalog($row));
                $this->set('catalogTitle', $row['title']);
                $this->set('catalogName', $row['name']);

                // Иконка
                if (empty($row['icon']))
                    $row['icon'] = $this->no_photo;
                $this->set('catalogIcon', $row['icon']);
                $this->set('catalogIconDesc', $row['icon_description']);

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                // Если нет подкаталогов
                if ($this->chek($row['id'])) {
                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_3'));
                }
                // Если есть подкаталоги
                else {
                    if ($row['vid'] == 1) {
                        $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma_2'));
                    } else {
                        $dis.=$this->parseTemplate($this->getValue('templates.catalog_forma'));
                    }
                }
                $i++;
            }

        // Замена стилей
        if (is_array($replace)) {
            foreach ($replace as $key => $val)
                $dis = str_replace($key, $val, $dis);
        }

        return $dis;
    }

    /**
     * Вывод подкаталогов
     * @param int $n ИД каталога
     * @return string
     */
    function subcatalog($parent_data) {

        // ID родителя
        $n = $parent_data['id'];
        $i=1;

        $dis = null;

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $where['parent_to'] = '=' . $n;

        // Не выводить скрытые каталоги
        $where['skin_enabled'] = "!='1'";

        // Мультибаза
        if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
            $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
        }


        // Сортировка каталога
        switch ($parent_data['order_to']) {
            case(1): $order_direction = "";
                break;
            case(2): $order_direction = " desc";
                break;
            default: $order_direction = "";
                break;
        }
        switch ($parent_data['order_by']) {
            case(1): $order = array('order' => 'name' . $order_direction);
                break;
            case(2): $order = array('order' => 'name' . $order_direction);
                break;
            case(3): $order = array('order' => 'num' . $order_direction);
                break;
            default: $order = array('order' => 'num' . $order_direction);
                break;
        }

        $data = $PHPShopOrm->select(array('*'), $where, $order, array('limit' => 100), __CLASS__, __FUNCTION__);


        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogName', $row['name']);
                $this->set('catalogUid', $row['id']);
                $row['i']= $i;

                // Иконка
                if (empty($row['icon']))
                    $row['icon'] = $this->no_photo;
                $this->set('catalogIcon', $row['icon']);
                $this->set('catalogIconDesc', $row['icon_description']);

                $PHPShopCategory = new PHPShopCategory($n);
                $this->set('catalogTitle', $PHPShopCategory->getName());

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row);

                // Подключаем шаблон
                $dis.=ParseTemplateReturn($this->getValue('templates.podcatalog_forma'));
                $i++;
            }
        return $dis;
    }

    /**
     * Проверка подкатлогов
     * @param Int $id ИД каталога
     * @return bool
     */
    function chek($n) {

        // Если проверка в памяти есть, подкаталогов нет
        if ($this->memory_get('product_enabled.' . $n) == 1)
            return true;
        // Если проверка в памяти есть, подкаталоги есть
        elseif ($this->memory_get('product_enabled.' . $n) == 2)
            return false;
        // Если проверки в памяти нет, запрос к БД
        elseif (!empty($this->chek_catalog)) {
            $PHPShopOrm = new PHPShopOrm($this->objBase);
            $PHPShopOrm->cache_format = $this->cache_format;
            $PHPShopOrm->cache = $this->cache;
            $PHPShopOrm->debug = $this->debug;

            $where['parent_to'] = '=' . $n;

            // Мультибаза
            if ($this->PHPShopSystem->ifSerilizeParam('admoption.base_enabled')) {
                $where['servers'] = " REGEXP 'i" . $this->PHPShopSystem->getSerilizeParam('admoption.base_id') . "i'";
            }

            $num = $PHPShopOrm->select(array('*'), $where, false, array('limit' => 1), __CLASS__, __FUNCTION__);
            if (empty($num['id'])) {
                // Заносим в память
                $this->memory_set('product_enabled.' . $n, 1);
                return true;
            }
            else
                $this->memory_set('product_enabled.' . $n, 2);
        }
    }

}

/**
 * Элемент корзина покупок
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCartElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopCartElement extends PHPShopElements {

    /**
     * Конструктор
     * @param bool $order режим корзины в заказе
     */
    function PHPShopCartElement($order = false) {

        PHPShopObj::loadClass('cart');
        $this->PHPShopCart = new PHPShopCart();
        $this->order = $order;

        parent::PHPShopElements();
    }

    /**
     *  Мини корзина
     */
    function miniCart() {

        // Если вывод не в разделах офомления заказа
        if ($this->PHPShopNav->notPath(array('order', 'done')) or !empty($this->order)) {

            if (!empty($_SESSION['compare']))
                $compare = $_SESSION['compare'];
            else
                $compare = array();
            $numcompare = 0;

            // Если есть товары в корзине
            if ($this->PHPShopCart->getNum() > 0) {
                $this->set('orderEnabled', 'block');

                // Отключение выдачи даты изменения при активной корзине для защита от кэша
                $this->setValue("cache.last_modified", false);
            }
            else
                $this->set('orderEnabled', 'none');

            // Если есть сравнение
            if (count($compare) > 0) {
                if (is_array($compare)) {
                    foreach ($compare as $j => $v) {
                        $numcompare = count($compare);
                    }
                }
                $this->set('compareEnabled', 'block');
            } else {
                $numcompare = "0";
                $this->set('compareEnabled', 'none');
            }

            // Локализация
            $this->set('tovarNow', $this->getValue('lang.cart_tovar_now'));
            $this->set('summaNow', $this->getValue('cart_summa_now'));
            $this->set('orderNow', $this->getValue('cart_order_now'));

            // Сравнение
            $this->set('numcompare', $numcompare);

            // Товаров
            $this->set('num', $this->PHPShopCart->getNum());

            // Сумма
            $this->set('sum', $this->PHPShopCart->getSum());
        } else {
            $this->set('productValutaName', $this->PHPShopSystem->getDefaultValutaCode(true));
            // Товаров
            $this->set('num', 0);
            // Сумма
            $this->set('sum', 0);
        }

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__);
    }

}

/**
 * Элемент смены валюты
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCurrencyElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopCurrencyElement extends PHPShopElements {

    /**
     * Конструктор
     */
    function PHPShopCurrencyElement() {
        global $PHPShopValutaArray;
        parent::PHPShopElements();
        $this->PHPShopValuta = $PHPShopValutaArray->getArray();
        $this->setAction(array('post' => 'valuta'));
    }

    /**
     * Перенаправление формы смены валюты
     */
    function valuta() {
        $currency = intval($_POST['valuta']);
        if (!empty($this->PHPShopValuta[$currency])) {
            $_SESSION['valuta'] = $currency;
            header("Location: " . $_SERVER['REQUEST_URI']);
        }
    }

    /**
     * Форма выбора валюты
     * @return string
     */
    function valutaDisp() {

        if ($this->PHPShopNav->notPath('order')) {

            if (isset($_SESSION['valuta']))
                $valuta = $_SESSION['valuta'];
            else
                $valuta = $this->PHPShopSystem->getParam('dengi');

            if (is_array($this->PHPShopValuta))
                foreach ($this->PHPShopValuta as $v) {
                    if ($valuta == $v['id'])
                        $sel = "selected";
                    else
                        $sel = false;
                    $value[] = array($v['name'], $v['id'], $sel);
                }

            // Определяем переменные
            $this->set('leftMenuName', 'Валюта');
            $select = PHPShopText::select('valuta', $value, 100, "none", false, "ChangeValuta()");
            $this->set('leftMenuContent', PHPShopText::form($select, 'ValutaForm'));

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $this->PHPShopValuta);

            // Подключаем шаблон
            $dis = $this->parseTemplate($this->getValue('templates.valuta_forma'));
            return $dis;
        }
    }

}

/**
 * Элемент Облако тегов
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopCloudElement
 * @version 1.2
 * @package PHPShopElements
 */
class PHPShopCloudElement extends PHPShopElements {

    var $debug = false;

    /**
     * Лимит записей для анализа
     * @var int
     */
    var $page_limit = 100;

    /**
     * Лимит слов для вывода
     * @var int
     */
    var $word_limit = 20;

    /**
     * Цвет ссылок облака тегов
     * @var string
     */
    var $color = "0x518EAD";

    /**
     * Конструктор
     */
    function PHPShopCloudElement() {
        $this->objBase = $GLOBALS['SysValue']['base']['products'];
        parent::PHPShopElements();
    }

    /**
     * Облако тегов
     * @param array $row массив данных
     * @return string
     */
    function index($row = null) {
        $disp = $dis = $CloudCount = $ArrayWords = $CloudCountLimit = null;
        $ArrayLinks = array();

        // Перехват модуля в начале функции
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'START');
        if ($hook)
            return $hook;

        if ($this->PHPShopSystem->ifSerilizeParam('admoption.cloud_enabled')) {
            switch ($GLOBALS['SysValue']['nav']['nav']) {

                case(""):
                    $tip = "search";
                    $str = array('enabled' => "='1'", 'keywords' => " !=''");
                    break;

                case("CID"):
                    $tip = "words";
                    if (empty($row))
                        return false;
                    else
                        $data = $row;
                    break;

                case("UID"):
                    $tip = "words";
                    if (empty($row))
                        return false;
                    else
                        $data[] = $row;
                    break;

                default:
                    $tip = "search";
                    $str = array('enabled' => "='1'", 'keywords' => " !=''");
                    break;
            }

            if (empty($row))
                $data = $this->PHPShopOrm->select(array('keywords', 'id'), $str, false, array("limit" => $this->page_limit), __CLASS__, __FUNCTION__);

            if (is_array($data))
                foreach ($data as $row) {
                    $explode = explode(", ", $row['keywords']);
                    foreach ($explode as $ev)
                        if (!empty($ev)) {
                            $ArrayWords[] = $ev;
                            $ArrayLinks[$ev] = $row['id'];
                        }
                }
            if (is_array($ArrayWords))
                foreach ($ArrayWords as $k => $v) {
                    $count = array_keys($ArrayWords, $v);
                    $CloudCount[$v]['size'] = count($count);
                }

            // Урезаем лишние элементы
            $i = 0;
            if (is_array($CloudCount))
                foreach ($CloudCount as $k => $v) {
                    if ($i < $this->word_limit)
                        $CloudCountLimit[$k] = $v;
                    $i++;
                }


            //!!!!!! Убрать строку, если нужен вывод в виде флэша, как было в предыдущих версиях!!!!
            $tip = "words";

            if (is_array($CloudCountLimit))
                foreach ($CloudCountLimit as $key => $val) {

                    // Чистим теги
                    $key = str_replace('"', '', $key);
                    $key = str_replace("'", '', $key);
                    if ($tip == "words")
                        $disp.='<div><a href="/search/?words=' . urlencode($key) . '">' . $key . '</a></div>';
                    else
                        $disp.="<a href='/search/?words=" . urlencode($key) . "' style='font-size:12pt;'>$key</a>";
                }

            // Чистим теги
            $disp = str_replace('\n', '', $disp);

            if ($tip == "search" and !empty($disp))
                $disp = '
<div id="wpcumuluscontent">Загрузка флеш...</div><script type="text/javascript">
var dd=new Date();
var spath = "' . $this->get('dir.dir') . 'phpshop/lib/templates";
var so = new SWFObject(spath+"/tagcloud/tagcloud.swf?rnd="+dd.getTime(), "tagcloudflash", "180", "180", "9", "' . $this->color . '");
so.addParam("wmode", "transparent");
so.addParam("allowScriptAccess", "always");
so.addVariable("tcolor", "' . $this->color . '");
so.addVariable("tspeed", "150");
so.addVariable("distr", "true");
so.addVariable("mode", "tags");
so.addVariable("tagcloud", "<tags>' . $disp . '</tags>");
so.write("wpcumuluscontent");</script>
';

            // Чистим содержание
            $disp = str_replace('\n', '', $disp);
            $disp = str_replace(chr(13), '', $disp);
            $disp = str_replace(chr(10), '', $disp);

            // Определяем переменные
            if (!empty($disp)) {
                $this->set('leftMenuName', __("Облако тегов"));
                $this->set('leftMenuContent', '<div class="product-tags">' . $disp . '</div>');

                // Перехват модуля в конце функции
                $this->setHook(__CLASS__, __FUNCTION__, $disp, 'END');

                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.left_menu'));
            }
            return $dis;
        }
    }

}

/**
 * Элемент Flash-карусель товаров
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopFlashGalleryElement
 * @version 1.0
 * @package PHPShopElements
 */
class PHPShopFlashGalleryElement extends PHPShopElements {

    var $width = 520;
    var $height = 150;
    var $background = true;
    var $limit = 10;

    /**
     * Конструктор
     */
    function __construct() {
        parent::PHPShopElements();
    }

    /**
     * Flash-карусель товаров
     * @return string
     */
    function index() {

        // Перехват модуля в начале функции
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;

        $dis = '
        <div id="flashban" style="padding-top:10px;" align="center">загрузка флеш...</div>
        <script type="text/javascript">
        var dd = new Date();
        var spath = "' . $this->get('dir.dir') . 'phpshop/lib/templates";
        var so = new SWFObject(spath+"/stockgallery/banner.swf?rnd=" + dd.getTime(), "banner", "' . $this->width . '", "' . $this->height . '", "9", "#ffffff");
        so.addParam("flashvars", "itempath="+spath+"/stockgallery/item.swf&xmlpath="+spath+"/stockgallery/banner.xml.php?background=' . $this->background . '&limit=' . $this->limit . '");
        so.addParam("quality", "best");
        so.addParam("scale", "noscale");
        so.addParam("wmode", "transparent");
        so.write("flashban");   
        </script>';

        return $dis;
    }

}

?>