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
    private $xmlPriceId;

    public function __construct($xmlPriceId) {

        $this->xmlPriceId = $xmlPriceId;
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
            'items' => ' > 0',
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

            $product['description'] .= '<br/>' . $product['content'];

            if(!empty($this->Avito->options['additional_description'])) {
                $product['description'] .= '<br/>' . $this->Avito->options['additional_description'];
            }
            if((int) $this->Avito->options['use_params'] === 1) {
                $product['description'] .= '<br/>' . $this->sort_table($product);
            }

            $product['description'] = '<![CDATA[' . PHPShopString::win_utf8(trim(strip_tags($product['description'], '<p><br><strong><em><ul><ol><li>'))) . ']]>';

            $PHPShopOrm = new PHPShopOrm('phpshop_foto');
            $images = $PHPShopOrm->getList(array('*'), array('parent' => '=' . $product['id']), array('order' => 'num'));
            if(count($images) === 0) {
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
                "subtype" => PHPShopString::win_utf8($this->categories[$product['category']]['subtype']),
                "name" => str_replace(array('&#43;', '&#43'), '+', $product['name']),
                "images" => $images,
                "price" => $this->getProductPrice($product),
                "description" => $product['description'],
                "prod_seo_name" => $product['prod_seo_name'],
                "condition" => PHPShopString::win_utf8($product['condition_avito']),
                "status" => $product['ad_status_avito'],
                "listing_fee" => $product['listing_fee_avito'],
                "ad_type" => PHPShopString::win_utf8($product['ad_type_avito'])
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
        $orm = new PHPShopOrm('phpshop_modules_avito_categories');
        $categories = array_column($orm->getList(['id'], ['xml_price_id' => sprintf('="%s"', $this->xmlPriceId)]), 'id');

        $where = [
            'skin_enabled' => "!='1'",
            'category_avito' => sprintf(' IN (%s)', implode(',', $categories))
        ];

        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['skin_enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $categories = $PHPShopOrm->getList(array('id', 'category_avito', 'type_avito', 'subtype_avito'), $where);

        foreach ($categories as $category) {
            $avitoCategory = $this->Avito->getCategoryById((int) $category['category_avito']);
            if(!empty($avitoCategory)) {
                $this->categories[$category['id']] = array(
                    'category' => $avitoCategory,
                    'type'     => $this->Avito->getAvitoType($category['type_avito']),
                    'subtype'  => $this->Avito->getAvitoSubType($category['subtype_avito'])
                );
            }
        }
    }

    private function sort_table($product) {

        $category = new PHPShopCategory((int) $product['category']);

        $sort = $category->unserializeParam('sort');
        $vendor_array = unserialize($product['vendor_array']);
        $dis = $sortCat = $sortValue = null;
        $arrayVendorValue = [];

        if (is_array($sort))
            foreach ($sort as $v) {
                $sortCat .= (int) $v . ',';
            }

        if (!empty($sortCat)) {

            // Массив имен характеристик
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
            $arrayVendor = array_column($PHPShopOrm->getList(['*'], ['id' => sprintf(' IN (%s 0)', $sortCat)], ['order' => 'num']), null, 'id');

            if (is_array($vendor_array))
                foreach ($vendor_array as $v) {
                    foreach ($v as $value)
                        if (is_numeric($value))
                            $sortValue.= (int) $value . ',';
                }

            if (!empty($sortValue)) {

                // Массив значений характеристик
                $PHPShopOrm = new PHPShopOrm();
                $result = $PHPShopOrm->query("select * from " . $GLOBALS['SysValue']['base']['sort'] . " where id IN ( $sortValue 0) order by num");
                while (@$row = mysqli_fetch_array($result)) {
                    $arrayVendorValue[$row['category']]['name'][$row['id']] = $row['name'];
                    $arrayVendorValue[$row['category']]['id'][] = $row['id'];
                }

                if (is_array($arrayVendor))
                    foreach ($arrayVendor as $idCategory => $value) {

                        if (!empty($arrayVendorValue[$idCategory]['name'])) {
                            if (!empty($value['name'])) {
                                $arr = array();
                                foreach ($arrayVendorValue[$idCategory]['id'] as $valueId) {
                                    $arr[] = $arrayVendorValue[$idCategory]['name'][(int)$valueId];
                                }

                                $sortValueName = implode(', ', $arr);

                                $dis.=PHPShopText::li($value['name'] . ': ' . $sortValueName, null, '');
                            }
                        }
                    }

                return PHPShopText::ul($dis, '');
            }
        }
    }
}