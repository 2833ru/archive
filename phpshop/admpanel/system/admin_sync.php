<?php

$TitlePage = __("���������������");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm;

    PHPShopObj::loadClass('order');

    // �������
    $data = $PHPShopOrm->select();
    $option = unserialize($data['1c_option']);
    $data = $PHPShopGUI->valid($data, 'update_name', 'update_descriptio', 'update_content');

    $PHPShopGUI->action_button['CRM ������'] = array(
        'name' => __('������ ��������'),
        'action' => 'report.crm',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-hourglass'
    );

    // ������ �������� ����
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./system/gui/system.gui.js');
    $PHPShopGUI->setActionPanel($TitlePage, false, array('CRM ������', '���������'));

    // �������� ������� �������
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('�� ������������'), 0, $option['1c_load_status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $option['1c_load_status']);

    // ��� �������� �������������
    $sort_value[] = array(__('��������� �������������� ��� ���������'), 0, $option['update_sort_type']);
    $sort_value[] = array(__('����� �������������� ��� ���������'), 1, $option['update_sort_type']);

    $PHPShopGUI->_CODE = $PHPShopGUI->setCollapse('������', $PHPShopGUI->setField("������ ��� ������������� ������������", $PHPShopGUI->setCheckbox('option[update_name]', 1, '������������ ������������', $option['update_name']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_description]', 1, '������� ��������', $option['update_description']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_content]', 1, '��������� ��������', $option['update_content']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_category]', 1, '������������ ���������', $option['update_category']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_sort]', 1, '���������c���� � ��������', $option['update_sort']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_option]', 1, '�������', $option['update_option']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_price]', 1, '����', $option['update_price']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_item]', 1, '�����', $option['update_item']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[seo_update]', 1, 'SEO ������', $option['seo_update'])
            ) .
            $PHPShopGUI->setField("���������c���� � ��������", $PHPShopGUI->setSelect('option[update_sort_type]', $sort_value, 300)
            ) .
            $PHPShopGUI->setField("������ ������", $PHPShopGUI->setSelect('option[1c_load_status]', $order_status_value, 300)
            , 1, '������ ����������� ������ ��� ������������ �������'));



    $PHPShopGUI->_CODE .= $PHPShopGUI->setCollapse('����� � ������', $PHPShopGUI->setField("������������� ���������", $PHPShopGUI->setCheckbox('1c_load_accounts_new', 1, '������������ ���� � ������� � ��������� �� 1�', $data['1c_load_accounts']) . '<br>' .
                    $PHPShopGUI->setCheckbox('1c_load_invoice_new', 1, '������������ ����-������� � ������� �� 1�', $data['1c_load_invoice']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[1c_load_status_email]', 1, 'E-mail ���������� ���������� � ����� ����������� ������������� ���������� �� 1�', $option['1c_load_status_email'])
                    , 1, '������������ ��������� ����������� �� 1� ��� ������������� ������� � ������� PHPShop Exchange.')
    );

    // ���� ����������
    $key_value[] = array(__('�������'), 'uid', $option['exchange_key']);
    $key_value[] = array(__('������� ���'), 'external', $option['exchange_key']);

    // �����������
    $auth_value[] = array(__('����� � ������'), 0, $option['exchange_auth']);
    $auth_value[] = array(__('��� �����'), 1, $option['exchange_auth']);

    if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
        $protocol = 'https://';
    } else
        $protocol = 'http://';

    $PHPShopGUI->_CODE .= $PHPShopGUI->setCollapse('CommerceML', $PHPShopGUI->setField("���� ����������", $PHPShopGUI->setSelect('option[exchange_key]', $key_value, 300) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_zip]', 1, '������ ������ ZIP', $option['exchange_zip']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_create]', 1, '��������� ����� ������', $option['exchange_create']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_create_category]', 1, '��������� ����� ��������', $option['exchange_create_category']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_image]', 1, '��������� ����� �����������', $option['exchange_image']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_log]', 1, '������ ����������', $option['exchange_log']) . '<br>'
            ) .
            $PHPShopGUI->setField("�����������", $PHPShopGUI->setSelect('option[exchange_auth]', $auth_value, 300)) .
            $PHPShopGUI->setField("��� �����", $PHPShopGUI->setInputText($protocol . $_SERVER['SERVER_NAME'] . '/1cManager/', 'option[exchange_auth_path]', $option['exchange_auth_path'], 400, '.php', false, false, 'secret_cml_path')));


    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "���������", "right", 70, "", "but", "actionUpdate.system.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $sidebarleft[] = array('title' => '���������', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './system/'));
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // �����
    $PHPShopGUI->Compile(2);
    return true;
}

/**
 * ����� ����������
 */
function actionSave() {

    // ���������� ������
    actionUpdate();
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // �������
    $data = $PHPShopOrm->select();
    $option = unserialize($data['1c_option']);
    $_POST['option']['exchange_auth_path'] = substr($_POST['option']['exchange_auth_path'], 0, 10);

    if ($_POST['option']['exchange_image'] == 1) {
        $_POST['option']['exchange_key'] = 'external';
        $_POST['option']['exchange_zip'] = 1;
    }

    if (is_array($_POST['option']))
        foreach ($_POST['option'] as $key => $val)
            $option[$key] = $val;



    // ����� ������� ��������
    if (is_array($_POST['option']))
        $option_null = array_diff_key($option, $_POST['option']);
    else
        $option_null = $option;

    if (is_array($option_null)) {
        foreach ($option_null as $key => $val)
            $option[$key] = 0;
    }

    $_POST['1c_load_accounts_new'] = $_POST['1c_load_accounts_new'] ? 1 : 0;
    $_POST['1c_load_invoice_new'] = $_POST['1c_load_invoice_new'] ? 1 : 0;
    $_POST['1c_option_new'] = serialize($option);

    // ��������������
    if (!empty($option['exchange_auth']) and ! empty($option['exchange_auth_path']) and ! file_exists($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/1cManager/' . $option['exchange_auth_path'] . '.php')) {
        copy($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/1cManager/cml.php', $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/1cManager/' . $option['exchange_auth_path'] . '.php');
    }

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));

    return array("success" => $action);
}

// ��������� �������
$PHPShopGUI->getAction();
?>