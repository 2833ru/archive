<?php

function updateTransPoints($data) {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $PHPShopSystem;
    
    //Статус для зачисления и отметы из системных настроек
    $PHPShopOrmL = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
    $system = $PHPShopOrmL->select(array('*'), false);
    $status_order = $system['status_order'];
    $status_order_null = $system['status_order_null'];

    //Если изменен статус
    if($data['statusi'] != $_POST['statusi_new']) {

    	// Данные по заказу
	    $PHPShopOrm->debug = false;
	    $dataorder = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['visitorID'])));
	    $order = unserialize($dataorder['orders']);

	    //Данные для обновления
	    $user_id = $order['Person']['user_id'];
	    $user_mail = $order['Person']['mail'];
	    $order_id = $_POST['visitorID'];
	    $order_uid = $order['Person']['ouid'];

	    //Баланс пользователия
			$PHPShopUserBal = new PHPShopUser($user_id);
			$pointBalance = $PHPShopUserBal->getParam('point');
      if($pointBalance=='')
          $pointBalance = 0;

	    //Подключаемся к таблице транзакций
	    $PHPShopOrmTr = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_users_transaction"));
	    $data_tr = $PHPShopOrmTr->select(array('*'), array('id_users' => '="' . $user_id . '"', 'id_order'=>'="' . $order_id . '"'), array('order' => 'id DESC'), array('limit' => 1));

	    //Баллы для зачисления на счет
	    $number_points = $data_tr['number_points'];

    	//Если статус изменен на зачисление
    	if($_POST['statusi_new']==$status_order) {
		    //Если операция по зачислению, то применям
		    if($data_tr['operation']==1) {
		    	$bal_upd = $pointBalance + $number_points;
		    	//Зачислеяем на счет
		    	mysql_query("UPDATE `phpshop_shopusers` SET `point` = '".$bal_upd."' WHERE `id`=".$user_id);
		    	//Обновляем статус ожидания
		    	mysql_query("UPDATE `phpshop_modules_rewardpoints_users_transaction` SET  `confirmation` =  '1' WHERE `id` =".$data_tr['id']);
		    	// Отсылаем письмо покупателю о баллах
		    	$titleMailShopuser = "Зачисление на счет ".$number_points." бал. - по заказу №".$order_uid;
		      $PHPShopMail = new PHPShopMail($user_mail, $PHPShopSystem->getParam('adminmail2'), $titleMailShopuser, '', true, true);
		      $content = $titleMailShopuser.'. Баланс баллов: '.$bal_upd;
		      $PHPShopMail->sendMailNow($content);
		    }
	  	}
	  	//Если статус изменен на аннулирование
    	if($_POST['statusi_new']==$status_order_null) {
		    //Если операция по зачислению, то применям
		    if($data_tr['operation']==1) {
		    	//Если было в ожидание
		    	if($data_tr['confirmation']==0) {
		    		//Обновляем статус ожидания
		    		mysql_query("UPDATE `phpshop_modules_rewardpoints_users_transaction` SET  `confirmation` =  '2' WHERE `id` =".$data_tr['id']);
		    	}
		    	//Если было зачислено
		    	if($data_tr['confirmation']==1) {
		    		$bal_upd = $pointBalance - $number_points;
		    		//Удаляем со счета
		    		mysql_query("UPDATE `phpshop_shopusers` SET `point` = '".$bal_upd."' WHERE `id`=".$user_id);
		    		//Обновляем статус ожидания
		    		mysql_query("UPDATE `phpshop_modules_rewardpoints_users_transaction` SET  `confirmation` =  '2' WHERE `id` =".$data_tr['id']);
		    		// Отсылаем письмо покупателю о баллах
			    	$titleMailShopuser = "Списание со счета ".$number_points." бал. (Причина - отмена заказа №".$order_uid.")";
			      $PHPShopMail = new PHPShopMail($user_mail, $PHPShopSystem->getParam('adminmail2'), $titleMailShopuser, '', true, true);
			      $content = "Списание со счета ".$number_points." бал. - по причине отмены заказа №".$order_uid.". Баланс баллов: ".$bal_upd.". Все вопросы можно задать администрации магазина.";
			      $PHPShopMail->sendMailNow($content);	
		    	}

		    }
    	}

  	}

    
    

    //mysql_query("UPDATE `phpshop_shopusers` SET `point` = '400' WHERE `id`=");

    //"UPDATE `phpshop_modules_rewardpoints_users_transaction` SET  `confirmation` =  '1' WHERE `id` =13;"

    //$Tab1 = $PHPShopGUI->setField("Количество баллов:", $PHPShopGUI->setInput("text", "point_new", $data['point'], "left", 100,false,false,false,'Цена товара в баллах'), "none");
    
    //$PHPShopGUI->addTab(array("Баллы",$Tab1,450));
}

function startTransPoints($data) {
	// Библиотека заказа
    $PHPShopOrder = new PHPShopOrderFunction($data['id']);

	$order = unserialize($data['orders']);
	$Person = $order['Person'];
	$Cart = $order['Cart'];

	if($Person['pointOk']!='') {
    $script = '<script type="text/javascript" src="/phpshop/admpanel/java/jquery-1.11.0.min.js"></script>
		<script>
		$(document).ready(function(){
		  $(".table").append(\'<tr bgcolor="#C0D2EC"><td style="padding:3" colspan="3" align="center"><span name="txtLang" id="txtLang">Покупка за баллы</span></td><td style="padding:3"><span name="txtLang" id="txtLang">Часть покупки куплена за <b>'.$Person['pointOk'].'</b> бал.</span></td><td style="padding:3" colspan="2" align="center">Заплачено реально (без учета доставки): <b>'.$Cart['sum'].'</b> '.$PHPShopOrder->default_valuta_code.'</td></tr>\');
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