<?php

$TitlePage = __("Обновить товары");
PHPShopObj::loadClass('valuta');
PHPShopObj::loadClass('category');

$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

// Построение дерева категорий
function treegenerator($array, $i, $curent) {
    global $tree_array;
    $del = '¦&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree_select = $check = false;
    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator($tree_array[$k], $i + 1, $curent);

            if ($k == $curent)
                $selected = 'selected';
            else
                $selected = null;

            if (empty($check['select'])) {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';
                $i = 1;
            } else {
                $tree_select.='<option value="' . $k . '" ' . $selected . ' disabled>' . $del . $v . '</option>';
                //$i++;
            }

            $tree_select.=$check['select'];
        }
    }
    return array('select' => $tree_select);
}

function viewCatalog($name="search_category",$category=0) {
    
    if(!empty($category))
    $_REQUEST['cat']=$category;

    $PHPShopCategoryArray = new PHPShopCategoryArray();
    $CategoryArray = $PHPShopCategoryArray->getArray();

    $CategoryArray[0]['name'] = '- Кореневой уровень -';
    $tree_array = array();

    foreach ($PHPShopCategoryArray->getKey('parent_to.id', true) as $k => $v) {
        foreach ($v as $cat) {
            $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
        }
        $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
        $tree_array[$k]['id'] = $k;
    }


    $GLOBALS['tree_array'] = &$tree_array;

    $tree_select = '<select class="form-control input-sm" name="'.$name.'" style="width:250px;">
        <option value=""> - Все категории - </option>';

    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $_REQUEST['cat']);

            if ($k == $_REQUEST['cat'])
                $selected = 'selected';
            else
                $selected = null;

            if (empty($tree_array[$k]))
                $disabled = null;
            else
                $disabled = ' disabled';

            $tree_select.='<option value="' . $k . '" ' . $selected . $disabled . '>' . $v . '</option>';

            $tree_select.=$check['select'];
        }
    $tree_select.='</select>';

    return $tree_select;
}

/**
 * Поиск товара
 */
function actionSearch() {
    global $PHPShopInterface, $PHPShopOrm;

    $PHPShopInterface->field_col = 2;

    $PHPShopInterface->_CODE.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'search_name', 'size' => '300px', 'placeholder' => __('Наименование товара, атикул или ID'), 'class' => 'pull-left', 'value' => PHPShopSecurity::true_search($_REQUEST['words'])));
    $PHPShopInterface->_CODE.= $PHPShopInterface->set_(3);
    $PHPShopInterface->_CODE.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'search_price_start', 'size' => '100px', 'placeholder' => __('Цена от'), 'class' => 'pull-left', 'value' => $_REQUEST['price_start']));
    $PHPShopInterface->_CODE.= $PHPShopInterface->set_(2);
    $PHPShopInterface->_CODE.= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'search_price_end', 'size' => '100px', 'placeholder' => __('Цена до'), 'class' => 'pull-left', 'value' => $_REQUEST['price_end']));
    $PHPShopInterface->_CODE.= $PHPShopInterface->set_(3);
    $PHPShopInterface->_CODE.= '<div class="pull-left">' . viewCatalog() . '</div>';
    $PHPShopInterface->_CODE.=$PHPShopInterface->setInput("button", "search_action", "Найти", "right", 70, "", "btn-sm btn-success pull-right search-action");
    $PHPShopInterface->_CODE.= '<p class="clearfix"> </p>';

    // Заказы
    if ($_POST['selectID'] == 1) {
        $PHPShopInterface->checkbox_action = false;
        $class = 'cart-list';
        $select = $_SESSION['selectCart'];
        $PHPShopInterface->setCaption(array("Наименование", "70%"), array("Цена", "12%", array('align' => 'right')), array('Кол-во', "15%", array('align' => 'center')));
    }
    // Подбор по ID товара
    else {
        $PHPShopInterface->setCaption(array("", "3%"), array("Наименование", "85%"), array("Цена", "15%", array('align' => 'right')));
        $class = 'search-list';

        if (strstr($_POST['currentID'], ",")) {
            $current_array = explode(",", $_POST['currentID']);
            if (is_array($current_array)) {
                foreach ($current_array as $v)
                    $select[intval($v)] = intval($v);
            }
        } elseif (!empty($_POST['currentID']))
            $select[$_POST['currentID']] = $_POST['currentID'];
    }


    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

    if (!empty($_REQUEST['words'])) {
        $where['( name'] = " REGEXP '\x20*" . PHPShopSecurity::true_search($_REQUEST['words']) . "' or uid  REGEXP '^" . PHPShopSecurity::true_search($_REQUEST['words']) . "' or id='" . intval($_REQUEST['words']) . "' )";
    }

    if (!empty($_REQUEST['cat']))
        $where['category'] = "=" . intval($_REQUEST['cat']);

    if (!empty($_REQUEST['price_end']))
        $where['price'] = " BETWEEN " . intval($_REQUEST['price_start']) . " AND " . intval($_REQUEST['price_end']);

    // Если нет поиска
    if (empty($where)) {
        $str = null;
        if (is_array($select)) {
            foreach ($select as $k => $v) {
                $str.=intval($k) . ',';
            }
        }
        $is_cart = true;
        $where['id'] = ' IN (' . $str . '0)';
    }

    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'name'), array('limit' => 100));
    if (is_array($data))
        foreach ($data as $row) {

            // Заказ
            if ($_POST['selectID'] == 1) {

                // Возможность удалить товар из корзины с 0 
                if (!empty($is_cart))
                    $add = 'data-cart="true"';
                else
                    $add = null;

                $items = '<div class="input-group">
      <span class="input-group-btn">
        <button class="btn btn-sm btn-default item-minus hidden-xs" type="button" data-id="' . $row['id'] . '"><span class="glyphicon glyphicon-minus"></span></button>
      </span>
      <input type="text" class="form-control input-sm" id="select_id_' . $row['id'] . '" name="select[' . $row['id'] . '][item]" data-id="' . $row['id'] . '" value="' . intval($_SESSION['selectCart'][$row['id']]['num']) . '" ' . $add . '>
       <span class="input-group-btn">
        <button class="btn btn-sm btn-default item-plus hidden-xs" type="button" data-id="' . $row['id'] . '"><span class="glyphicon glyphicon-plus"></span></button>
      </span>
    </div>';

                $PHPShopInterface->setRow(array('name' => $row['name'], 'align' => 'left'), array('name' => $row['price'], 'align' => 'right'), array('name' => $items, 'align' => 'center'));
            }
            // Подбор по ID товара
            else
                $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'align' => 'left'), array('name' => $row['price'], 'align' => 'right'));
        }

    exit('<table class="table table-hover ' . $class . '">' . $PHPShopInterface->getContent() . '</table><p class="clearfix"> </p>');
}

