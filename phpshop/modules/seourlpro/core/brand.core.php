<?php

include_once("phpshop/core/shop.core.php");

class PHPShopBrand extends PHPShopShopCore {

    function __construct() {

        // Отладка
        $this->debug = false;

        $this->path = '/brand';

        // Список экшенов
        $this->action = array("nav" => "index");

        parent::__construct();
    }

    /**
     * SEO навигация брендов
     */
    function index(){

        // Валюта
        $this->set('productValutaName', $this->currency());

        // Количество ячеек
        if (empty($this->cell) and isset($_GET['gridChange']))
            $this->cell = $this->calculateCell("selection", $this->PHPShopSystem->getValue('num_vitrina'));
        elseif (empty($this->cell))
            $this->cell = 3;

        $PHPShopNav = new PHPShopNav();
        $seo_name = explode(".", $PHPShopNav->getNav());
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);
        $vendor = $PHPShopOrm->select(array("*"), array('sort_seo_name' => "='$seo_name[0]'"));
        $v = array($vendor["category"] => $vendor["id"]);

        // Фильтр сортировки
        $order = $this->query_filter($this, $v);

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

        // Описание значения характеристики
        $PHPShopOrm = new PHPShopOrm();
        $result = $PHPShopOrm->query('SELECT a.*, b.content FROM ' . $this->getValue("base.sort") . ' AS a JOIN ' . $this->getValue("base.page") . ' AS b ON a.page = b.link where a.id = ' . $vendor["id"] . ' limit 1');
        $row = mysqli_fetch_array($result);
        if (is_array($row)) {

            // Описание
            $this->set('sortDes', stripslashes($row['content']));

            // Название
            $this->set('sortName', $row['name']);

            // Заголовок
            $this->title = __('Бренд') . " - " . $row['name'] . " - " . $this->PHPShopSystem->getParam('title');
            $this->description = __('Бренд') . " - " . $row['name'];
            $this->keywords = $row['name'];
        }
        else
            $this->title = __('Бренд') . " - " . $this->PHPShopSystem->getParam('title');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.product_selection_list'));
    }

    /**
     * Cортировка товаров по бренду
     */
    function query_filter($obj, $v) {
        global $SysValue;

        $s = intval(@$_REQUEST['s']);
        $f = intval(@$_REQUEST['f']);

        if (!empty($_REQUEST['p']))
            $p = intval($_REQUEST['p']);
        else
            $p = 1;

        $num_row = $obj->num_row;
        $num_ot = 0;
        $q = 0;
        $sort =  $sortQuery = null;

        // Сортировка по характеристикам
        if (is_array($v)) {
            $sort.= ' and (';
            foreach ($v as $key => $value) {

                // Обычный отбор []
                if (PHPShopSecurity::true_num($key) and PHPShopSecurity::true_num($value)) {
                    $hash = $key . "-" . $value;
                    $sort.=" vendor REGEXP 'i" . $hash . "i' or";
                    $sortQuery .= "&v[$key]=$value";
                }
            }
            $sort = substr($sort, 0, strlen($sort) - 2);
            $sort.=")";
        }

        // Сортировка принудительная пользователем
        switch ($f) {
            case(1): $order_direction = "";
                break;
            case(2): $order_direction = " desc";
                break;
            default: $order_direction = "";
                break;
        }
        switch ($s) {
            case(1): $order = array('order' => 'name' . $order_direction);
                break;
            case(2): $order = array('order' => 'price' . $order_direction);
                break;
            case(3): $order = array('order' => 'num' . $order_direction);
                break;
            default: $order = array('order' => 'num, name' . $order_direction);
        }

        // Преобзазуем массив уловия сортировки в строку
        foreach ($order as $key => $val)
            $string = $key . ' by ' . $val;

        // Все страницы
        if ($p == "all") {
            $sql = "select * from " . $SysValue['base']['table_name2'] . " where enabled='1' and parent_enabled='0' $sort  $string";
        }
        else
            while ($q < $p) {

                $sql = "select * from " . $SysValue['base']['table_name2'] . " where enabled='1' and parent_enabled='0' $sort  $string LIMIT $num_ot, $num_row";
                $q++;
                $num_ot = $num_ot + $num_row;
            }

        $obj->selection_order = array(
            'sortQuery' => $sortQuery,
            'sortV' => $sort
        );

        // Возвращаем SQL запрос
        return $sql;
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

            $this->set("previousLink", "?f=" . $_REQUEST['f'] . "&s=" . $_REQUEST['s'] . "&p=" . $p_do);

            $this->set("nextLink", "?f=" . $_REQUEST['f'] . "&s=" . $_REQUEST['s'] . "&p=" . $p_to);

            $nav.=$navigat;


            $this->set("pageNow", $this->getValue('lang.page_now'));
            $this->set("navBack", $this->lang('nav_back'));
            $this->set("navNext", $this->lang('nav_forw'));
            $this->set("navigation", $navigat);

            // Назначаем переменную шаблонизатора
            $nav = parseTemplateReturn($template_location . "paginator/paginator_main.tpl", $template_location_bool);
            $this->set('productPageNav', $nav);

        }
    }

}

?>