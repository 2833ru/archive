<?php

function setProducts_ymladvance_hook($obj, $data) {
    $warrant = $content = null;

    // Гарантия производителя
    if (!empty($_SESSION['module']['ymladvance']['warranty_enabled'])) {
        $warranty = '
   <manufacturer_warranty>true</manufacturer_warranty>
';
    }

        if (!empty($_SESSION['module']['ymladvance']['content_enabled'])) {
            $content = '
   <content>' . $data['val']['content'] . '</content>
';
        }

        $xml = str_replace('</offer>', $warranty . $content.'</offer>', $data['xml']);

        return $xml;
    }

    function PHPShopYml_ymladvance_hook($obj) {

        // Настройки модуля
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['ymladvance']['ymladvance_system']);
        $option = $PHPShopOrm->select();

        if (!empty($option['password']))
            if ($_GET['pas'] != $option['password'])
                exit('Login error!');


        if (!empty($vendor['sort1_name']))
            $obj->vendor = true;

        $vendor = unserialize($option['vendor']);

        if (!empty($vendor['sort1_tag']) and !empty($vendor['sort1_name']))
            $vendor_array[$vendor['sort1_tag']] = $vendor['sort1_name'];

        if (!empty($vendor['sort2_tag']) and !empty($vendor['sort2_name']))
            $vendor_array[$vendor['sort2_tag']] = $vendor['sort2_name'];

        if (!empty($vendor['sort3_tag']) and !empty($vendor['sort3_name']))
            $vendor_array[$vendor['sort3_tag']] = $vendor['sort3_name'];

        if (!empty($vendor['sort4_tag']) and !empty($vendor['sort4_name']))
            $vendor_array[$vendor['sort4_tag']] = $vendor['sort4_name'];

        if (!empty($vendor['sort1_name'])) {
            $obj->vendor = true;

            $obj->vendor_name = $vendor_array;
        }

        if (!empty($option['warranty_enabled']))
            $_SESSION['module']['ymladvance']['warranty_enabled'] = 1;
        
        if (!empty($option['content_enabled']))
            $_SESSION['module']['ymladvance']['content_enabled'] = 1;
    }

    $addHandler = array
        (
        'setProducts' => 'setProducts_ymladvance_hook',
        'PHPShopYml' => 'PHPShopYml_ymladvance_hook'
    );
?>