/**
 * Поиск товара расширенный
 */
function actionAdvanceSearch() {
    global $PHPShopInterface;

    $PHPShopInterface->field_col = 3;
    
    // Память заполнения
    parse_str(parse_url($_SERVER['HTTP_REFERER'], PHP_URL_QUERY),$query);

    $searchforma =  $PHPShopInterface->setField(__('Название товара'),$PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[name]', 'placeholder' => '', 'class' => 'pull-left', 'value' => $query['where']['name'])));
    $searchforma.=  $PHPShopInterface->setField(__('Артикул'),$PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[uid]', 'size' => 200, 'placeholder' => '', 'class' => 'pull-left', 'value' =>  $query['where']['uid'])));
    $searchforma.= $PHPShopInterface->setField(__('ID'),$PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'where[id]', 'size' => 200, 'placeholder' => '1005', 'class' => 'pull-left', 'value' =>  $query['where']['id'])));
    $searchforma.= $PHPShopInterface->setField(__('Характеристика'),$PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'sort',  'placeholder' => 'Характеристика:Значение', 'class' => 'pull-left', 'value' =>  $query['sort'])));
    $searchforma.= $PHPShopInterface->setField(__('Категория'), viewCatalog('where[category]',$query['where']['category']));
    $searchforma.= $PHPShopInterface->setField(__('Вывод'),$PHPShopInterface->setCheckbox('where[spec]', 1, __('Спецпредложение'), intval($query['where']['spec'])).
    $PHPShopInterface->setCheckbox('where[newtip]', 1, __('Новинка'), intval($query['where']['newtip'])).
    $PHPShopInterface->setCheckbox('where[sklad]', 1, __('Под заказ'), intval($query['where']['sklad'])).'<br>'.
    $PHPShopInterface->setCheckbox('where[enabled]', 0, __('Не выводить'), intval($query['where']['enabled'])));
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => 'catalog'));
    $searchforma.= $PHPShopInterface->setInputArg(array('type' => 'hidden', 'name' => 'cat', 'value' => $_REQUEST['cat']));
    
    $searchforma.='<p class="clearfix"> </p>';
    
    if (!empty($_REQUEST['cat'])) {
        PHPShopObj::loadClass("sort");
        $PHPShopSort = new PHPShopSort($_REQUEST['cat'], false, false, 'sorttemplate', false, false, true);
        $searchforma.=$PHPShopSort->disp;
    }

    $PHPShopInterface->_CODE.=$searchforma;

    exit($PHPShopInterface->getContent() . '<p class="clearfix"> </p>');
}

/**
 * Шаблон вывода характеристик
 */
function sorttemplate($value, $n, $title, $vendor) {
    global $PHPShopInterface;
    $i = 1;
    $value_new[0]=array(__('Не учитывать'),null, null);
    if (is_array($value)) {
        sort($value);
        foreach ($value as $p) {
            $hash = "i".$n . "-" . $p[1]."i";
            $value_new[$i] = array($p[0], $hash, null);
            $i++;
        }
    }

    $value = $PHPShopInterface->setSelect('where[vendor][]', $value_new, 300, null, false, $search = true, false, $size = 1, false ,false,'form-control');

    $disp = $PHPShopInterface->setField($title, $value);

    return $disp;
}

// Обработка событий
$PHPShopInterface->getAction();
?>