<?php

/**
 * Изменения в "галлерее изображений в подробном описании товара"
 * @param array $obj объект
 */
function image_gallery_nt_hook($obj, $row) {
    if (!empty($row['pic_big'])) {
        $n = $row['id'];
        $pic_big = $row['pic_big'];
        $pic_big_b = str_replace(".", "_big.", $pic_big);
        $pic_big_big = $_SERVER['DOCUMENT_ROOT'] . $pic_big_b;

        // Проверяем наличие оригинального изображения на сервере
        if (!file_exists($pic_big_big))
            $pic_big_b = $pic_big;

        $name_foto = $row['name'];
        $disp = null;
        $PHPShopOrm = new PHPShopOrm($obj->getValue('base.foto'));
        $data = $PHPShopOrm->select(array('*'), array('parent' => '=' . $row['id']), array('order' => 'num'), array('limit' => 100));
        if (is_array($data)) {
            foreach ($data as $row) {

                $name = $row['name'];
                $name_s = str_replace(".", "s.", $name);
                $name_bigstr = str_replace(".", "_big.", $name);
                $name_big = $_SERVER['DOCUMENT_ROOT'] . $name_bigstr;


                // Проверяем наличие оригинального изображения на сервере
                if (file_exists($name_big))
                    $name_b = $name_bigstr;
                else
                    $name_b = $name;

                $id = $row['id'];
                $info = $row['info'];
                $FotoArray[] = array(
                    "id" => $id,
                    "name" => $obj->checkMultibase($name, true),
                    "name_s" => $obj->checkMultibase($name_s, true),
                    "name_b" => $obj->checkMultibase($name_b, true),
                    "info" => $info
                );
            }


            if (is_array($FotoArray))
                $dBig = '<div class="image">
                                <a href="' . $obj->checkMultibase($pic_big_b, true) . '" class="jqzoom" rel="gal1"  title="' . $name_foto . '">
                                    <img src="' . $obj->checkMultibase($pic_big, true) . '" title="' . $name_foto . '" alt="' . $name_foto . '" id="image">
                                </a>
                        </div>
                        

';
            if (count($FotoArray) > 1) {
                foreach ($FotoArray as $key => $value) {
                    if ($pic_big == $value['name'])
                        $class = 'class="zoomThumbActive"';
                    else
                        $class = 'class=""';
                    $disThumb .= '<li>
                                    <a ' . $class . ' href="javascript:void(0);" rel="{gallery: \'gal1\', smallimage: \'' . $value['name'] . '\',largeimage: \'' . $value['name_b'] . '\'}">
                                        <img src="' . $value['name_s'] . '"  alt="' . $value['info'] . '">
                                    </a>
                                  </li>';
                }
                $dBig .= '<div class="image-additional"><ul id="thumblist" class="clearfix">' . $disThumb . '</ul></div>';
            }


            $d = $dBig;
        } else {
            $d = '<a class=highslide onclick="return hs.expand(this)" href="' . $obj->checkMultibase($name_b, true) . '" target=_blank getParams="null"><div align="center" id="IMGloader" style="padding-bottom: 10px">
 <img src="' . $obj->checkMultibase($pic_big, true) . '" border="1" class="imgOn"  onerror="NoFoto2(this)"></a></div>';
        }

        $obj->set('productFotoList', $d);
    }
    return true;
}

function uid_nt_hook($obj, $dataArray, $rout) {

    if ($rout == 'END') {
        // Новинки в колонку
        $PHPShopProductIconElements = new PHPShopProductIconElements();
        $cont = $PHPShopProductIconElements->specMainIcon(true, $obj->category, null, 2);
        if ($cont) {
            $obj->set('leftMenuContent', '<ul>' . $cont . '</ul>');
            $obj->set('leftMenuName', $obj->get('specMainTitle'));

            // Подключаем шаблон
            $dis = ParseTemplateReturn($obj->getValue('templates.left_menu'));
            // выводим в метку
            $obj->set('specMainIconUID', $dis);

            // прячем левое меню
            $obj->set('UidLeftColHide', 'UidLeftColHide');
        }
    }
}

