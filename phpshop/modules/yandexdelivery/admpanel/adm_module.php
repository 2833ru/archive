<?php

include_once dirname(__DIR__) . '/class/include.php';

// SQL
$PHPShopOrm = new PHPShopOrm('phpshop_modules_yandexdelivery_system');

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

// ������� ����������
function actionUpdate() {
    global $PHPShopModules;

    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    // ��������
    if (isset($_POST['delivery_id_new'])) {
        if (is_array($_POST['delivery_id_new'])) {
            foreach ($_POST['delivery_id_new'] as $val) {
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
                $PHPShopOrm->update(array('is_mod_new' => 2), array('id' => '=' . intval($val)));
            }
            $_POST['delivery_id_new'] = @implode(',', $_POST['delivery_id_new']);
        }
    }
    if(empty($_POST['delivery_id_new']))
        $_POST['delivery_id_new'] = '';

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexdelivery.yandexdelivery_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->addJSFiles('../modules/yandexdelivery/admpanel/gui/script.gui.js?v=1.0');

    // �������
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('��������������� ����', $PHPShopGUI->setInputText(false, 'api_key_new', $data['api_key'], 300));
    $Tab1.= $PHPShopGUI->setField('����� ������.OAuth', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], 300));
    $Tab1.= $PHPShopGUI->setField('������������� ��������', $PHPShopGUI->setInputText(false, 'sender_id_new', $data['sender_id'], 300));
    $Tab1.= $PHPShopGUI->setField('������������� ������', $PHPShopGUI->setInputText(false, 'warehouse_id_new', $data['warehouse_id'], 300));
    $Tab1.= $PHPShopGUI->setField('������ ��� ��������', $PHPShopGUI->setSelect('status_new', YandexDelivery::getDeliveryStatuses($data['status']), 300));
    $Tab1.= $PHPShopGUI->setField('��������', $PHPShopGUI->setSelect('delivery_id_new', YandexDelivery::getDeliveryVariants($data['delivery_id']), 300));
    $Tab1.= $PHPShopGUI->setField('����������� ��������', $PHPShopGUI->setInputText('�� ���� ������', 'declared_percent_new', $data['declared_percent'], 300,'%'));
    $Tab1.= $PHPShopGUI->setField('�������� �������', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="0.1" min="0" value="' . $data['fee'] . '" name="fee_new" style="width:300px;">');
    $Tab1.= $PHPShopGUI->setField('��� �������', $PHPShopGUI->setSelect('fee_type_new', array(array('%', 1, $data['fee_type']), array('���.', 2, $data['fee_type'])), 300, null, false, $search = false, false, $size = 1));
    $Tab1.= $PHPShopGUI->setCollapse('��� � �������� �� ���������',
        $PHPShopGUI->setField('���, ��.', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="1" min="1" value="' . $data['weight'] . '" name="weight_new" style="width:300px; ">') .
        $PHPShopGUI->setField('������, ��.', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="1" min="1" value="' . $data['width'] . '" name="width_new" style="width:300px;">') .
        $PHPShopGUI->setField('������, ��.', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="1" min="1" value="' . $data['height'] . '" name="height_new" style="width:300px;">') .
        $PHPShopGUI->setField('�����, ��.', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="1" min="1" value="' . $data['length'] . '" name="length_new" style="width:300px;">')
    );

    $info = '<h4>��������� ������� ������ � ���������</h4>
       <ol>
        <li>����������������� � <a href="https://delivery.yandex.ru" target="_blank">������.��������</a> ��������� ��� ����������� ������ � �������� �������. �������� ���������/��������, ���������� "�������������" � ���� "������������� ��������".</li>
        <li>�������� <a href="https://yandex.ru/support/delivery-3/widgets/widgets.html#begin__api-key" target="_blank">��������������� ����</a>, ������� ��� � ���� "��������������� ����" �������� ������.</li>
        <li>��������  <a href="https://yandex.ru/dev/delivery-3/doc/dg/concepts/access.html#access__token" target="_blank">����� ������.OAuth</a> � ������� ��� � ���� "����� ������.OAuth".</li>
        <li>� ������ �������� ������.�������� �������� ���������/������, ���������� "�������������" ������ � ���� "������������� ������" �������� ������.</li>
        <li>������� ������ �������� ��� ������ ������.</li>
        <li>������� ������ ��� �������� ������ � ������ ������� ������.��������.</li>
        </ol>
        
       <h4>��������� ��������</h4>
        <ol>
        <li>� �������� �������������� �������� � �������� <kbd>��������� ��������� ��������</kbd> ��������� �������������� �������� ���������� ��������� �������� ��� ������. ����� "�� �������� ���������" ������ ���� �������.</li>
         <li>� �������� �������������� �������� � �������� <kbd>������ ������������</kbd> �������� <kbd>�������</kbd> "���." � "������������"</li>
        </ol>

';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // ����� �����������
    $Tab4 = $PHPShopGUI->setPay($serial = false, false, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true), array("����������", $Tab2), array("� ������", $Tab4));

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
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>