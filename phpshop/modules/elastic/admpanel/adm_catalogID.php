<?php

include_once dirname(__DIR__) . '/class/include.php';

function elasticBeforeDeleteCategory($category)
{
    if(isset($category['secure_groups_new'])) { // Что бы хук не сработал на каталогах страниц.
        $Elastic = new Elastic();

        try {
            $categoryObj = new PHPShopCategory((int) $category['rowID']);
            $Elastic->client->deleteCategory($categoryObj->getParam('elastic_category_id'));
        } catch (\Exception $exception) {
            // var_dump($exception->getMessage()); exit; for debug
        }
    }
}

function elasticBeforeUpdateCategory($category)
{
    if(isset($category['secure_groups_new'])) { // Что бы хук не сработал на каталогах страниц.
        $oldCategory = new PHPShopCategory((int) $category['rowID']);
        $Elastic = new Elastic();

        $description = trim(strip_tags($category['content_new']));
        if(empty($description)) {
            $description = trim(strip_tags($oldCategory->getParam('content')));
        }
        if(empty($category['servers_new'])) {
            $category['servers_new'] = $oldCategory->getParam('servers');
        }
        $servers = explode('i', $category['servers_new']);
        if(!is_array($servers)) {
            $servers = [];
        }
        $servers = array_map(function ($server) {
            return (int) str_replace('i', '', $server);
        }, $servers);

        if(empty($category['dop_cat_new'])) {
            $category['dop_cat_new'] = $oldCategory->getParam('dop_cat');
        }
        $dopCat = explode('#', $category['dop_cat_new']);
        if(!is_array($dopCat)) {
            $dopCat = [];
        }
        $dopCat = array_map(function ($dopcat) {
            return (int) str_replace('#', '', $dopcat);
        }, $dopCat);

        $icon = $category['icon_new'];
        if(empty($icon)) {
            $icon = $oldCategory->getParam('icon');
        }

        $data = [
            'title'            => isset($category['name_new']) ? $category['name_new'] : $oldCategory->getName(),
            'sort'             => isset($category['num_new']) ? (int) $category['num_new'] : (int) $oldCategory->getParam('num'),
            'parent_id'        => isset($category['parent_to_new']) ? (int) $category['parent_to_new'] : (int) $oldCategory->getParam('parent_to'),
            'products_in_row'  => isset($category['num_row_new']) ? (int) $category['num_row_new'] : (int) $oldCategory->getParam('num_row'),
            'products_in_page' => isset($category['num_cow_new']) ? (int) $category['num_cow_new'] : (int) $oldCategory->getParam('num_cow'),
            'description'      => $description,
            'vid'              => isset($category['vid_new']) ? (int) $category['vid_new'] : (int) $oldCategory->getParam('vid'),
            'servers'          => array_values(array_diff(array_unique($servers), [0])),
            'active'           => isset($category['skin_enabled_new']) ? (int) $category['skin_enabled_new'] === 0 : (int) $oldCategory->getParam('skin_enabled') === 0,
            'order_by'         => isset($category['order_by_new']) ? (int) $category['order_by_new'] : (int) $oldCategory->getParam('order_by'),
            'order_to'         => isset($category['order_to_new']) ? (int) $category['order_to_new'] : (int) $oldCategory->getParam('order_to'),
            'icon'             => !empty($icon) ? $Elastic->getFullUrl($icon) : null,
            'icon_description' => isset($category['icon_description_new']) ? $category['icon_description_new'] : $oldCategory->getParam('icon_description'),
            'dop_cat'          => array_values(array_diff(array_unique($dopCat), [0])),
            'menu'             => isset($category['menu_new']) ? (int) $category['menu_new'] : (int) $oldCategory->getParam('menu')
        ];

        try {
            $Elastic->client->updateCategory($oldCategory->getParam('elastic_category_id'), $data);
        } catch (\Exception $exception) {
            // var_dump($exception->getMessage()); exit; for debug
        }
    }
}

$addHandler = [
    'actionStart'  => false,
    'actionDelete' => 'elasticBeforeDeleteCategory',
    'actionUpdate' => 'elasticBeforeUpdateCategory'
];
?>