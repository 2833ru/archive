<?php

/**
 * Изменение формата решетки между товарами c <td> на <li>
 * @param array $obj объект
 * @param array $arg массив данных
 * @return string
 */
function setcell_hook($obj, $arg) {

    $li = null;

    foreach ($arg as $val) {
        if (!empty($val)) {
            $li.='<li class="table-view-cell media">' . $val . '</li>';
        }
    }

    return $li;
}

/**
 * Изменение формата решетки между товарами c <td> на <li>, компиляция списка в <ul>
 * @return string
 */
function compile_hook($obj) {
    $ul = '<ul class="table-view">' . $obj->product_grid . '</ul>';
    $obj->product_grid = null;
    return $ul;
}

function CID_Product_cell_hook($obj, $row, $rout) {
    if ($rout == "START") {
        $obj->PHPShopCategory->setParam('num_row', 1);
        $obj->PHPShopCategory->setParam('num_cow', 500);
    }
}

function UID_mob_hook($obj, $row, $rout) {

    if ($rout == 'END') {
        $product_modals = PHPShopParser::file('./phpshop/templates/mobile/product/product_forma_modal.tpl', true);
        PHPShopParser::set('product_modals', $product_modals);
    }
}

function image_gallery_mob_hook($obj, $row) {

    $dis = null;
    $PHPShopOrm = new PHPShopOrm($obj->getValue('base.foto'));
    $data = $PHPShopOrm->select(array('*'), array('parent' => '=' . $row['id']), array('order' => 'num'), array('limit' => 100));
    if (is_array($data)) {
        foreach ($data as $row) {
            $name = $row['name'];

            $dis.='
<div class="slide">
      <img src="' . $name . '">
</div>';
        }
    } else {

        $dis.='
<div class="slide">
      <img src="' . $row['pic_big'] . '">
</div>';
    }

    $disp = '
<div class="slider" id="mySlider" style="background-color:#FFF">
  <div class="slide-group">
  ' . $dis . '
  </div>
</div>';
    $obj->set('productFotoList', $disp);
    return true;
}

function sort_table_mob_hook($obj, $row) {
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
        while (@$row = mysqli_fetch_assoc($result)) {
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
            while (@$row = mysqli_fetch_array($result)) {
                $arrayVendorValue[$row['category']]['name'].= ", " . $row['name'];
                $arrayVendorValue[$row['category']]['page'] = $row['page'];
            }


            // Таблица характеристик с учетом сортировки
            if (is_array($arrayVendor))
                foreach ($arrayVendor as $idCategory => $value)
                    if (!empty($arrayVendorValue[$idCategory]['name'])) {
                        if (!empty($value['name'])) {

                            $sort_name = $value['name'];

                            $value_name = substr($arrayVendorValue[$idCategory]['name'], 2);

                            $dis.='<li class="table-view-divider">' . $sort_name . '</li>
                                <li class="table-view-cell">' . $value_name . '</li>';
                        }
                    }

            $disp = '<ul class="table-view">' . $dis . '</ul>';
            $obj->set('vendorDisp', $disp);
        }
    }
    return true;
}

function parent_mob_hook($obj, $row, $rout) {


    if ($rout == 'START') {

        $select_value = array();
        $dis = null;
        $row['parent'] = PHPShopSecurity::CleanOut($row['parent']);

        if (!empty($row['parent'])) {
            $parent = explode(",", $row['parent']);

            // Учет склада
            $sklad_status = $obj->PHPShopSystem->getSerilizeParam('admoption.sklad_status');

            // Убираем добавление в корзину главного товара
            $obj->set('ComStartCart', '<!--');
            $obj->set('ComEndCart', '-->');

            // Собираем массив товаров
            if (is_array($parent))
                foreach ($parent as $value) {
                    if (PHPShopProductFunction::true_parent($value))
                        $Product[$value] = $obj->select(array('*'), array('uid' => '="' . $value . '"', 'enabled' => "='1'", 'sklad' => "!='1'"), false, false, __FUNCTION__);
                    else
                        $Product[intval($value)] = $obj->select(array('*'), array('id' => '=' . intval($value), 'enabled' => "='1'"), false, false, __FUNCTION__);
                }

            // Цена главного товара
            if (!empty($row['price']) and empty($row['priceSklad']) and (!empty($row['items']) or (empty($row['items']) and $sklad_status == 1))) {
                $select_value[] = array($row['name'] . " -  (" . $obj->price($row) . "
                    " . $obj->get('productValutaName') . ')', $row['id'], false);
            } else {
                $obj->set('ComStartNotice', PHPShopText::comment('<'));
                $obj->set('ComEndNotice', PHPShopText::comment('>'));
            }

            // Выпадающий список товаров
            if (is_array($Product))
                foreach ($Product as $p) {
                    if (!empty($p)) {

                        // Если товар на складе
                        if (empty($p['priceSklad']) and (!empty($p['items']) or (empty($p['items']) and $sklad_status == 1))) {
                            $price = $obj->price($p);
                            $select_value[] = array($p['name'] . ' -  (' . $price . ' ' . $obj->get('productValutaName') . ')', $p['id'], false);
                            $dis.='<li class="table-view-cell"> <a class="navigate-right" href="/order/?from=html&id=' . $p['id'] . '" onclick="go(this.href)">' . $p['name'] . ' (' . $price . ' ' . $obj->get('productValutaName') . ')</a> </li>';
                        }
                    }
                }

            if (count($select_value) > 0) {
                $obj->set('parentList', $dis);
                $obj->set('productParentList', ParseTemplateReturn("product/product_odnotip_product_parent.tpl"));
            }

            $obj->set('productPrice', '');
            $obj->set('productPriceRub', '');
            $obj->set('productValutaName', '');
        }
        // Опции товара
        elseif ($obj->get('optionsDisp') != '') {

            // Убираем добавление в корзину главного товара
            $obj->set('ComStartCart', '<!--');
            $obj->set('ComEndCart', '-->');

            $obj->set('parentList', $obj->get('optionsDisp'). ' <A href="javascript:addCartOption(\''.$row['id'].'\')"><button class="btn btn-positive btn-block"><span class="icon icon-download"></span> Купить за '.$obj->price($row).' ' . $obj->get('productValutaName') . '</button></a>');
            $obj->set('productParentList', ParseTemplateReturn("product/product_odnotip_product_parent.tpl"));
        }


        return true;
    }
}

$addHandler = array
    (
    'CID_Product' => 'CID_Product_cell_hook',
    '#CID_Category' => 'CID_Category_cell_hook',
    'setCell' => 'setcell_hook',
    'UID' => 'UID_mob_hook',
    'image_gallery' => 'image_gallery_mob_hook',
    'sort_table' => 'sort_table_mob_hook',
    'parent' => 'parent_mob_hook'
);
?>