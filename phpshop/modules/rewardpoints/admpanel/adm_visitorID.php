<?php

function updateTransPoints($data) {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $PHPShopSystem;
    
    //������ ��� ���������� � ������ �� ��������� ��������
    $PHPShopOrmL = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
    $system = $PHPShopOrmL->select(array('*'), false);
    $status_order = $system['status_order'];
    $status_order_null = $system['status_order_null'];

    //���� ������� ������
    if($data['statusi'] != $_POST['statusi_new']) {

    	// ������ �� ������
	    $PHPShopOrm->debug = false;
	    $dataorder = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['visitorID'])));
	    $order = unserialize($dataorder['orders']);

	    //������ ��� ����������
	    $user_id = $order['Person']['user_id'];
	    $user_mail = $order['Person']['mail'];
	    $order_id = $_POST['visitorID'];
	    $order_uid = $order['Person']['ouid'];

	    //������ �������������
			$PHPShopUserBal = new PHPShopUser($user_id);
			$pointBalance = $PHPShopUserBal->getParam('point');
      if($pointBalance=='')
          $pointBalance = 0;

	    //������������ � ������� ����������
	    $PHPShopOrmTr = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_users_transaction"));
	    $data_tr = $PHPShopOrmTr->select(array('*'), array('id_users' => '="' . $user_id . '"', 'id_order'=>'="' . $order_id . '"'), array('order' => 'id DESC'), array('limit' => 1));

	    //����� ��� ���������� �� ����
	    $number_points = $data_tr['number_points'];

    	//���� ������ ������� �� ����������
    	if($_POST['statusi_new']==$status_order) {
		    //���� �������� �� ����������, �� ��������
		    if($data_tr['operation']==1) {
		    	$bal_upd = $pointBalance + $number_points;
		    	//���������� �� ����
		    	mysql_query("UPDATE `phpshop_shopusers` SET `point` = '".$bal_upd."' WHERE `id`=".$user_id);
		    	//��������� ������ ��������
		    	mysql_query("UPDATE `phpshop_modules_rewardpoints_users_transaction` SET  `confirmation` =  '1' WHERE `id` =".$data_tr['id']);
		    	// �������� ������ ���������� � ������
		    	$titleMailShopuser = "���������� �� ���� ".$number_points." ���. - �� ������ �".$order_uid;
		      $PHPShopMail = new PHPShopMail($user_mail, $PHPShopSystem->getParam('adminmail2'), $titleMailShopuser, '', true, true);
		      $content = $titleMailShopuser.'. ������ ������: '.$bal_upd;
		      $PHPShopMail->sendMailNow($content);
		    }
	  	}
	  	//���� ������ ������� �� �������������
    	if($_POST['statusi_new']==$status_order_null) {
		    //���� �������� �� ����������, �� ��������
		    if($data_tr['operation']==1) {
		    	//���� ���� � ��������
		    	if($data_tr['confirmation']==0) {
		    		//��������� ������ ��������
		    		mysql_query("UPDATE `phpshop_modules_rewardpoints_users_transaction` SET  `confirmation` =  '2' WHERE `id` =".$data_tr['id']);
		    	}
		    	//���� ���� ���������
		    	if($data_tr['confirmation']==1) {
		    		$bal_upd = $pointBalance - $number_points;
		    		//������� �� �����
		    		mysql_query("UPDATE `phpshop_shopusers` SET `point` = '".$bal_upd."' WHERE `id`=".$user_id);
		    		//��������� ������ ��������
		    		mysql_query("UPDATE `phpshop_modules_rewardpoints_users_transaction` SET  `confirmation` =  '2' WHERE `id` =".$data_tr['id']);
		    		// �������� ������ ���������� � ������
			    	$titleMailShopuser = "�������� �� ����� ".$number_points." ���. (������� - ������ ������ �".$order_uid.")";
			      $PHPShopMail = new PHPShopMail($user_mail, $PHPShopSystem->getParam('adminmail2'), $titleMailShopuser, '', true, true);
			      $content = "�������� �� ����� ".$number_points." ���. - �� ������� ������ ������ �".$order_uid.". ������ ������: ".$bal_upd.". ��� ������� ����� ������ ������������� ��������.";
			      $PHPShopMail->sendMailNow($content);	
		    	}

		    }
    	}

  	}

    
    

    //mysql_query("UPDATE `phpshop_shopusers` SET `point` = '400' WHERE `id`=");

    //"UPDATE `phpshop_modules_rewardpoints_users_transaction` SET  `confirmation` =  '1' WHERE `id` =13;"

    //$Tab1 = $PHPShopGUI->setField("���������� ������:", $PHPShopGUI->setInput("text", "point_new", $data['point'], "left", 100,false,false,false,'���� ������ � ������'), "none");
    
    //$PHPShopGUI->addTab(array("�����",$Tab1,450));
}

function startTransPoints($data) {
	// ���������� ������
    $PHPShopOrder = new PHPShopOrderFunction($data['id']);

	$order = unserialize($data['orders']);
	$Person = $order['Person'];
	$Cart = $order['Cart'];

	if($Person['pointOk']!='') {
    $script = '<script type="text/javascript" src="/phpshop/admpanel/java/jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function(){
		  $(".table").append(\'<tr bgcolor="#C0D2EC"><td style="padding:3" colspan="3" align="center"><span name="txtLang" id="txtLang">������� �� �����</span></td><td style="padding:3"><span name="txtLang" id="txtLang">����� ������� ������� �� <b>'.$Person['pointOk'].'</b> ���.</span></td><td style="padding:3" colspan="2" align="center">��������� ������� (��� ����� ��������): <b>'.$Cart['sum'].'</b> '.$PHPShopOrder->default_valuta_code.'</td></tr>\');
		});
		</script>';
	}

		echo $script;
}

$addHandler=array(
        'actionStart'=>'startTransPoints',
        'actionDelete'=>false,
        'actionUpdate'=>'updateTransPoints'
);

?>