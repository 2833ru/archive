<?php

$TitlePage = __('�������� �������');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['servers']);

// ����� ������� �������
function GetSkinList($skin) {
    global $PHPShopGUI;
    $dir = "../templates/";

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (file_exists($dir . '/' . $file . "/main/index.tpl")) {

                    if ($skin == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file != "." and $file != ".." and !strpos($file, '.'))
                        $value[] = array($file, $file, $sel);
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('skin_new', $value);
}

// ��������� ���
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $PHPShopSystem;

    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("�������� �������"), false, array('��������� � �������'));

    // �������
    $data['name'] = '����� �������';
    $data['enabled'] = 1;

    $Tab1 = $PHPShopGUI->setField("��������:", $PHPShopGUI->setInputText(null, "name_new", $data['name'], 300));
    $Tab1 .= $PHPShopGUI->setField("�����:", $PHPShopGUI->setInputText('http://', "host_new", $data['host'], 300), false);
    $Tab1.=$PHPShopGUI->setField("������", $PHPShopGUI->setRadio("enabled_new", 1, "���.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "����.", $data['enabled']));
       $Tab1.=$PHPShopGUI->setField('��������� (Title)', $PHPShopGUI->setTextarea('title_new', $data['title'], false, false, 100));
    $Tab1.=$PHPShopGUI->setField('�������� (Description)', $PHPShopGUI->setTextarea('descrip_new', $data['descrip'], false, false, 100));

    if (empty($data['skin']))
        $data['skin'] = $PHPShopSystem->getParam('skin');
    $Tab1.=$PHPShopGUI->setField('������', GetSkinList($data['skin']));

    $sql_value[] = array('�� �������', 0, 0);
    $sql_value[] = array('�������� ��� ��������', 1, 0);
    $sql_value[] = array('��������� ��� ��������', 2, 0);

    $Tab1.=$PHPShopGUI->setField("�������� ���������", $PHPShopGUI->setSelect('sql', $sql_value));

    // ������ ������ �� ��������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true),array("����������", $PHPShopGUI->loadLib('tab_showcase', false, './system/')));


    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "��", "right", 70, "", "but", "actionInsert.servers.create");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ������� ����������
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules, $PHPShopBase;

    $License = @parse_ini_file_true("../../license/" . PHPShopFile::searchFile("../../license/", 'getLicense'), 1);
    $_POST['code_new'] = md5($License['License']['Serial'] . str_replace('www.','',getenv('SERVER_NAME')) . $_POST['host_new'] . $PHPShopBase->getParam("connect.host") . $PHPShopBase->getParam("connect.user_db") . $PHPShopBase->getParam("connect.pass_db"));

    // �������� ������
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->insert($_POST);

    // �������
    switch ($_POST['sql']) {

        case 1:
            $PHPShopOrmCat = new $PHPShopOrm();
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['categories'] . ' set `servers`=CONCAT("i' . $action . 'i", `servers` )');
            break;

        case 2:
            $PHPShopOrmCat = new $PHPShopOrm();
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['categories'] . ' set `servers`=REPLACE(`servers`,"i' . $action . 'i",  "")');
            break;
    }


    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>
