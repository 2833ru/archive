<?php

/**
 * Файл выгрузки для Яндекс Маркет
 * @author PHPShop Software
 * @version 3.4
 * @package PHPShopXML
 * @example ?marketplace=retailcrm [bool] Выгрузка для RetailCRM
 * @example ?marketplace=cdek [bool] Выгрузка для СДЭК (упрощенный тип YML с использованием count)
 * @example ?marketplace=aliexpress [bool] Выгрузка для AliExpress (товары отмеченные для AliExpress)
 * @example ?marketplace=sbermarket [bool] Выгрузка для СберМаркет (товары отмеченные для СберМаркет)
 * @example ?getall [bool] Выгрузка всех товаров без учета флага YML. Выгрузка всех изображений.
 * @example ?from [bool] Метка в ссылки товара from
 * @example ?amount [bool] Добавление склада в тег amount для CRM
 * @example ?search [bool] Убрать подтипы из выгрузки (для Яндекс.Поиск по сайту)
 * @example ?utf [bool] Вывод в кодировке UTF-8
 * @example ?price [int] Колонка цен (2/3/4/5)
 * @example ?available [bool] Выводить только в наличии
 * @example ?image_source [bool]  Показывать исходные изображения _big
 */
$_classPath = "../phpshop/";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("promotions");
PHPShopObj::loadClass("order");

// Настройки
$PHPShopSystem = new PHPShopSystem();

// Мультибаза
$PHPShopBase->checkMultibase();

// Промоакции
$PHPShopPromotions = new PHPShopPromotions();

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/**
 * Создание YML для Яндекс Маркета
 * @author PHPShop Software
 * @version 1.3
 * @package PHPShopClass
 */
class PHPShopYml {

    var $xml = null;
    private $categories = [];

    /**
     * вывод характеристик
     * @var bool
     */
    var $vendor = false;

    /**
     * вывод параметров
     * @var bool
     */
    var $param = false;
    var $option = false;

    /**
     * массив брендов
     * @var array
     */
    var $brand_array = array();

    /**
     * массив параметров
     * @var array
     */
    var $param_array = array();

    /**
     * массив значений тег/имя характеристики
     * @var array
     */
    var $vendor_name = array('vendor' => 'Бренд');

    /**
     * память событий модулей
     * @var bool
     */
    var $memory = true;
    var $ssl = 'http://';
    var $image_source = false;

    /**
     * Конструктор
     */
    function __construct() {
        global $PHPShopModules, $PHPShopSystem, $PHPShopPromotions;

        $this->PHPShopSystem = $PHPShopSystem;
        $PHPShopValuta = new PHPShopValutaArray();
        $this->PHPShopValuta = $PHPShopValuta->getArray();

        // Модули
        $this->PHPShopModules = &$PHPShopModules;

        // Промоакции
        $this->PHPShopPromotions = $PHPShopPromotions;

        // Процент накрутки
        $this->percent = $this->PHPShopSystem->getValue('percent');

        // Валюта по умолчанию
        $this->defvaluta = $this->PHPShopSystem->getValue('dengi');
        $this->defvalutaiso = $this->PHPShopValuta[$this->defvaluta]['iso'];
        $this->defvalutacode = $this->PHPShopValuta[$this->defvaluta]['code'];

        // Кол-во знаков после запятой в цене
        $this->format = $this->PHPShopSystem->getSerilizeParam('admoption.price_znak');

        //Ведущий товар в подтипах можно положить в корзину
        $this->parent_price_enabled = $this->PHPShopSystem->getSerilizeParam('admoption.parent_price_enabled');

        // CRM
        $this->option = $this->PHPShopSystem->ifSerilizeParam('1c_option.update_option');

        // SSL
        if (isset($_GET['ssl']))
            $this->ssl = 'https://';
        else if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS']))
            $this->ssl = 'https://';

        // Исходное изображение
        if (isset($_GET['image_source']) and $this->PHPShopSystem->ifSerilizeParam('admoption.image_save_source'))
            $this->image_source = true;
        else
            $this->image_source = false;

        // Колонка цен
        $this->price = $this->PHPShopSystem->getPriceColumn();
        if ($_GET['price'] > 1)
            $this->price = 'price' . intval($_GET['price']);

        $this->setHook(__CLASS__, __FUNCTION__);
    }