/**
 * Вывод подтипов в подробном описании в виде радиобоксов
 */
function parent_nt_hook($obj, $dataArray, $rout) {

    if ($rout == 'END') {
        if (count($obj->select_value > 0)) {
            $obj->set('parentList', '');
            foreach ($obj->select_value as $value) {
                $obj->set('parentName', $value[0]);
                $obj->set('parentId', $value[1]);
                if (!$flag) {
                    $obj->set('checked', 'checked');
                    $flag = 1;
                    $obj->set('parentCheckedId', $value[1]);
                }
                else
                    $obj->set('checked', '');

                $disp = ParseTemplateReturn("product/product_odnotip_product_parent_one.tpl");
                $obj->set('parentList', $disp, true);
            }
            $obj->set('productParentList', ParseTemplateReturn("product/product_odnotip_product_parent.tpl"));
        }
    }
}

/**
 * Меняем шаблон для вывода однотипных товаров подробном описании
 */
function odnotip_nt_hook($obj, $dataArray, $rout) {
    if ($rout == 'START') {
        $obj->template_odnotip = 'main_product_forma_2';
    }
}

function cid_category_nt_hook($obj, $dataArray, $rout) {
    if ($rout == 'END') {
        // Случайный выбор каталога
        if (is_array($dataArray))
            foreach ($dataArray as $val)
                $cat .= "," . $val['id'];

        $cat = "(0$cat)";

        // Используем элемент вывода спецпредложений
        $PHPShopProduct_nt_IconElements = new PHPShopProduct_nt_IconElements();
        $PHPShopProduct_nt_IconElements->template = 'main_product_forma_3';

        $spec = $PHPShopProduct_nt_IconElements->specMainIcon_nt(false, $cat, 3, 9, true);

        // Добавляем в переменную списка категорий вывод спецпредложений
        if (!empty($spec) AND strlen($spec) > 10) {
            $obj->set('subCatProdList2', $spec, true);
            $specTemp = ParseTemplateReturn('catalog/catalog_info_forma_newtip.tpl');
            $obj->set('subCatProdList', $specTemp, true);
        }
    }
}

function template_CID_Product($obj, $data, $rout) {
    if ($rout == 'START') {


        // Фасетный фильтр
        $obj->sort_template = 'sorttemplatehook';

        switch ($_REQUEST['gridChange']) {
            case 1:
                $obj->set('gridSetAactive', 'active');
                break;
            case 2:
                $obj->set('gridSetBactive', 'active');
                break;
            default: $obj->set('gridSetBactive', 'active');
        }


        switch ($_REQUEST['s']) {
            case 1:
                $obj->set('sSetAactive', 'active');
                break;
            case 2:
                $obj->set('sSetBactive', 'active');
                break;
            //default: $obj->set('sSetAactive', 'active');
        }


        switch ($_REQUEST['f']) {
            case 1:
                $obj->set('fSetAactive', 'active');
                break;
            case 2:
                $obj->set('fSetBactive', 'active');
                break;
            //default: $obj->set('fSetBactive', 'active');
        }
    }
}

/**
 * Шаблон вывода характеристик
 */
function sorttemplatehook($value, $n, $title, $vendor) {
    $disp = null;

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


            $disp.= '<label >
    <input type="checkbox" value="1" name="' . $n . '-' . $p[1] . '" ' . $checked . ' data-url="v[' . $n . ']=' . $p[1] . '"  data-name="' . $n . '-' . $p[1] . '">
    <span class="filter-item"  title="' . $p[0] . '">' . $text . '</span>
  </label><br>
';
        }
    }
    return '<h5>' . $title . '</h5>' . $disp;
}

