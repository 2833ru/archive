<?php

function PHPShopRSS_yandexcart_hook($obj) {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexcart']['yandexcart_system']);
    $option = $PHPShopOrm->select();

    // ѕароль
    if (!empty($option['password']))
        if ($_GET['pas'] != $option['password'])
            exit('Login error!');

    // Ѕренды
    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->sql = 'SELECT b.id, b.name FROM ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' AS a LEFT JOIN ' . $GLOBALS['SysValue']['base']['sort'] . ' AS b ON a.id = b.category where a.brand="1" limit 1000';
    $vendor = $PHPShopOrm->select();
    if (is_array($vendor)) {
        $obj->vendor = true;
        foreach ($vendor as $brand) {
            $obj->brand_array[$brand['id']] = $brand['name'];
        }
    }
}

function setProducts_google_hook($obj, $data)
{
    $add = null;

    // Brand из характеристики, если не задано принудительно в карточке товара.
    if (is_array($data['val']['vendor_array']) && empty($data['val']['vendor_name']))
        foreach ($data['val']['vendor_array'] as $v) {
            // Brand
            if (!empty($obj->brand_array[$v[0]])) {
                $add .='<g:brand>' . $obj->brand_array[$v[0]] . '</g:brand>';
            }
    }

    // Brand из карточки товара
    if (!empty($data['val']['vendor_name']))
        $add .='<g:brand>' . $data['val']['vendor_name'] . '</g:brand>';

    if(!empty($data['val']['vendor_code'])) {
        $add .= '<g:gtin>' . $data['val']['vendor_code'] . '</g:gtin>';
    }

    // condition
    switch ($data['val']['condition']) {
        case 2:
            $add .= '<g:condition>refurbished</g:condition>';
            break;
        case 3:
            $add .= '<g:condition>used</g:condition>';
            break;
        default:
            $add .= '<g:condition>new</g:condition>';
            break;
    }

    if (!empty($add))
        $data['xml'] = str_replace('</item>', $add . '</item>', $data['xml']);

    return $data['xml'];
}

$addHandler = array (
    'setProducts' => 'setProducts_google_hook',
    '__construct' => 'PHPShopRSS_yandexcart_hook'
);