<?php

/*
 * **** 
 * Модуль "СМАРТ-ФИЛЬТР" для PHPShop Enterprise 3.6
 * Copyright © WEB for ALL, 20010-2014 
 * @author "WEB for ALL" (www.web4.su) 
 * @version 1.0
 * ****
 */
/*
 * Обработчик страницы Smart Filter
 */

class PHPShopSmart extends PHPShopShopCore {

    var $debug = false;
    var $cache = true;
    var $cell;
    var $num_row;
    var $smart = 'sf_'; // префикс к имени инпутов для отправки данных из формы.
    var $ALL = 200; // максимальное значения вывода товаров для "Показать все"

    /**
     * Конструктор
     */

    function PHPShopSmart() {

        // Настройка
        $this->system();

        // выборка из БД
        $this->sql_sort = $this->sql_sort();

        parent::PHPShopShopCore();
        $this->PHPShopOrm->cache_format = $this->cache_format;
    }

    /**
     * Настройка
     */
    function system() {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['w4asmartfilter']['w4asmartfilter_system']);
        $this->system = $PHPShopOrm->select();
    }

    /**
     * Вывод списка товаров
     */
    function index() {

        $system = $this->system;
        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // Путь для навигации
        $this->objPath = './page_';

        // Валюта
        $this->set('productValutaName', $this->currency());

        $this->set('catalogCategory', 'Умный фильтр');

        // Количество ячеек
        if (empty($this->cell))
            $this->cell = $this->PHPShopSystem->getValue('num_vitrina');

        if (empty($this->num_row))
            $this->num_row = $this->PHPShopSystem->getValue('num_row');

        // выборка из БД 
        //$sql_sort = $this->sql_sort;
        //формафильтра
        $this->set('vendorDisp', $this->w4a_smart_vendor_disp());
        //$this->set('vendorSelectDisp',$this->w4a_smart_vendor_select_disp());



        if ($system['price_enabled'] == '1') {
            $dis = $GLOBALS['SysValue']['other']['w4aSmartFilter'];
            $dis.=$this->w4a_price_disp() . $this->w4a_smart_vendor_disp() . $this->w4a_smart_vendor_select_disp();
        } else {
            $dis = $GLOBALS['SysValue']['other']['w4aSmartFilter'];
            $dis.=$this->w4a_smart_vendor_disp() . $this->w4a_smart_vendor_select_disp();
        }

        $this->set('w4aSmartFilterForm', $dis);

        $diss = parseTemplateReturn($GLOBALS['SysValue']['templates']['w4asmartfilter']['smart_filter'], true);
        $disp = '
							' . $diss . '
							
						
			';
        $this->set('w4aSmartFilterModWin', '<div class="w4a_smart_modal_0"><div class="w4a_smart_modal_1" id="w4asmart">Найдено: <span id="w4asmart_num"></span> шт.
				<a href="javascript:document.sort2.submit()" >показать</a></div></div>');
        $this->set('w4aSmartFilter', $disp);


        // Фильтр сортировки
        $order = $this->query_filter();

        // Простой запрос
        if (is_array($order)) {
            $this->dataArray = parent::getListInfoItem(array('*'), array('enabled' => "='1'"), $order, __CLASS__, __FUNCTION__);
        } else {
            // Сложный запрос
            $this->PHPShopOrm->sql = $order;
            $this->PHPShopOrm->comment = __CLASS__ . '.' . __FUNCTION__;
            $this->dataArray = $this->PHPShopOrm->select();
            $this->PHPShopOrm->clean();
        }


        // Пагинатор
        if (is_array($order)) {
            $this->setPaginator(count($this->dataArray));
        } else {
            $this->setPaginator($this->num_row, $this->sql_sort);
        }

        // 
        $smart_arr = $this->get_smart();
        if (is_array($smart_arr)) {
            foreach ($smart_arr as $key => $val) {
                foreach ($val as $k => $v) {
                    $smart_get .= '&amp;' . $this->smart . $k . '=' . $key;
                }
            }
            $this->set('productVendor', $smart_get);
        }



        // добавляем к пагинатору GET-параметры (все!!! не только смарт фильтра, но и сортировки по Цене/Наименованию)
        // определяем количество страниц в выборке
        $sql = "SELECT count(id) as count FROM " . $GLOBALS['SysValue']['base']['table_name2'] . " WHERE " . $this->sql_sort;
        $count = mysql_fetch_array(mysql_query($sql));
        $page_max = ceil($count['count'] / $this->num_row);

        // парсим пагинатор
        for ($i = 0; $i <= $page_max; $i++) {
            $GLOBALS['SysValue']['other']['productPageNav'] = str_replace($this->objPath . $i . '.html', $this->objPath . $i . '.html?' . $GLOBALS['SysValue']['nav']['querystring'], $GLOBALS['SysValue']['other']['productPageNav']);
        }
        $GLOBALS['SysValue']['other']['productPageNav'] = str_replace($this->objPath . 'ALL.html', $this->objPath . 'ALL.html?' . $GLOBALS['SysValue']['nav']['querystring'], $GLOBALS['SysValue']['other']['productPageNav']);
        $GLOBALS['SysValue']['other']['productPageNav'] = str_replace('page.html', 'page.html?' . $GLOBALS['SysValue']['nav']['querystring'], $GLOBALS['SysValue']['other']['productPageNav']);

        // Добавляем в дизайн ячейки с товарами
        $grid = $this->product_grid($this->dataArray, $this->cell);
        if (empty($grid))
            $grid = PHPShopText::h2($this->lang('empty_product_list'));
        $this->add($grid, true);

        // Заголовок
        $this->title = "Умный фильтр - " . $this->PHPShopSystem->getParam('title');

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $this->dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($GLOBALS['SysValue']['templates']['w4asmartfilter']['smart_page_list'], true);
    }

    /**
     * Генерация SQL запроса со сложными фильтрами и условиями
     * @return mixed
     */
    function query_filter($where = false) {

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $where);

        $system = $this->system;

        if (!empty($hook))
            return $hook;

        //выборка из БД
        $sort = $this->sql_sort;

        // учет пагинации
        $page = $GLOBALS['SysValue']['nav']['id'];
        if ($page == 'ALL') {
            $limit = 'limit 0,' . $this->ALL;
        } elseif ($page > 1) {
            $limit = 'limit ' . (($page - 1) * ($this->num_row)) . ',' . $this->num_row;
        } else {
            $limit = 'limit 0,' . $this->num_row;
        }



        // Сортировка
        if (!empty($_GET['ss']) and !empty($_GET['fs'])) { // наличие параметров сортиовки
            if ($_GET['ss'] == 1) {
                $order = 'ORDER BY name';
            } elseif ($_GET['ss'] == 2) {
                $order = 'ORDER BY price';
            } else {
                $order = 'ORDER BY name';
            }

            if ($_GET['fs'] == 1) {
                $order .= ' DESC';
            } elseif ($_GET['fs'] == 2) {
                $order .= ' ASC';
            } else {
                $order .= ' ASC';
            }
        } else {

            $order = 'ORDER BY name ASC';
        }

        if (!empty($sort)) {
            $sql = "SELECT * FROM " . $GLOBALS['SysValue']['base']['table_name2'] . " where $sort  $order $limit";
            //	print_r($sql);
        } else {
            $sql = array('order' => 'num');
        }

        return $sql;
    }

    function sql_sort($flag = false) {
        $system = $this->system;
        $smart_arr = $this->get_smart();
        $price_arr = $this->get_price();

        if (is_array($smart_arr)) { // если есть GET-параметры характеристик 
            $ii = 0;
            foreach ($smart_arr as $key => $val) {

                $i = 0;
                foreach ($val as $k => $v) {

                    if ($i == 0) {
                        $sort_or = "var_id_" . intval($key) . "=" . intval($k);
                    } else {
                        $sort_or .= " OR var_id_" . intval($key) . "=" . intval($k);
                    }

                    $i++;
                }

                if ($ii == 0) {
                    $sort = "($sort_or)";
                } else {
                    $sort .= " AND ($sort_or)";
                }
                $sort_or = '';
                $ii++;
            }

            if (is_array($price_arr)) {

                $price = " AND price between " . intval($price_arr['from']) . " and " . intval($price_arr['till']);
            } else {
                $price = '';
            }

            if ($flag === false) {
                $sql = "id in(
					SELECT product_id
					FROM `phpshop_modules_w4asmartfilter`
					WHERE $sort $price
					GROUP BY product_id
					)";
            } else {
                $sql = "id in(
					SELECT product_id
					FROM `phpshop_modules_w4asmartfilter`
					WHERE $sort $price
					GROUP BY product_id
					)";
            }
        }
        else
            $sql = false;

        return $sql;
    }

    /**
     * возвращает массив характеристик из GET-параметров
     * $smart_arr[k1][k2]
     * k1 - # поля в таблице smart-фильтра
     * k2 - ID значения характеристики
     * false - в GET-параметрах отсутствует значения характеристик
     */
    function get_smart() {

        // собираем массив характеристик фильтра из GET- параметров
        foreach ($_GET as $key => $val) {

            // если ключ содержит префикс относящийся к смарт-фильтру
            if (strpos($key, $this->smart) !== false) {
                $k1 = $val;
                $k2 = str_replace($this->smart, '', $key);
                // проверка на отсутствие значений
                if ($k1 != '' and $k2 != '') {
                    $smart_arr[$k1][$k2] = 'true';
                }
            }
        }
        if (is_array($smart_arr))
            return $smart_arr;
        else
            return false;
    }

    //возвращает массив максим. и мин. значений цены в выборке  
    function get_price_max_min() {

        // заменяем поле id на prod_id
        $sort = str_replace('id in', 'product_id in', $this->sql_sort);

        $sql = "SELECT min(price) as min, max(price) as max FROM " . $GLOBALS['SysValue']['base']['w4asmartfilter']['w4asmartfilter'] . " WHERE " . $sort;

        $row = mysql_fetch_array(mysql_query($sql));
        if (empty($row['min']))
            $min = 0;
        else
            $min = $row['min'];

        if (empty($row['max']))
            return false;
        else
            $max = $row['max'];

        $price_arr = array('min' => $min, 'max' => $max);

        return $price_arr;
    }

    /**
     * возвращает массив цен ОТ и ДО для выборки из GET-параметров
     * $pice_arr=false - по дефолту (не участвует цена)
     * $pice_arr(from=>0,till=>1000) - от 0 до 1000
     * $pice_arr(from=>1000,till=>1000) - равен 1000
     * false - в GET-параметрах отсутствует значения цены или они некорректны
     */
    function get_price() {

        // ключи относящиеся к ценам ps & pf (price start & price finish)
        $ps = $_GET['ps'];
        $pf = $_GET['pf'];

        if (empty($pf) or $pf < $ps or $pf <= 0) { // если введенные данные в поле цен некорректы либо отсутсвуют
            $price_arr = false;
        } else {
            if (empty($ps) or $ps < 0)
                $ps = 0;
            $price_arr = array('from' => $ps, 'till' => $pf);
        }

        return $price_arr;
    }

    // вывод элементов формы фильтра 
    function w4a_smart_vendor_disp() {
        global $SysValue;
        $system = $this->system;

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['w4asmartfilter']['w4asmartfilter_categories']);
        $d = $PHPShopOrm->select(array("*"), array('id_sort' => '>0'));

        if (empty($d[0])) {
            $data[0] = $d; // если характеристика в СМАРТ-ФИЛЬТРЕ только 1
        } else {
            $data = $d;
        }

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name21']);

        $PHPShopOrm2 = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name20']);
        // Редактор
        PHPShopObj::loadClass("text");
        $PHPShopText = new PHPShopText();

        foreach ($data as $val) {

            $row = $PHPShopOrm2->select(array("*"), array('id' => '=' . intval($val['id_sort'])));
            $name = $row['name'];
            $id_sort = $row['id'];
            $row = $PHPShopOrm->select(array("*"), array('category' => '=' . intval($val['id_sort'])), array('order' => 'num ASC'));

            if ($val['id'] == $system['color']) {
                foreach ($row as $v) {
                    if ($_GET[$this->smart . '' . $v['id']] == $val['id']) {
                        // ACHTUNG!!! 
                        // class="w4asmart_color" - Impotant!!! 
                        // По нему определяем наличие данной характеристики через JavaSript						
                        $input = '<input type="hidden" name="' . $this->smart . $v['id'] . '" id="' . $this->smart . $v['id'] . '" value="' . $val['id'] . '" class="w4asmart_color">';
                        $style = 'style="background-color:#' . $v['code'] . ';"';
                        $class = ' active';
                    } else {
                        $input = '';
                        $style = 'style="background-color:#' . $v['code'] . ';"';
                        $class = '';
                    }
                    $diss .= '
					<a href="javascript:w4a_smart_get_color(' . $v['id'] . ',' . $val['id'] . ',\'' . $this->smart . '\');w4a_smart_get_num(\'' . $this->smart . '\');" title="' . $v['name'] . '">
					<div class="smart_color' . $class . '"  id="div_' . $v['id'] . '" ' . $style . '></div></a>
					<span id="input_' . $v['id'] . '" style="display:none;">' . $input . '</span>
					';
                }

                $this->set('w4aSmartName', $name);
                $this->set('w4aSmartValues', $diss);

                $disp .= parseTemplateReturn($GLOBALS['SysValue']['templates']['w4asmartfilter']['smart_vendor_element'], true);
                $diss = '';
            } else {

                if (is_array($row))
                    foreach ($row as $v) {

                        if ($_GET[$this->smart . '' . $v['id']] == $val['id']) {
                            $checker = '" checked style="margin-top:4px;';
                        } else {
                            $checker = '" style="margin-top:4px;';
                        }
                        if ($i == 5) {
                            $diss.='<div style="display:none;" id="add_el_' . $id_sort . '">';

                            $this->set('w4aSmartValueHidderEnd', '</div><div style="text-align: right; padding-right: 10px;"><span style=" cursor:pointer; border-bottom: 1px dotted #CCC; text-decoretion:none;" onClick="w4a_show_smart_element(' . $id_sort . ');"  id="link_' . $id_sort . '">Показать все</span></div>');
                        }

                        $diss.= $PHPShopText->setInput('checkbox" onClick="w4a_smart_get_num(\'' . $this->smart . '\');"', $this->smart . '' . $v['id'] . '', $val['id'] . $checker, $float = "none", $size = 200, $onclick = "return true", $class = false, $caption = false, $description = $v['name']);

                        $i++;
                    }

                $this->set('w4aSmartName', $name);
                $this->set('w4aSmartValues', $diss);

                $disp .= parseTemplateReturn($GLOBALS['SysValue']['templates']['w4asmartfilter']['smart_vendor_element'], true);
                $diss = '';
                $i = 0;
                $this->set('w4aSmartValueHidderEnd', '');
            }
        }

        return '<td class="" id="" colspan="">' . $disp . '</td>';
    }

    // прорисовка кнопки подбора
    function w4a_smart_vendor_select_disp() {

        $disp = parseTemplateReturn($GLOBALS['SysValue']['templates']['w4asmartfilter']['smart_vendor_select_disp'], true);
        return $disp;
    }

    // прорисовка формы ввода для цены
    function w4a_price_disp() {

        //устанавливаем макс и мин значения выборки
        //$price_max_min_arr = $this->get_price_max_min();
        //устанавливаем OT и DO значения из GET-параметров
        $price_arr = $this->get_price();
        if (is_array($price_arr)) {
            $this->set('w4aSmartPriceS', $price_arr['from']);
            $this->set('w4aSmartPriceF', $price_arr['till']);
        } else {
            //$this->set('w4aSmartPriceS',$price_max_min_arr['min']);
            //$this->set('w4aSmartPriceF',$price_max_min_arr['max']);
            $this->set('w4aSmartPriceS', '');
            $this->set('w4aSmartPriceF', '');
        }
        $disp = parseTemplateReturn($GLOBALS['SysValue']['templates']['w4asmartfilter']['smart_price_element'], true);
        return $disp;
    }

}

?>
