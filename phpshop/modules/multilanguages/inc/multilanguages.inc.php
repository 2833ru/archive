<?php

/**
 * Элемент последние записи блога
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopElements
 */
if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));

class PHPShopMultilanguagesElement extends PHPShopElements {

    /**
     * @var bool  показывать только на главной
     */
    var $disp_only_index = false;

    /**
     * @var Int Кол-во записей
     */
    var $limit = 3;

    /**
     * Сортировка корневых каталогов [num|name]
     * @var string 
     */
    var $root_order = 'num, name';

    /**
     * Конструктор
     */
    function __construct() {

        // Отладка
        $this->debug = false;

        // Имя Бд
        $this->objBase = $GLOBALS['SysValue']['base']['categories'];
        parent::__construct();
    }

    /**
     * Форма последних объявлений
     * @return string
     */
    function lastForma() {
        $ini = $_SERVER['DOCUMENT_ROOT'] . "/phpshop/modules/multilanguages/inc/lang" . $_SESSION['lang_prefix'] . ".ini";



        if (file_exists($ini)) {
            $SysValue = parse_ini_file($ini, 1);

            if (!empty($SysValue['lang']) and is_array($SysValue['lang'])) {
                foreach ($SysValue['lang'] as $k => $v) {
                    if (!strstr($k, '#'))
                        $GLOBALS['SysValue']['multilanguages'][$k] = $v;
                }
            }
            else
                $SysValue['lang'] = null;
        }


        foreach ($GLOBALS['SysValue']['multilanguages'] as $key => $value) {
            $this->set($key, $value);
        }

        global $link_db;
        $sql = 'SELECT * FROM `phpshop_modules_multilanguages` WHERE enabled="1"';
        $query = mysqli_query($link_db, $sql);
        $multilanguages = mysqli_fetch_array($query);
        do {
            $multilanguages_new[] = $multilanguages;
            if ($_SESSION['lang_prefix'] == '_' . $multilanguages['prefix']) {
                $selected_prefix = $multilanguages['prefix'];
            }
        } while ($multilanguages = mysqli_fetch_array($query));

        if ($selected_prefix == '') {
            $selected_prefix = 'ru';
        }

        $m_menu = '<li><a href="?lang_prefix=ru">Русский</a></li>';
        if (is_array($multilanguages_new)) {

            foreach ($multilanguages_new as $key => $value) {
                $m_menu .= '<li><a href="?lang_prefix=' . $value['prefix'] . '">' . $value['name'] . '</a></li>';
            }
        }

        $lang_menu = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-angle-down" aria-hidden="true"></i> ' . $selected_prefix . '</a>
                        <ul class="dropdown-menu dropdown-menu-right" role="menu">
                            ' . $m_menu . '
                        </ul>';

        $this->set('lang_panel_menu_top', $lang_menu);
    }

    /**
     * Вывод навигации каталогов
     * @param array $replace массив замены стилей
     * @param array $where массив параметров выборки, используется для вывода определенного каталога
     * PHPShopShopCatalogElement::leftCatal(false,$where['id']=1);
     * @return string
     */
    function leftCatalMulti($replace = null, $where = null) {
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
        if (defined("HostID"))
           $where['servers'] = " REGEXP 'i" . HostID . "i'";

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $this->data = $PHPShopOrm->select(array('*'), $where, array('order' => $this->root_order), array("limit" => 100), __CLASS__, __FUNCTION__);
        if (is_array($this->data))
            foreach ($this->data as $row) {

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                $multilanguages = unserialize($row['multilanguages']);


                // Определяем переменные
                $this->set('catalogId', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));
                $this->set('catalogPodcatalog', $this->subcatalog($row));
                $this->set('catalogTitle', $row['title']);

                //Имя
                if ($multilanguages['multilanguages_name'][$_SESSION['lang_id']] != '')
                    $this->set('catalogName', $multilanguages['multilanguages_name'][$_SESSION['lang_id']]);
                else
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

        $this->set('leftCatalMulti', $dis);
    }

