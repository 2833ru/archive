<?php

PHPShopObj::loadClass('order');

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.netpay.netpay_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    return $action;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;
    
    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=netpay');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();
	
	$Tab1.=$PHPShopGUI->setField('Режим работы', 
		$PHPShopGUI->setRadio('work_new', 1, 'Рабочий', $data['work']) . 
        $PHPShopGUI->setRadio('work_new', 0, 'Тестовый', $data['work'])
		);
	
    $Tab1.=$PHPShopGUI->setField('Api Key', $PHPShopGUI->setInputText(false, 'apikey_new', $data['apikey'], 210));
    $Tab1.=$PHPShopGUI->setField('Auth signature', $PHPShopGUI->setInputText(false, 'auth_new', $data['auth'], 100));
    $Tab1.=$PHPShopGUI->setField('Актуальность оплаты заказа', $PHPShopGUI->setInputText(false, 'expiredtime_new', $data['expiredtime'], 100,'дней'));
	
	// Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField('Разрешить оплату при статусе заказа', $PHPShopGUI->setSelect('status_new', $order_status_value, 210));
	
	$Tab1.= $PHPShopGUI->setField('Сообщение до разрешения оплаты', $PHPShopGUI->setTextarea('title_sub_new', $data['title_sub']));
	
    $Tab1.=$PHPShopGUI->setField('Предварительно отобразить страницу описания оплаты', 
		$PHPShopGUI->setRadio('autosubmit_new', 2, 'Вкл.', $data['autosubmit']) . 
        $PHPShopGUI->setRadio('autosubmit_new', 1, 'Выкл.', $data['autosubmit'])
		);

    $Tab1.= $PHPShopGUI->setField('Сообщение перед оплатой', $PHPShopGUI->setTextarea('title_new', $data['title']));
	
	/*foreach ($order_status_value as $i => $v) $order_status_value[$i][2] = $data['status_paid'];
	$Tab1.= $PHPShopGUI->setField('Статус оплаченного заказа', $PHPShopGUI->setSelect('status_paid_new', $order_status_value, 210));*/
	
	foreach ($order_status_value as $i => $v) $order_status_value[$i][2] = $data['status_refund'];
	$Tab1.= $PHPShopGUI->setField('Статус заказа с возвратом оплаты', $PHPShopGUI->setSelect('status_refund_new', $order_status_value, 210));
    
	$Tab1.=$PHPShopGUI->setField('Оформлять онлайн-чек через партнёра Net Pay', 
		$PHPShopGUI->setRadio('online_bill_new', 1, 'Вкл.', $data['online_bill']) . 
        $PHPShopGUI->setRadio('online_bill_new', 0, 'Выкл.', $data['online_bill'])
		);
		
	$Tab1.=$PHPShopGUI->setField('ИНН для онлайн-чека', $PHPShopGUI->setInputText(false, 'inn_new', $data['inn'], 210));
	
	$nds_arr = array(
		array('без НДС', 'none','none'), 
		array('НДС по ставке 0%', 'vat0','none'), 
		array('НДС чека по ставке 10%', 'vat10','none'), 
		array('НДС чека по ставке 18%', 'vat18','none'), 
		array('НДС чека по расчетной ставке 10/110', 'vat110','none'), 
		array('НДС чека по расчетной ставке 18/118', 'vat118','none'),
		);
	foreach ($nds_arr as $i => $v) $nds_arr[$i][2] = $data['tax'];
	$Tab1.= $PHPShopGUI->setField('Ставка НДС для онлайн-чека', $PHPShopGUI->setSelect('tax_new', $nds_arr, 210));
	
	$Tab1.=$PHPShopGUI->setField('Использовать режим "Холдирование"', 
		$PHPShopGUI->setRadio('hold_new', 1, 'Вкл.', $data['hold']) . 
        $PHPShopGUI->setRadio('hold_new', 0, 'Выкл.', $data['hold'])
		);
		
	foreach ($order_status_value as $i => $v) $order_status_value[$i][2] = $data['status_hold'];
	$Tab1.= $PHPShopGUI->setField('Статус заказа с замороженной суммой при холдировании', $PHPShopGUI->setSelect('status_hold_new', $order_status_value, 210));
    
    $info = '<h4>Настройка модуля</h4>
       <ol>
        <li>Зарегистрироваться на сайте <a href="http://net2pay.ru/" target="_blank">Net2pay.ru</a>.
        <li>"API Key" и "Auth signature" (выдаются при подключении к Net2pay.ru) скопировать в одноименные поля настроек модуля.
        <li>Сообщить техподдержке Net2pay.ru адрес оповещения о платеже: <code>http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/netpay/payment/result.php</code>
        </ol>
        
';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>