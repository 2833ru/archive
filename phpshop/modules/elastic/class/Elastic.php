<?php

include_once dirname(__DIR__) . '/class/include.php';

class Elastic
{
    /** @var ElasticClient */
    public $client;

    static $options;

    public function __construct()
    {
        $this->client = new ElasticClient();
    }

    public function importProducts($from, $size)
    {
        $products = $this->getProducts($from, $size);

        $this->client->importProducts($products);

        return count($products);
    }

    public function importCategories($from, $size)
    {
        $categories = $this->getCategories($from, $size);

        $this->client->importCategories($categories);

        return count($categories);
    }

    public function getDocumentsCount()
    {
        $products = (int) (new PHPShopOrm('phpshop_products'))
            ->select(['COUNT(id) as count'], ['parent_enabled' => '="0"'])['count'];

        $categories = (int) (new PHPShopOrm('phpshop_categories'))
            ->select(['COUNT(id) as count'])['count'];

        return $products + $categories;
    }

    public function getFullUrl($url) {
        if(!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }

        if (strpos($url, 'http:') === false && strpos($url, 'https:') === false) {
            $url = $protocol. $_SERVER['SERVER_NAME'] . $url;
        }

        return $url;
    }

    public function checkRestAccess($token = null)
    {
        if(empty($token) || \Ramsey\Uuid\Uuid::isValid($token) === false) {
            throw new \Exception('Access denied! Token is empty or invalid.');
        }

        if(\Ramsey\Uuid\Uuid::fromString($token)->equals(\Ramsey\Uuid\Uuid::fromString(self::getOption('api'))) === false) {
            throw new \Exception('Access denied!');
        }
    }

    public function getCategories($from, $size)
    {
        $orm = new PHPShopOrm('phpshop_categories');
        $categories = $orm->select(['*'], false, ['order'=>'id ASC'], ['limit' => $from . ', ' . $size]);

        $data = [];
        foreach ($categories as $category) {
            $servers = explode('i', $category['servers']);
            if(!is_array($servers)) {
                $servers = [];
            }
            $servers = array_map(function ($server) {
                return (int) str_replace('i', '', $server);
            }, $servers);

            $dopCat = explode('#', $category['dop_cat']);
            if(!is_array($dopCat)) {
                $dopCat = [];
            }
            $dopCat = array_map(function ($dopcat) {
                return (int) str_replace('#', '', $dopcat);
            }, $dopCat);

            if(empty($category['elastic_category_id']) || \Ramsey\Uuid\Uuid::isValid($category['elastic_category_id']) === false) {
                $category['elastic_category_id'] = \Ramsey\Uuid\Uuid::uuid4()->toString();
                $orm->update(['elastic_category_id_new' => $category['elastic_category_id']], ['id' => sprintf('="%s"', $category['id'])]);
            }

            $data[] = [
                'id'               => $category['elastic_category_id'],
                'title'            => $category['name'],
                'sort'             => (int) $category['num'],
                'parent_id'        => (int) $category['parent_to'],
                'products_in_row'  => (int) $category['num_row'],
                'products_in_page' => (int) $category['num_cow'],
                'description'      => $category['content'],
                'vid'              => (int) $category['vid'],
                'servers'          => array_values(array_diff(array_unique($servers), [0])),
                'active'           => (int) $category['skin_enabled'] === 0,
                'order_by'         => (int) $category['order_by'],
                'order_to'         => (int) $category['order_to'],
                'icon'             => !empty($category['icon']) ? $this->getFullUrl($category['icon']) : null,
                'icon_description' => $category['icon_description'],
                'dop_cat'          => array_values(array_diff(array_unique($dopCat), [0])),
                'menu'             => (int) $category['menu']
            ];
        }

        return $data;
    }

    public function getProducts($from, $size)
    {
        $hook = $this->setHook(__CLASS__, __FUNCTION__, null, 'START');
        if (!empty($hook)) {
            return $hook;
        }

        $orm = new PHPShopOrm('phpshop_products');
        $products = $orm->select(['*'], ['parent_enabled' => '="0"'], ['order'=>'id ASC'], ['limit' => $from . ', ' . $size]);

        $data = [];
        foreach ($products as $product) {
            $description = trim(strip_tags($product['description']));
            $content = trim(strip_tags($product['content']));
            $categories = explode('#', $product['dop_cat']);
            if(!is_array($categories)) {
                $categories = [];
            }
            $categories[] = $product['category'];
            $categories = array_diff($categories, ['']);

            $attributes = unserialize($product['vendor_array']);
            if(!is_array($attributes)) {
                $attributes = [];
            }
            $atts = [];
            foreach ($attributes as $attributeId => $values) {
                $atts[] = [
                    'id'     => (int) $attributeId,
                    'values' => array_values(array_diff(array_diff(array_unique(array_map(function ($value) {
                        return (int) $value;
                    }, $values)), ['']), [0]))
                ];
            }

            if(empty($product['elastic_id']) || \Ramsey\Uuid\Uuid::isValid($product['elastic_id']) === false) {
                $product['elastic_id'] = \Ramsey\Uuid\Uuid::uuid4()->toString();
                $orm->update(['elastic_id_new' => $product['elastic_id']], ['id' => sprintf('="%s"', $product['id'])]);
            }

            if(!empty($product['name'])) {
                $import = [
                    'id'                => $product['elastic_id'],
                    'title'             => $product['name'],
                    'description'       => $content,
                    'short_description' => $description,
                    'price'             => $product['price'],
                    'price2'            => $product['price2'],
                    'price3'            => $product['price3'],
                    'price4'            => $product['price4'],
                    'price5'            => $product['price5'],
                    'price_n'           => $product['price_n'],
                    'price_search'      => $product['price_search'],
                    'article'           => $product['uid'],
                    'categories'        => array_values($categories),
                    'main_category'     => $product['category'],
                    'keywords'          => $product['keywords'],
                    'image'             => !empty($product['pic_big']) ? $this->getFullUrl($product['pic_big']) : null,
                    'preview_image'     => !empty($product['pic_small']) ? $this->getFullUrl($product['pic_small']) : null,
                    'barcode'           => isset($product['barcode']) ? $product['barcode'] : null,
                    'vendor_name'       => isset($product['vendor_name']) ? $product['vendor_name'] : null,
                    'vendor_code'       => isset($product['vendor_code']) ? $product['vendor_code'] : null,
                    'country_of_origin' => isset($product['country_of_origin']) ? $product['country_of_origin'] : null,
                    'length'            => $product['length'],
                    'width'             => $product['width'],
                    'height'            => $product['height'],
                    'weight'            => $product['weight'],
                    'active'            => (int) $product['enabled'],
                    'available'         => (int) $product['sklad'] !== 1,
                    'attributes'        => $atts
                ];

                $hook = $this->setHook(__CLASS__, __FUNCTION__, ['product' => $product, 'import' => $import], 'MIDDLE');
                if (!empty($hook)) {
                    $import = $hook;
                }

                $data[] = $import;
            }
        }

        $hook = $this->setHook(__CLASS__, __FUNCTION__, $data, 'END');
        if (!empty($hook)) {
            return $hook;
        }

        return $data;
    }

