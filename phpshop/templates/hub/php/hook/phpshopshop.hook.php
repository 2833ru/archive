<?php

function template_CID_Product($obj, $data, $rout) {
    if ($rout == 'START') {

        // Фасетный фильтр
        $obj->sort_template = 'sorttemplatehook';

        // Виртуальные каталоги
        $obj->cat_template = 'sortсattemplatehook';

        if (empty($_GET['gridChange']))
            $_GET['gridChange'] = null;

        if (empty($_GET['s']))
            $_GET['s'] = null;

        if (empty($_GET['f']))
            $_GET['f'] = null;

        switch ($_GET['gridChange']) {
            case 1:
                $obj->set('gridSetAactive', 'active');
                break;
            case 2:
                $obj->set('gridSetBactive', 'active');
                break;
            default:
                if ($obj->cell == 1)
                    $obj->set('gridSetAactive', 'active');
                else
                    $obj->set('gridSetBactive', 'active');
        }


        switch ($_GET['s']) {
            case 1:
                $obj->set('sSetAactive', 'active');
                break;
            case 2:
                $obj->set('sSetBactive', 'active');
                break;
            default: $obj->set('sSetCactive', 'active');
        }


        switch ($_GET['f']) {
            case 1:
                $obj->set('fSetAactive', 'active');
                break;
            case 2:
                $obj->set('fSetBactive', 'active');
                break;
            default: $obj->set('fSetAactive', 'active');
        }
    }
}

/**
 * Шаблон вывода характеристик виртуальные каталоги
 */
function sortсattemplatehook($value, $n, $title, $vendor) {
    $disp = null;

    if (is_array($value)) {
        foreach ($value as $p) {

            $text = $p[0];
            $checked = null;
            if (is_array($vendor)) {
                foreach ($vendor as $v) {
                    if ($v == $p[1])
                        $checked = 'active';
                }
            }
            if ($p[3] != null)
                $text .= ' (' . $p[3] . ')';

            $disp .= '<a class="btn btn-default sortcat ' . $checked . '" href="?v[' . $n . ']=' . $p[1] . '">' . $text . '</a> ';
        }
    }

    return '<p>' . $disp . '</p>';
}

/**
 * Вывод подтипов в подробном описании 
 */
