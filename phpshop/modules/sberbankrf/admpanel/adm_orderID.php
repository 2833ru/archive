<?php

function sberbank($data) {
    global $PHPShopGUI,$PHPShopModules;

    // Проверка способа оплаты
    $orders = unserialize($data['orders']);

    if($orders['Person']['order_metod']  == 10010){

        // SQL
        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sberbankrf.sberbankrf_log"));

        // Выборка логов
        $log = $PHPShopOrm->select(array('*'), array("order_id=" => "'$data[uid]'"), array('order' => 'date DESC'));
        $logArray = array();
        if(!empty($log['id']))
            $logArray[] = $log;
        else
            $logArray = $log;

        // Выводим кнопку возврата, если возврат еще не выполнялся
        $refund = false;
        foreach ($logArray as $logItem){
            if($logItem['type'] == 'refundTrue')
                $refund = true;
        }
        if($refund == false)
            $Tab1 = $PHPShopGUI->setInput("submit", "refund", "Возврат денежных средств", "center", null, "", "btn-sm ", "actionRefundSberbank");
        else
            $Tab1 = '';

        $PHPShopInterface = new PHPShopInterface();
        $PHPShopInterface->checkbox_action = false;

        $PHPShopInterface->setCaption(array("Журнал операций", "50%"), array("Дата", "20%"), array("Статус", "30%"));

        if (is_array($logArray))
            foreach ($logArray as $row) {
                $PHPShopInterface->setRow(array('name' => $row['type'], 'link' => '?path=modules.dir.sberbankrf&id=' . $row['id']), PHPShopDate::get($row['date'], true), $row['status']);
            }

        $Tab1 .= '<hr><table class="table table-hover">'.$PHPShopInterface->getContent().'</table>';

        $PHPShopGUI->addTab(array("Сбербанк России", $Tab1, true));
    }
}
function actionRefundSberbank(){
    global $PHPShopModules, $PHPShopSystem;

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sberbankrf.sberbankrf_log"));
    $ordersORM = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);

    $orderData = $ordersORM->select(array('*'), array('id=' => intval($_GET['id'])));

    // Настройки модуля
    include_once(dirname(__FILE__) . '/../hook/mod_option.hook.php');
    $PHPShopSberbankRFArray = new PHPShopSberbankRFArray();
    $option = $PHPShopSberbankRFArray->getArray();

    // Выборка логов
    $log = $PHPShopOrm->select(array('*'), array("order_id=" => "'$orderData[uid]'", 'type' => '="register"'));

    // Единичная выборка
    if(isset($log['id']))
        $log = array(0 => $log);

    foreach ($log as $item){
        $message = unserialize($item['message']);
        if(isset($message['orderId'])){
            $orderId = $message['orderId'];
            break;
        }
    }

    // Регистрация заказа в платежном шлюзе
    $params = array(
        "userName"  => $option["login"],
        "password"  => $option["password"],
        "orderId" => $orderId,
        "amount"    => floatval($orderData['sum'] * 100),
    );


    // Режим разработки и боевой режим
    if($option["dev_mode"] == 0)
        $url ='https://securepayments.sberbank.ru/payment/rest/refund.do';
    else
        $url ='https://3dsec.sberbank.ru/payment/rest/refund.do';

    $rbsCurl = curl_init();
    curl_setopt_array($rbsCurl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($params, '', '&')
    ));

    $result =json_decode(curl_exec($rbsCurl), true);

    curl_close($rbsCurl);

    if($result['errorCode'] == 0){
        // Пишем лог
        $PHPShopSberbankRFArray->log("Возврат денежных средств успешно выполнен", $orderData['uid'], 'Возврат денежных средств выполнен', 'refundTrue');
    }else{
        // Пишем лог ошибки, меняем статус заказа
        $PHPShopSberbankRFArray->log($result, $orderData['uid'], 'Возврат денежных средств не выполнен', 'refundFalse');
        $ordersORM->update(array('statusi' => 1), array('id=' => $orderData['id']));
    }
}

// Обработка событий
$PHPShopGUI->getAction();
$addHandler = array(
    'actionStart' => 'sberbank',
    'actionDelete' => false,
    'actionUpdate' => false
);
