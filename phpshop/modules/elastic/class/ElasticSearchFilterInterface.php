<?php

interface ElasticSearchFilterInterface
{
    public static function getFilter($query, $fields, $from, $size, $categories = null);
}