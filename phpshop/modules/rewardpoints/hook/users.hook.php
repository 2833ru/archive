<?php
/**
 * ������� ����� � ����������
 * @param array $obj ������
 */
function user_info_rewardpoints_hook($obj,$row,$root) {
		global $PHPShopModules,$PHPShopUser;

		if($root=='START') {
			//����� ������ ������������ ����� �����
			$obj->PHPShopUserM = new PHPShopUser($_SESSION['UsersId']);
			$balancePoint = $obj->PHPShopUserM->getParam('point');

			//������� ������ ������
			if($balancePoint=='')
				$balancePoint = 0;
	    $obj->set('pointsBalance', $balancePoint);

	    //���� �������������
	    $PHPShopSys = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
	    $sys = $PHPShopSys->select(array('*'), false);
	    $daysSys = $sys['days'];
	    //�� ��������� 10 ����
	    $daysInterval = $sys['daysInterval'];
	    $days = $daysSys - $daysInterval;
	    if($days!='') {
		    $select = "SELECT * FROM `phpshop_modules_rewardpoints_users_transaction` WHERE `id_users`='".$_SESSION['UsersId']."' and `confirmation`='1' and `cron`='0' and (date < NOW() - INTERVAL ".$days." DAY)";
		    $que = mysql_query($select);
		    $srok_transaction = mysql_fetch_array($que);
		    do {
		    	//echo $srok_transaction['date'].($srok_transaction['operation']==1 ? '+'.$srok_transaction['number_points'] : '-'.$srok_transaction['number_points']).' ('.$srok_transaction['confirmation'].')<br>';
		    	//������� ������ �� ������ ��������� 90 ����
		    	if($srok_transaction['operation']==1) {
		    		//���� ���������� ������ �������
		    		$sumPoints = $sumPoints  + $srok_transaction['number_points'];
		    	}
		    	if($srok_transaction['operation']==0) {
		    		//���� �������� ������ �������
		    		$sumPoints = $sumPoints - $srok_transaction['number_points'];
		    	}
		    }
		    while ($srok_transaction = mysql_fetch_array($que));
		    //������ �������
		    //echo $sumPoints;
		    //����� 10 ����
		    $dateIn = date('d.m.Y', strtotime("+".$daysInterval." day"));

	  	}

	    //���������� � ���������
	    if($sumPoints>0) {
	    	if($balancePoint>=$sumPoints) {
	    		$obj->set('pointsWriteOff', '<i>'.$dateIn.' (����� '.$daysInterval.' ��.) � ������ ����� ����� ������� <b>'.$sumPoints.' ���.</b></i>');
	    	}
	  	}

	    $PHPShopOrmTr = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_users_transaction"));
	    $data = $PHPShopOrmTr->select(array('*'), array('id_users' => '="' . $_SESSION['UsersId'] . '"'), array('order' => 'id DESC'), array('limit' => 2000));
	    @extract($data);

	    if(isset($data)) {
		    foreach ($data as $transaction) {

		    	//�������
		      if($transaction['confirmation']==0) {
		      	$confirmation = '<span class="minus">��������</span>';
		      	$confmin = '��������';
		      }

		      if($transaction['confirmation']==1) {
		      	$confirmation = '<span class="plus">���������</span>';
		      	$confmin = '���������';
		      }

		      if($transaction['confirmation']==2) {
		      	$confirmation = '<span class="minus">������ ��������</span>';
		      	$confmin = '������ ��������';
		      }

		    	if($limit<=4):
		    		$minTd .= '<tr>
		                    <td>'.$transaction['id'].'</td>
		                    <td>'.$transaction['date'].'</td>
		                    <td><span class="'.($transaction['operation']==1 ? 'plus' : 'minus').'">'.($transaction['operation']==1 ? '+' : '-').' '.$transaction['number_points'].'</span><br><i>('.$confmin.')</i></td>
		                </tr>';
		      endif;	      

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

		    $minTdCase = '<table class="operationPoints" cellpadding="0" cellspacing="0">
		    <tr>
		    	<th>ID</th>
		      <th>����</th>
		      <th>���-�� ������</th>
		    </tr>'.$minTd.'</table>';

		    $maxTdCase = '<table class="operationPoints" cellpadding="0" cellspacing="0">
		    <tr>
		    	<th>ID</th>
		      <th>����</th>
		      <th>������</th>
		      <th>���-�� ������</th>
		      <th>������ �� ������</th>
		      <th>����� ������</th>
		      <th>�����������</th>
		    </tr>'.$maxTd.'</table>';

		    $obj->set('minTd', $minTdCase);
		    $obj->set('maxTd', $maxTdCase);

	  	}
	  	else {
	  		$obj->set('minTd', '��� ������');
		    $obj->set('maxTd', '��� ������');
	  	}
  	}

  	
}
 
$addHandler=array
(
	'user_info'=>'user_info_rewardpoints_hook'
);
?>