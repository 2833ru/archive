<?php

/**
 * Вывод имени набора характеристики
 */
function sort_table_get_category_name($category) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
    $data=$PHPShopOrm->select(array('name'),array('id'=>'='.intval($category)),false,array('limit'=>1));
    return $data['name'];
}

/**
 * Вывод сортировок для товаров таблицей
 */
function sort_table_hook($obj, $row) {
    global $SysValue;

    $sort = $obj->PHPShopCategory->unserializeParam('sort');
    $vendor_array = unserialize($row['vendor_array']);
    $dis = null;

    if (is_array($sort))
        foreach ($sort as $v) {
            $sortCat.=' id=' . $v . ' OR';
        }
    $sortCat = substr($sortCat, 0, strlen($sortCat) - 2);

    if (!empty($sortCat)) {

        // Массив имен характеристик
        $PHPShopOrm = new PHPShopOrm();
        $PHPShopOrm->debug = $obj->debug;
        $result = $PHPShopOrm->query("select * from " . $SysValue['base']['table_name20'] . " where ($sortCat and goodoption!='1') order by num");
        while (@$row = mysql_fetch_assoc($result)) {
            $arrayVendor[$row['id']] = $row;
        }


        if (is_array($vendor_array))
            foreach ($vendor_array as $v) {
                foreach ($v as $value)
                    if (is_numeric($value))
                        $sortValue.=' id=' . $value . ' OR';
            }
        $sortValue = substr($sortValue, 0, strlen($sortValue) - 2);

        if (!empty($sortValue)) {

            // Массив значений характеристик
            $PHPShopOrm = new PHPShopOrm();
            $PHPShopOrm->debug = $obj->debug;
            $result = $PHPShopOrm->query("select * from " . $SysValue['base']['table_name21'] . " where $sortValue order by num");
            while (@$row = mysql_fetch_array($result)) {
                $arrayVendorValue[$row['category']]['name'] .= ", " . $row['name'];
            }


            // Создаем таблицу характеристик с учетом сортировки
            if (is_array($arrayVendor))
                foreach ($arrayVendor as $idCategory => $value)
                    if (!empty($arrayVendorValue[$idCategory]['name'])) {
                        if (!empty($value['name'])) {
                            
                            if(empty($SortNameCatHook[$value['category']])){
                            $dis.= PHPShopText::tr(PHPShopText::b(sort_table_get_category_name($value['category'])),'');
                            $SortNameCatHook[$value['category']]=1;
                            }
                            
                            if (!empty($value['page']))
                                $dis.=PHPShopText::tr(PHPShopText::b($value['name']) . ': ', PHPShopText::a('../page/' . $value['page'] . '.html', substr($arrayVendorValue[$idCategory]['name'], 2)));
                            else
                                $dis.=PHPShopText::tr(PHPShopText::b($value['name']) . ': ', substr($arrayVendorValue[$idCategory]['name'], 2));
                        }
                    }

            $disp = PHPShopText::table($dis);
            $obj->set('vendorDisp', $disp);
        }
    }

    return true;
}

$addHandler = array
    (
    'sort_table' => 'sort_table_hook',
);
?>