    /**
     * Вывод подкаталогов
     * @param int $n ИД каталога
     * @return string
     */
    function subcatalog($parent_data) {

        // ID родителя
        $n = $parent_data['id'];
        $i = 1;

        $dis = null;

        $PHPShopOrm = new PHPShopOrm($this->objBase);
        $PHPShopOrm->cache_format = $this->cache_format;
        $PHPShopOrm->cache = $this->cache;
        $PHPShopOrm->debug = $this->debug;

        $where['parent_to'] = '=' . $n;

        // Не выводить скрытые каталоги и дополнительные каталоги
        $where['skin_enabled'] = "!='1' or dop_cat LIKE '%#$n#%'";

        // Мультибаза
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif(defined("HostMain"))
            $where['skin_enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';
        
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

                $multilanguages = unserialize($row['multilanguages']);
                // Определяем переменные
                //Имя
                if ($multilanguages['multilanguages_name'][$_SESSION['lang_id']] != '')
                    $this->set('catalogName', $multilanguages['multilanguages_name'][$_SESSION['lang_id']]);
                else
                    $this->set('catalogName', $row['name']);

                $this->set('catalogUid', $row['id']);
                $row['i'] = $i;

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
     * Вывод главного навигационного меню
     * @return string
     */
    function topMenuMulti() {
        $dis = null;

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__, null, 'START');
        if ($hook)
            return $hook;

        $where['category'] = "=1000";
        $where['enabled'] = "='1'";

        
        // Мультибаза
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif(defined("HostMain"))
            $where['enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        $objBase = $GLOBALS['SysValue']['base']['page'];
        $PHPShopOrm = new PHPShopOrm($objBase);
        $PHPShopOrm->debug=false;

        $data = $PHPShopOrm->select(array('name', 'link', 'multilanguages'), $where, array('order' => 'num'), array("limit" => 20));
        if (is_array($data))
            foreach ($data as $row) {
                $multilanguages = unserialize($row['multilanguages']);
                // Определяем переменные
                //Имя
                if ($multilanguages['multilanguages_name'][$_SESSION['lang_id']] != '')
                    $this->set('topMenuName', $multilanguages['multilanguages_name'][$_SESSION['lang_id']]);
                else
                    $this->set('topMenuName', $row['name']);


                // Определяем переменные
                $this->set('topMenuLink', $row['link']);

                // Активная страница
                if ($row['link'] == $this->PHPShopNav->getName(true))
                    $this->set('topMenuActive', 'active');
                else
                    $this->set('topMenuActive', '');

                // Перехват модуля
                $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                // Подключаем шаблон
                $dis.=$this->parseTemplate($this->getValue('templates.top_menu'));
            }

        $this->set('topMenuMulti', $dis);
    }

    /**
     * Вывод навигации каталогов
     * @return string
     */
    function pageCatalMulti() {
        $dis = null;
        $i = 0;

        $this->PHPShopOrm->cache = true;
        $data = $this->PHPShopOrm->select(array('*'), array('parent_to' => '=0'), array('order' => 'num'), array("limit" => 100));

        // Перехват модуля в начале
        $hook = $this->setHook(__CLASS__, __FUNCTION__, $data, 'START');
        if ($hook)
            return $hook;

        if (is_array($data))
            foreach ($data as $row) {

                // Определяем переменные
                $this->set('catalogId', $row['id']);
                $this->set('catalogI', $i);
                $this->set('catalogTemplates', $this->getValue('dir.templates') . chr(47) . $this->PHPShopSystem->getValue('skin') . chr(47));

                // Если есть страницы
                if ($this->chek($row['id'])) {

                    $multilanguages = unserialize($row['multilanguages']);
                    // Определяем переменные
                    //Имя
                    if ($multilanguages['multilanguages_name'][$_SESSION['lang_id']] != '')
                        $this->set('catalogName', $multilanguages['multilanguages_name'][$_SESSION['lang_id']]);
                    else
                        $this->set('catalogName', $row['name']);

                    $this->set('catalogId', $row['id']);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'MIDDLE');

                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_page_forma_2'));
                } else {
                    $this->set('catalogPodcatalog', $this->subcatalog($row['id']));

                    $multilanguages = unserialize($row['multilanguages']);
                    // Определяем переменные
                    //Имя
                    if ($multilanguages['multilanguages_name'][$_SESSION['lang_id']] != '')
                        $this->set('catalogName', $multilanguages['multilanguages_name'][$_SESSION['lang_id']]);
                    else
                        $this->set('catalogName', $row['name']);

                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    $dis.=$this->parseTemplate($this->getValue('templates.catalog_page_forma'));
                }

                $i++;
            }
        $this->set('pageCatalMulti', $dis);
    }

    /**
     * Вывод последних новостей
     * @return string
     */
    function miniNewsMulti() {
        $dis = null;

        // Перехват модуля
        $this->setHook(__CLASS__, __FUNCTION__, false, 'START');

        if ($this->PHPShopNav->index())
            $view = true;
        else
            $view = false;

        if (!empty($view)) {

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);
            $result = $PHPShopOrm->select(array('id', 'zag', 'datas', 'kratko', 'multilanguages'), false, array('order' => 'id DESC'), array("limit" => 5));

            // Проверка на еденичню запись
            if ($this->limit > 1)
                $data = $result;
            else
                $data[] = $result;


            if (is_array($data))
                foreach ($data as $row) {

                    $multilanguages = unserialize($row['multilanguages']);
                    // Определяем переменные
                    //Имя
                    if ($multilanguages['multilanguages_name'][$_SESSION['lang_id']] != '')
                        $this->set('newsZag', $multilanguages['multilanguages_name'][$_SESSION['lang_id']]);
                    else
                        $this->set('newsZag', $row['zag']);


                    if ($multilanguages['multilanguages_content'][$_SESSION['lang_id']] != '')
                        $this->set('newsKratko', $multilanguages['multilanguages_content'][$_SESSION['lang_id']]);
                    else
                        $this->set('newsKratko', $row['kratko']);

                    // Определяем переменные
                    $this->set('newsId', $row['id']);
                    //$this->set('newsZag', $row['zag']);
                    $this->set('newsData', $row['datas']);
                    //$this->set('newsKratko', $row['kratko']);
                    // Перехват модуля
                    $this->setHook(__CLASS__, __FUNCTION__, $row, 'END');

                    // Подключаем шаблон
                    $dis.=$this->parseTemplate($this->getValue('templates.news_main_mini'));
                }
            $this->set('miniNewsMulti', $dis);
        }
    }

}

