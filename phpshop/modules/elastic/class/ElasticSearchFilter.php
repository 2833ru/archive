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
        if ((int) Elastic::getOption('search_uid_first') === 1) {
            array_unshift($filter['query']['bool']['should'],
                [
                    'match' => [
                        'article' => [
                            'query' => self::escape(strtolower($query)),
                            'boost' => 50
                        ]
                    ]
                ]
            );
        }

        $filter = [
            '_source' => ['id'],
            'from'    => $from,
            'size'    => $size,
            'query' => [
                'bool' => [
                    'should' => [
                        [
                            'multi_match' => [
                                'query'  => self::escape(strtolower($query)),
                                'fields' => $fields,
                                'boost' => 30
                            ]
                        ],
                        [
                            'query_string' => [
                                'query'  => self::escape(strtolower($query)),
                                'fields' => $fields,
                                'boost' => 20
                            ]
                        ],
                        [
                            'query_string' => [
                                'query'  => '*' . self::escape(strtolower($query)) . '*',
                                'fields' => $fields,
                                'boost' => 10
                            ]
                        ],
                    ],
                    'minimum_should_match' => 1,
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
            $filter['query']['bool']['filter']['bool']['must'][]['terms']['categories'] = $categories;
        }

        if (Elastic::isFuzziness((int) Elastic::getOption('misprints'), strlen($query))) {
            $filter['query']['bool']['should'][0]['multi_match']['fuzziness'] = (int) Elastic::getOption('misprints');
        }

        if ((int) Elastic::getOption('search_uid_first') === 1) {
             array_unshift($filter['query']['bool']['should'],
                 [
                     'match' => [
                         'article' => [
                             'query' => self::escape(strtolower($query)),
                             'boost' => 50
                         ]
                     ]
                 ]
             );
        }

        if ((int) Elastic::getOption('available_sort') === 1) {
            $filter['sort'] = [
                'available' => ['order' => 'desc'],
                '_score' => ['order' => 'desc'],
            ];
        }

        return $filter;
    }

    static function escape($string) {
        $regex = "/[\\+\\-\\=\\&\\|\\!\\(\\)\\{\\}\\[\\]\\^\\\"\\~\\*\\<\\>\\?\\:\\\\\\/]/";
        $string = preg_replace_callback ($regex,
            function ($matches) {
                return "\\" . $matches[0];
            }, $string);

        return $string;
    }
}
