<?php
 
/**
 * Изменение места вывода сопутствующих товаров
 */
function UID_multilanguages_hook($obj,$row,$rout){
    if($_SESSION['lang_prefix']!='') {
        //Если есть префикс подменяем переменные
        
        $multilanguages = unserialize($row['multilanguages']);

        if($multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]!='') {
            $obj->set('productName', $multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]);

        }

        if($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]!='')
            $obj->set('productDes', $multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]);


    }

    
}
function odnotip_multilanguages_hook($obj,$row,$rout){
    if($rout=='MIDDLE') {
         $obj->set('productOdnotip', __($GLOBALS['SysValue']['multilanguages']['multilanguages_w_16']));
    }
    
}
function product_grid_phpshopshop_multilanguages($obj,$row,$rout){
    
    if($_SESSION['lang_prefix']!='') {
        //Если есть префикс подменяем переменные
        
        $multilanguages = unserialize($row['multilanguages']);

        if($multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]!='')
            $obj->set('productName', $multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]);

        if($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]!='')
            $obj->set('productDes', $multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]);

    }
    
} 
 
function CID_Product_multilanguages($obj,$row,$rout){
    


    if($_SESSION['lang_prefix']!='') {
        //Если есть префикс подменяем переменные
        
        $multilanguages = unserialize($obj->PHPShopCategory->getParam('multilanguages'));

        if($multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]!='')
            $obj->set('catalogCategory', $multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]);

        if($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]!='')
            $obj->set('catalogContent', $multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]);

    }
    
}
function CID_Category_multilanguages($obj,$row,$rout){
    


    if($_SESSION['lang_prefix']!='') {
        //Если есть префикс подменяем переменные
        
        $multilanguages = unserialize($obj->PHPShopCategory->getParam('multilanguages'));

        if($multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]!='')
            $obj->set('catalogName', $multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]);

        if($multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]!='')
            $obj->set('catalogContent', $multilanguages['multilanguages_content'][ $_SESSION['lang_id'] ]);

    }

    if($rout=='END') {
        // ID категории
        $obj->category = PHPShopSecurity::TotalClean($obj->PHPShopNav->getId(), 1);
        $obj->PHPShopCategory = new PHPShopCategory($obj->category);

        // Скрытый каталог
        if ($obj->PHPShopCategory->getParam('skin_enabled') == 1 or $obj->errorMultibase($obj->category))
            return $obj->setError404();

        // Название категории
        $obj->category_name = $obj->PHPShopCategory->getName();

        // Условия выборки
        $where = array('parent_to' => '=' . $obj->category, 'skin_enabled' => "!='1' or dop_cat LIKE '%#" . $obj->category . "#%'");

        // Мультибаза
        if (defined("HostID"))
            $where['servers'] = " REGEXP 'i" . HostID . "i'";

        // Сортировка каталога
        switch ($obj->PHPShopCategory->getValue('order_to')) {
            case(1): $order_direction = "";
                break;
            case(2): $order_direction = " desc";
                break;
            default: $order_direction = "";
                break;
        }
        switch ($obj->PHPShopCategory->getValue('order_by')) {
            case(1): $order = array('order' => 'name' . $order_direction);
                break;
            case(2): $order = array('order' => 'name' . $order_direction);
                break;
            case(3): $order = array('order' => 'num' . $order_direction);
                break;
            default: $order = array('order' => 'num' . $order_direction);
                break;
        }

        // Выборка данных
        $PHPShopOrm = new PHPShopOrm($obj->getValue('base.categories'));
        $PHPShopOrm->debug = $obj->debug;
        $PHPShopOrm->cache = $obj->cache;
        $dis = null;
        $dataArray = $PHPShopOrm->select(array('*'), $where, $order, array('limit' => $obj->max_item));
        if (is_array($dataArray))
            if (PHPShopParser::checkFile($obj->cid_cat_with_foto_template)) {
                foreach ($dataArray as $row) {
                    if (empty($row['icon']))
                        $row['icon'] = $obj->no_photo;
                    $obj->set('podcatalogIcon', $row['icon']);
                    $obj->set('podcatalogId', $row['id']);
                    //$obj->set('podcatalogName', $row['name']);
                    $obj->set('podcatalogDesc', $row['content']);

                    $multilanguages = unserialize($row['multilanguages']);
                    if($multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]!='')
                        $obj->set('podcatalogName', $multilanguages['multilanguages_name'][ $_SESSION['lang_id'] ]);


                    $dis.=ParseTemplateReturn($obj->cid_cat_with_foto_template);
                }
                $disp = $dis;
            }
            else {
                foreach ($dataArray as $row) {
                    $dis.=PHPShopText::li($row['name'], '/shop/CID_' . $row['id'] . '.html');
                }
                $disp = PHPShopText::ul($dis);
            }

        $obj->set('catalogList', $disp);
    }
    
}

$addHandler=array
        (
        'UID'=>'UID_multilanguages_hook',
        'odnotip'=>'odnotip_multilanguages_hook',
        'product_grid'=>'product_grid_phpshopshop_multilanguages',
        'CID_Product'=>'CID_Product_multilanguages',
        'CID_Category'=>'CID_Category_multilanguages'

);
?>