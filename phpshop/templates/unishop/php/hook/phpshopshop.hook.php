<?php

function template_CID_Product($obj, $data, $rout) {
    if ($rout == 'START') {

        // Фасетный фильтр
        $obj->sort_template = 'sorttemplatehook';

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
            //default: $obj->set('fSetAactive', 'active');
        }
    }
    if($rout == "END"){
        $obj->set("productDescriptionSmall", $data["description"]);
    }
}


/**
 * Вывод подтипов в подробном описании 
 */
function template_parent($obj, $dataArray, $rout) {



    if ($rout == 'END') {

        $currency = $obj->currency;

        $true_color_array = $true_size_color_array = $color_array = array();
        $size = $color = null;

        if (count($obj->select_value > 0)) {

            foreach ($obj->select_value as $value) {

                $row = $value[3];
                if (!empty($value[3]['parent_enabled'])) {
                    $row['price'] = number_format($obj->price($row), $obj->format, '.', ' ');
                    $obj->set('productPrice', $row['price']);
                    $obj->set('productValutaName', $currency);
                    $obj->set('parentName', $value[0]);
                    $obj->set('parentCheckedId', $value[1]);

                    $size_color_array[] = array('id' => $value[3]['id'], 'size' => $value[3]['parent'], 'price' => $row['price'], 'color' => array($value[3]['parent2']));

                    if (!empty($value[3]['color']))
                        $color_array[$value[3]['parent2']] = $value[3]['color'];
                    else if(!empty($value[3]['parent2']))
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
            

            if (is_array($true_size_color_array) and count($true_size_color_array)>0) {
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
                    $size.= ParseTemplateReturn("product/product_odnotip_product_parent_one.tpl");

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
                            $id.=' select-color-' . $ids;
                        }

                    $obj->set('parentColorId', $id);
                    $color.= ParseTemplateReturn("product/product_odnotip_product_parent_one_color.tpl");
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
                $obj->set('parentListColorTitle', __('Цвет'));

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
                foreach ($vendor as $v) {
                    if (is_array($v))
                        foreach ($v as $s)
                            if ($s == $p[1])
                                $checked = 'checked';
                }
            }

            if($p[3] != null)
                $text .= ' (' . $p[3] . ')';

            // Определение цвета
            if ($text[0] == '#')
                $text = '<div class="filter-color" style="background:' . $text . '"></div>';

            $disp.= '<div class="checkbox">
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

    return '<div class="faset-filter-block-wrapper"><h4>' . $title . '</h4>' . $disp.'</div></div>';
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
    if (!is_array($data) and !empty($array['pic_big']))
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
            if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $name_bigstr))
                $name_bigstr = $name;

            $bxslider.= '<div><a class href="#"><img src="' . $name . '" title="'.$array['name'].'" alt="'.$array['name'].'" /></a></div>';
            $bxsliderbig.= '<li><a class href=\'#\'><img src=\'' . $name_bigstr . '\' title=\''.$array['name'].'\' alt=\''.$array['name'].'\'></a></li>';
            $bxpager.='<a data-slide-index=\'' . $i . '\' href=\'\'><img class=\'img-thumbnail\'  src=\'' . $name_s . '\'></a>';
            $i++;
        }


        if ($i < 2)
            $bxpager = null;


        $obj->set('productFotoList', '<img class="bxslider-pre" alt="' . $array['name'] . '" title="' . $array['name'] . '" src="' . $array['pic_big'] . '" /><div class="bxslider hide">' . $bxslider . '</div><div class="bx-pager">' . $bxpager . '</div>');
        $obj->set('productFotoListBig', '<ul class="bxsliderbig" data-content="' . $bxsliderbig . '" data-page="' . $bxpager . '"></ul><div class="bx-pager-big">' . $bxpager . '</div>');
        return true;
    }
}
/**
 * Изменение сетки сопутствующих товаров, сетка товаров = 3
 */
function odnotip_hook($obj,$row,$rout) {
    if($rout=='START') {
        $obj->odnotip_setka_num=4;
        $obj->template_odnotip='main_product_forma_4';
        $obj->line=true;
    }
}

$addHandler = array
    (
    'CID_Product' => 'template_CID_Product',
    'parent' => 'template_parent',
    'odnotip'=>'odnotip_hook',
    'UID' => 'template_UID',
    'image_gallery' => 'template_image_gallery'
);
?>