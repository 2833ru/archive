<?php

$TitlePage = __('�������� ������� ������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment_systems']);

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules;

    // ��������� ������
    $data['name'] = '����� ������ ������';
    $data['enabled'] = 1;

    // ������ �������� ����
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("������� ������") . ' / ' . $data['name'], null, array('������� � �������������', '��������� � �������'));

    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('message_new');
    $oFCKeditor->Height = '300';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['message'];

    // ���������� 
    $Tab1 = $PHPShopGUI->setCollapse(__('����������'), $PHPShopGUI->setField("������������:", $PHPShopGUI->setInput("text", "name_new", $data['name'])) .
            $PHPShopGUI->setField("�����:", $PHPShopGUI->setRadio("enabled_new", 1, "����������", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "������", $data['enabled'])) .
            $PHPShopGUI->setField("���������:", $PHPShopGUI->setInputText(null, "num_new", $data['num'], '100')) .
            $PHPShopGUI->setField("����������� ������:", $PHPShopGUI->setCheckbox("yur_data_flag_new", 1, "����������� ���������", $data['yur_data_flag'])) .
            $PHPShopGUI->setField("��� �����������:", $PHPShopGUI->setSelect("path_new", $PHPShopGUI->loadLib('GetTipPayment', $data['path']), 350))
    );

    $Tab1.=$PHPShopGUI->setField("������:", $PHPShopGUI->setIcon($data['icon'], "icon_new", false));

    $Tab1.=$PHPShopGUI->setCollapse(__('��������� ����� ������'), $PHPShopGUI->setField("���������:", $PHPShopGUI->setInput("text", "message_header_new", $data['message_header'])) .
            $PHPShopGUI->setField("���������", $oFCKeditor->AddGUI()));


    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, null);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, 350));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.order.edit");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    $_POST['icon_new'] = iconAdd();

    // ������������� ������ ��������
    $PHPShopOrm->updateZeroVars('yur_data_flag_new');

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    if ($_POST['saveID'] == '������� � �������������')
        header('Location: ?path=' . $_GET['path'] . '&id=' . $action);
    else
        header('Location: ?path=' . $_GET['path']);

    return $action;
}

function iconAdd() {

    // ����� ����������
    $path = '/UserFiles/Image/';

    // �������� �� ������������
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'])) {
                $file = $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // ������ ���� �� URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST['icon_new'];
    }

    // ������ ���� �� ��������� ���������
    elseif (!empty($_POST['icon_new'])) {
        $file = $_POST['icon_new'];
    }


    if (!empty($file)) {
        return $file;
    }
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>