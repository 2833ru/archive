<?php

function sendSmsfly($data) {
    
    // Статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $GetOrderStatusArray = $PHPShopOrderStatusArray->getArray();

    include "../../modules/smsfly/hook/mod_option.hook.php";
    
    // SMS оповещение пользователю о смене статуса заказа
    if (intval($data['statusi']) != intval($_POST['statusi_new'])) {
        
        // Настройки модуля
        $PHPShopSmsfly = new PHPShopSmsfly();

        // Сообщение
        $msg = 'Новый статус заказа №' . $_POST['order_num'] . ' - ' . $GetOrderStatusArray[$_POST['statusi_new']]['name'];
        $phone=$_POST['tel_new'];
        $PHPShopSmsfly->send($msg,$phone);
    }
}

$addHandler = array(
    'actionStart' => false,
    'actionDelete' => false,
    'actionUpdate' => 'sendSmsfly'
);
?>