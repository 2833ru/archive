<?php

/**
 * Обработчик подбора товаров по характеристикам
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopSelection
 * @version 1.2
 * @package PHPShopShopCore
 */
class PHPShopSelection extends PHPShopShopCore {

    /**
     * Отладка
     * @var bool
     */
    var $debug = false;
    /*
     * Кэширование
     */
    var $cache = false;

    /**
     * Лимит вывода товаров
     * @var int
     */
    var $max_item = 250;

    /**
     * Флаг, выводить в подборке описани значения или самой характеристики. True - характеристик. False - значения.
     * @var bool
     */
    var $descrFlag = false;

    /**
     * Конструктор
     */
    function __construct() {

        PHPShopObj::loadClass("sort");

        // Список экшенов
        $this->action = array("get" => "v", 'nav' => 'index');
        parent::__construct();
        $this->PHPShopOrm->cache_format = $this->cache_format;

        // Навигация хлебные крошки
        $this->navigation(false, __('Бренды'));
    }

    function index() {
        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, null, 'START'))
            return true;

        $this->setError404();
    }

    /**
     * Поиск одноименных характеристик
     */
    function checkName() {

        foreach ($_GET['v'] as $v)
            $id = intval($v);

        $PHPShopSortArray1 = new PHPShopSortArray(array('id' => '=' . $id));
        $name = $PHPShopSortArray1->getParam($id . '.name');

        $PHPShopSortArray = new PHPShopSortArray(array('name' => "='$name'", 'id' => '!=' . $id));

        if (is_array($PHPShopSortArray->getArray()))
            foreach ($PHPShopSortArray->getArray() as $key => $val)
                $_GET['v'][$val['category']] = $val['id'];
    }

    /**
     * Вывод списка товаров
     */
    function v() {

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, null, 'START'))
            return true;

        // Валюта
        $this->set('productValutaName', $this->currency());

        // Количество ячеек
        if (empty($this->cell) and isset($_GET['gridChange']))
            $this->cell = $this->calculateCell("selection", $this->PHPShopSystem->getValue('num_vitrina'));
        elseif (empty($this->cell))
            $this->cell = 1;

        // Поиск одноименных характеристик
        $this->checkName();

        // Фильтр сортировки
        $order = $this->query_filter();

        // Сложный запрос
        $this->PHPShopOrm->sql = $order;
        $this->PHPShopOrm->debug = $this->debug;
        $this->PHPShopOrm->mysql_error = false;
        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $this->dataArray = $this->PHPShopOrm->select();
        $this->PHPShopOrm->clean();
        // Пагинатор
        $count = count($this->dataArray);
        if ($count)
            $this->setPaginator($count, $order);


        // Добавляем в дизайн ячейки с товарами
        $grid = $this->product_grid($this->dataArray, $this->cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // ID характеристики
        foreach ($GLOBALS['SysValue']['nav']['query']['v'] as $key => $val) {
            if ($this->descrFlag)
                $v = intval($key);
            else
                $v = intval($val);
        }

        // Описание значения характеристики
        $PHPShopOrm = new PHPShopOrm();
        $result = $PHPShopOrm->query('SELECT a.*, b.content FROM ' . $this->getValue("base.sort") . ' AS a JOIN ' . $this->getValue("base.page") . ' AS b ON a.page = b.link where a.id = ' . $v . ' limit 1');
        $row = mysqli_fetch_array($result);
        if (is_array($row)) {

            // Описание
            $this->set('sortDes', stripslashes($row['content']));

            // Название
            $this->set('sortName', $row['name']);

            // Заголовок
            $this->title = __('Подбор товаров') . " - " . $row['name'] . " - " . $this->PHPShopSystem->getParam('title');
            $this->description = __('Подбор товаров') . " - " . $row['name'];
            $this->keywords = $row['name'];
        }
        else
            $this->title = __('Подбор товаров') . " - " . $this->PHPShopSystem->getParam('title');

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_selection_list'));
    }

    /**
     * Генерация SQL запроса со сложными фильтрами и условиями
     * Функция вынесена в отдельный файл query_filter.php
     * @return mixed
     */
    function query_filter($where = false) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if (!empty($hook))
            return $hook;

        return $this->doLoadFunction(__CLASS__, __FUNCTION__);
    }

    /**
     * Генерация пагинатора
     */
    function setPaginator($count, $sql = null) {

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

        if (is_array($this->selection_order)) {
            $SQL = " where enabled='1' and parent_enabled='0' " . $this->selection_order['sortV'];
        }
        else
            $SQL = null;


        // Всего страниц
        $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
        $this->PHPShopOrm->clean();
        $result = $this->PHPShopOrm->query("select COUNT('id') as count from " . $this->objBase . $SQL);
        $row = mysqli_fetch_array($result);
        $this->num_page = $row['count'];

        $i = 1;
        $navigat = null;
        $num = ceil($this->num_page / $this->num_row);
        if (empty($_GET['p']))
            $_GET['p'] = 1;
        $this->page = $_GET['p'];

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
                    $p_start = $this->num_row * ($i - 1);
                    $p_end = $p_start + $this->num_row;
                } else {
                    $p_start = $i;
                    $p_end = $this->num_row;
                }


                $this->set("paginPageRangeStart", $p_start);
                $this->set("paginPageRangeEnd", $p_end);
                $this->set("paginPageNumber", $i);


                if ($i != $this->page) {
                    $this->set("paginLink", "?f=" . $_REQUEST['f'] . "&s=" . $_REQUEST['s'] . $this->selection_order['sortQuery'] . "&p=" . $i);
                    if ($i == 1) {
                        $navigat.= parseTemplateReturn($template_location . "paginator/paginator_one_link.tpl", $template_location_bool);
                    } else {
                        if ($i > ($this->page - $this->nav_len) and $i < ($this->page + $this->nav_len)) {
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


            $nav = $this->getValue('lang.page_now') . ': ';

            $this->set("previousLink", "?f=" . $_REQUEST['f'] . "&s=" . $_REQUEST['s'] . $this->selection_order['sortQuery'] . "&p=" . $p_do);

            $this->set("nextLink", "?f=" . $_REQUEST['f'] . "&s=" . $_REQUEST['s'] . $this->selection_order['sortQuery'] . "&p=" . $p_to);

            $nav.=$navigat;


            $this->set("pageNow", $this->getValue('lang.page_now'));
            $this->set("navBack", $this->lang('nav_back'));
            $this->set("navNext", $this->lang('nav_forw'));
            $this->set("navigation", $navigat);

            // Назначаем переменную шаблонизатора
            $nav = parseTemplateReturn($template_location . "paginator/paginator_main.tpl", $template_location_bool);
            $this->set('productPageNav', $nav);

            // Перехват модуля
            $this->setHook(__CLASS__, __FUNCTION__, $nav);
        }
    }

}

?>