<?php

class ElasticSearchFilter implements ElasticSearchFilterInterface
{
    private static $filterActive = [
        'bool' => [
            'must' => [
                [
                    'term' => [
                        'active' => true
                    ]
                ]
            ]
        ]
    ];

    public static function getFilter($query, $fields, $from, $size, $categories = null)
    {
        $filter = [
            '_source' => ['id'],
            'from'    => $from,
            'size'    => $size,
            'query' => [
                'bool' => [
                    'must' => [
                        'multi_match' => [
                            'query'  => strtolower($query),
                            'fields' => $fields
                        ]
                    ],
                    'filter' => self::$filterActive
                ]
            ],
            'highlight' => [
                'pre_tags'  => ["<span class='theme-color'>"],
                'post_tags' => ['</span>'],
                'fields'    => [
                    'title' => [
                        'force_source' => true
                    ],
                    'description' => [
                        'force_source' => true
                    ],
                    'short_description' => [
                        'force_source' => true
                    ]
                ]
            ],
            'aggregations' => [
                'categories' => [
                    'terms' => [
                        'field' => (int) Elastic::getOption('use_additional_categories') === 1 ? 'categories' : 'main_category',
                        'size'  => (int) Elastic::getOption('max_categories') > 0 ? (int) Elastic::getOption('max_categories') : 10
                    ]
                ]
            ]
        ];

        if(!empty($categories)) {
            $filter['query']['bool']['filter']['bool']['must'][1]['terms']['categories'] = $categories;
        }

        if (Elastic::isFuzziness((int) Elastic::getOption('misprints'), strlen($query))) {
            $filter['query']['bool']['must']['multi_match']['fuzziness'] = (int) Elastic::getOption('misprints');
        }

        if ((int) Elastic::getOption('available_sort') === 1) {
            $filter['sort'] = [
                'available' => ['order' => 'desc'],
                '_score' => ['order' => 'desc'],
            ];
        }

        return $filter;
    }
}