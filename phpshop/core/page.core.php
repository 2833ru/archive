<?php

/**
 * Обработчик страниц
 * @author PHPShop Software
 * @tutorial http://wiki.phpshop.ru/index.php/PHPShopPage
 * @version 1.3
 * @package PHPShopCore
 */
class PHPShopPage extends PHPShopCore {

    /**
     * Таблица для навигации хлебных крошек
     * @var string
     */
    var $navigationBase = 'base.page_categories';

    /**
     * Режим отладки
     * @var bool
     */
    var $debug = false;
    var $odnootip_cell_center = 2;
    var $odnootip_cell_block = 1;

    /**
     * Конструктор
     */
    function __construct() {

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['page'];

        // Список экшенов
        $this->action = array("nav" => "CID");
        $this->empty_index_action = true;

        parent::__construct();
    }

    /**
     * Однотипные товары
     * @param array $row массив данных
     */
    function odnotip($row) {
        global $PHPShopProductIconElements;

        //$this->odnotip_setka_num = 2;
        $this->line = false;
        $this->template_odnotip = 'main_spec_forma_icon';

        // Перехват модуля в начале функции
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $row, 'START');
        if ($hook)
            return true;

        $disp = null;
        $odnotipList = null;
        if (!empty($row['odnotip'])) {
            if (strpos($row['odnotip'], ','))
                $odnotip = explode(",", $row['odnotip']);
            elseif (is_numeric(trim($row['odnotip'])))
                $odnotip[] = trim($row['odnotip']);
        }

        // Список для выборки
        if (is_array($odnotip))
            foreach ($odnotip as $value) {
                if (!empty($value))
                    $odnotipList.=' id=' . trim($value) . ' OR';
            }

        $odnotipList = substr($odnotipList, 0, strlen($odnotipList) - 2);

        // Режим проверки остатков на складе
        if ($this->PHPShopSystem->getSerilizeParam('admoption.sklad_status') == 2)
            $chek_items = ' and items>0';
        else
            $chek_items = null;

        if (!empty($odnotipList)) {

            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug = $this->debug;
            $result = $PHPShopOrm->query("select * from " . $this->getValue('base.products') . " where (" . $odnotipList . ") " . $chek_items . " and  enabled='1' and parent_enabled='0' and sklad!='1' order by num");
            while ($row = mysqli_fetch_assoc($result))
                $data[] = $row;

            // Сетка товаров
            if (!empty($data) and is_array($data))
                $disp = $PHPShopProductIconElements->seamply_forma($data, $this->odnotip_setka_num, $this->template_odnotip, $this->line);
        }


        if (!empty($disp)) {
            // Вставка в центральную часть
            if (PHPShopParser::check($this->getValue('templates.main_product_odnotip_list'), 'productOdnotipList')) {
                $this->set('productOdnotipList', $disp);
                $this->set('productOdnotip', __('Рекомендуемые товары'));
            } else {
                // Вставка в правый столбец
                $this->set('specMainTitle', __('Рекомендуемые товары'));
                $this->set('specMainIcon', $disp);
            }

            // Перехват модуля в середине функции
            $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

            $odnotipDisp = ParseTemplateReturn($this->getValue('templates.main_product_odnotip_list'));
            $this->set('odnotipDisp', $odnotipDisp);
        }
        // Выводим последние новинки
        else {
            $this->set('specMainIcon', $PHPShopProductIconElements->specMainIcon(true, $this->category));
        }

