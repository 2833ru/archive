<?php

/**
 * Вывод иконок распродажи и спецпредложений в кратком описании товаров.
 */
function phpshopshopcore_product_grid_nt_hook($obj, $dataArray) {
    // Спецпредложения
    if ($dataArray['spec'])
        $obj->set('specIcon', ParseTemplateReturn('product/specIcon.tpl'));
    else
        $obj->set('specIcon', '');

    // Новинки
    if ($dataArray['newtip'])
        $obj->set('newtipIcon', ParseTemplateReturn('product/newtipIcon.tpl'));
    else
        $obj->set('newtipIcon', '');

    // выводим по 1 в колонку, если мобильная версия.
    if ($obj->cell != 1 AND detect()) {
        $obj->cell = 1;
        $obj->SysValue['templates']['main_product_forma_1'] = "product/main_product_forma_3.tpl";
    }
}

/**
 * Добавление в список каталогов спецпредложения товаров в 3 ячейки, лимит 3
 */
$addHandler = array
    (
    'product_grid' => 'phpshopshopcore_product_grid_nt_hook',
);

// функция определения мобильных браузеров.
function detect() {
    $ipad = strpos($_SERVER['HTTP_USER_AGENT'], "iPad");
    $iphone = strpos($_SERVER['HTTP_USER_AGENT'], "iPhone");
    $android = strpos($_SERVER['HTTP_USER_AGENT'], "Android");
    $palmpre = strpos($_SERVER['HTTP_USER_AGENT'], "webOS");
    $berry = strpos($_SERVER['HTTP_USER_AGENT'], "BlackBerry");
    $ipod = strpos($_SERVER['HTTP_USER_AGENT'], "iPod");
    $mobile = strpos($_SERVER['HTTP_USER_AGENT'], "Mobile");
    $symb = strpos($_SERVER['HTTP_USER_AGENT'], "Symbian");
    $operam = strpos($_SERVER['HTTP_USER_AGENT'], "Opera M");
    $htc = strpos($_SERVER['HTTP_USER_AGENT'], "HTC_");
    $fennec = strpos($_SERVER['HTTP_USER_AGENT'], "Fennec/");
    $winphone = strpos($_SERVER['HTTP_USER_AGENT'], "Windows Phone");
    $wp7 = strpos($_SERVER['HTTP_USER_AGENT'], "WP7");
    $wp8 = strpos($_SERVER['HTTP_USER_AGENT'], "WP8");

    if ($ipad || $iphone || $ipod === true)
        $detect = 'ios';
    elseif ($android)
        $detect = 'android';
    elseif ($winphone || $wp7 || $wp8 === true)
        $detect = 'wp';
    elseif ($symb)
        $detect = 'symbian';
    elseif ($palmpre || $berry || $mobile || $operam || $htc || $fennec === true)
        $detect = 'other';

    if ($detect)
        return $detect;
}

?>