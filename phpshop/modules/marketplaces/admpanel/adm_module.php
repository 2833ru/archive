<?php

include_once dirname(__DIR__) . '/class/Marketplaces.php';

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.marketplaces.marketplaces_system"));

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate(number_format($option['version'], 1, '.', false));
    $PHPShopOrm->clean();
    $PHPShopOrm->update(['version_new' => $new_version]);
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->field_col = 3;

    $data = $PHPShopOrm->select();

    $options = unserialize($data['options']);
    
    $Tab1 = $PHPShopGUI->setField('������ ������ �����', $PHPShopGUI->setInputText(Marketplaces::getProtocol() . $_SERVER['SERVER_NAME'] . '/yml/?pas=', 'password_new', $data['password'], 534));
    $Tab1 .= $PHPShopGUI->setField('����� �������������', $PHPShopGUI->setCheckbox('use_params_new', 1, '�������� ����� ������������� � YML', $data['use_params']));
    $Tab1 .= $PHPShopGUI->setField('������ ��������� ��������', '<div id="marketplacesDescriptionShablon">
<textarea class="form-control marketplace-shablon" name="description_template_new" rows="3" style="max-width: 534px;height: 70px;">' . $data['description_template'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
    <input  type="button" value="' . __('��������') . '" onclick="marketplacesShablonAdd(\'@Content@\')" class="btn btn-default btn-sm">
    <input  type="button" value="' . __('������� ��������') . '" onclick="marketplacesShablonAdd(\'@Description@\')" class="btn btn-default btn-sm">
    <input  type="button" value="' . __('��������������') . '" onclick="marketplacesShablonAdd(\'@Attributes@\')" class="btn btn-default btn-sm">
<input  type="button" value="' . __('�������') . '" onclick="marketplacesShablonAdd(\'@Catalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="' . __('����������') . '" onclick="marketplacesShablonAdd(\'@Subcatalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="' . __('�����') . '" onclick="marketplacesShablonAdd(\'@Product@\',)" class="btn btn-default btn-sm">
    </div>
</div>
<script>function marketplacesShablonAdd(variable) {
    var shablon = $(".marketplace-shablon").val() + " " + variable;
    $(".marketplace-shablon").val(shablon);
}</script>', 1, '�������������� � �������� ������� �������������� ��������. ������������� ������������ ������ ��� ������ ���������� ���������� �������.');

    $Tab1.= $PHPShopGUI->setCollapse('��������� ���',
        $PHPShopGUI->setField('������� ��� Google Merchant', $PHPShopGUI->setSelect('options[price_google]', $PHPShopGUI->setSelectValue($options['price_google'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_google_fee]', $options['price_google_fee'], 100, '%')) .

        $PHPShopGUI->setField('������� ��� ����.������', $PHPShopGUI->setSelect('options[price_cdek]', $PHPShopGUI->setSelectValue($options['price_cdek'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_cdek_fee]', $options['price_cdek_fee'], 100, '%')) .

        $PHPShopGUI->setField('������� ��� AliExpress', $PHPShopGUI->setSelect('options[price_ali]', $PHPShopGUI->setSelectValue($options['price_ali'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_ali_fee]', $options['price_ali_fee'], 100, '%')) .

        $PHPShopGUI->setField('������� ��� ����������', $PHPShopGUI->setSelect('options[price_sbermarket]', $PHPShopGUI->setSelectValue($options['price_sbermarket'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_sbermarket_fee]', $options['price_sbermarket_fee'], 100, '%'))
    );
    
    // ����������
    $Tab2 = $PHPShopGUI->loadLib('tab_info', $data, '../modules/' . $_GET['id'] . '/admpanel/');

    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(["��������", $Tab1, true], ["����������", $Tab2], ["� ������", $Tab3]);

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);
    
    $_POST['options_new'] = serialize($_POST['options']);
    $PHPShopOrm->debug = false;
    
    if (empty($_POST["use_params_new"]))
        $_POST["use_params_new"] = 0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>