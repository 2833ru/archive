<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.avito.avito_system"));

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate(number_format($option['version'], 1, '.', false));
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('������ ������ XML �����', $PHPShopGUI->setInputText(
        'http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/avito/xml/appliances.php?pas=', 'password_new', $data['password'], 600)
    );
    $Tab1 .= $PHPShopGUI->setField('��� ���������', $PHPShopGUI->setInputText( false, 'manager_new', $data['manager'], 600));
    $Tab1 .= $PHPShopGUI->setField('������� ���������', $PHPShopGUI->setInputText( false, 'phone_new', $data['phone'], 600));
    $Tab1 .= $PHPShopGUI->setField('�����', $PHPShopGUI->setInputText( false, 'address_new', $data['address'], 600));
    $Tab1 .= $PHPShopGUI->setField('������ ��������� ��������', '<div id="avitotitleShablon">
<textarea class="form-control avito-shablon" name="preview_description_template_new" rows="3" style="max-width: 600px;height: 70px;">' . $data['preview_description_template'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
    <input  type="button" value="'.__('��������').'" onclick="AvitoShablonAdd(\'@Content@\')" class="btn btn-default btn-sm">
    <input  type="button" value="'.__('������� ��������').'" onclick="AvitoShablonAdd(\'@Description@\')" class="btn btn-default btn-sm">
    <input  type="button" value="'.__('��������������').'" onclick="AvitoShablonAdd(\'@Attributes@\')" class="btn btn-default btn-sm">
<input  type="button" value="'.__('�������').'" onclick="AvitoShablonAdd(\'@Catalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="'.__('����������').'" onclick="AvitoShablonAdd(\'@Subcatalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="'.__('�����').'" onclick="AvitoShablonAdd(\'@Product@\',)" class="btn btn-default btn-sm">
<input  type="button" value="'.__('�������').'" onclick="AvitoShablonAdd(\'@Article@\',)" class="btn btn-default btn-sm">
    </div>
</div>
<script>function AvitoShablonAdd(variable) {
    var shablon = $(".avito-shablon").val() + " " + variable;
    $(".avito-shablon").val(shablon);
}</script>');

    // ����������
    $Tab2 = $PHPShopGUI->loadLib('tab_info', $data,'../modules/'.$_GET['id'].'/admpanel/');

    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1,true),array("����������", $Tab2), array("� ������", $Tab3));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ����������
function actionUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $PHPShopOrm->debug = false;
    $_POST['region_data_new']=1;

    if (empty($_POST["use_params_new"]))
        $_POST["use_params_new"] = 0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>