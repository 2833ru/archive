<?php

class ElasticAjaxSearchFilter implements ElasticAjaxSearchFilterInterface
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

    public static function getFilter($query, $limit, $categories = null)
    {
        $filter = [
            '_source' => ['id'],
            'from'    => 0,
            'size'    => $limit,
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'multi_match' => [
                                'query'  => strtolower($query),
                                'fields' => [ 'title.autocomplete^4', 'title^3', 'article^2', 'keywords' ],
                                'operator'  =>  'and'
                            ]
                        ]
                    ],
                    'filter' => self::$filterActive
                ],
            ],
            'highlight' => [
                'pre_tags'  => ["<span class='theme-color'>"],
                'post_tags' => ['</span>'],
                'fields'    => [
                    'title.autocomplete' => [
                        'force_source' => true,
                        'highlight_query' => [
                            'bool' => [
                                'must' => [
                                    'multi_match' => [
                                        'query'  => strtolower($query),
                                        'fields' => [ 'title.autocomplete^4', 'title^3', 'article^2', 'keywords' ],
                                        'operator'  =>  'and'
                                    ]
                                ],
                                'filter' => self::$filterActive
                            ]
                        ]
                    ]
                ]
            ]
        ];

        if(!empty($categories)) {
            $filter['query']['bool']['filter']['bool']['must'][1]['terms']['categories'] = $categories;
            $filter['highlight']['fields']['title.autocomplete']['highlight_query']['bool']['filter']['bool']['must'][1]['terms']['categories'] = $categories;
        }

        if (Elastic::isFuzziness((int) Elastic::getOption('misprints_ajax'), strlen($query))) {
            $filter['query']['bool']['should'][0]['multi_match']['fuzziness'] = (int) Elastic::getOption('misprints_ajax');
        }

        if ((int) Elastic::getOption('search_uid_first') === 1) {
            $filter['query']['bool']['should'][1] = [
                'match' => [
                    'article' => [
                        'query' => strtolower($query),
                        'boost' => 10
                    ]
                ]
            ];
        }

        if ((int) Elastic::getOption('available_sort') === 1) {
            $filter['sort'] = [
                'available' => ['order' => 'desc'],
                '_score' => ['order' => 'desc'],
            ];
        }

        return $filter;
    }

    public static function getCategoriesFilter($query, $limit, $categories = null)
    {
        $filterCategories = [
            '_source' => ['id'],
            'from'    => 0,
            'size'    => $limit,
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'bool' => [
                                'must' => [
                                    'match' => [
                                        'title.autocomplete' => [
                                            'query'    => strtolower($query),
                                            'operator' => 'and'
                                        ]
                                    ]
                                ],
                                'filter' => self::$filterActive,
                                'boost'  => 2.0
                            ]
                        ],
                        [
                            'bool' => [
                                'must' => [
                                    'match' => [
                                        'title.autocomplete' => [
                                            'query'    => strtolower($query),
                                            'operator' => 'and',
                                            'fuzziness' => 1
                                        ]
                                    ]
                                ],
                                'filter' => self::$filterActive,
                                'boost'  => 1.0
                            ]
                        ]
                    ]
                ],
            ],
            'highlight' => [
                'pre_tags'  => ["<span class='theme-color'>"],
                'post_tags' => ['</span>'],
                'fields'    => [
                    'title.autocomplete' => [
                        'force_source' => true,
                        'highlight_query' => [
                            'bool' => [
                                'must' => [
                                    'match' => [
                                        'title.autocomplete' => [
                                            'query'    => strtolower($query),
                                            'operator' => 'and'
                                        ]
                                    ]
                                ],
                                'filter' => self::$filterActive
                            ]
                        ]
                    ]
                ]
            ]
        ];
        if(!empty($categories)) {
            $filter['query']['bool']['should'][0]['bool']['filter']['bool']['must'][1]['terms']['categories'] = $categories;
            $filter['query']['bool']['should'][1]['bool']['filter']['bool']['must'][1]['terms']['categories'] = $categories;
            $filter['highlight']['fields']['title.autocomplete']['highlight_query']['bool']['filter']['bool']['must'][1]['terms']['categories'] = $categories;
        }

        return $filterCategories;
    }
}