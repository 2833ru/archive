<?php

function setProducts_yandexcart_hook($obj, $data) {
    $add = $list = null;

    // Характеристики
    if (!empty($obj->vendor) or !empty($obj->param)) {

        if (is_array($data['val']['vendor_array']))
            foreach ($data['val']['vendor_array'] as $v) {

                // Vendor
                if ($obj->brand_array[$v[0]] != "")
                    $add.='<vendor>' . $obj->brand_array[$v[0]] . '</vendor>';

                // Param
                if ($obj->param_array[$v['name']] != "" and $obj->param_array[$v[0]]['yandex_param_unit'] != "")
                    $add.='<param name="' . $obj->param_array[$v[0]]['param'] . '" unit="' . $obj->param_array[$v[0]]['yandex_param_unit'] . '">' . $obj->param_array[$v[0]]['name'] . '</param>';
                elseif ($obj->param_array[$v[0]]['param'] != "")
                    $add.='<param name="' . $obj->param_array[$v[0]]['param'] . '">' . $obj->param_array[$v[0]]['name'] . '</param>';
            }
    }

    // Подтип
    if (!empty($data['val']['group_id'])) {

        // Размер
        if (!empty($data['val']['size']))
            $add.='<param name="Размер" unit="RU">' . $data['val']['size'] . '</param>';

        // Цвет
        if (!empty($data['val']['color']))
            $add.='<param name="Цвет">' . $data['val']['color'] . '</param>';
    }


    // fee
    if ($data['val']['cpa'] == 1 and !empty($data['val']['fee'])) {
        $data['xml'] = str_replace('<offer', '<offer fee="' . $data['val']['fee'] . '"', $data['xml']);
    }

    // Oldprice
    if (!empty($data['val']['oldprice']))
        $data['xml'] = str_replace('<price>' . $data['val']['price'] . '</price>', '<price>' . $data['val']['price'] . '</price><oldprice>' . $data['val']['oldprice'] . '</oldprice>', $data['xml']);


    // Доставка
    if ($data['val']['delivery'] == 1)
        $add.='<delivery>true</delivery>';
    else
        $add.='<delivery>false</delivery>';

    $i = 0;
    $delivery = $GLOBALS['delivery'];
    if (is_array($delivery)) {
        foreach ($delivery as $row) {
            if ($i < 5)
                $list.='<option cost="' . $row['price'] . '" days="' . $row['yandex_day_min'] . '-' . $row['yandex_day'] . '" order-before="' . $row['yandex_order_before'] . '"/>';
            else
                continue;
            $i++;
        }
    }

    if (!empty($list))
        $add.='<delivery-options>' . $list . '</delivery-options>';

    // Pickup
    if ($data['val']['pickup'] == 1)
        $add.='<pickup>true</pickup>';
    else
        $add.='<pickup>false</pickup>';

    // Store
    if ($data['val']['store'] == 1)
        $add.='<store>true</store>';
    else
        $add.='<store>false</store>';

    // Notes
    if (!empty($data['val']['sales_notes']))
        $add.='<sales_notes>' . $data['val']['sales_notes'] . '</sales_notes>';

    // Гарантия
    if ($data['val']['manufacturer_warranty'] == 1)
        $add.='<manufacturer_warranty>true</manufacturer_warranty>';

    // Страна
    if (!empty($data['val']['country_of_origin']))
        $add.='<country_of_origin>' . $data['val']['country_of_origin'] . '</country_of_origin>';

    // Adult
    if ($data['val']['adult'] == 1)
        $add.='<adult>true</adult>';

    // min-quantity
    if (!empty($data['val']['yandex_min_quantity']))
        $add.='<min-quantity>' . $data['val']['yandex_min_quantity'] . '</min-quantity>';

    // step-quantity
    if (!empty($data['val']['yandex_step_quantity']))
        $add.='<step-quantity>' . $data['val']['yandex_step_quantity'] . '</step-quantity>';

    // cpa
    if ($data['val']['cpa'] == 1) {
        $add.='<cpa>1</cpa>';
    }
    else
        $add.='<cpa>0</cpa>';

    // weight
    if (!empty($data['val']['weight']))
        $add.='<weight>' . round($data['val']['weight'] / 1000, 3) . '</weight>';

    // rec
    if (!empty($data['val']['rec']))
        $add.='<rec>' . $data['val']['rec'] . '</rec>';

    // vendorCode
    if (!empty($data['val']['vendor_code']))
        $add.='<vendorCode>' . $data['val']['vendor_code'] . '</vendorCode>';


    if (!empty($add))
        $data['xml'] = str_replace('</offer>', $add . '</offer>', $data['xml']);

    return $data['xml'];
}

function setDelivery_yandexcart_hook($obj, $data) {

    // Доставка
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
    $delivery = $PHPShopOrm->select(array('price', 'yandex_day', 'yandex_day_min', 'yandex_order_before'), array('enabled' => "='1'", 'is_folder' => "!='1'", 'yandex_enabled' => "='2'", 'yandex_check' => "='2'", 'yandex_type' => "='1'"), false, array('limit' => 300));
    $GLOBALS['delivery'] = $delivery;

    /*
      if (!empty($delivery))
      $data['xml'] = str_replace('<local_delivery_cost>' . $data['val']['price'] . '</local_delivery_cost>', '<delivery-options/>', $data['xml']); */

    // Бренды
    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->sql = 'SELECT b.id, b.name FROM ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' AS a LEFT JOIN ' . $GLOBALS['SysValue']['base']['sort'] . ' AS b ON a.id = b.category where a.brand="1" limit 1000';
    $vendor = $PHPShopOrm->select();
    if (is_array($vendor)) {
        $obj->vendor = true;
        foreach ($vendor as $brand) {
            $obj->brand_array[$brand['id']] = $brand['name'];
        }
    }

    // Параметры
    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->sql = 'SELECT a.yandex_param_unit,a.name as param, b.id, b.name FROM ' . $GLOBALS['SysValue']['base']['sort_categories'] . ' AS a LEFT JOIN ' . $GLOBALS['SysValue']['base']['sort'] . ' AS b ON a.id = b.category where a.yandex_param="2" and a.brand!="1" limit 1000';
    $param = $PHPShopOrm->select();
    if (is_array($param)) {
        $obj->param = true;
        foreach ($param as $par) {
            $obj->param_array[$par['id']] = $par;
        }
    }

    return $data['xml'];
}

$addHandler = array
    (
    'setProducts' => 'setProducts_yandexcart_hook',
    'setDelivery' => 'setDelivery_yandexcart_hook'
);
?>