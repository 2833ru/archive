<?php

include_once dirname(__DIR__) . '/class/include.php';

class ElasticSearch
{
    /** @var ElasticClient */
    private $client;

    public function __construct()
    {
        $this->client = new ElasticClient();
    }

    public function search($query, $pole, $category = 0, $from = 0, $size = 15)
    {
        if((defined('HostID') or defined('HostMain')) && $category === 0) {
            $categories = array_keys($this->getServerCategories());
        }
        if($category > 0) {
            $categories = [$category];
        }

        $fields = [ 'title^5', 'title.eng^5', 'article^4', 'description^2', 'short_description^3', 'keywords' ];
        if($pole === 1) {
            $fields = [ 'title^3', 'title.eng^3', 'article^2', 'keywords' ];
        }

        $result = $this->client->searchByQuery(Elastic::getFilter($query, $fields, $from, $size, array_values($categories)));

        $ids = array_column($result['hits']['hits'], '_id');
        $highlights = array_column($result['hits']['hits'], 'highlight', '_id');
        if(count($ids) === 0) {
            return ['products' => [] , 'total' => 0];
        }

        $products = (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))
            ->getList(['*'], [
                'elastic_id' => sprintf(" IN ('%s')", implode("','", $ids)),
                'category' => sprintf(' IN (%s)', implode(',', array_keys($this->getServerCategories())))
            ],
                ['order' => sprintf(" FIELD(elastic_id, '%s')", implode("','", $ids))]
            );

        $products = array_map(function ($product) use($highlights) {
            $title = html_entity_decode(PHPShopString::utf8_win1251(array_shift($highlights[$product['elastic_id']]['title'])));
            $description = html_entity_decode(PHPShopString::utf8_win1251(array_shift($highlights[$product['elastic_id']]['short_description'])));
            $content = html_entity_decode(PHPShopString::utf8_win1251(array_shift($highlights[$product['elastic_id']]['description'])));

            if(!empty($title)) {
                $product['name'] = $title;
            }
            if(!empty($description)) {
                $product['description'] = $description;
            }
            if(!empty($content)) {
                $product['content'] = $content;
            }

            return $product;
        }, $products);

