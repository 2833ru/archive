<?php

include_once dirname(__DIR__) . '/class/ElasticSearch.php';

function words_elastic_hook($obj, $request, $route)
{
    if($route === 'START') {
        $Elastic = new ElasticSearch();
        $pageSize = (int) Elastic::getOption('search_page_size') > 0 ? (int) Elastic::getOption('search_page_size') : 15;
        if ((int) $_REQUEST['pole'] > 0)
            $pole = (int) $_REQUEST['pole'];
        else
            $pole = (int) $obj->PHPShopSystem->getSerilizeParam('admoption.search_pole');
        (int) $_REQUEST['p'] > 0 ? $page = (int) $_REQUEST['p'] :  $page = 1;
        (int) $_REQUEST['cat'] > 0 ? $category = (int) $_REQUEST['cat'] :  $category = 0;
        if(empty($pole))
            $pole = 1;
        $query = PHPShopSecurity::true_search(trim($_REQUEST['words']));
        $obj->set('productValutaName', $obj->currency());

        if(isset($_REQUEST['ajax'])) {
            header('Content-type: text/html; charset=' . $GLOBALS['PHPShopLang']->charset);
            exit($Elastic->searchAjax($_REQUEST['words'], $obj, (int) Elastic::getOption('ajax_search_products_cnt')));
        }

        // Категория поиска
        $obj->category_select();

        if(empty($_REQUEST['words'])) {
            $obj->parseTemplate($obj->getValue('templates.search_page_list'));
            return true;
        }

        try {
            $result = $Elastic->search($query, $pole, $category, $pageSize * ($page - 1), $pageSize);
        } catch (\Exception $exception) {
            return null; // выбрасываем из хука на стандартный поиск если пойман exception
        }
        $obj->set('searchString', $_REQUEST['words']);
        if ($pole == 1)
            $obj->set('searchSetC', 'checked');
        else
            $obj->set('searchSetD', 'checked');

        if($result['total'] === 0) {
            $obj->add(PHPShopText::h3(__('Ничего не найдено')), true);

            $obj->parseTemplate($obj->getValue('templates.search_page_list'));

            return true;
        }

        $Elastic->setPaginator($page, $result['total'], $pageSize, $obj, $query, $category, $pole);

        $categoryIds = array_column($result['categories'], 'key');

        if ((int) Elastic::getOption('use_additional_categories') === 1) {
            $additionalCategories = [];
            foreach ($result['products'] as $product) {
                if(!empty($product['dop_cat'])) {
                    $additionalCategories = array_merge(preg_split('/#/', $product['dop_cat'], -1, PREG_SPLIT_NO_EMPTY), $additionalCategories);
                }
            }
            $categoryIds = array_unique(array_merge($categoryIds, $additionalCategories));
        }

        $grid = '';

        // Блок "Найдено в категориях".
        if(is_array($categoryIds) && count($categoryIds) > 0) {
            $categories = Elastic::getCategoriesByIds($categoryIds);

            if((int) Elastic::getOption('search_show_informer_string') === 1) {
                $obj->set('elastic_categories_count', is_array($categories) && count($categories) > 0 ? count($categories) : 0);
                $obj->set('elastic_products_count', $result['total']);
                $grid = PHPShopParser::file($GLOBALS['SysValue']['templates']['elastic']['search_informer_string'], true, false, true);
            }

            if ((int) Elastic::getOption('find_in_categories') === 1 || (int) Elastic::getOption('find_in_categories') === 2) {
                $categoriesHtml = '';
                if(count($result['categories']) > 0) {
                    foreach ($result['categories'] as $cat) {
                        if(isset($categories[$cat['key']])) {
                            $obj->set('elastic_category_title', $categories[$cat['key']]['name']);
                            $obj->set('elastic_category_count', $cat['doc_count']);
                            $obj->set('elastic_category_icon', $categories[$cat['key']]['icon']);
                            $obj->set('elastic_category_url', '/search/' . "?words=" . $query . "&pole=" . $pole . "&p=" . $page . "&cat=" . $categories[$cat['key']]['id']);

                            $categoriesHtml .=  PHPShopParser::file(ElasticSearch::getCategoriesTemplate(), true, false, true);
                        }
                    }

                    $obj->set('elastic_search_categories', $categoriesHtml);
                    $grid .= PHPShopParser::file(ElasticSearch::getCategoriesWrapperTemplate(), true, false, true);
                }
            }
        }

        // Добавляем в дизайн ячейки с товарами
        (int) Elastic::getOption('search_page_row') > 0 ? $cell = (int) Elastic::getOption('search_page_row') : $cell = 15;
        $grid .= $obj->product_grid($result['products'], $cell, false, $obj->line);

        $obj->add($grid, true);

        // Запись в журнал
        $obj->write($obj->get('searchString'), $page, @$_REQUEST['cat'], @$_REQUEST['set']);

        // Подключаем шаблон
        $obj->parseTemplate($obj->getValue('templates.search_page_list'));

        return true;
    }
}

$addHandler = [
    'words' => 'words_elastic_hook'
];