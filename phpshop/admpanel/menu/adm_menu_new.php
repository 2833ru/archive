<?php

$TitlePage = __('�������� ���������� �����');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['menu']);

// ��������� �����
function setSelectChek($n) {
    $i = 1;
    while ($i <= 10) {
        if ($n == $i)
            $s = "selected";
        else
            $s = "";
        $select[] = array($i, $i, $s);
        $i++;
    }
    return $select;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $TitlePage, $PHPShopModules;

    $PHPShopGUI->field_col = 3;

    // �������
    $data['flag'] = 1;
    $data['name'] = __('����� ����');
    $data = $PHPShopGUI->valid($data, 'content', 'num', 'element', 'dir', 'servers');

    $PHPShopGUI->setActionPanel($TitlePage, false, array('��������� � �������'));

    // �������� 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '350';
    $oFCKeditor->Value = $data['content'];

    $Select1 = setSelectChek($data['num']);

    $Select2[] = array("�����", 0, $data['element']);
    $Select2[] = array("������", 1, $data['element']);

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField("��������", $PHPShopGUI->setInput("text", "name_new", $data['name'])) .
            $PHPShopGUI->setField("������", $PHPShopGUI->setCheckbox("flag_new", 1, null, $data['flag'])) .
            $PHPShopGUI->setField("�������", $PHPShopGUI->setSelect("num_new", $Select1, 150)) .
            $PHPShopGUI->setField("�����", $PHPShopGUI->setSelect("element_new", $Select2, 150, true)) .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField("���������", $PHPShopGUI->setInput("text", "dir_new", $data['dir']) .
                    $PHPShopGUI->setHelp('* ������: /page/,/news/. ����� ������� ��������� ������� ����� �������.'));

    $Tab1 .= $PHPShopGUI->setField("�������", $PHPShopGUI->loadLib('tab_multibase', $data, 'catalog/'));

    $Tab1 = $PHPShopGUI->setCollapse('����������', $Tab1);

    $Tab1 .= $PHPShopGUI->setCollapse("����������", $oFCKeditor->AddGUI());

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true, false, true));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.menu.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // ����������
    $_POST['servers_new'] = "";
    if (is_array($_POST['servers']))
        foreach ($_POST['servers'] as $v)
            if ($v != 'null' and ! strstr($v, ','))
                $_POST['servers_new'] .= "i" . $v . "i";

    if (empty($_POST['flag_new']))
        $_POST['flag_new'] = 0;

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->insert($_POST);

    header('Location: ?path=' . $_GET['path']);
}

// ��������� ������� 
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>