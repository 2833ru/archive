<?php

include_once dirname(__DIR__) . '/class/include.php';

function elasticBeforeCreateCategory($category)
{
    if(isset($category['secure_groups_new'])) {
        $Elastic = new Elastic();

        $servers = explode('i', $category['servers_new']);
        if(!is_array($servers)) {
            $servers = [];
        }
        $servers = array_map(function ($server) {
            return (int) str_replace('i', '', $server);
        }, $servers);

        $dopCat = explode('#', $category['dop_cat_new']);
        if(!is_array($dopCat)) {
            $dopCat = [];
        }
        $dopCat = array_map(function ($dopcat) {
            return (int) str_replace('#', '', $dopcat);
        }, $dopCat);

        $_POST['elastic_category_id_new'] = \Ramsey\Uuid\Uuid::uuid4()->toString();

        $data = [
            'id'               => $_POST['elastic_category_id_new'],
            'title'            => $category['name_new'],
            'sort'             => (int) $category['num_new'],
            'parent_id'        => (int) $category['parent_to_new'],
            'products_in_row'  => (int) $category['num_row_new'],
            'products_in_page' => (int) $category['num_cow_new'],
            'description'      => trim(strip_tags($category['content_new'])),
            'vid'              => (int) $category['vid_new'],
            'servers'          => array_values(array_diff(array_unique($servers), [0])),
            'active'           => (int) $category['skin_enabled_new'] === 0,
            'order_by'         => (int) $category['order_by_new'],
            'order_to'         => (int) $category['order_to_new'],
            'icon'             => !empty($category['icon_new']) ? $Elastic->getFullUrl($category['icon_new']) : null,
            'icon_description' => $category['icon_description_new'],
            'dop_cat'          => array_values(array_diff(array_unique($dopCat), [0])),
            'menu'             => (int) $category['menu_new']
        ];

        try {
            $Elastic->client->importCategories([$data]);
        } catch (\Exception $exception) {
            // var_dump($exception->getMessage()); exit; for debug
        }
    }
}

$addHandler = [
    'actionStart'  => false,
    'actionInsert' => 'elasticBeforeCreateCategory'
];
?>