<?php

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonrocket.ozonrocket_system"));

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
function actionUpdate()
{
    global $PHPShopModules;

    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    if ((int) $_POST['delivery_id_new'] > 0) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
        $PHPShopOrm->update(array('is_mod_new' => 2), array('id' => '=' . (int) $_POST['delivery_id_new']));
    }

    if (empty($_POST['hide_pvz_new'])) {
        $_POST['hide_pvz_new'] = 0;
    }
    if (empty($_POST['hide_postamat_new'])) {
        $_POST['hide_postamat_new'] = 0;
    }
    if (empty($_POST['show_delivery_time_new'])) {
        $_POST['show_delivery_time_new'] = 0;
    }
    if (empty($_POST['show_delivery_price_new'])) {
        $_POST['show_delivery_price_new'] = 0;
    }
    if (empty($_POST['dev_mode_new'])) {
        $_POST['dev_mode_new'] = 0;
    }
    
    $_POST['token_new'] = str_replace('=','%3D',$_POST['token_new']);
    
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonrocket.ozonrocket_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function actionStart()
{
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->addJSFiles('../modules/ozonrocket/admpanel/gui/script.gui.js');
    $PHPShopGUI->field_col = 4;

    // �������
    $data = $PHPShopOrm->select();

    // ������
    $status[] = [__('����� �����'), 0, $data['status']];
    $statusArray = (new PHPShopOrm('phpshop_order_status'))->getList(['id', 'name']);
    foreach ($statusArray as $statusParam) {
        $status[] = [$statusParam['name'], $statusParam['id'], $data['status']];
    }

    // ��������
    $PHPShopDeliveryArray = new PHPShopDeliveryArray(array('is_folder' => "!='1'", 'enabled' => "='1'"));

    $DeliveryArray = $PHPShopDeliveryArray->getArray();
    $deliveryValue[] = [__('�� �������'), 0, $data['delivery_id']];
    if (is_array($DeliveryArray)) {
        foreach ($DeliveryArray as $delivery) {
            if (strpos($delivery['city'], '.')) {
                $name = explode(".", $delivery['city']);
                $delivery['city'] = $name[0];
            }
            $deliveryValue[] = [$delivery['city'], $delivery['id'], $data['delivery_id']];
        }
    }

    $Tab1 = $PHPShopGUI->setField('�����', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], 300));
    $Tab1.= $PHPShopGUI->setField('Client id', $PHPShopGUI->setInputText(false, 'client_id_new', $data['client_id'], 300));
    $Tab1.= $PHPShopGUI->setField('Client secret', $PHPShopGUI->setInputText(false, 'client_secret_new', $data['client_secret'], 300));
    $Tab1.= $PHPShopGUI->setField('����� ����������', $PHPShopGUI->setCheckbox("dev_mode_new", 1,"�������� ������ �� �������� �����", $data["dev_mode"]));
    $Tab1.= $PHPShopGUI->setField('��������', $PHPShopGUI->setSelect('delivery_id_new', $deliveryValue, 300));
    $Tab1.= $PHPShopGUI->setField('����� �� ����� �� ���������', $PHPShopGUI->setInputText(false, 'default_city_new', $data['default_city'], 300));
    $Tab1.= $PHPShopGUI->setField('������ ��� ��������', $PHPShopGUI->setSelect('status_new', $status, 300));
    $Tab1.= $PHPShopGUI->setField('������ ������', $PHPShopGUI->setCheckbox("hide_pvz_new", 1,"������ ����� ������ ������", $data["hide_pvz"]));
    $Tab1.= $PHPShopGUI->setField('���������', $PHPShopGUI->setCheckbox("hide_postamat_new", 1, "������ ����� ���������", $data["hide_postamat"]));
    $Tab1.= $PHPShopGUI->setField('���� ��������', $PHPShopGUI->setCheckbox("show_delivery_time_new", 1, "���������� ���� ��������", $data["show_delivery_time"]));
    $Tab1.= $PHPShopGUI->setField('���� ��������', $PHPShopGUI->setCheckbox("show_delivery_price_new", 1, "���������� ���� ��������", $data["show_delivery_price"]));
    $Tab1.= $PHPShopGUI->setField('�������� �������', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="0.1" min="0" value="' . $data['fee'] . '" name="fee_new" style="width:300px;">');
    $Tab1.= $PHPShopGUI->setField('��� �������', $PHPShopGUI->setSelect('fee_type_new', array(array('%', 1, $data['fee_type']), array(__('���.'), 2, $data['fee_type'])), 300, null, false, $search = false, false, $size = 1));
    $Tab1.= $PHPShopGUI->setField('�������� ������ ��', $PHPShopGUI->setSelect('type_transfer_new', array(array('�������� ������������ �� ����� Ozon', 'DropOff', $data['type_transfer']), array('����� �� ������ �����������', 'PickUp', $data['type_transfer'])), 300,true, false, $search = false, false, $size = 1));
    $Tab1.= $PHPShopGUI->setField('����� ������ � ������', $PHPShopGUI->setInputText(false, 'btn_text_new', $data['btn_text'], 300));
    $Tab1.= $PHPShopGUI->setField('������������� ������ �������� ', $PHPShopGUI->setInputText(false, 'from_place_id_new', $data['from_place_id'], 300));
    
    $Tab1= $PHPShopGUI->setCollapse('���������',$Tab1);
    $Tab1.= $PHPShopGUI->setCollapse('��� � �������� �� ���������',
        $PHPShopGUI->setField('���, ��.', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="1" min="1" value="' . $data['weight'] . '" name="weight_new" style="width:300px; ">') .
        $PHPShopGUI->setField('������, ��.', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="1" min="1" value="' . $data['width'] . '" name="width_new" style="width:300px;">') .
        $PHPShopGUI->setField('������, ��.', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="1" min="1" value="' . $data['height'] . '" name="height_new" style="width:300px;">') .
        $PHPShopGUI->setField('�����, ��.', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="1" min="1" value="' . $data['length'] . '" name="length_new" style="width:300px;">')
    );

    $info = '<h4>��������� ������</h4>
    <ol>
        <li>
        � ������ �������� OZON Rocket ������� <a href="https://rocket.ozon.ru/" target="_blank">�������</a>, ������� � ���� <b>������ ��������</b>. ������ ������ <b>����������� ������</b>. � ������������� ���� 
            ���������� ����� <b>token</b>, �������� <kbd>token=uT7SIKAUsWUNKjgSpUTgdg%3D%3D</kbd>. ���������� ����������� �������� <b>token</b>, ��������, <kbd>uT7SIKAUsWUNKjgSpUTgdg%3D%3D</kbd>, � ���� 
            ����� �������� ������.
        </li>
        <li>
        � ������ �������� OZON Rocket ������� <b>�������</b>, ������� � ���� <b>���������� API</b>. ������� ����� API Key 
        � ���������� <b>Client id</b> � <b>Client secret</b> ������� � ��������������� ���� �������� ������.
        </li>
        <li>���� �� ����������� �������� ������� ������ - ���������� ���������� ������� <b>����� ����������</b></li>
        <li>��������� ��� � �������� �� ���������. ��� ����� ��������������, ���� � ������ �� ������ ��� � ��������.</li>
        <li>��������� ��������� ��������� �� ������ ����������.</li>
    </ol>';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // ����� �����������
    $Tab4 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true,false,true), array("����������", $Tab2), array("� ������", $Tab4));

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
