<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.visualcart.visualcart_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['memory_new']))
        $_POST['memory_new'] = 0;
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();

    $e_value[] = array('�� ��������', 0, $data['enabled']);
    $e_value[] = array('�����', 1, $data['enabled']);
    $e_value[] = array('������', 2, $data['enabled']);


    $Tab1 = $PHPShopGUI->setField('��������� �����', $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    $Tab1.=$PHPShopGUI->setField('������ �������', $PHPShopGUI->setCheckbox('memory_new', 1, '������� ������������� ������� � ����', $data['memory']));
    $Tab1.=$PHPShopGUI->setField('����� ������', $PHPShopGUI->setSelect('enabled_new', $e_value, 100));
    $Tab1.=$PHPShopGUI->setField('������ ������ ������', $PHPShopGUI->setInputText(false, 'pic_width_new', $data['pic_width'], 100, 'px'));

    $info = '��� ������������ ������� �������� ������� ������� ������� ������ "�� ��������" � � ������ ������ �������� ����������
        <kbd>@visualcart@</kbd> � ���� ������. ��� ����� ������ ���������� �������� ��������� ����, ������������� � ����� ��������� ���� (������� - ��������� - ������ - ���������� ��������),
        ������� ����� <kbd>@visualcart@</kbd> - ������ ���� ����� �������� ������� � ������ ��� �����.
        <p>��� �������������� ����� ������ �������������� ������� <code>phpshop/modules/visualcart/templates/</code></p>
';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay($serial);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1), array("����������", $Tab2), array("� ������", $Tab3),array("������������� ������", null,'?path=modules.dir.visualcart'));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>