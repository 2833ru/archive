<?php

include_once dirname(__DIR__) . '/class/PickPoint.php';

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.pickpoint.pickpoint_system"));

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;

    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopSystem;

    // �������
    $data = $PHPShopOrm->select();

    $PHPShopGUI->addJSFiles('../modules/pickpoint/admpanel/gui/script.gui.js?v=1.5');
    // ���������
    if ($PHPShopSystem->ifSerilizeParam('admoption.dadata_enabled')) {
        $PHPShopGUI->addJSFiles('./js/jquery.suggestions.min.js', './order/gui/dadata.gui.js');
        $PHPShopGUI->addCSSFiles('./css/suggestions.min.css');
    }

    $type_service_value = array(
        array('10001 - ��������, �������� ��������������� ������ ��� ������ ������ �� �����', 10001, $data['type_service']),
        array('10003 - �������� � ������� ������ �� �����, �.�. ���������� ������', 10003, $data['type_service'])
    );

    $type_reception_value = array(
        array('101 � ����� �������', 101, $data['type_reception']),
        array('102 � � ���� ������ ��', 102, $data['type_reception'])
    );

    $Tab1 = $PHPShopGUI->setField('�����', $PHPShopGUI->setInputText(false, 'login_new', $data['login'], 300));
    $Tab1 .= $PHPShopGUI->setField('������', $PHPShopGUI->setInput("password", 'password_new', $data['password'], false, 300));
    $Tab1 .= $PHPShopGUI->setField('����� ���������', $PHPShopGUI->setInputText(false, 'ikn_new', $data['ikn'], 300));
    $Tab1 .= $PHPShopGUI->setField('��������', $PHPShopGUI->setSelect('delivery_id_new', PickPoint::getDeliveryVariants($data['delivery_id']), 300));
    $Tab1 .= $PHPShopGUI->setField('����� ������', $PHPShopGUI->setInputText(false, 'name_new', $data['name'], 300));
    $Tab1 .= $PHPShopGUI->setField('���� �����', $PHPShopGUI->setSelect('type_service_new', $type_service_value, 400, true));
    $Tab1 .= $PHPShopGUI->setField('��� ������', $PHPShopGUI->setSelect('type_reception_new', $type_reception_value, 400, true));
    $Tab1 .= $PHPShopGUI->setField('����� ����� �����������', $PHPShopGUI->setInputText(false, 'city_from_new', $data['city_from'], 300));
    $Tab1 .= $PHPShopGUI->setField('������ ������ ����� �����������', $PHPShopGUI->setInputText(false, 'region_from_new', $data['region_from'], 300));
    $Tab1 .= $PHPShopGUI->setField('������ ��� ��������', $PHPShopGUI->setSelect('status_new', PickPoint::getStatusesVariants($data['status']), 300));
    $Tab1 .= $PHPShopGUI->setField('�������� �������', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="0.1" min="0" value="' . $data['fee'] . '" name="fee_new" style="width:300px;">');
    $Tab1 .= $PHPShopGUI->setField('��� �������', $PHPShopGUI->setSelect('fee_type_new', array(array('%', 1, $data['fee_type']), array('���.', 2, $data['fee_type'])), 300, null, false, $search = false, false, $size = 1));
    $Tab1 .= $PHPShopGUI->setCollapse('��� � �������� �� ���������', $PHPShopGUI->setField('���, ��.', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="1" min="1" value="' . $data['weight'] . '" name="weight_new" style="width:300px; ">') .
            $PHPShopGUI->setField('������, ��.', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="1" min="1" value="' . $data['width'] . '" name="width_new" style="width:300px;">') .
            $PHPShopGUI->setField('������, ��.', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="1" min="1" value="' . $data['height'] . '" name="height_new" style="width:300px;">') .
            $PHPShopGUI->setField('�����, ��.', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="1" min="1" value="' . $data['length'] . '" name="length_new" style="width:300px;">')
    );

    $info = '
        <h4>��������� ������</h4>
       <ol>
        <li>������������������ � <a href="https://pickpoint.ru/" target="_blank">PickPoint</a>, ��������� �������.</li>
        <li>������ ����� � ������ � API PickPoint, ��������� ����� ���������.</li>
        <li>������� ������ �������� ��� ������ ������.</li>
        <li>������ ����� � ������ ����� �����������, ������� ��� ������ � ���� �����.</li>
        <li>������� ������ ������ ��� �������� � ������ ������� PickPoint.</li>
        <li>��������� ��� � �������� �� ���������.</li>
        </ol>
        
       <h4>��������� ��������</h4>
        <ol>
        <li>� �������� �������������� �������� � �������� <kbd>��������� ��������� ��������</kbd> ��������� �������������� �������� ���������� ��������� �������� ��� ������. ����� "�� �������� ���������" ������ ���� �������.</li>
        <li>� �������� �������������� �������� � �������� <kbd>������ ������������</kbd> �������� <kbd>���</kbd> "���." � "������������"</li>
         <li>� �������� �������������� �������� � �������� <kbd>������ ������������</kbd> �������� <kbd>�������</kbd> "���." � "������������"</li>
        </ol>
';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay();

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true), array("����������", $Tab2), array("� ������", $Tab3));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>