function template_parent($obj, $dataArray, $rout) {

    if ($rout == 'END') {

        $currency = $obj->currency;

        $true_color_array = $true_size_color_array = $color_array = array();
        $size = $color = null;

        if (is_array($obj->select_value) and count($obj->select_value > 0)) {

            foreach ($obj->select_value as $value) {

                $row = $value[3];
                if (!empty($row['parent_enabled'])) {

                    $row['price_n'] = number_format($obj->price($row, true), $obj->format, '.', ' ');
                    $row['price'] = number_format($obj->price($row), $obj->format, '.', ' ');

                    // Цена для YML ?option=ID
                    if (!empty($_GET['option'])) {
                        if ($value[1] == $_GET['option'])
                            $obj->set('productPrice', $row['price']);
                    } else
                        $obj->set('productPrice', number_format($obj->price($dataArray), $obj->format, '.', ' '));

                    $obj->set('productValutaName', $currency);
                    $obj->set('parentName', $value[0]);
                    $obj->set('parentCheckedId', $value[1]);

                    // Единица измерения
                    if (empty($row['ed_izm']))
                        $row['ed_izm'] = $obj->lang('product_on_sklad_i');

                    $size_color_array[$value[3]['id']] = array('id' => $row['id'], 'size' => $row['parent'], 'price' => $row['price'], 'color' => array($row['parent2']), 'image' => $row['pic_small'], 'price_n' => $row['price_n'], 'items' => $row['items'], 'ed_izm' => $row['ed_izm']);

                    if (!empty($value[3]['color']))
                        $color_array[$value[3]['parent2']] = $value[3]['color'];
                    else if (!empty($value[3]['parent2']))
                        $color_array[$value[3]['parent2']] = PHPShopString::getColor($value[3]['parent2']);
                }
            }


            if (is_array($size_color_array)) {
                foreach ($size_color_array as $v) {

                    if (empty($true_size_color_array[$v['size']])) {
                        $true_size_color_array[$v['size']] = $v;
                    } else {
                        $true_size_color_array[$v['size']]['color'][] = $v['color'][0];
                    }
                }
            }


            if (is_array($true_size_color_array) and count($true_size_color_array) > 0) {
                $parentSizeEnabled = true;
                foreach ($true_size_color_array as $key => $val) {

                    // Размер
                    if (empty($key)) {
                        $obj->set('parentSizeHide', 'hide hidden');
                        $obj->set('parentSizeChecked', 'checked');
                        $parentSizeEnabled = false;
                    } else {
                        $obj->set('parentSizeChecked', null);
                    }

                    $obj->set('parentSize', $key);
                    $obj->set('parentId', $val['id']);
                    $obj->set('parentPrice', $val['price']);
                    $obj->set('parentImage', $size_color_array[$val['id']]['image']);
                    // Склад
                    if ($obj->PHPShopSystem->getSerilizeParam('admoption.sklad_enabled') == 1)
                        $obj->set('parentItems', $obj->lang('product_on_sklad') . " " . $val['items'] . " " . $val['ed_izm']);

                    if ((float) $size_color_array[$val['id']]['price_n'] > 0)
                        $obj->set('parentPriceOld', $size_color_array[$val['id']]['price_n']);
                    else
                        $obj->set('parentPriceOld', '');


                    $size .= ParseTemplateReturn("product/product_odnotip_product_parent_one.tpl");

                    // Цвет
                    foreach ($val['color'] as $colors) {
                        $true_color_array[$colors][] = $val['id'];
                    }
                }
            }

            if (is_array($color_array)) {

                foreach ($color_array as $true_name => $true_colors) {
                    $obj->set('parentColor', $true_colors);
                    $obj->set('parentName', $true_name);
                    $id = null;

                    if (is_array($true_color_array[$true_name]))
                        foreach ($true_color_array[$true_name] as $ids) {
                            $id .= ' select-color-' . $ids;
                        }

                    $obj->set('parentColorId', $id);

                    // Цвет
                    if (!empty($true_colors)) {
                        $color .= ParseTemplateReturn("product/product_odnotip_product_parent_one_color.tpl");
                    }
                    // Параметр
                    else {
                        $color .= ParseTemplateReturn("product/product_odnotip_product_parent_one_value.tpl");
                    }
                }
            }

            // Отладка
            /*
              print_r($true_size_color_array);
              print_r($true_color_array);
              print_r($color_array);
             */

            if ($parentSizeEnabled)
                $obj->set('parentListSizeTitle', $obj->parent_title);

            $obj->set('parentListSize', $size, true);

            if (!empty($color))
                $obj->set('parentListColorTitle', $obj->parent_color);

            $obj->set('parentListColor', $color, true);
            $obj->set('parentSizeMessage', $obj->lang('select_size'));

            // Наличие
            if (!$obj->get('elementCartOptionHide'))
                $obj->set('elementCartOptionHide', null);

            $obj->set('ComStartCart', null);
            $obj->set('ComEndCart', null);
            //$obj->set('productParentJson',json_safe_encode($true_color_array));
            $obj->set('productParentList', ParseTemplateReturn("product/product_odnotip_product_parent.tpl"));
        }
    }
}

function template_UID($obj, $dataArray, $rout) {
    if ($rout == 'MIDDLE') {
        if ($obj->get('optionsDisp') != '' and $obj->get('parentList') == '') {
            //$obj->set('ComStart','<!--');
            $obj->set('ComStartCart', '<!--');
            $obj->set('ComEndCart', '-->');
            //$obj->set('ComEnd','-->');

            if (empty($dataArray['sklad']))
                $obj->set('optionsDisp', ParseTemplateReturn("product/product_option_product.tpl"));
        }

        // Спецпредложения
        if (!empty($dataArray['spec']))
            $obj->set('specIcon', ParseTemplateReturn('product/specIcon.tpl'));
        else
            $obj->set('specIcon', '');

        // Новинки
        if (!empty($dataArray['newtip']))
            $obj->set('newtipIcon', ParseTemplateReturn('product/newtipIcon.tpl'));
        else
            $obj->set('newtipIcon', '');

        //$obj->set('brandUidDescription',str_replace('href','href="#" data-url',$GLOBALS['SysValue']['other']['brandUidDescription']));
    }
}

/**
 * Шаблон вывода характеристик
 */
