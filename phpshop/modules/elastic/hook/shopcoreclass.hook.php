<?php

include_once dirname(__DIR__) . '/class/include.php';

function set_paginator_elastic_hook($obj, $data, $route)
{
    if($route === 'START') {

        ElasticSort::$sortTemplate = $obj->sort_template;
        $obj->sort_template = 'elastic_attribute_template';

        ElasticSort::$categories = [$obj->category];
        if(is_array($obj->category_array) && count($obj->category_array) > 0) {
            ElasticSort::$categories = $obj->category_array;
        }
    }

    if($route === 'END') {
        $obj->set('productPageNav', sprintf("<input type='hidden' name='elastic-categories' value='%s'>", json_encode(ElasticSort::$categories)), true);
    }
}

function elastic_attribute_template($values, $sortId, $title, $vendor, $help)
{
    $template = ElasticSort::$sortTemplate;

    try {
        $values = ElasticSort::filterSortValues($values);
    } catch (\Exception $exception) {}

    return $template($values, $sortId, $title, $vendor, $help);
}

$addHandler = [
    'setPaginator' => 'set_paginator_elastic_hook'
];