<?php

interface ElasticAjaxSearchFilterInterface
{
    public static function getFilter($query, $limit, $categories = null);
    public static function getCategoriesFilter($query, $limit, $categories = null);
}