<?php

function sendSmsfly($data) {
    global $_classPath,$PHPShopSystem;
    
    // Статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $GetOrderStatusArray = $PHPShopOrderStatusArray->getArray();

    include $_classPath."modules/smsfly/hook/mod_option.hook.php";
    
    // SMS оповещение пользователю о смене статуса заказа
    if (intval($data['statusi']) != intval($_POST['statusi_new']) and $PHPShopSystem->ifSerilizeParam('admoption.sms_status_order_enabled')) {
        
        // Настройки модуля
        $PHPShopSmsfly = new PHPShopSmsfly();

        // Сообщение
        $msg = __('Новый статус заказа №').  $data['uid'] . ' - ' . $GetOrderStatusArray[$_POST['statusi_new']]['name'];

        $phone=$_POST['tel_new'];

        if(!$phone)
            $phone = $data['tel'];

        $PHPShopSmsfly->send($msg,$phone);
    }
}

$addHandler = array(
    'actionStart' => false,
    'actionDelete' => false,
    'actionUpdate' => 'sendSmsfly'
);
?>