<?php
/**
 * Выводим баллы и транзакции
 * @param array $obj объект
 */
function user_info_rewardpoints_hook($obj,$row,$root) {
		global $PHPShopModules,$PHPShopUser;

		if($root=='START') {
			//Вызов данных пользователя через класс
			$obj->PHPShopUserM = new PHPShopUser($_SESSION['UsersId']);
			$balancePoint = $obj->PHPShopUserM->getParam('point');

			//Текущий баланс баллов
			if($balancePoint=='')
				$balancePoint = 0;
	    $obj->set('pointsBalance', $balancePoint);

	    //Срок использования
	    $PHPShopSys = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
	    $sys = $PHPShopSys->select(array('*'), false);
	    $daysSys = $sys['days'];
	    //За последние 10 дней
	    $daysInterval = $sys['daysInterval'];
	    $days = $daysSys - $daysInterval;
	    if($days!='') {
		    $select = "SELECT * FROM `phpshop_modules_rewardpoints_users_transaction` WHERE `id_users`='".$_SESSION['UsersId']."' and `confirmation`='1' and `cron`='0' and (date < NOW() - INTERVAL ".$days." DAY)";
		    $que = mysql_query($select);
		    $srok_transaction = mysql_fetch_array($que);
		    do {
		    	//echo $srok_transaction['date'].($srok_transaction['operation']==1 ? '+'.$srok_transaction['number_points'] : '-'.$srok_transaction['number_points']).' ('.$srok_transaction['confirmation'].')<br>';
		    	//Подсчет баллов не считая последних 90 дней
		    	if($srok_transaction['operation']==1) {
		    		//Если начисление узнаем сколько
		    		$sumPoints = $sumPoints  + $srok_transaction['number_points'];
		    	}
		    	if($srok_transaction['operation']==0) {
		    		//Если списание узнаем сколько
		    		$sumPoints = $sumPoints - $srok_transaction['number_points'];
		    	}
		    }
		    while ($srok_transaction = mysql_fetch_array($que));
		    //Узнаем остаток
		    //echo $sumPoints;
		    //Через 10 дней
		    $dateIn = date('d.m.Y', strtotime("+".$daysInterval." day"));

	  	}

	    //Информация о списаниях
	    if($sumPoints>0) {
	    	if($balancePoint>=$sumPoints) {
	    		$obj->set('pointsWriteOff', '<i>'.$dateIn.' (через '.$daysInterval.' дн.) с вашего счета будет списано <b>'.$sumPoints.' бал.</b></i>');
	    	}
	  	}

	    $PHPShopOrmTr = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_users_transaction"));
	    $data = $PHPShopOrmTr->select(array('*'), array('id_users' => '="' . $_SESSION['UsersId'] . '"'), array('order' => 'id DESC'), array('limit' => 2000));
	    @extract($data);

	    if(isset($data)) {
		    foreach ($data as $transaction) {

		    	//Статусы
		      if($transaction['confirmation']==0) {
		      	$confirmation = '<span class="minus">Ожидание</span>';
		      	$confmin = 'Ожидание';
		      }

		      if($transaction['confirmation']==1) {
		      	$confirmation = '<span class="plus">Выполнено</span>';
		      	$confmin = 'Выполнено';
		      }

		      if($transaction['confirmation']==2) {
		      	$confirmation = '<span class="minus">Отмена операции</span>';
		      	$confmin = 'Отмена операции';
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
		      <th>Дата</th>
		      <th>Кол-во баллов</th>
		    </tr>'.$minTd.'</table>';

		    $maxTdCase = '<table class="operationPoints" cellpadding="0" cellspacing="0">
		    <tr>
		    	<th>ID</th>
		      <th>Дата</th>
		      <th>Статус</th>
		      <th>Кол-во баллов</th>
		      <th>Баланс на момент</th>
		      <th>Сумма заказа</th>
		      <th>Комментарий</th>
		    </tr>'.$maxTd.'</table>';

		    $obj->set('minTd', $minTdCase);
		    $obj->set('maxTd', $maxTdCase);

	  	}
	  	else {
	  		$obj->set('minTd', 'Нет данных');
		    $obj->set('maxTd', 'Нет данных');
	  	}
  	}

  	
}
 
$addHandler=array
(
	'user_info'=>'user_info_rewardpoints_hook'
);
?>