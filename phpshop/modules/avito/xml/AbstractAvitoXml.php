<?php

include_once dirname(__FILE__) . '/../class/Avito.php';

/**
 * Базовый класс для генерации XML для Авито.
 * Class AbstractAvitoXml
 */
abstract class AbstractAvitoXml
{
    /** @var string */
    protected $xml;

    /** @var Avito */
    protected $Avito;

    /** @var PHPShopSystem */
    private $PHPShopSystem;

    private $ssl = 'http://';
    private $categories = array();

    /**
     * AbstractAvitoXml constructor.
     */
    public function __construct() {

        $this->PHPShopSystem = new PHPShopSystem();

        // SSL
        if (isset($_GET['ssl']))
            $this->ssl = 'https://';

        $this->Avito = new Avito();

        // Пароль
        if (!empty($this->Avito->options['password']))
            if ($_GET['pas'] != $this->Avito->options['password'])
                exit('Login error!');
    }

    abstract function setAds();


    /**
     * Компиляция документа, вывод результата
     */
    public function compile() {

        $this->prepareData();

        $this->setAds();

        echo $this->xml;
    }

    public function getProducts($getAll = false)
    {
        // Исходное изображение
        $image_source = $this->PHPShopSystem->ifSerilizeParam('admoption.image_save_source');

        $result = array();

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

        $where = array(
            'enabled' => '="1"',
            'parent_enabled' => '="0"',
            'export_avito' => '="1"',
            'category' => ' IN (' . implode(',', array_keys($this->categories)) . ')'
        );

        if ($getAll)
            unset($where['export_avito']);

        $products = $PHPShopOrm->getList(array('*'), $where);

        foreach ($products as $product) {

            $product['name'] = '<![CDATA[' . PHPShopString::win_utf8(trim(strip_tags($product['name']))) . ']]>';
            if(!empty($product['name_avito'])) {
                $product['name'] = '<![CDATA[' . PHPShopString::win_utf8(trim(strip_tags($product['name_avito']))) . ']]>';
            }

            if (empty($product['description']))
                $product['description'] = $product['content'];

            if(!empty($this->Avito->options['additional_description'])) {
                $product['description'] .= '<br/>' . $this->Avito->options['additional_description'];
            }

            $product['description'] = '<![CDATA[' . PHPShopString::win_utf8(trim(strip_tags($product['description'], '<p><br><strong><em><ul><ol><li>'))) . ']]>';

            $PHPShopOrm = new PHPShopOrm('phpshop_foto');
            $images = $PHPShopOrm->getList(array('*'), array('parent' => '=' . $product['id']), array('order' => 'num'));
            if(!empty($product['pic_big'])) {
                $images[] = array('name' => $product['pic_big']);
            }

            // Изображения
            foreach ($images as $key => $image) {
                if (!strstr('http:', $image['name'])) {

                    if (!empty($image_source))
                        $images[$key]['name'] = str_replace(".", "_big.", $image['name']);

                    $images[$key]['name'] = $this->ssl . $_SERVER['SERVER_NAME'] . $image['name'];
                }
            }

            $result[$product['id']] = array(
                "id" => $product['id'],
                "category" => PHPShopString::win_utf8($this->categories[$product['category']]['category']),
                "type" => PHPShopString::win_utf8($this->categories[$product['category']]['type']),
                "name" => str_replace(array('&#43;', '&#43'), '+', $product['name']),
                "images" => $images,
                "price" => $this->getProductPrice($product),
                "description" => $product['description'],
                "prod_seo_name" => $product['prod_seo_name'],
                "condition" => PHPShopString::win_utf8($product['condition_avito']),
                "status" => $product['ad_status_avito'],
                "listing_fee" => $product['listing_fee_avito'],
            );
        }

        return $result;
    }

    public function getAddress()
    {
        return $this->PHPShopSystem->getSerilizeParam('bank.org_adres');
    }

    /**
     * @param array $product
     * @return float
     */
    private function getProductPrice($product)
    {
        $PHPShopPromotions = new PHPShopPromotions();
        $PHPShopValuta = new PHPShopValutaArray();
        $currencies = $PHPShopValuta->getArray();
        $defvaluta = $this->PHPShopSystem->getValue('dengi');
        $percent = $this->PHPShopSystem->getValue('percent');
        $format = $this->PHPShopSystem->getSerilizeParam('admoption.price_znak');

        // Промоакции
        $promotions = $PHPShopPromotions->getPrice($product['price']);
        if (is_array($promotions)) {
            $product['price'] = $promotions['price'];
        }

        //Если валюта отличается от базовой
        if ($product['baseinputvaluta'] !== $defvaluta) {
            $vkurs = $currencies[$product['baseinputvaluta']]['kurs'];

            // Если курс нулевой или валюта удалена
            if (empty($vkurs))
                $vkurs = 1;

            // Приводим цену в базовую валюту
            $product['price'] = $product['price'] / $vkurs;
        }

        return round($product['price'] + (($product['price'] * $percent) / 100), (int) $format);
    }

    // Разовая загрузка категорий, типов Авито.
    private function prepareData()
    {
        $this->loadCategories();
    }

    /**
     * Заполнение свойства categories массивом вида id категории => название категории в Авито.
     */
    private function loadCategories()
    {
        $where['skin_enabled '] = "!='1'";
        $where['category_avito'] = " > '0'";

        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['skin_enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $categories = $PHPShopOrm->getList(array('id', 'category_avito', 'type_avito'), $where);

        foreach ($categories as $category) {
            $avitoCategory = $this->Avito->getCategoryById((int) $category['category_avito']);
            if(!empty($avitoCategory)) {
                $this->categories[$category['id']] = array(
                    'category' => $avitoCategory,
                    'type' => $this->Avito->getAvitoType($category['type_avito'])
                );
            }
        }
    }
}