$addHandler = array
    (
    'image_gallery' => 'image_gallery_nt_hook',
    'CID_Category' => 'cid_category_nt_hook',
    'parent' => 'parent_nt_hook',
    'odnotip' => 'odnotip_nt_hook',
    'UID' => 'uid_nt_hook',
    'CID_Product' => 'template_CID_Product'
);

/**
 * Добавление в список каталогов спецпредложения товаров в 3 ячейки, лимит 3
 */
class PHPShopProduct_nt_IconElements extends PHPShopProductIconElements {

    function __construct() {
        parent::__construct();
    }

    function specMainIcon_nt($force = false, $category = null, $cell = 1, $limit = null, $line = false) {

        $this->limitspec = $limit;
        $this->cell = $cell;


        // Условие вывода из текущего каталога

        switch ($GLOBALS['SysValue']['nav']['nav']) {

            // Раздел списка товаров
            case "CID":

                if (!empty($category))
                    $where['category'] = " IN " . $category;

                elseif (PHPShopSecurity::true_num($this->PHPShopNav->getId())) {
                    $category = $this->PHPShopNav->getId();
                    $where['category'] = '=' . $category;
                }
                break;

            // Раздел подробного описания
            case "UID":
                if (empty($force))
                    return false;
                else
                    $where['category'] = '=' . $category;

                $where['id'] = '!=' . $this->PHPShopNav->getId();
                break;
        }

        // Кол-во товаров на странице
        if (empty($this->limitspec))
            $this->limitspec = $this->PHPShopSystem->getParam('new_num');

        // Перехват модуля
        $hook = $this->setHook(__CLASS__, __FUNCTION__);
        if ($hook)
            return $hook;


        // Завершение если отключен вывод
        if (empty($this->limitspec))
            return false;

        // Случаные товары для больших баз
        //$where['id']=$this->setramdom($limit);
        // Параметры выборки учета товара в новинках и наличия
        $where['newtip'] = "='1'";
        $where['enabled'] = "='1'";
        $where['parent_enabled'] = "='0'";

        // Проверка на единичную выборку
        if ($limit == 1) {
            $array_pop = true;
            $limit++;
        }

        // Память режима выборки новинок из каталогов
        $memory_spec = $this->memory_get('product_spec.' . $category);

        // Выборка новинок
        if ($memory_spec != 2 and $memory_spec != 3)
            $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limitspec), __FUNCTION__);

        // Проверка на единичную выборку
        if (!empty($array_pop) and is_array($this->dataArray)) {
            array_pop($this->dataArray);
        }

        if (!empty($this->dataArray) and is_array($this->dataArray)) {
            $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
            $this->set('specMainTitle', $this->lang('newprod'));

            // Заносим в память
            $this->memory_set('product_spec.' . $category, 1);
        } else {
            // Выборка спецпредложение
            unset($where['newtip']);
            $where['spec'] = "='1'";

            if ($memory_spec != 1 and $memory_spec != 3)
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'RAND()'), array('limit' => $this->limitspec), __FUNCTION__);

            // Проверка на единичную выборку
            if (!empty($array_pop) and is_array($this->dataArray)) {
                array_pop($this->dataArray);
            }

            if (!empty($this->dataArray) and is_array($this->dataArray)) {
                $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
                $this->set('specMainTitle', $this->lang('specprod'));

                // Заносим в память
                $this->memory_set('product_spec.' . $category, 2);
            } else {
                // Выборка последних добавленных товаров
                unset($where['id']);
                unset($where['spec']);
                $this->dataArray = $this->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => $this->limitspec), __FUNCTION__);

                // Проверка на единичную выборку
                if (!empty($array_pop) and is_array($this->dataArray)) {
                    array_pop($this->dataArray);
                }

                if (is_array($this->dataArray)) {
                    $this->product_grid($this->dataArray, $this->cell, $this->template, $line);
                    $this->set('specMainTitle', $this->lang('newprod'));

                    // Заносим в память
                    $this->memory_set('product_spec.' . $category, 3);
                }
            }
        }

        // Собираем и возвращаем таблицу с товарами
        return $this->compile();
    }

}

?>