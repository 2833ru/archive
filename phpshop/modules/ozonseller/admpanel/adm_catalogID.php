<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

function addOzonsellerTab($data) {
    global $PHPShopGUI, $category_ozonseller;

    // Проверка на каталог страниц
    if (isset($data['skin_enabled'])) {

        // Проверка на подкаталоги
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $data_categories = $PHPShopOrm->getOne(['id'], ['parent_to' => '=' . (int) $data['id']]);
        if (is_array($data_categories))
            return false;

        $OzonSeller = new OzonSeller();


        $PHPShopCategoryArray = new PHPShopCategoryOzonArray();
        $CategoryArray = $PHPShopCategoryArray->getArray();

        $tree_array = array();

        foreach ($PHPShopCategoryArray->getKey('parent_to.id', true) as $k => $v) {
            foreach ($v as $cat) {
                $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
            }
            $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
            $tree_array[$k]['id'] = $k;
            if ($k == $data['parent_to'])
                $tree_array[$k]['selected'] = true;
        }

        $GLOBALS['tree_array'] = &$tree_array;

        if (empty($data['category_ozonseller']))
            $selected = 'selected';
        else
            $tree_select = null;

        $tree_select = '<option value="0" ' . $selected . '>' . __('Ничего не выбрано') . '</option>';

        if ($k == $data['parent_to'])
            $selected = 'selected';

        $category_ozonseller = $data['category_ozonseller'];

        if (!empty($tree_array) and is_array($tree_array[0]['sub']))
            foreach ($tree_array[0]['sub'] as $k => $v) {
                $check = treegenerator_ozonseller(@$tree_array[$k], 1, $data['category_ozonseller']);
                $tree_select .= '<option value="' . $k . '"  disabled>' . $v . '</option>';
                $tree_select .= $check;
            }


        $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-live-search="true" data-container="body"  data-style="btn btn-default btn-sm" name="category_ozonseller_new" data-width="100%">' . $tree_select . '</select>';

        // Размещение
        $Tab1 = $PHPShopGUI->setCollapse('Размещение в OZON', $tree_select);


        // Характеристики локальные
        $sort = unserialize($data['sort']);
        if (is_array($sort)) {
            $PHPShopSort = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
            $sort_data = $PHPShopSort->getList($select = array('id,name,attribute_ozonseller'), array('id' => ' IN(' . implode(',', $sort) . ')'), array('order' => 'num,name'));
        }


        // Характеристики с Ozon
        $Tab2 = null;
        $sort_ozon_data = $OzonSeller->getTreeAttribute(["category_id" => [$data['category_ozonseller']], "attribute_type" => "REQUIRED"]);
        if (is_array($sort_ozon_data['result'][0]['attributes'])) {
            foreach ($sort_ozon_data['result'][0]['attributes'] as $sort_ozon_value) {
                $name = PHPShopString::utf8_win1251($sort_ozon_value['name']);

                $sort_select_value = [];
                if (is_array($sort_data)) {
                    $sort_select_value[] = array(__('Ничего не выбрано'), 0, $sort_ozon_value['id']);
                    foreach ($sort_data as $sort_value) {

                        if ($sort_ozon_value['id'] == $sort_value['attribute_ozonseller'])
                            $sel = 'selected';
                        else
                            $sel = null;

                        $sort_select_value[] = array($sort_value['name'], $sort_value['id'], $sel);
                    }
                }

                $help_list = $OzonSeller->getAttributesValues($sort_ozon_value['id'], $data['category_ozonseller'], null, true);
                if (count($help_list) > 0)
                    $help = '<a data-toggle="collapse" href="#collapseOzonValue' . $sort_ozon_value['id'] . '" aria-expanded="false" aria-controls="collapseExample">' . __('Доступные значения') . '</a><div class="collapse" id="collapseOzonValue' . $sort_ozon_value['id'] . '"><div class="well well-sm">' . implode('<br>', $help_list) . '</div></div>';
                else
                    $help = null;

                if (empty($sort_ozon_value['dictionary_id']) and $name == 'Название') {
                    continue;
                    //$sort_ozon_value['description'] = __('Будет заполнено автоматически из имени товара.');
                }

                $Tab2 .= $PHPShopGUI->setField($name, $PHPShopGUI->setSelect('attribute_ozonseller[' . $sort_ozon_value['id'] . ']', $sort_select_value, '100%') . $PHPShopGUI->setHelp(PHPShopString::utf8_win1251($sort_ozon_value['description']) . '<br>' . $help,false,false),1,  $sort_ozon_value['id'], null,'control-label', false);
            }
        } else {
            $Tab2 = $PHPShopGUI->setHelp('Выберите размещение в OZON для сопоставления характеристик и перегрузите страницу');
        }


        // Сопоставление характеристик
        $Tab2 = $PHPShopGUI->setCollapse('Сопоставление характеристик с OZON', $Tab2);

        $PHPShopGUI->addTabSeparate(array("OZON", $Tab1 . $Tab2, true));
    }
}

function treegenerator_ozonseller($array, $i, $curent) {
    global $tree_array, $category_ozonseller;
    $del = '&brvbar;&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree_select = $check = false;

    $del = str_repeat($del, $i);
    if (!empty($array) and is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator_ozonseller($tree_array[$k], $i + 1, $k);

            if ($k == $category_ozonseller)
                $selected = 'selected';
            else
                $selected = null;

            if (empty($check)) {
                $disabled = null;
                $tree_select .= '<option value="' . $k . '" ' . $selected . $disabled . '>' . $del . $v . '</option>';

                $i = 1;
            } else {
                 $disabled = ' disabled ';
                 $tree_select .= '<option value="' . $k . '" ' . $selected . $disabled . '>' . $del . $v . '</option>';
            }

            $tree_select .= $check;
        }
    }
    return $tree_select;
}


function updateOzonseller() {
    if (is_array($_POST['attribute_ozonseller'])) {
        $PHPShopSort = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);
        $PHPShopSort->debug = false;
        foreach ($_POST['attribute_ozonseller'] as $k => $v) {
            if (!empty($v)) {
                
                // Очистка старых значений
                $PHPShopSort->update(['attribute_ozonseller_new' => null], ['attribute_ozonseller' => '=' . intval($k)]);
                
                // Новое значение
                $PHPShopSort->update(['attribute_ozonseller_new' => $k], ['id' => '=' . intval($v)]);
            }
        }
    }
}

class PHPShopCategoryOzonArray extends PHPShopArray {

    function __construct($sql = false, $select = ["id", "name", "parent_to"]) {
        global $PHPShopModules;

        $this->objSQL = $sql;
        $GLOBALS['SysValue']['my']['array_limit'] = 1000000;
        $this->cache = false;
        $this->debug = false;
        $this->ignor = false;
        $this->order = ['order' => 'name'];
        $this->objBase = $PHPShopModules->getParam("base.ozonseller.ozonseller_categories");
        parent::__construct(...$select);
    }

}

$addHandler = array(
    'actionStart' => 'addOzonsellerTab',
    'actionDelete' => false,
    'actionUpdate' => 'updateOzonseller'
);
?>