    public static function getWordForm($num, $form_for_1, $form_for_2, $form_for_5)
    {
        $num = abs((int) $num) % 100; // берем число по модулю и сбрасываем сотни (делим на 100, а остаток присваиваем переменной $num)
        $num_x = $num % 10; // сбрасываем десятки и записываем в новую переменную
        if ($num > 10 && $num < 20) // если число принадлежит отрезку [11;19]
            return $form_for_5;
        if ($num_x > 1 && $num_x < 5) // иначе если число оканчивается на 2,3,4
            return $form_for_2;
        if ($num_x == 1) // иначе если оканчивается на 1
            return $form_for_1;

        return $form_for_5;
    }

    public static function getOption($key)
    {
        if(!is_array(self::$options)) {
            self::$options = (new PHPShopOrm('phpshop_modules_elastic_system'))->select();
        }

        if(isset(self::$options[$key])) {
            return self::$options[$key];
        }

        return null;
    }

    public static function getOptions()
    {
        if(!is_array(self::$options)) {
            self::$options = (new PHPShopOrm('phpshop_modules_elastic_system'))->select();
        }

        return self::$options;
    }

    public static function getFilter($query, $fields, $from, $size, $categories = null)
    {
        $file = Elastic::getOption('search_filter');

        if(!empty($file) && is_file(dirname(__DIR__) . '/filters/' . $file)) {
            include_once dirname(__DIR__) . '/filters/' . $file;

            $class = str_replace('.php', '', $file);

            if(class_exists($class) && in_array(ElasticSearchFilterInterface::class, class_implements($class))) {
                return $class::getFilter($query, $fields, $from, $size, $categories);
            }
        }

        return ElasticSearchFilter::getFilter($query, $fields, $from, $size, $categories);
    }

    public static function getAjaxFilter($query, $limit, $categories)
    {
        $file = Elastic::getOption('ajax_search_filter');

        if(!empty($file) && is_file(dirname(__DIR__) . '/filters/' . $file)) {
            include_once dirname(__DIR__) . '/filters/' . $file;

            $class = str_replace('.php', '', $file);

            if(class_exists($class) && in_array(ElasticAjaxSearchFilterInterface::class, class_implements($class))) {
                return $class::getFilter($query, $limit, $categories);
            }
        }

        return ElasticAjaxSearchFilter::getFilter($query, $limit, $categories);
    }

    public static function getAjaxCategoriesFilter($query, $limit, $categories)
    {
        $file = Elastic::getOption('ajax_search_filter');

        if(!empty($file) && is_file(dirname(__DIR__) . '/filters/' . $file)) {
            include_once dirname(__DIR__) . '/filters/' . $file;

            $class = str_replace('.php', '', $file);

            if(class_exists($class) && in_array(ElasticAjaxSearchFilterInterface::class, class_implements($class))) {
                return $class::getCategoriesFilter($query, $limit, $categories);
            }
        }

        return ElasticAjaxSearchFilter::getCategoriesFilter($query, $limit, $categories);
    }

    public static function isFuzziness($misprints, $queryLength)
    {
        if ($misprints === 0) {
            return false;
        }

        return (int) Elastic::getOption('misprints_from_cnt') === 0 || ($queryLength >= (int) Elastic::getOption('misprints_from_cnt'));
    }

    public function setHook($class_name, $function_name, $data = false, $route = false) {
        global $PHPShopModules;

        if ($PHPShopModules instanceof PHPShopModules)
            return $PHPShopModules->setHookHandler($class_name, $function_name, [&$this], $data, $route);
        else
            return false;
    }

    public static function getCategoriesByIds($categoryIds)
    {
        $where = [
            'id' => sprintf(' IN (%s)', implode(',', $categoryIds)),
            'skin_enabled ' => "!='1'"
        ];
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";
        elseif (defined("HostMain"))
            $where['skin_enabled'] .= ' and (servers ="" or servers REGEXP "i1000i")';

        return array_column((new PHPShopOrm('phpshop_categories'))
            ->getList(
                ['id', 'name', 'icon'],
                $where,
                ['order' => sprintf(' FIELD(id, %s)', implode(',', $categoryIds))]
            ),
            null,
            'id'
        );
    }
}