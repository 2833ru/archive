<?php

include_once dirname(__DIR__) . '/class/include.php';

function footer_elastic_hook()
{
    $dis = '<link rel="stylesheet" href="/phpshop/modules/elastic/templates/style/elastic.css">';

    if((int) Elastic::getOption('filter_show_counts') === 1 || (int) Elastic::getOption('filter_update') === 1) {
        $dis .= sprintf('<script src="/phpshop/modules/elastic/templates/js/filter.js" data-show-counts="%s" data-filter-update="%s"></script>',
            Elastic::getOption('filter_show_counts'),
            Elastic::getOption('filter_update')
        );
    }

    echo $dis;
}

$addHandler = [
    'footer' => 'footer_elastic_hook'
];