    /**
     * Назначение перехвата события выполнения модулем
     * @param string $class_name имя класса
     * @param string $function_name имя метода
     * @param mixed $data данные для обработки
     * @param string $rout позиция вызова к функции [END | START | MIDDLE], по умолчанию END
     * @return bool
     */
    function setHook($class_name, $function_name, $data = false, $rout = false) {
        return $this->PHPShopModules->setHookHandler($class_name, $function_name, array(&$this), $data, $rout);
    }

    /**
     * Запись в память
     * @param string $param имя параметра [catalog.param]
     * @param mixed $value значение
     */
    function memory_set($param, $value) {
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]] = $value;
            $_SESSION['Memory'][__CLASS__]['time'] = time();
        }
    }

    /**
     * Выборка из памяти
     * @param string $param имя параметра [catalog.param]
     * @param bool $check сравнить с нулем
     * @return
     */
    function memory_get($param, $check = false) {
        if (!empty($this->memory)) {
            $param = explode(".", $param);
            if (isset($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]])) {
                if (!empty($check)) {
                    if (!empty($_SESSION['Memory'][__CLASS__][$param[0]][$param[1]]))
                        return true;
                } else
                    return $_SESSION['Memory'][__CLASS__][$param[0]][$param[1]];
            }
            elseif (!empty($check))
                return true;
        } else
            return true;
    }

    /**
     * Проверка прав каталога режима Multibase
     * @return string
     */
    function queryMultibase() {

        // Мультибаза
        if (defined("HostID") or defined("HostMain")) {

            // Не выводить скрытые каталоги
            $where['skin_enabled '] = "!='1'";

            if (defined("HostID"))
                $where['servers'] = " REGEXP 'i" . HostID . "i'";
            elseif (defined("HostMain"))
                $where['skin_enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
            $PHPShopOrm->debug = $this->debug;
            $this->categories = array_column($PHPShopOrm->getList(['id'], $where, false, ['limit' => 1000], __CLASS__, __FUNCTION__), 'id');

            if (count($this->categories) > 0) {
                $dop_cats = '';
                foreach ($this->categories as $category) {
                    $dop_cats .= ' OR dop_cat LIKE \'%#' . $category . '#%\' ';
                }
                $categories_str = implode("','", $this->categories);

                return " (category IN ('$categories_str') " . $dop_cats . " ) and ";
            }
        }
    }

    /**
     * Изображения товара
     * @param array $product_row
     * @return string
     */
    function getImages($product_row) {

        $xml = null;
        if (isset($_GET['getall'])) {
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
            $data = $PHPShopOrm->select(array('*'), array('parent' => '=' . $product_row['id']), false, array('limit' => 10000));

            if (is_array($data))
                foreach ($data as $row) {

                    // Исходное изображение
                    if (!empty($this->image_source))
                        $row['name'] = str_replace(".", "_big.", $row['name']);

                    if (strpos($row['name'], 'http:') === false && strpos($row['name'], 'https:') === false)
                        $xml .= '<picture>' . $this->ssl . $_SERVER['SERVER_NAME'] . htmlspecialchars($row['name']) . '</picture>';
                    else
                        $xml .= '<picture>' . htmlspecialchars($row['name']) . '</picture>';
                }
        }
        if (empty($xml))
            $xml = '<picture>' . $product_row['picture'] . '</picture>';

        return $xml;
    }

    /**
     * Данные по каталогам
     * @return array массив каталогов
     */
    function category() {
        $Catalog = array();
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);

        // Не выводить скрытые каталоги
        if (isset($_GET['getall']) or isset($_GET['retailcrm']))
            $where = null;
        else
            $where['skin_enabled'] = "!='1'";

        // Мультибаза
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['skin_enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        $data = $PHPShopOrm->select(array('id,name,parent_to'), $where, false, array('limit' => 10000));
        if (is_array($data))
            foreach ($data as $row) {
                if ($row['id'] != $row['parent_to']) {
                    $Catalog[$row['id']]['id'] = $row['id'];
                    $Catalog[$row['id']]['name'] = $row['name'];
                    $Catalog[$row['id']]['parent_to'] = $row['parent_to'];
                }
            }

        return $Catalog;
    }

    /**
     * Данные по товарам. Оптимизировано.
     * @return array массив товаров
     */
    function product() {
        $Products = array();

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

        if (isset($_GET['getall']))
            $where = null;
        else {
            if (isset($_GET['marketplace']) && $_GET['marketplace'] === 'cdek' && isset($GLOBALS['SysValue']['base']['marketplaces']['marketplaces_system'])) {
                $where = "cdek='1' and";
            } elseif (isset($_GET['marketplace']) && $_GET['marketplace'] === 'aliexpress' && isset($GLOBALS['SysValue']['base']['marketplaces']['marketplaces_system'])) {
                $where = "aliexpress='1' and";
            } elseif (isset($_GET['marketplace']) && $_GET['marketplace'] === 'sbermarket' && isset($GLOBALS['SysValue']['base']['marketplaces']['marketplaces_system'])) {
                $where = "sbermarket='1' and";
            } else {
                $where = "yml='1' and";
            }

            if (isset($_GET['available'])) {
                $where .= " p_enabled='1' and";
            }
        }

        // Мультибаза
        $queryMultibase = $this->queryMultibase();
        if (!empty($queryMultibase))
            $where .= ' ' . $queryMultibase;

        $wherePrice = 'and price>0';
        if ($_GET['search']) {
            $wherePrice = '';
        }

        $result = $PHPShopOrm->query("select * from " . $GLOBALS['SysValue']['base']['products'] . " where $where enabled='1' and parent_enabled='0' $wherePrice");
        while ($row = mysqli_fetch_array($result)) {

            // Пропуск неопределенных товаров
            if (in_array($row['category'], array(1000001, 1000004, 0)))
                continue;

            $id = $row['id'];
            $name = trim(strip_tags($row['name']));

            // Основная категория
            $category = $row['category'];
            // Товар с доп. каталога, основного каталога в выводе нет.
            if (count($this->categories) > 0) {
                if (in_array($category, $this->categories) === false) {
                    foreach (explode('#', $row['dop_cat']) as $dopCat) {
                        if (!empty($dopCat) && in_array($dopCat, $this->categories)) {
                            $category = $dopCat;
                            break;
                        }
                    }
                }
            }

            $uid = $row['uid'];

            $price = $row[$this->price];
            $oldprice = $row['price_n'];

            // Промоакции
            $promotions = $this->PHPShopPromotions->getPrice($row);
            if (is_array($promotions)) {
                $price = $promotions['price'];
                $oldprice = $promotions['price_n'];
            }

            if (empty($row['description']))
                $row['description'] = $row['content'];

            if ($row['p_enabled'] == 1)
                $p_enabled = "true";
            else
                $p_enabled = "false";

            $description = '<![CDATA[' . trim(strip_tags($row['description'], '<p><h3><ul><li><br>')) . ']]>';
            $content = '<![CDATA[' . $row['content'] . ']]>';
            $baseinputvaluta = $row['baseinputvaluta'];

            //Если валюта отличается от базовой
            if ($baseinputvaluta !== $this->defvaluta) {
                $vkurs = $this->PHPShopValuta[$baseinputvaluta]['kurs'];

                // Если курс нулевой или валюта удалена
                if (empty($vkurs))
                    $vkurs = 1;

                // Приводим цену в базовую валюту
                $price = $price / $vkurs;
                $oldprice = $oldprice / $vkurs;
            }


            $price = ($price + (($price * $this->percent) / 100));
            $price = round($price, intval($this->format));
            $oldprice = round($oldprice, intval($this->format));

            $array = array(
                "id" => $id,
                "category" => $category,
                "name" => str_replace(array('&#43;', '&#43'), '+', $name),
                "picture" => htmlspecialchars($row['pic_big']),
                "price" => $price,
                "price2" => round($row['price2'], (int) $this->format),
                "price3" => round($row['price3'], (int) $this->format),
                "price4" => round($row['price4'], (int) $this->format),
                "price5" => round($row['price5'], (int) $this->format),
                "oldprice" => $oldprice,
                "weight" => $row['weight'],
                "length" => $row['length'],
                "width" => $row['width'],
                "height" => $row['height'],
                "p_enabled" => $p_enabled,
                "yml_bid_array" => unserialize($row['yml_bid_array']),
                "uid" => $uid,
                "vkurs" => $vkurs,
                "description" => $description,
                "raw_description" => $row['description'],
                "content" => $content,
                "raw_content" => $row['content'],
                "prod_seo_name" => $row['prod_seo_name'],
                "manufacturer_warranty" => $row['manufacturer_warranty'],
                "sales_notes" => $row['sales_notes'],
                "country_of_origin" => $row['country_of_origin'],
                "adult" => $row['adult'],
                "delivery" => $row['delivery'],
                "pickup" => $row['pickup'],
                "store" => $row['store'],
                "yandex_min_quantity" => $row['yandex_min_quantity'],
                "yandex_step_quantity" => $row['yandex_step_quantity'],
                "vendor_code" => $row['vendor_code'],
                "vendor_name" => $row['vendor_name'],
                "manufacturer" => $row['manufacturer'],
                "condition" => $row['yandex_condition'],
                "condition_reason" => $row['yandex_condition_reason'],
                "items" => $row['items'],
                "gift" => $row['gift'],
                "gift_check" => $row['gift_check'],
                "gift_items" => $row['gift_items'],
                "barcode" => $row['barcode'],
                "model" => $row['model'],
                "market_sku" => $row['market_sku'],
                "cpa" => $row['cpa'],
                "price_yandex_dbs" => round($row['price_yandex_dbs'], (int) $this->format),
                "price_sbermarket" => round($row['price_sbermarket'], (int) $this->format),
                "price_cdek" => round($row['price_cdek'], (int) $this->format),
                "price_aliexpress" => round($row['price_aliexpress'], (int) $this->format),
            );

            // Параметр сортировки
            if (!empty($this->vendor))
                $array['vendor_array'] = unserialize($row['vendor_array']);

            // Цвет-размер
            if ($_GET['search']) {
                $row['parent'] = null;
                $array['parent'] = null;
            }
            if (!empty($row['parent'])) {
                $parent = @explode(",", $row['parent']);

                $Parents = $this->parent($parent, $array);
                if (is_array($Parents)) {
                    $array['parent'] = 1;
                    $Products = array_merge($Products, $Parents);
                }
            }

            $Products[] = $array;
        }
        return $Products;
    }

    /**
     * Данные по товарам подтипам.
     * @return array массив товаров
     */
    function parent($parent, $parent_array) {

    $PHPShopOrm = new  PHPShopOrm($GLOBALS['SysValue']['base']['products']);

    // Подтипы из 1С
    if ($this->option)
        $result = $PHPShopOrm->query("select * from " . $GLOBALS['SysValue']['base']['products'] . " where uid IN (\"" . @implode('","', $parent) . "\") and enabled='1' and parent_enabled='1' and price>0");
    else
        $result = $PHPShopOrm->query("select * from " . $GLOBALS['SysValue']['base']['products'] . " where id IN (\"" . @implode('","', $parent) . "\") and enabled='1' and parent_enabled='1' and price>0");

    while ($row = mysqli_fetch_array($result)) {
        $id = $row['id'];
        $name = trim(strip_tags($row['name']));
        $uid = $row['uid'];
        $price = $row[$this->price];
        $oldprice = $row['price_n'];

        // Промоакции
        $promotions = $this->PHPShopPromotions->getPrice($row);
        if (is_array($promotions)) {
            $price = $promotions['price'];
            $oldprice = $promotions['price_n'];
        }

        $baseinputvaluta = $row['baseinputvaluta'];

        if ($baseinputvaluta) {

            //Если валюта отличается от базовой
            if ($baseinputvaluta !== $this->defvaluta) {

                // Приводим цену в базовую валюту
                $price = $price / $parent_array['vkurs'];
                $oldprice = $oldprice / $parent_array['vkurs'];
            }
        }

        $price = ($price + (($price * $this->percent) / 100));
        $price = round($price, intval($this->format));
        $oldprice = round($oldprice, intval($this->format));

        // Изображение
        if (empty($row['pic_big']))
            $row['pic_big'] = $parent_array['picture'];

        $array = array(
            "id" => $id,
            "group_id" => $parent_array['id'],
            "parent_name" => $parent_array['name'],
            "size" => $row['parent'],
            "color" => $row['parent2'],
            "category" => $parent_array['category'],
            "name" => str_replace(array('&#43;', '&#43'), '+', $name),
            "picture" => htmlspecialchars($row['pic_big']),
            "price" => $price,
            "oldprice" => $oldprice,
            "weight" => $parent_array['weight'],
            "length" => $parent_array['length'],
            "width" => $parent_array['width'],
            "height" => $parent_array['height'],
            "p_enabled" => $parent_array['p_enabled'],
            "yml_bid_array" => $parent_array['yml_bid_array'],
            "uid" => $uid,
            "description" => $parent_array['description'],
            "raw_description" => $parent_array['raw_description'],
            "raw_content" => $parent_array['raw_content'],
            "prod_seo_name" => $parent_array['prod_seo_name'],
            "fee" => $parent_array['fee'],
            "cpa" => $parent_array['cpa'],
            "manufacturer_warranty" => $parent_array['manufacturer_warranty'],
            "sales_notes" => $parent_array['sales_notes'],
            "country_of_origin" => $parent_array['country_of_origin'],
            "adult" => $parent_array['adult'],
            "delivery" => $parent_array['delivery'],
            "pickup" => $parent_array['pickup'],
            "store" => $parent_array['store'],
            "manufacturer" => $parent_array['manufacturer'],
            "yandex_min_quantity" => $parent_array['yandex_min_quantity'],
            "yandex_step_quantity" => $parent_array['yandex_step_quantity'],
            "vendor_array" => $parent_array['vendor_array'],
            "items" => $row['items'],
            "gift" => $row['gift'],
            "gift_check" => $row['gift_check'],
            "gift_items" => $row['gift_items'],
            "barcode" => $row['barcode'],
            "model" => $row['model'],
            "market_sku" => $row['market_sku'],
            "price_yandex_dbs" => round($row['price_yandex_dbs'], (int) $this->format),
            "price_sbermarket" => round($row['price_sbermarket'], (int) $this->format),
            "price_cdek" => round($row['price_cdek'], (int) $this->format),
            "price_aliexpress" => round($row['price_aliexpress'], (int) $this->format),
        );

        $Products[$id] = $array;
    }
    return $Products;
}

/**
 * Заголовок
 */
function setHeader() {
    $this->xml .= '<?xml version="1.0" encoding="' . $this->encoding . '"?>
<!DOCTYPE yml_catalog SYSTEM "shops.dtd">
<yml_catalog date="' . date('Y-m-d H:i') . '">
<shop>
<name>' . $this->PHPShopSystem->getName() . '</name>
<company>' . $this->PHPShopSystem->getValue('company') . '</company>
<url>' . $this->ssl . $_SERVER['SERVER_NAME'] . '</url>
<platform>PHPShop</platform>
<version>' . $GLOBALS['SysValue']['upload']['version'] . '</version>';
}

/**
 * Валюты
 */
function setCurrencies() {
    $this->xml .= '<currencies>';
    $this->xml .= '<currency id="' . $this->PHPShopValuta[$this->PHPShopSystem->getValue('dengi')]['iso'] . '" rate="1"/>';
    $this->xml .= '</currencies>';
}

/**
 * Категории
 */
function setCategories() {
    $this->xml .= '<categories>';
    $category = $this->category();
    foreach ($category as $val) {
        if (empty($val['parent_to']))
            $this->xml .= '<category id="' . $val['id'] . '">' . $this->cleanStr($val['name']) . '</category>
';
        else
            $this->xml .= '<category id="' . $val['id'] . '" parentId="' . $val['parent_to'] . '">' . $this->cleanStr($val['name']) . '</category>
';
    }

    $this->xml .= '</categories>';
}

/**
 * Доставка
 */
function setDelivery() {

    $xml = '<delivery-options/>';

    // Перехват модуля, занесение в память наличия модуля для оптимизации
    if ($this->memory_get(__CLASS__ . '.' . __FUNCTION__, true)) {
        $hook = $this->setHook(__CLASS__, __FUNCTION__, array('xml' => $xml));
        if ($hook) {
            $this->xml .= $hook;
        } else {
            $this->xml .= $xml;
            $this->memory_set(__CLASS__ . '.' . __FUNCTION__, 0);
        }
    } else
        $this->xml .= $xml;
}

/**
 * Очистка спецсимволов
 */
function cleanStr($string) {
    $string = html_entity_decode($string, ENT_QUOTES, 'windows-1251');
    $string = str_replace('&#43;', '+', $string);
    $string = str_replace(array('"', '&', '>', '<', "'"), array('&quot;', '&amp;', '&gt;', '&lt;', '&apos;'), $string);
    return $string;
}

/**
 * Товары
 */
function setProducts() {
    $vendor = null;
    $this->xml .= '<offers>';
    $product = $this->product($vendor = true);

    // Учет модуля SEOURL
    if (!empty($GLOBALS['SysValue']['base']['seourl']['seourl_system'])) {
        $seourl_enabled = true;
    }

    // Учет модуля SEOURLPRO
    if (!empty($GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system'])) {
        $seourlpro_enabled = true;
    }

    // Передавать параметр
    if (isset($_GET['from']))
        $from = '?from=yml';
    else
        $from = null;

    foreach ($product as $val) {

        $bid_str = null;
        $vendor = $param = null;
        $id = $val['id'];

        // Если есть bid
        if (!empty($val['yml_bid_array']['bid']))
            $bid_str = '  bid="' . $val['yml_bid_array']['bid'] . '" ';


        // Стандартный урл
        $url = '/shop/UID_' . $val['id'];

        // SEOURL
        if (!empty($seourl_enabled))
            $url .= '_' . PHPShopString::toLatin($val['name']);

        // SEOURLPRO
        if (!empty($seourlpro_enabled)) {
            if (empty($val['prod_seo_name']))
                $url = '/id/' . str_replace("_", "-", PHPShopString::toLatin($val['name'])) . '-' . $val['id'];
            else
                $url = '/id/' . $val['prod_seo_name'] . '-' . $val['id'];
        }

        // Подтип
        if (!empty($val['group_id'])) {
            $val['id'] = $id;
            $group_id = ' group_id="' . $val['group_id'] . '"';
            $group_postfix = '?option=' . $id;

            if (!empty($seourlpro_enabled)) {

                if (!empty($val['prod_seo_name']))
                    $url = '/id/' . $val['prod_seo_name'] . '-' . $val['group_id'];
                else
                    $url = '/id/' . str_replace("_", "-", PHPShopString::toLatin($val['parent_name'])) . '-' . $val['group_id'];
            } else
                $url = '/shop/UID_' . $val['group_id'];
        }
        // Родитель
        elseif (!empty($val['parent']))
            $group_postfix = '?option=' . $id;
        else
            $group_id = $group_postfix = null;

        // Ведущий товар в подтипах можно положить в корзину
        if ($this->parent_price_enabled == 0 and ! empty($val['parent']))
            continue;

        // Изображение
        if (!empty($val['picture'])) {
            if (strpos($val['picture'], 'http:') === false && strpos($val['picture'], 'https:') === false) {

                if (!empty($this->image_source))
                    $val['picture'] = str_replace(".", "_big.", $val['picture']);

                $val['picture'] = $this->ssl . $_SERVER['SERVER_NAME'] . $val['picture'];
            }
        }

        $picture = $this->getImages($val);

        if (isset($_GET['getall'])) {
            $val['description'] = $val['content'];
        }

        $name = '<name>' . $this->cleanStr($val['name']) . '</name>';
        $type = '';
        if (!empty($val['model']) && !empty($val['vendor_name']) && !isset($_GET['cdek'])) {
            $name = '<typePrefix>' . $this->cleanStr($val['name']) . '</typePrefix>';
            $type = ' type="vendor.model"';
        }

        $retailQuantity = '';
        if (isset($_GET['retailcrm'])) {
            $retailQuantity = sprintf('quantity="%s"', $val['items']);
        }

        $xml = '
<offer id="' . $val['id'] . '" available="' . $val['p_enabled'] . '" ' . $bid_str . $group_id . $type . $retailQuantity . '>
 <url>' . $this->ssl . $_SERVER['SERVER_NAME'] . $GLOBALS['SysValue']['dir']['dir'] . $url . '.html' . $group_postfix . '</url>
      <price>' . $val['price'] . '</price>';

        // Старая цена
        if ($val['price_n'] > $val['price'])
            $xml .= '<oldprice>' . $val['price_n'] . '</oldprice>';

        // weight
        if (!empty($val['weight']))
            $xml .= '<weight>' . round($val['weight']/1000,3). '</weight>';

        // Габариты
        if (!empty($val['length']) && !empty($val['width']) && !empty($val['height']))
            $xml .= '<dimensions>' . sprintf('%s/%s/%s', number_format($val['length'], 2, '.', ''), number_format($val['width'], 2, '.', ''), number_format($val['height'], 2, '.', '')
                    ) . '</dimensions>';

        $xml .= '<currencyId>' . $this->defvalutaiso . '</currencyId>
      <categoryId>' . $val['category'] . '</categoryId>
      ' . $picture . '
      ' . $name . '
      <description>' . $val['description'] . '</description>
</offer>';

        $hook = $this->setHook(__CLASS__, __FUNCTION__, array('xml' => $xml, 'val' => $val));
        if ($hook) {
            $this->xml .= $hook;
        } else {
            $this->xml .= $xml;
        }
    }
    if (!empty($this->xml))
        $this->xml .= '
        </offers>
        ';
}

/**
 * Подвал
 */
function serFooter() {

    // Перехват модуля
    $hook = $this->setHook(__CLASS__, __FUNCTION__);
    if ($hook) {
        $this->xml .= $hook;
    }


    $this->xml .= '</shop></yml_catalog>';
}

/**
 * Компиляция документа, вывод результата
 */
function compile() {

    if (isset($_GET['utf'])) {
        $this->encoding = 'utf-8';
        $this->charset = 'utf-8';
    } else {
        $this->charset = 'cp1251';
        $this->encoding = 'windows-1251';
    }

    $this->setHeader();
    $this->setCurrencies();
    $this->setCategories();
    $this->setDelivery();
    $this->setProducts();
    $this->serFooter();

    if (isset($_GET['utf'])) {
        $this->xml = PHPShopString::win_utf8($this->xml);
        $this->charset = 'utf-8';
    } else
        $this->charset = 'cp1251';
    echo $this->xml;
}

}

$PHPShopYml = new PHPShopYml();
header("HTTP/1.1 200");
header("Content-Type: application/xml; charset=" . $PHPShopYml->charset);
$PHPShopYml->compile();
?>