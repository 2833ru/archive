<?php

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// ��������� ������
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// ��������
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cleversite.cleversite_system"));


// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug=false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

function actionStart() {
    global $PHPShopGUI,$_classPath,$PHPShopOrm;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="��������� ������ Cleversite";
    $PHPShopGUI->size="500,450";

    // �������
    $data = $PHPShopOrm->select();
    @extract($data);  

    $e_value[]=array('JQuery cleversite 2.0',1,$s1);
    $e_value[]=array('cleversite 1.0',2,$s2);

    // ����������� ��������� ����
    $PHPShopGUI->setHeader("��������� ������ 'Cleversite'","��������� �����������",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

	$ContentField1=$PHPShopGUI->setInputText('�����:', 'client_new',$client,'100%');
	$ContentField1.=$PHPShopGUI->setInput('password', 'password_new',$password, "none", '100%', "return true", false, false, '������:', false);
	$Tab1 = $PHPShopGUI->setField("������ ��� �����������", $ContentField1);
	$Tab1.=$PHPShopGUI->setField('����� �����:', $PHPShopGUI->setInputText(null, 'site_new',$site,'100%','* ������ ����� ������ ��������� � ��������� ���� � ������ �������� ������� �����, �� ������� ��������� ���������� ������'),'none');
	
    $Info='<h4>��� ������� ������� ������ �������� ����������:</h4>
        <ol>
        <li> ����������������� �� ����� <a href="http://cleversite.ru/" target="_blank"> cleversite.ru</a>
		<li> �������� �� ����� ������ � ���������������� �������.
		<li> �������� � ������ �������� ����� ������� �� ������ ���������� �� ����� �����.
        <li> ���������� ��� ����� � �������� ��� � ���� "�����" �� ������� "��������" �������� ���� ��������� ������.
		<li> ���������� ��� ������ � �������� ��� � ���� "������" �� ������� "��������" �������� ���� ��������� ������.
		<li> ������� ����� �����, ������� �� �������� � ��������� ������� �������� �� ����� <a href="http://cleversite.ru/" target="_blank">cleversite.ru</a> 
		� �������� ��� � ���� "����" �� ������� "��������" �������� ���� ��������� ������.
		<li> ��������� ��������� ���� ������.
		</ol>';
	$Tab2=$PHPShopGUI->setInfo($Info, '200px', '95%');
		
    // ����� �����������
    $Tab3=$PHPShopGUI->setPay($serial,false);

    $About='���� � ��� �������� �������, �� ������ �������� ��������� �� <a href="http://cleversite.ru/" target="_blank">����� �����</a> � ������-����������� ��� ��������� ��������� �� help@cleversite.ru, ��������� ���� ��������� 24 ���� � �����. �� ������� ���������� ��� �� ��� ���� � ������ ������ � �������.';
    $Tab3.=$PHPShopGUI->setInfo($About,50,'95%');

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������",$Tab1,270),array("����������",$Tab2,270), array("� ������",$Tab3,270));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            $PHPShopGUI->setInput("button","","������","right",70,"return onCancel();","but").
            $PHPShopGUI->setInput("submit","editID","��","right",70,"","but","actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if($UserChek->statusPHPSHOP < 2) {

    // ����� ����� ��� ������
    $PHPShopGUI->setLoader($_POST['editID'],'actionStart');

    // ��������� �������
    $PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();

?>