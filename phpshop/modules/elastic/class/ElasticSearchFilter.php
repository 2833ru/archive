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
                    'should' => [
                        [
                            'bool' => [
                                'must' => [
                                    'multi_match' => [
                                        'query'  => strtolower($query),
                                        'fields' => $fields
                                    ]
                                ],
                                'filter' => self::$filterActive,
                                'boost'  => 3.0
                            ]
                        ],
                        [
                            'bool' => [
                                'must' => [
                                    'multi_match' => [
                                        'query'     => strtolower($query),
                                        'fields'    => $fields,
                                        'fuzziness' => 1
                                    ]
                                ],
                                'filter' => self::$filterActive,
                                'boost'  => 2.0
                            ]
                        ],
                        [
                            'bool' => [
                                'must' => [
                                    'multi_match' => [
                                        'query'     => strtolower($query),
                                        'fields'    => $fields,
                                        'fuzziness' => 2
                                    ]
                                ],
                                'filter' => self::$filterActive,
                                'boost'  => 1.0
                            ]
                        ],
                    ]
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
                        'field' => 'main_category'
                    ]
                ]
            ]
        ];

        if(isset($categories)) {
            $filter['query']['bool']['should'][0]['bool']['filter']['bool']['must'][1]['terms']['categories'] = $categories;
            $filter['query']['bool']['should'][1]['bool']['filter']['bool']['must'][1]['terms']['categories'] = $categories;
            $filter['query']['bool']['should'][2]['bool']['filter']['bool']['must'][1]['terms']['categories'] = $categories;
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