        return [
            'products'   => $products,
            'total'      => (int) $result['hits']['total']['value'],
            'categories' => isset($result['aggregations']['categories']['buckets']) ? $result['aggregations']['categories']['buckets']: []
        ];
    }

    public function searchAjax($query, $obj, $limit = 5)
    {
        // Убираем дублирование в другой раскладке
        $wordsArr = explode(' ', urldecode(PHPShopSecurity::true_search($query)));
        $query = implode(' ', array_slice($wordsArr, 0, ceil(count($wordsArr) / 2)));

        if(defined('HostID') or defined('HostMain')) {
            $categories = $this->getServerCategories();
        }

        $filter = Elastic::getAjaxFilter($query, $limit, array_keys($categories));

        if((int) Elastic::getOption('ajax_search_categories') === 1) {
            $filterCategories = Elastic::getAjaxCategoriesFilter($query, $limit, array_keys($categories));

            $result = $this->client->searchAllByQuery([
                ['index' => '_categories'],
                $filterCategories,
                ['index' => '_products'],
                $filter
            ]);

            $categoriesIds = array_column($result['responses'][0]['hits']['hits'], '_id');
            $productsIds = array_column($result['responses'][1]['hits']['hits'], '_id');
            $highlightsCategories = array_column($result['responses'][0]['hits']['hits'], 'highlight', '_id');
            $highlightsProducts = array_column($result['responses'][1]['hits']['hits'], 'highlight', '_id');
        } else {
            $result = $this->client->searchByQuery($filter);
            $categoriesIds = [];
            $productsIds = array_column($result['hits']['hits'], '_id');
            $highlightsCategories = [];
            $highlightsProducts = array_column($result['hits']['hits'], 'highlight', '_id');
        }

        if(count($categoriesIds) === 0 && count($productsIds) === 0) { exit;}
        $grid = '';

        if(count($categoriesIds) > 0) {
            $categories = (new PHPShopOrm($GLOBALS['SysValue']['base']['categories']))
                ->getList(
                    ['*'],
                    ['elastic_category_id' => sprintf(" IN ('%s')", implode("','", $categoriesIds))],
                    ['order' => sprintf(" FIELD(elastic_category_id, '%s')", implode("','", $categoriesIds))]
                );

            $categories = array_map(function ($category) use($highlightsCategories) {
                $title = PHPShopString::utf8_win1251(array_shift($highlightsCategories[$category['elastic_category_id']]['title.autocomplete']));
                if(!empty($title)) {
                    $category['name'] = $title;
                }

                return $category;
            }, $categories);

            $grid .= $obj->product_grid($categories, 1, 'search/search_ajax_catalog_forma.tpl', $obj->line);
        }

        if(count($productsIds) > 0) {
            $products = (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))
                ->getList(
                    ['*'],
                    [
                        'elastic_id' => sprintf(" IN ('%s')", implode("','", $productsIds)),
                        'category' => sprintf(' IN (%s)', implode(',', array_keys($this->getServerCategories())))
                    ],
                    ['order' => sprintf(" FIELD(elastic_id, '%s')", implode("','", $productsIds))]
                );

            $products = array_map(function ($product) use($highlightsProducts) {
                $title = PHPShopString::utf8_win1251(array_shift($highlightsProducts[$product['elastic_id']]['title.autocomplete']));
                if(!empty($title)) {
                    $product['name'] = $title;
                }

                return $product;
            }, $products);

            $grid .= $obj->product_grid($products, 1, 'search/search_ajax_product_forma.tpl', $obj->line);
        }

        if (!empty($GLOBALS['SysValue']['base']['seourlpro']['seourlpro_system'])) {
            $grid = $GLOBALS['PHPShopSeoPro']->AjaxCompile($grid);
        }

        return PHPShopParser::replacedir($obj->separator . $grid);
    }

    public function setPaginator($page, $total, $pageSize, $obj, $query, $category, $pole) {

        $i = 1;
        $navigat = null;
        $pageCount = (int) ceil($total / $pageSize);
        $page > 1 ? $previousPage = $page - 1 : $previousPage = 1;
        $page === $pageCount ? $nextPage = $page : $nextPage = $page + 1;

        if ($pageCount > 1) {
            while ($i <= $pageCount) {

                if ($i > 1) {
                    $p_start = $pageSize * ($i - 1);
                    $p_end = $p_start + $pageSize;
                } else {
                    $p_start = $i;
                    $p_end = $pageSize;
                }

                $obj->set("paginPageRangeStart", $p_start);
                $obj->set("paginPageRangeEnd", $p_end);
                $obj->set("paginPageNumber", $i);

                if ($i != $page) {
                    if ($i == 1) {
                        $obj->set("paginLink", "?words=" . $query . "&pole=" . $pole . "&p=" . $i . "&cat=" . $category);
                        $navigat .= parseTemplateReturn("paginator/paginator_one_link.tpl");
                    } else {
                        if ($i > ($page - 3) and $i < ($page + 3)) {
                            $obj->set("paginLink", "?words=" . $query . "&pole=" . $pole . "&p=" . $i . "&cat=" . $category);
                            $navigat .= parseTemplateReturn("paginator/paginator_one_link.tpl");
                        } else if ($i - ($page + 3) < 3 and (($page - 3) - $i) < 3) {
                            $navigat .= parseTemplateReturn("paginator/paginator_one_more.tpl");
                        }
                    }
                } else {
                    $obj->set("paginLink", "?words=" . $query . "&pole=" . $pole . "&p=" . $page . "&cat=" . $category);
                    $navigat .= parseTemplateReturn("paginator/paginator_one_selected.tpl");
                }

                $i++;
            }

            $obj->set("previousLink", "?words=" . $query . "&pole=" . $pole . "&p=" . $previousPage . "&cat=" . $category);
            $obj->set("nextLink", "?words=" . $query . "&pole=" . $pole . "&p=" . $nextPage . "&cat=" . $category);
            $obj->set("pageNow", $obj->getValue('lang.page_now'));
            $obj->set("navBack", $obj->lang('nav_back'));
            $obj->set("navNext", $obj->lang('nav_forw'));
            $obj->set("navigation", $navigat);

            // Назначаем переменную шаблонизатора
            $obj->set('searchPageNav', parseTemplateReturn("paginator/paginator_main.tpl"));
        }
    }

    public static function getCategoriesTemplate()
    {
        if((int) Elastic::getOption('find_in_categories') === 2) {
            return $GLOBALS['SysValue']['templates']['elastic']['search_categories_list'];
        }

        return $GLOBALS['SysValue']['templates']['elastic']['search_categories'];
    }

    public static function getCategoriesWrapperTemplate()
    {
        if((int) Elastic::getOption('find_in_categories') === 2) {
            return $GLOBALS['SysValue']['templates']['elastic']['search_categories_list_wrapper'];
        }

        return $GLOBALS['SysValue']['templates']['elastic']['search_categories_wrapper'];
    }

    /**
     * Учет категорий витрин
     * @return array
     */
    private function getServerCategories()
    {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $PHPShopOrm->debug = false;

        if (defined("HostID")) {
            return array_column($PHPShopOrm->getList(['id', 'elastic_category_id'], ['skin_enabled' => '!= "1"', 'servers' => " REGEXP 'i" . HostID . "i'"]),'elastic_category_id', 'id'
            );
        }

        return array_column($PHPShopOrm->getList(['id', 'elastic_category_id'], ['skin_enabled' => '!= "1"', 'servers' => ' ="" or servers REGEXP "i1000i"']), 'elastic_category_id', 'id'
        );
    }
}