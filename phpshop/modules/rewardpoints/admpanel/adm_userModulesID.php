<?
$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");
include($_classPath."admpanel/enter_to_admin.php");

$PHPShopSystem = new PHPShopSystem();

// ��������� ������
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");


// ��������
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->debug_close_window=false;
$PHPShopGUI->reload='top';
$PHPShopGUI->ajax="'modules','rewardpoints'";
$PHPShopGUI->includeJava='<SCRIPT language="JavaScript" src="../../../lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>';
$PHPShopGUI->dir=$_classPath."admpanel/";

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm;

    //if(empty($_POST['enabled_new'])) $_POST['enabled_new']=0;

    $action = 1;

    //return $action;
}

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI,$PHPShopSystem,$SysValue,$_classPath,$PHPShopOrm,$PHPShopModules;


    $PHPShopGUI->dir=$_classPath."admpanel/";
    $PHPShopGUI->title="���������� ������������";
    $PHPShopGUI->size="650,540";

    // ����������� ��������� ����
    $PHPShopGUI->setHeader("���������� ������������","",$PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $PHPShopGUI->addJSFiles('../../../admpanel/java/jquery-1.11.0.min.js','../js/admin_rewardpoints.js');
    $PHPShopGUI->addCSSFiles('../css/admin_rewardpoints.css');

    // ������������
    $PHPShopOrmUser = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
    $shopuser = $PHPShopOrmUser->select(array('*'), array('id' => '="' . $_GET['id'] . '"'));

    $Tab1_2 = $PHPShopGUI->setLine() . $PHPShopGUI->setField('���������� � ������������:', 
            '<p class="pu">������������: <b>'.$shopuser['name'].'</b> - ('.$shopuser['mail'].')</p>'.
            '<p class="pu">�����: <b>'.$shopuser['point'].'</b></p>' . 
            '<input type="hidden" id="id_users" value="'.$_GET['id'].'">' .
            '<input type="hidden" id="mail_users" value="'.$shopuser['mail'].'">'
        , 'left', 0, 0, array('width' => '98%'));

    $PHPShopOrmTr = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_users_transaction"));
    $datatr = $PHPShopOrmTr->select(array('*'), array('id_users' => '="' . $_GET['id'] . '"'), array('order' => 'id DESC'), array('limit' => 300));

    if(isset($datatr)) {
        foreach ($datatr as $transaction) {

            //�������
            if($transaction['confirmation']==0)
                $confirmation = '<span class="minus">��������</span>';

            if($transaction['confirmation']==1)
                $confirmation = '<span class="plus">���������</span>';

            if($transaction['confirmation']==2)
                $confirmation = '<span class="minus">������ ��������</span>';

            $maxTd .= '<tr>
                        <td>'.$transaction['id'].'</td>
                        <td>'.$transaction['date'].'</td>
                        <td>'.$confirmation.'</td>
                        <td class="'.($transaction['operation']==1 ? 'plus' : 'minus').'">'.($transaction['operation']==1 ? '+' : '-').' '.$transaction['number_points'].'</td>
                        <td>'.$transaction['balance_points'].'</td>
                        <td>'.$transaction['sum_orders'].'</td>
                        <td><i>'.$transaction['comment_admin'].'</i></td>
                    </tr>';

          $limit++;
        }


        $maxTdCase = '<div class="content-points"><table class="operationPoints" cellpadding="0" cellspacing="0">
        <thead>
        <tr class="titletd">
            <th>ID</th>
          <th>����</th>
          <th>������</th>
          <th>���-�� ������</th>
          <th>������ �� ������</th>
          <th>����� ������</th>
          <th>�����������</th>
        </tr>
        </thead>
        <tbody>'.$maxTd.'</tbody></table></div>';

        
        $Tab1 .= $PHPShopGUI->setInfo($maxTdCase, false, '98%');

    }
    else {
        $maxTdCase = '<div class="content-points"><table class="operationPoints" cellpadding="0" cellspacing="0">
        <thead>
        <tr class="titletd">
            <th>ID</th>
          <th>����</th>
          <th>������</th>
          <th>���-�� ������</th>
          <th>������ �� ������</th>
          <th>����� ������</th>
          <th>�����������</th>
        </tr>
        </thead>
        <tbody><tr class="no_data"><td colspan="7">��� ������ � �����������</td></tr></tbody></table></div>';
        $Tab1 .= $PHPShopGUI->setInfo($maxTdCase, false, '98%');
    }

    //�������� ��������
    $vaStatTrans[] = array('�������� ������', 0);  
    $vaStatTrans[] = array('���������� ������', 1, 'selected');
      
    //�������� ����������
    $Tab1 .= $PHPShopGUI->setLine() . $PHPShopGUI->setField('�������� ����������:', 
        $PHPShopGUI->setSelect('operation', $vaStatTrans, '120px', left) .
        $PHPShopGUI->setInputText('���-�� ������:', 'point', false, 35, false , left) . 
        $PHPShopGUI->setInputText('�����������:', 'comment_admin', false, 165, false , left) .
        "<div id='load_proc'><img src='../img/zoomloader.gif'></div>" .
        $PHPShopGUI->setInput("button","","��������","right",70,"addTransactionModules()","but")
    , 'left', 0, 0, array('width' => '98%'));
                            
    // ����� ����� ��������
    $PHPShopGUI->setTab(array("����������",$Tab1,400), array("���������� � ������������",$Tab1_2,400));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter=
            $PHPShopGUI->setInput("hidden","newsID",$id,"right",70,"","but").
            //$PHPShopGUI->setInput("button","","������","right",70,"return onCancel();","but").
            //$PHPShopGUI->setInput("submit","delID","�������","right",70,"","but","actionDelete").
            $PHPShopGUI->setInput("submit","editID","��","right",70,"return onCancel();","but");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}


// ������� ��������
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id'=>'='.$_POST['newsID']));
    return $action;
}

if($UserChek->statusPHPSHOP < 2) {

    // ����� ����� ��� ������
    $PHPShopGUI->setAction($_GET['id'],'actionStart','none');

    // ��������� �������
    $PHPShopGUI->getAction();

}else $UserChek->BadUserFormaWindow();

?>


