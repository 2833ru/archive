<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.countcat.countcat_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;
    
    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST);

    if (!empty($_POST['clean'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $PHPShopOrm->update(array('count' => '0'), false, false);
    }
    
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();
    $PHPShopGUI->field_col = 1;


    $Tab2 = '��� ������ ���������� ������� � ����������� �������� ���������� <kbd>@catalogCount@</kbd> � ������ ������ ����������� 
        phpshop/templates/��� �������/catalog/pogcatalog_forma.tpl
        <p>��� ������ ��������� ������ ����� ���������� �������������� ������ ������� � ������������ � ���������� � ���� ������. 
        ��� ���������� ������������� ����� ��������� ����������� ����������� ���� � �������� ������������� ����������� � ��������
        <kbd>Count</kbd>.</p>
';


    $Tab1=$PHPShopGUI->setField('�����', $PHPShopGUI->setCheckbox("enabled_new", 1, '�������� ���������� ������ � ����� ��������', $data['enabled']).'<br>'.
    $PHPShopGUI->setCheckbox("clean", 1, '����������� ����� ������������� �������� ���-�� ������ � ����������', 0));

    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("���������", $Tab1), array("����������", $PHPShopGUI->setInfo($Tab2)), array("� ������", $Tab3));

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