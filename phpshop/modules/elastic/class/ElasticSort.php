<?php

include_once dirname(__DIR__) . '/class/include.php';

class ElasticSort
{
    public static $categories = [];
    public static $sortTemplate;
    public static $counts;

    public static function filterSortValues($values)
    {
        if(!is_array(self::$counts)) {
            self::$counts = self::calculateProducts(static::$categories);
        }

        foreach ($values as $key => $value) {
            if(!isset(self::$counts[(int) $value[1]])) {
                unset($values[$key]);
            } else {
                if((int) Elastic::getOption('filter_show_counts') === 1) {
                    $values[$key][3] = sprintf("<small class='elastic-count' data-base-count='%s'>%s</small>", self::$counts[(int) $value[1]], self::$counts[(int) $value[1]]);
                }
            }
        }

        return $values;
    }

    public static function calculateProducts($categories, $attributes = [])
    {
        $client = new ElasticClient();

        $filter = [
            'size' => 0,
            'query' => [
                'bool' => [
                    'filter' => [
                        [
                            'terms' => [
                                'categories' => $categories
                            ]
                        ],
                    ],
                ]
            ],
            'aggregations' => [
                'attributes' => [
                    'nested' => [
                        'path' => 'attributes',
                    ],
                    "aggs"  => [
                        "values" => [
                            "terms" => [
                                "field" => "attributes.values",
                                "size" => 1000000000
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if(count($attributes) > 0) {
            foreach ($attributes as $attributeId => $values) {
                $filter['query']['bool']['filter'][] = [
                    'nested' => [
                        'path' => 'attributes',
                        'query' => [
                            'bool' => [
                                'must' => [
                                    [
                                        'bool' => [
                                            'must' => [
                                                [
                                                    'match' => [
                                                        'attributes.id' => $attributeId
                                                    ]
                                                ],
                                                [
                                                    'terms' => [
                                                        'attributes.values' => $values
                                                    ]
                                                ],
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ];
            }
        }

        $result = $client->searchByQuery($filter);

        return array_column($result['aggregations']['attributes']['values']['buckets'], 'doc_count', 'key');
    }
}