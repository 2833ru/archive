<?php

/**
 * SEO ссылки для таблицы категорий
 */
function template_cat_table_seourl_hook($obj, $val) {

    if ($GLOBALS['PHPShopSeoPro']) {
        if (!empty($val['cat_seo_name']))
            $GLOBALS['PHPShopSeoPro']->setMemory($val['id'], $val['cat_seo_name'], 1, false);
        else
            $GLOBALS['PHPShopSeoPro']->setMemory($val['id'], $val['name']);
    }
}

/**
 * SEO ссылки для элемента навигации каталога
 */
function leftCatal_seourl_hook($obj, $row, $rout) {
    if ($rout == 'END') {

        if (!empty($row['cat_seo_name']))
            $GLOBALS['PHPShopSeoPro']->setMemory($row['id'], $row['cat_seo_name'], 1, false);
        else
            $GLOBALS['PHPShopSeoPro']->setMemory($row['id'], $row['name']);
    }
}

/**
 * SEO ссылки для элемента навигации подкаталога
 */
function subcatalog_seourl_hook($obj, $row) {

    if (!empty($row['cat_seo_name'])) {
        if (!$GLOBALS['PHPShopSeoPro']->setMemory($row['id'], $row['cat_seo_name'], 1, false))
            $_SESSION['PHPShopSeoProError'] = true;
    }
    else {
        if (!$GLOBALS['PHPShopSeoPro']->setMemory($row['id'], $row['name']))
            $_SESSION['PHPShopSeoProError'] = true;
    }
}

$addHandler = array
    (
    'template_cat_table' => 'template_cat_table_seourl_hook',
    'leftCatalTable' => 'leftCatal_seourl_hook',
    'leftCatal' => 'leftCatal_seourl_hook',
    'subcatalog' => 'subcatalog_seourl_hook'
);
?>