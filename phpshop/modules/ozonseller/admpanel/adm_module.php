<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("category");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_system"));
$OzonSeller = new OzonSeller();

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

// ���������� ������ ���������
function treegenerator($array, $i, $curent, $dop_cat_array) {
    global $tree_array;
    $del = '&brvbar;&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree_select = $tree_select_dop = $check = false;

    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator($tree_array[$k], $i + 1, $k, $dop_cat_array);

            $selected = null;
            $disabled = null;

            if (is_array($dop_cat_array))
                foreach ($dop_cat_array as $vs) {
                    if ($k == $vs)
                        $selected = "selected";
                }

            if (empty($check['select'])) {
                $tree_select .= '<option value="' . $k . '" ' . $selected . $disabled . '>' . $del . $v . '</option>';

                $i = 1;
            } else {
                $tree_select .= '<option value="' . $k . '" ' . $selected . ' disabled>' . $del . $v . '</option>';
            }

            $tree_select .= $check['select'];
        }
    }
    return array('select' => $tree_select);
}

// ������� ����������
function actionUpdate() {
    global $PHPShopModules, $OzonSeller, $PHPShopOrm;

    // ������������� ���������
    if (!empty($_POST['load']))
        actionUpdateCategory();

    // ������������� ������ ��������
    $PHPShopOrm->updateZeroVars('link_new');

    // �����s
    if (is_array($_POST['warehouse'])) {


        $getWarehouse = $OzonSeller->getWarehouse();
        if (is_array($getWarehouse['result']))
            foreach ($getWarehouse['result'] as $warehouse) {

                if (is_array($_POST['warehouse'])) {
                    foreach ($_POST['warehouse'] as $val)
                        if ($warehouse['warehouse_id'] == $val)
                            $_POST['warehouse_new'][] = ['name' => PHPShopString::utf8_win1251($warehouse['name']), 'id' => $warehouse['warehouse_id']];
                }
            }


        $_POST['warehouse_new'] = serialize($_POST['warehouse_new']);
    } else
        $_POST['warehouse_new'] = "";

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);


    // ���������
    if (is_array($_POST['categories']) and $_POST['categories'][0] != 'null') {

        $cat_array = array();
        foreach ($_POST['categories'] as $v)
            if (!empty($v) and ! strstr($v, ','))
                $cat_array[] = $v;

        if (is_array($cat_array)) {
            $where = array('category' => ' IN ("' . implode('","', $cat_array) . '")');
            $PHPShopOrmProducts = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
            $PHPShopOrmProducts->debug = false;

            if ($_POST['enabled_all'] == 1)
                $PHPShopOrmProducts->update(array('export_ozon_new' => (int) $_POST['enabled_all']), $where);
            else
                $PHPShopOrmProducts->update(array('export_ozon_new' => (int) $_POST['enabled_all'], 'export_ozon_task_status_new' => '', 'export_ozon_id_new' => 0, 'export_ozon_task_id_new' => 0), $where);
        }
    }

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function setChildrenCategory($tree_array, $parent_to) {
    global $PHPShopModules;

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));

    if (is_array($tree_array)) {
        foreach ($tree_array as $category) {
            $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($category['title']), 'id_new' => $category['category_id'], 'parent_to_new' => $parent_to]);

            if (is_array($category['children'])) {
                foreach ($category['children'] as $children) {
                    $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($children['title']), 'id_new' => $children['category_id'], 'parent_to_new' => $category['category_id']]);
                    if (is_array($children['children']))
                        setChildrenCategory($children['children'], $children['category_id']);
                }
            }
        }
    }
}

