<?php

function sendSmsgate($data) {

    // SMS оповещение пользователю о смене статуса заказа
    if ($data['statusi'] != $_POST['statusi_new']) {

    // —татусы заказов
      $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
      $GetOrderStatusArray = $PHPShopOrderStatusArray->getArray();

      // Ќастройки модул€
      include_once(dirname(__FILE__) . '/mod_option.php');
      $PHPShopSmsgate = new PHPShopSmsgate();

      // получение шаблона
      $PHPShopSystem = new PHPShopSystem();
      $nameShop = $PHPShopSystem->objRow['name'];
      $statusOrderTpl = $PHPShopSmsgate->getTplStatusOrder();

      // массив данных дл€ вставки при парсинге строки
      $datainsert = array(
        '@NameShop@'    => $nameShop, 
        '@OrderNum@'    => $data['uid'],
        '@OrderStatus@' => $_POST['statusi_new'] ? $GetOrderStatusArray[$_POST['statusi_new']]['name'] : 'ѕодтвержден'
      );

      // телефон на который отправл€етс€ сообщение
      $phone = array($PHPShopSmsgate->true_num($data['tel']));

      // сообщение
      $msg = $PHPShopSmsgate->parseString($statusOrderTpl, $datainsert);

      $PHPShopSmsgate->sendSmsgate($phone,$msg);
      
    }
    
}

$addHandler = array(
    'actionStart' => false,
    'actionDelete' => false,
    'actionUpdate' => 'sendSmsgate'
);

?>