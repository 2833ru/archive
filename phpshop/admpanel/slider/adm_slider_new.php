<?php

$TitlePage = __('�������� ��������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['slider']);

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopModules,$TitlePage;

    $PHPShopGUI->setActionPanel($TitlePage, false, array('��������� � �������'));

    $data['enabled'] = 1;

    // ���������� �������� 1
    $Tab1 = $PHPShopGUI->setField("�����������", $PHPShopGUI->setIcon($data['image'], "image_new", false)) .
            $PHPShopGUI->setField("����", $PHPShopGUI->setInput("text", "link_new", $data['link'], "none", 300) . $PHPShopGUI->setHelp("������: /pages/info.html ��� http://google.com")) .
            $PHPShopGUI->setField("������", $PHPShopGUI->setRadio("enabled_new", 1, "��������", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "���������", $data['enabled'])) .
            $PHPShopGUI->setField("���������", $PHPShopGUI->setCheckbox("mobile_new", 1, "���������� ������ �� ��������� �����������", $data['mobile'])) .
            $PHPShopGUI->setField("���������", $PHPShopGUI->setInputText(false, 'num_new', $data['num'], 100)) .
            $PHPShopGUI->setField("��������", $PHPShopGUI->setTextarea("alt_new", $data['alt'])) .
            $PHPShopGUI->setField("�������", $PHPShopGUI->loadLib('tab_multibase', $data, 'catalog/'));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, null);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.slider.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    $_POST['image_new'] = iconAdd();

    // ����������
    $_POST['servers_new'] = "";
    if (is_array($_POST['servers']))
        foreach ($_POST['servers'] as $v)
            if ($v != 'null' and !strstr($v, ','))
                $_POST['servers_new'].="i" . $v . "i";

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ���������� ����������� 
function iconAdd() {
    global $PHPShopSystem;

    // ����� ����������
    $path = $GLOBALS['SysValue']['dir']['dir'] . '/UserFiles/Image/' . $PHPShopSystem->getSerilizeParam('admoption.image_result_path');

    // �������� �� ������������
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        $_FILES['file']['name'] = PHPShopString::toLatin(str_replace('.' . $_FILES['file']['ext'], '', PHPShopString::utf8_win1251($_FILES['file']['name']))) . '.' . $_FILES['file']['ext'];
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg', 'jpeg', 'svg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'])) {
                $file = $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // ������ ���� �� URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST['image_new'];
    }

    // ������ ���� �� ��������� ���������
    elseif (!empty($_POST['image_new'])) {
        $file = $_POST['image_new'];
    }

    if (empty($file))
        $file = '';

    // �������
    if ($PHPShopSystem->ifSerilizeParam('admoption.image_slider') and ! empty($file)) {
        require_once $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/phpshop/lib/thumb/phpthumb.php';

        // ��������� ����������
        $img_tw = $PHPShopSystem->getSerilizeParam('admoption.img_tw_s');
        $img_th = $PHPShopSystem->getSerilizeParam('admoption.img_th_s');
        $img_tw = empty($img_tw) ? 1440 : $img_tw;
        $img_th = empty($img_th) ? 300 : $img_th;
        $img_adaptive = $PHPShopSystem->getSerilizeParam('admoption.image_slider_adaptive');

        // ��������� ����������� (��������)
        $thumb = new PHPThumb($_SERVER['DOCUMENT_ROOT'] . $file);
        $thumb->setOptions(array('jpegQuality' => $PHPShopSystem->getSerilizeParam('admoption.width_kratko')));

        // ������������
        if (!empty($img_adaptive))
            $thumb->adaptiveResize($img_tw, $img_th);
        else
            $thumb->resize($img_tw, $img_th);

        $thumb->save($_SERVER['DOCUMENT_ROOT'] . $file);
    }

    return $file;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>