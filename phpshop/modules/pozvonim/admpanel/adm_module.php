<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
include("../class/pcurl.php");
include("../class/pozvonim.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

// ��������� ������
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// ��������
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL

$PHPShopSysOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.pozvonim.pozvonim_system"));

// ������� �����������
function actionRegister()
{
    global $PHPShopOrm, $PHPShopBase;
    $p = new Pozvonim();
    $data = array();
    foreach (array('email', 'phone', 'host', 'code', 'reset', 'token', 'restore') as $field) {
        if (isset($_POST[$field])) {
            $data[$field] = $_POST[$field];
        }
    }
    $oldData = $PHPShopOrm->select();
    if ($oldData['appId'] > 1) {
        echo '������ ��� ���������������';
        return false;
    }
    if (empty($data['token']) && $oldData['token']) {
        $data['token'] = $oldData['token'];
    }
    if ($result = $p->update($data)) {
        if ($PHPShopOrm->update($result, false, '')) {
            echo 'ok';
        } else {
            echo '������';
        }
    } else {
        echo $p->errorMessage ? $p->errorMessage : '������ ����������';
    }
    return false;
}

// ������� ��������� ����
function actionCode()
{
    global $PHPShopOrm, $PHPShopBase;
    $p = new Pozvonim();
    if (!isset($_POST['code'])) {
        echo '���������� ������� ��� �������';
        return false;
    }
    $code = $_POST['code'];

    $data = $PHPShopOrm->select();
    $data['code'] = $code;

    if ($data = $p->update($data)) {
        if ($PHPShopOrm->update($data, false, '')) {
            echo 'ok';
        } else {
            echo '������ ��������� ����';
        }
    } else {
        echo $p->errorMessage;
    }
    return false;
}

// ������� ��������� ����
function actionRestore()
{
    $p = new Pozvonim();
    if (!isset($_POST['email'])) {
        echo '���������� ������� email';
        return false;
    }
    if ($p->restoreTokenToEmail($_POST['email'])) {
        echo '��������� ��� ��������� �� ' . htmlspecialchars($_POST['email']);
    } else {
        echo $p->errorMessage;
    }
    return false;
}

function actionStart()
{
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm, $PHPShopSysOrm;

    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "��������� ������ ��������� ������";
    $PHPShopGUI->size = "500,450";

    //�������
    $data = $PHPShopOrm->select();
    $sysData = $PHPShopSysOrm->select();
    $isRegistered = $data['appId'] > 0;

    // ����������� ��������� ����
    $PHPShopGUI->setHeader("��������� ������ '������ ��������� ������ Pozvonim.com'", "���������", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    $Tab1 = '<fieldset ' . ($isRegistered ? 'disabled="disabled"' : '') . ' >';
    $Tab1 .= $PHPShopGUI->setField('Email', $PHPShopGUI->setInputText(false, 'email', $data['email'] ? $data['email'] : $sysData['adminmail2']));
    $Tab1 .= $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(false, 'phone', $data['phone'] ? $data['phone'] : $sysData['tel']));
    $Tab1 .= $PHPShopGUI->setField('�����', $PHPShopGUI->setInputText(false, 'host', $data['host'] ? $data['host'] : $_SERVER['HTTP_HOST']));
    $Tab1 .= $PHPShopGUI->setField('��������� ���',
        $PHPShopGUI->setInputText(false, 'token', $data['token'] ? $data['token'] : md5(uniqid('', true)))
    );
    if (!$isRegistered) {
        $Tab1 .= $PHPShopGUI->setButton('���������������� ������', '/phpshop/admpanel/icon/user_add.gif', 200, 50, 'left', 'pozvonim.register(this);return false;');
        $Tab1 .= $PHPShopGUI->setButton('������������ ��������� ���', '/phpshop/admpanel/icon/email_open_image.gif', 200, 50, 'left', 'pozvonim.restore(this);return false;');
    } else {
        $Tab1 .= '<br/>';
        $link = $PHPShopGUI->setLink(
            'http://appspozvonim.com/phpshop/login?id=' . $data['appId'] . '&token=' . md5($data['appId'] . $data['token']),
            '������� ������ ������� my.pozvonim.com'
        );
        $Tab1 .= $PHPShopGUI->setInfo('������ ��������������� � �������� � �������� <b>' . $data['email'] . '</b><br/>' .$link, false, '400px');
    }
    $Tab1 .= '</fieldset>';

    $Tab12 = '<fieldset>' . $PHPShopGUI->setField('��� �������', $PHPShopGUI->setTextarea('code', $data['code']));;
    if ($data['code']) {
        $Tab12 .= $PHPShopGUI->setInfo('<span style="font-weight:bold;color:green;">������ ���������� ��������� ��� �������</span>', false, '300px');
    }
    $Tab12 .= '<br/>' . $PHPShopGUI->setButton($data['code'] ? '���������' : '����������', '/phpshop/admpanel/icon/page_save.gif', 120, 50, 'none', 'pozvonim.saveCode(this);return false;');
    $Tab12 .= '</fieldset>';

    $Tab2 = $PHPShopGUI->setInfo('
           <b>��� ������ ������� ���������� ���������������� ���</b> ����� ����� ����������� �� ������� "�����������".<br/>
           <br/>
           � ������ <b>���� �� �������������� ������, �� �������� ���� ��������� ���</b> (��������� ������������� ����� ������������),
           �� ������ ������������ ��������� ��� �������� ���� email � ����� ������ "������������ ��������� ���".<br/>
           <br/>
           ���� �� ��� ���������������� � ������� pozvonim.com � <b>������ ������������ ������������ ��� �������</b>.
           �� ������ ������� ��� ������� � �������������� �������.
             <br/> <br/>
           �� ��������� ������ ��������� � ���������� <b>@leftMenu@</b>.
           ���� � ������� ��� ���������� <b>@leftMenu@</b> �� ���������� �������� ���������� <b>@pozvonim@</b> � �������� ������.<br/>
           ��������� � ������� ��� ������� ������������� � ������ �������� pozvonim.com.<br/>
           � ������ ������� ����� ������� �� ������ ������������ ����� ����������� �������.<br/>
           <p>��� ���������� ������ � ���������� <b>@leftMenu@</b> ��������������� 46 ������ � phpshop/modules/pozvonim/inc/pozvonim.inc.php </p>
   ', 270, '96%'
    );
    
        // ����� �����������
    $Tab3=$PHPShopGUI->setPay($serial,false);

    // ����� ����� ��������
    if (isset($data['code']) && $data['code'] != '') {
        $PHPShopGUI->setTab(array("��� �������", $Tab12, 300), array("�����������", $Tab1, 300), array("����������", $Tab2, 300),array("� ������",$Tab3,300));
    } else {
        $PHPShopGUI->setTab(array("�����������", $Tab1, 300), array("��� �������", $Tab12, 300), array("����������", $Tab2, 300),array("� ������",$Tab3,300));
    }

    // ����� ������ ��������� � ����� � �����
    $ContentFooter =
        $PHPShopGUI->setInput("hidden", "newsID", $data['id'], "right", 70, "", "but") .
        $PHPShopGUI->setInput("button", "", "�������", "right", 70, "return onCancel();", "but");

    //$ContentFooter .= $PHPShopGUI->setInput("submit", "editID", "�K", "right", 70, "", "but");//, "actionUpdate"
    $ContentFooter .= '<script src="/phpshop/modules/pozvonim/js/js.js"></script>';
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if ($UserChek->statusPHPSHOP < 2) {

    // ����� ����� ��� ������
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

    // ��������� �������
    $PHPShopGUI->getAction();

} else {
    $UserChek->BadUserFormaWindow();
}

?>