function sorttemplatehook($value, $n, $title, $vendor) {
    $limit = 5;
    $disp = null;
    $num = 0;

    if (is_array($value)) {
        foreach ($value as $p) {

            $text = $p[0];
            $checked = null;
            if (is_array($vendor)) {
                foreach ($vendor as $sortId => $v) {
                    if (is_array($v)) {
                        foreach ($v as $s)
                            if ($s == $p[1])
                                $checked = 'checked';
                    } else {
                        if ($n == $sortId && $p[1] == $v)
                            $checked = 'checked';
                    }
                }
            }

            if ($p[3] != null)
                $text .= ' (' . $p[3] . ')';

            // Определение цвета
            if ($text[0] == '#')
                $text = '<div class="filter-color" style="background:' . $text . '"></div>';

            $disp .= '<div class="checkbox">
  <label>
    <input type="checkbox" value="1" name="' . $n . '-' . $p[1] . '" ' . $checked . ' data-url="v[' . $n . ']=' . $p[1] . '"  data-name="' . $n . '-' . $p[1] . '">
    <span class="filter-item"  title="' . $p[0] . '">' . $text . '</span>
  </label>
</div>';
            $num++;
        }
    }

    if ($num > $limit) {
        $style = "collapse";
        $chevron = 'fa fa-chevron-down';
        $help = 'Показать';
    } else {
        $style = "collapse in";
        $chevron = 'fa fa-chevron-up';
        $help = 'Скрыть';
    }

    return '<div class="faset-filter-block-wrapper"><h4>' . $title . '</h4><div>' . $disp . '</div></div></div>';
}

/**
 *  Фотогалерея
 */
function template_image_gallery($obj, $array) {

    $bxslider = $bxsliderbig = $bxpager = null;
    $PHPShopOrm = new PHPShopOrm($obj->getValue('base.foto'));
    $data = $PHPShopOrm->select(array('*'), array('parent' => '=' . $array['id']), array('order' => 'num'), array('limit' => 100));
    $i = 0;
    $s = 1;

    // Нет данных в галерее
    if (!is_array($data) and ! empty($array['pic_big']))
        $data[] = array('name' => $array['pic_big']);

    if (is_array($data)) {

        // Сортировка
        foreach ($data as $k => $v) {

            if ($v['name'] == $array['pic_big'])
                $sort_data[0] = $v;
            else
                $sort_data[$s] = $v;

            $s++;
        }

        ksort($sort_data);

        foreach ($sort_data as $k => $row) {
            $name = $row['name'];
            $name_s = str_replace(".", "s.", $name);
            $name_bigstr = str_replace(".", "_big.", $name);

            // Подбор исходного изображения
            if (!$obj->PHPShopSystem->ifSerilizeParam('admoption.image_save_source') or ! file_exists($_SERVER['DOCUMENT_ROOT'] . $name_bigstr))
                $name_bigstr = $name;

            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $name_s)) {
                $name_s = $name;
            }

            // Поддержка Webp
            $name = $obj->setImage($name);
            $name_s = $obj->setImage($name_s);
            $name_bigstr = $obj->setImage($name_bigstr);

            $bxslider .= '<div><a class href="#"><img src="' . $name . '" title="' . $array['name'] . '" alt="' . $array['name'] . '" /></a></div>';
            $bxsliderbig .= '<li><a class href=\'#\'><img src=\'' . $name_bigstr . '\' title=\'' . $array['name'] . '\' alt=\'' . $array['name'] . '\'></a></li>';
            $bxpager .= '<a data-slide-index=\'' . $i . '\' href=\'\'><img class=\'img-thumbnail\'  src=\'' . $name_s . '\' data-big-image="' . $name . '"></a>';
            $i++;
        }


        if ($i < 2)
            $bxpager = null;


        $obj->set('productFotoList', '<img itemprop="image" content="http://' . $_SERVER['SERVER_NAME'] . $array['pic_big'] . '" class="bxslider-pre" alt="' . $array['name'] . '" src="' . $array['pic_big'] . '" /><div class="bxslider hide bigslider">' . $bxslider . '</div><div class="bx-pager">' . $bxpager . '</div>');
        $obj->set('productFotoListBig', '<ul class="bxsliderbig" data-content="' . $bxsliderbig . '" data-page="' . $bxpager . '"></ul><div class="bx-pager-big">' . $bxpager . '</div>');
        return true;
    }
}

$addHandler = array
    (
    'CID_Product' => 'template_CID_Product',
    'parent' => 'template_parent',
    'UID' => 'template_UID',
    'image_gallery' => 'template_image_gallery'
);
?>