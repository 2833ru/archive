<?php

/**
 * Вывод сортировок для товаров таблицей
 * @author PHPShop Software
 * @version 1.5
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @return mixed
 */
function sort_table($obj, $row) {
    global $SysValue;

    $sort = $obj->PHPShopCategory->unserializeParam('sort');
    $vendor_array = unserialize($row['vendor_array']);
    $dis = $sortCat = $sortValue = null;
    $arrayVendorValue = array();

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
                $arrayVendorValue[$row['category']]['name'].= ", " . $row['name'];
                $arrayVendorValue[$row['category']]['page'] = $row['page'];
            }


            // Таблица характеристик с учетом сортировки
            if (is_array($arrayVendor))
                foreach ($arrayVendor as $idCategory => $value)
                    if (!empty($arrayVendorValue[$idCategory]['name'])) {
                        if (!empty($value['name'])) {

                            // Описание храктеристики
                            if (!empty($value['page']))
                                $sort_name = PHPShopText::a('/page/' . $value['page'] . '.html', $value['name']);
                            else
                                $sort_name = PHPShopText::b($value['name']);

                            // Описание значения характеристики
                            if (!empty($arrayVendorValue[$idCategory]['page']))
                                $value_name = PHPShopText::a('/page/' . $arrayVendorValue[$idCategory]['page']. '.html', substr($arrayVendorValue[$idCategory]['name'], 2));
                            else
                                $value_name = substr($arrayVendorValue[$idCategory]['name'], 2);

                                $dis.=PHPShopText::tr($sort_name . ': ',$value_name);
                        }
                    }

            $disp = PHPShopText::table($dis);
            $obj->set('vendorDisp', $disp);
        }
    }
}

?>