// ������������� ���������
function actionUpdateCategory() {
    global $PHPShopModules, $OzonSeller;

    $getTree = $OzonSeller->getTree(['category_id' => 0]);
    $tree_array = $getTree['result'];

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));
    $PHPShopOrm->debug = false;

    // �������
    $PHPShopOrm->query('TRUNCATE TABLE `' . $PHPShopModules->getParam("base.ozonseller.ozonseller_categories") . '`');

    if (is_array($tree_array)) {
        foreach ($tree_array as $category) {
            $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($category['title']), 'id_new' => $category['category_id'], 'parent_to_new' => 0]);

            if (is_array($category['children'])) {
                foreach ($category['children'] as $children) {
                    $PHPShopOrm->insert(['name_new' => PHPShopString::utf8_win1251($children['title']), 'id_new' => $children['category_id'], 'parent_to_new' => $category['category_id']]);
                    if (is_array($children['children']))
                        setChildrenCategory($children['children'], $children['category_id']);
                }
            }
        }
    }
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $OzonSeller, $TitlePage, $select_name;

    $PHPShopGUI->field_col = 4;
    $PHPShopGUI->addJSFiles('../modules/ozonseller/admpanel/gui/order.gui.js');

    // �������
    $data = $PHPShopOrm->select();

    if ($data['token'] !== '' and $data['client_id'] !== '') {
        $PHPShopGUI->action_button['�������������� ������'] = [
            'name' => '�������������� ������',
            'class' => 'btn btn-default btn-sm navbar-btn ozon-export',
            'type' => 'button',
            'icon' => 'glyphicon glyphicon-export'
        ];
        $PHPShopGUI->setActionPanel($TitlePage, $select_name, ['�������������� ������', '��������� � �������']);
    }

    // ������
    $status[] = [__('����� �����'), 0, $data['status']];
    $statusArray = (new PHPShopOrm('phpshop_order_status'))->getList(['id', 'name']);
    foreach ($statusArray as $statusParam) {
        $status[] = [$statusParam['name'], $statusParam['id'], $data['status']];
    }

    // �������� ������� �������
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('����� �����'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status) {
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);
        }


    $Tab1 .= $PHPShopGUI->setField('Client id', $PHPShopGUI->setInputText(false, 'client_id_new', $data['client_id'], '100%'));
    $Tab1 .= $PHPShopGUI->setField('API key', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], '100%'));
    $catOption .= $PHPShopGUI->setField('������ YML-�����', $PHPShopGUI->setInputText(false, 'password_new', $data['password'], '100%', $PHPShopGUI->setLink('http://' . $_SERVER['SERVER_NAME'] . '/yml/?marketplace=ozon&pas=' . $data['password'], '<span class=\'glyphicon glyphicon-eye-open\'></span>', '_blank', false, __('�������'))));
    $Tab1 .= $PHPShopGUI->setField('������ ������ ������', $PHPShopGUI->setSelect('status_new', $order_status_value, '100%'));

    // ������� �������������� ��������
    $order_status_import_value[] = array(__('������ �� �������'), 0, $data['status_import']);
    foreach ($OzonSeller->status_list as $k => $status_val) {
        $order_status_import_value[] = array(__($status_val), $k, $data['status_import']);
    }
    $Tab1 .= $PHPShopGUI->setField('������ ������ � OZON ��� �������������� ��������', $PHPShopGUI->setSelect('status_import_new', $order_status_import_value, '100%'));


    // ��������
    $PHPShopDeliveryArray = new PHPShopDeliveryArray();

    $DeliveryArray = $PHPShopDeliveryArray->getArray();
    if (is_array($DeliveryArray))
        foreach ($DeliveryArray as $delivery) {

            // ������� ������������
            if (strpos($delivery['city'], '.')) {
                $name = explode(".", $delivery['city']);
                $delivery['city'] = $name[0];
            }

            $delivery_value[] = array($delivery['city'], $delivery['id'], $data['delivery'], 'data-subtext="' . $delivery['price'] . '"');
        }

    $Tab1 .= $PHPShopGUI->setField('��������', $PHPShopGUI->setSelect('delivery_new', $delivery_value, '100%'));

    $catOption .= $PHPShopGUI->setField('���� ����������', $PHPShopGUI->setRadio("type_new", 1, "ID ������", $data['type']) . $PHPShopGUI->setRadio("type_new", 2, "������� ������", $data['type']));

    $PHPShopOrmCat = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_categories"));
    $category = $PHPShopOrmCat->select(['COUNT(`id`) as num']);

    $Tab1 .= $PHPShopGUI->setField('���� ���������', $PHPShopGUI->setText($category['num'] . ' ' . __('������� � ��������� ����'), null, false, false) . '<br>' . $PHPShopGUI->setCheckbox('load', 1, '�������� ���� ��������� ������� ��� OZON', 0));

    $Tab1 .= $PHPShopGUI->setField('������ �� �����', $PHPShopGUI->setCheckbox('link_new', 1, '�������� ������ �� ����� � OZON', $data['link']));



    if ($data['fee_type'] == 1) {
        $status_pre = '-';
    } else {
        $status_pre = '+';
    }

    $data['warehouse'] = unserialize($data['warehouse']);

    $getWarehouse = $OzonSeller->getWarehouse();
    if (is_array($getWarehouse['result']))
        foreach ($getWarehouse['result'] as $warehouse) {

            if (is_array($data['warehouse'])) {
                $selected = null;
                foreach ($data['warehouse'] as $val)
                    if ($warehouse['warehouse_id'] == $val['id'])
                        $selected = "selected";
            }

            $warehouse_value[] = array(PHPShopString::utf8_win1251($warehouse['name'], true), $warehouse['warehouse_id'], $selected);
        }


    $Tab3 = $PHPShopGUI->setCollapse('����', $PHPShopGUI->setField('������� ��� OZON', $PHPShopGUI->setSelect('price_new', $PHPShopGUI->setSelectValue($data['price'], 5), 100)) .
            $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText($status_pre, 'fee_new', $data['fee'], 100, '%')) .
            $PHPShopGUI->setField('��������', $PHPShopGUI->setRadio("fee_type_new", 1, "���������", $data['fee_type']) . $PHPShopGUI->setRadio("fee_type_new", 2, "���������", $data['fee_type']))
    );


    $PHPShopCategoryArray = new PHPShopCategoryArray($where);
    $CategoryArray = $PHPShopCategoryArray->getArray();
    $GLOBALS['count'] = count($CategoryArray);

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

    // �����������
    $dop_cat_array = preg_split('/,/', $data['categories'], -1, PREG_SPLIT_NO_EMPTY);

    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $k, $dop_cat_array);

            // �����������
            $selected = null;
            if (is_array($dop_cat_array))
                foreach ($dop_cat_array as $vs) {
                    if ($k == $vs)
                        $selected = "selected";
                }


            if (empty($tree_array[$k]))
                $disabled = null;
            else
                $disabled = ' disabled';

            $tree_select .= '<option value="' . $k . '"  ' . $selected . $disabled . '>' . $v . '</option>';

            $tree_select .= $check['select'];
        }


    $tree_select = '<select class="selectpicker show-menu-arrow hidden-edit" data-live-search="true" data-container="body" data-style="btn btn-default btn-sm" name="categories[]"  data-width="100%" multiple>' . $tree_select . '</select>';

    $catOption .= $PHPShopGUI->setField("����������", $tree_select . $PHPShopGUI->setCheckbox("categories_all", 1, "������� ��� ���������?", 0), 1, '�������� ��������������. ��������� �� �����������.');
    $catOption .= $PHPShopGUI->setField("����� � OZON", $PHPShopGUI->setRadio("enabled_all", 1, "���.", 1) . $PHPShopGUI->setRadio("enabled_all", 0, "����.", 1));

    $Tab1 .= $PHPShopGUI->setField("�������� �����", $PHPShopGUI->setSelect('warehouse[]', $warehouse_value, '100%', false, false, false, false, 1, true), 1, 'OZON ������, �������������� � ������� ������� ��������');

    $Tab1 = $PHPShopGUI->setCollapse('���������', $Tab1);
    $Tab1 .= $PHPShopGUI->setCollapse('������', $catOption);

    // ����������
    $Tab2 = $PHPShopGUI->loadLib('tab_info', $data, '../modules/' . $_GET['id'] . '/admpanel/');

    // ����� �����������
    $Tab4 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1 . $Tab3, true, false, true), array("����������", $Tab2), array("� ������", $Tab4));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("hidden", "locale_ozon_start_export", __('�� ������������� ������ ��������� ������� � OZON?')) .
            $PHPShopGUI->setInput("hidden", "locale_ozon_stop_export", __('�� ������������� ������ �������� ������� ������?')) .
            $PHPShopGUI->setInput("hidden", "locale_ozon_export", __('������� ������� � OZON')) .
            $PHPShopGUI->setInput("hidden", "locale_ozon_export_done", __('������� � OZON ��������, ��������� % �������')) .
            $PHPShopGUI->setInput("hidden", "stop", 0) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * ������ ���������
 */
function actionCategorySearch() {

    $PHPShopOrm = new PHPShopOrm('phpshop_modules_ozonseller_categories');
    $data = $PHPShopOrm->getList(['*'], ['name' => " LIKE '%" . PHPShopString::utf8_win1251($_POST['words'], true) . "%'", 'parent_to' => '!=0']);
    if (is_array($data)) {
        foreach ($data as $row) {

            $parent = $PHPShopOrm->getOne(['name'], ['id' => '=' . $row['parent_to']])['name'];

            $child = $PHPShopOrm->getOne(['name'], ['parent_to' => '=' . $row['id']])['name'];
            if ($child)
                continue;

            $result .= '<a href=\'#\' class=\'select-search\' data-id=\'' . $row['id'] . '\'  data-name=\'' . PHPShopString::utf8_win1251($row['name'], true) . '\'    >' . $parent . ' &rarr; ' . $row['name'] . '</a><br>';
        }
        $result .= '<button type="button" class="close pull-right" aria-label="Close"><span aria-hidden="true">&times;</span></button>';

        exit($result);
    } else
        exit();
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