$PHPShopMultilanguagesElement = new PHPShopMultilanguagesElement();

// Ссылка в навигацию
$PHPShopMultilanguagesElement->lastForma();
$PHPShopMultilanguagesElement->leftCatalMulti();
$PHPShopMultilanguagesElement->topMenuMulti();
$PHPShopMultilanguagesElement->pageCatalMulti();
$PHPShopMultilanguagesElement->miniNewsMulti();

if ($_GET['lang_prefix']) {
    global $link_db;
    $sql = 'SELECT * FROM `phpshop_modules_multilanguages` WHERE prefix="' . $_GET['lang_prefix'] . '" LIMIT 1';
    $query = mysqli_query($link_db, $sql);
    $multilanguages = mysqli_fetch_array($query);


    $_SESSION['lang_prefix'] = '_' . $_GET['lang_prefix'];
    $_SESSION['lang_id'] = $multilanguages['id'];
}

if ($_GET['lang_prefix'] == 'ru') {
    $_SESSION['lang_prefix'] = '';
    $_SESSION['lang_id'] = '';
}


if ($_GET['lang_prefix'] != '') {
    header("HTTP/1.1 301 Moved Permanently");
    header("Location: http://" . $_SERVER['SERVER_NAME'] . $_SERVER['REDIRECT_URL']);
    exit();
}
?>