<?php
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("file");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");


// ��������� ������
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/","rewardpoints");


// ��������
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));



// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->update($_POST);

    //��������� ������
    foreach ($_POST as $key=>$val) {
        $arname = explode('_', $key);
        if($arname[0]=='valuta') {
            //������ �� ��������� ������
            mysql_query("UPDATE `phpshop_valuta` SET `price_point` =  '".$val."' WHERE `id` =".$arname[1]);
        }
    }

    return $action;
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI,$_classPath,$PHPShopOrm;

    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="��������� ������";
    $PHPShopGUI->size="500,450";

    // ����������� ��������� ����
    $PHPShopGUI->setHeader("��������� ������ '�������� �����'","���������",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    //��������� ���������
    $data = $PHPShopOrm->select();
    @extract($data);

    //������ �����
    $PHPShopOrmValuta = new PHPShopOrm($GLOBALS['SysValue']['base']['currency']);
    $data_currency = $PHPShopOrmValuta->select(array('*'));

    //�������� input ��� �����
    if(isset($data_currency)):
        foreach ($data_currency as $value) {
            $html_valuta .= $PHPShopGUI->setInputText('1 ���� = ', 'valuta_'.$value['id'], $value['price_point'], 70, $value['code']);
        }
    endif;

    $Tab1 = $PHPShopGUI->setLine() . $PHPShopGUI->setField('���� �� 1 ����:', 
        $html_valuta . 
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>��������: 1���� = 100 ���</i>'), 'left', 0, 0, array('width' => '98%'));

    $Tab1_2 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('������� ��� ����������:', 
        $PHPShopGUI->setInputText('������� ���������� ', 'percent_add_new', $data['percent_add'], 40, '%') . 
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>��������: 10% �� ��������� ������ (���� ��� ������� ������� ����������� ���-�� ������, �� ��������� ����������� �� �����)</i>'), 'left', 0, 0, array('width' => '98%'));

    //�������� ������� �������
    $selectBuy[ $data['percent'] ] = 'selected';
    for($u=1;$u<=100;$u++): 
        $vaBuy[] = array($u.'% �������', $u, $selectBuy[$u]);
    endfor;

    $Tab1_2 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('������� �������:', 
        $PHPShopGUI->setSelect('percent_new', $vaBuy, '120px') .
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>�������� ��� ������ 50% ��������, ��� ������������ ������ ������ ������� ������ �������� ����� �������</i>')
    , 'left', 0, 0, array('width' => '98%'));

    $Tab1_3 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('�����:', 
        $PHPShopGUI->setInputText('���� �������� ������ (���������� ����) ', 'days_new', $data['days'], 40, '��.') . 
        $PHPShopGUI->setInputText('���� ���������� � �������� ������ (���������� ����) ', 'daysInterval_new', $data['daysInterval'], 40, '��.') . 
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>��������: 10 ����. <b>�����!</b> - ���� ���������� � �������� �� ����� ���� ������ ����� �������� ������</i>')

        , 'left', 0, 0, array('width' => '98%'));



    //������ �� �������
    $PHPShopOrmValuta = new PHPShopOrm($GLOBALS['SysValue']['base']['order_status']);
    $data_order_status = $PHPShopOrmValuta->select(array('*'));
    foreach ($data_order_status as $order_status) {
        if($data['status_order']==$order_status['id'])
            $order_status_check = 'selected';
        else
            $order_status_check = '';

        if($data['status_order_null']==$order_status['id'])
            $order_null_status_check = 'selected';
        else
            $order_null_status_check = '';

        $vaOrderStatus[] = array($order_status['name'], $order_status['id'], $order_status_check);
        $vaOrderStatusNull[] = array($order_status['name'], $order_status['id'], $order_null_status_check);
    }


    $Tab1_4 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('�������:', 
        $PHPShopGUI->setSelect('status_order_new', $vaOrderStatus, '120px', false, '������ ������ �� ������������� ������') .
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setSelect('status_order_null_new', $vaOrderStatusNull, '120px',false, '������ ������ �� ������������� ����������� ������:') .
        $PHPShopGUI->setLine(false, 10) .
        $PHPShopGUI->setImage('../../../admpanel/icon/icon_info.gif', 16, 16) .
            __('<i>�������� ��� ������������� ������ "��������", � ��� �������� ������ "�����������"</i>')

    , 'left', 0, 0, array('width' => '98%'));

    


    $Info = '
    <p>���� �� ������� <b>phpshop/templates/��� �������/</b></p>
    <p>1. ������ <b>/users/users_page_info.tpl</b> (������ �������)<br>
    <ul>
        <li><b>@pointsBalance@</b> - ������ ������</li>
        <li><b>@minTd@</b> - ������� ���������� ��������� (������)</li>
        <li><b>@maxTd@</b> - ������� ���������� ������</li>
        <li><b>@pointsWriteOff@</b> - ���������� � ���������</li>
    </ul>
    </p>
    <p>2. ������ <b>/users/users_forma_enter.tpl</b> (��������� ������������ � �����)<br>
    <ul>
        <li><b>@pointsBalance@</b> - ������ ������</li>
    </ul>
    </p>
    <p>3. ������ <b>/product/main_product_forma_full.tpl</b> (�������� ������)<br>
    <ul>
        <li><b>@pointsAccrued@</b> - ���-�� ����������� ������� �� �����</li>
    </ul>
    </p>
    <p>4. ���������� ����������� � ����� <b>/order/</b> ������ ������� ��� ����� �� ����� � ������� <b>/phpshop/modules/rewardpoints/templates/order/</b><br>
    <ul>
        <li>1). <b>cart.tpl</b> - �������</li>
        <li>2). <b>product.tpl</b> - ������ � �������</li>
    </ul>
    </p>';

    // ���������� �������� 3
    $Tab3=$PHPShopGUI->setInfo($Info, 300, '95%');

    // ���������� �������� 4
    $Tab4=$PHPShopGUI->setPay($data['serial'],true);

    // ���������� �������� 5
    $Info = '
    <p><b>���������� �� ��������� ��������������� �������� ������� �� ��������� �����.</b></p>
    <p>1. �������� ������ <b>Cron</b></p>
    <p>2. ������� � ������ <b>������ > ������ > ��������� Cron</b></p>
    <p>3. ������� ����� ������, ��� ��������� � ���� "����������� ����" ������ <b>phpshop/modules/rewardpoints/cron/pointswriteoff.php</b> ��� ���� �������� ������<br>��� �� �������� ������ ���������� �������� � ����.</p>
    <p>4. ������� ����� ������, ��� ��������� � ���� "����������� ����" ������ <b>phpshop/modules/rewardpoints/cron/mailpointswriteoff.php</b> ��� �������� ����� � ������������ ���������<br>��� �� �������� ������ ���������� �������� � ����.</p>
    <p>5. � ������ <b>phpshop/modules/rewardpoints/cron/mailpointswriteoff.php</b> � <b>phpshop/modules/rewardpoints/cron/pointswriteoff.php</b> � ������ ������� ���� ��������� <b>true</b> ������ false <p>';

    $Tab5=$PHPShopGUI->setInfo($Info, 300, '95%');

    // ���������� �������� 6
    //$Tab6=$PHPShopGUI->setInfo("����", 250, '95%');

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("����",$Tab1,350), array("�������/����������",$Tab1_2,350), array("�����",$Tab1_3,350), array("�������",$Tab1_4,350), array("������",$Tab3,350), array("����-��������",$Tab5,350), array("� ������",$Tab4,350));

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