        // Перехват модуля в конце функции
        $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
    }

    /**
     * Экшен по умолчанию, вывод данных по странице
     * @return string
     */
    function index($link = false) {

        $hook_start = $this->setHook(__CLASS__, __FUNCTION__, false, 'START');
        if ($hook_start)
            return true;

        // Безопасность
        if (empty($link))
            $link = PHPShopSecurity::TotalClean($this->PHPShopNav->getName(true), 2);

        // Страницы только для аторизованных
        if (isset($_SESSION['UsersId'])) {
            $sort = " and ((secure !='1') OR (secure ='1' AND secure_groups='') OR (secure ='1' AND secure_groups REGEXP 'i" . $_SESSION['UsersStatus'] . "-1i')) ";
        } else {
            $sort = " and (secure !='1') ";
        }

        // Мультибаза
        if (defined("HostID")) {
            $sort.= " and servers REGEXP 'i" . HostID . "i'";
        }
        elseif(defined("HostMain"))
            $sort.= " and (servers = '' or servers REGEXP 'i1000i')";

        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->debug = $this->debug;
        $result = $PHPShopOrm->query("select * from " . $this->objBase . " where link='$link' and enabled='1' $sort limit 1");
        $row = mysqli_fetch_array($result);

        // Прикрываем страницу от дубля
        if ($row['category'] == 2000)
            return $this->setError404();
        elseif (empty($row['id']))
            return $this->setError404();

        $this->category = $row['category'];
        $this->PHPShopCategory = new PHPShopPageCategory($this->category);
        $this->category_name = $this->PHPShopCategory->getName();

        // Определяем переменные
        $this->set('pageContent', Parser(stripslashes($row['content'])));
        $this->set('pageTitle', $row['name']);
        $this->set('catalogCategory', $this->category_name);
        $this->set('catalogId', $this->category);
        $this->PHPShopNav->objNav['id'] = $row['id'];

        // Выделяем меню раздела
        $this->set('NavActive', $row['link']);

        // Однотипные товары
        $this->odnotip($row);

        // Мета
        if (empty($row['title']))
            $title = $row['name'] . " - " . $this->PHPShopSystem->getValue("name");
        else
            $title = $row['title'];

        $this->title = $title;
        $this->description = $row['description'];
        $this->keywords = $row['keywords'];
        $this->lastmodified = $row['datas'];

        // Навигация хлебные крошки
        $this->navigation($row['category'], $row['name']);

        // Перехват модуля
        $hook_end = $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');
        if ($hook_end)
            return true;

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Экшен выборки подробной информации при наличии переменной навигации CID
     */
    function CID() {

        if ($this->setHook(__CLASS__, __FUNCTION__))
            return true;

        // ID категории
        $this->category = PHPShopSecurity::TotalClean($this->PHPShopNav->getId(), 1);
        $this->PHPShopCategory = new PHPShopPageCategory($this->category);
        $this->category_name = $this->PHPShopCategory->getName();

        $PHPShopOrm = new PHPShopOrm($this->getValue('base.page_categories'));
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('id,name'), array('parent_to' => "=" . $this->category), false, array('limit' => 1));

        // Если страницы
        if (empty($row['id'])) {

            $this->ListPage();
        }
        // Если каталоги
        else {

            $this->ListCategory();
        }
    }

    /**
     * Вывод списка страниц
     * @return string
     */
    function ListPage() {
        $dis = null;
        $lastmodified = 0;

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // 404
        if (empty($this->category_name))
            return $this->setError404();

        // Выборка данных
        $dataArray = $this->PHPShopOrm->select(array('*'), array('category' => '=' . $this->category, 'enabled' => "='1'"), array('order' => 'num'), array('limit' => 100));
        if (is_array($dataArray)) {

            if (count($dataArray) > 1)
                foreach ($dataArray as $row) {
                    $dis.=PHPShopText::li($row['name'], '/page/' . $row['link'] . '.html');

                    // Максимальная дата изменения
                    if ($row['datas'] > $lastmodified)
                        $lastmodified = $row['datas'];
                }
            else {
                return $this->index($dataArray[0]['link']);
            }
        }


        $disp = PHPShopText::ul($dis);

        // Описание каталога
        $this->set('catContent', $this->PHPShopCategory->getContent());
        $this->set('pageContent', $disp);
        $this->set('pageTitle', $this->category_name);

        // Данные родительской категории
        $cat = $this->PHPShopCategory->getValue('parent_to');
        if (!empty($cat)) {
            $PHPShopOrm = new PHPShopOrm($this->getValue('base.page_categories'));
            $PHPShopOrm->cache = true;
            $PHPShopOrm->debug = $this->debug;
            $parent_category_row = $PHPShopOrm->select(array('id,name'), array('id' => '=' . $cat), false, array('limit' => 1), __FUNCTION__);
        } else {
            $parent_category_row['name'] = $this->lang('catalog');
        }
        $this->set('catalogCategory', $parent_category_row['name']);
        $this->set('catalogId', $cat);

        // Мета
        $this->title = $this->category_name . " - " . $this->PHPShopSystem->getValue("name");
        $this->lastmodified = $lastmodified;

        // Навигация хлебные крошки
        $this->navigation($cat, $this->category_name);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_page_list'));
    }

    /**
     * Вывод списка категорий
     */
    function ListCategory() {
        $dis = null;

        // Перехват модуля
        if ($this->setHook(__CLASS__, __FUNCTION__, false, 'START'))
            return true;

        // 404
        if (empty($this->category_name))
            return $this->setError404();

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($this->getValue('base.page_categories'));
        $PHPShopOrm->debug = $this->debug;
        $dataArray = $PHPShopOrm->select(array('name', 'id'), array('parent_to' => '=' . $this->category), array('order' => 'num,id desc'), array('limit' => 100));
        if (is_array($dataArray))
            foreach ($dataArray as $row) {

                $dis.=PHPShopText::li($row['name'], '/page/CID_' . $row['id'] . '.html');
            }

        // Список подкаталогов
        $disp = PHPShopText::ul($dis);

        // Описание каталога
        $this->set('catContent', $this->PHPShopCategory->getContent());
        $this->set('catName', $this->category_name);
        $this->set('pageContent', $disp);
        $this->set('pageTitle', $this->category_name);

        // Мета
        $this->title = $this->category_name . " - " . $this->PHPShopSystem->getValue("name");

        // Навигация хлебные крошки
        $this->navigation($this->PHPShopCategory->getParam('parent_to'), $this->category_name);

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, $dataArray, 'END');

        // Подключаем шаблон
        $this->parseTemplate($this->getValue('templates.page_catalog_list'));
    }

}

?>