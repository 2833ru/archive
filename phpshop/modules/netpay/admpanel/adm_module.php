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
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=netpay');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('Наименование типа оплаты', $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    $Tab1.=$PHPShopGUI->setField('API Key', $PHPShopGUI->setInputText(false, 'merchant_key_new', $data['merchant_key'], 210));
    $Tab1.=$PHPShopGUI->setField('API Signature', $PHPShopGUI->setInputText(false, 'merchant_skey_new', $data['merchant_skey'], 210));
    $Tab1.=$PHPShopGUI->setField('Актуальность оплаты заказа', $PHPShopGUI->setInputText(false, 'expiredtime_new', $data['expiredtime'], 100,'дней'));
    $Tab1.=$PHPShopGUI->setField('Страница описания оплаты', $PHPShopGUI->setRadio('autosubmit_new', 2, 'Вкл.', $data['autosubmit']) . 
            $PHPShopGUI->setRadio('autosubmit_new', 1, 'Выкл.', $data['autosubmit']));

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField('Оплата при статусе', $PHPShopGUI->setSelect('status_new', $order_status_value, 210));

    $Tab1.= $PHPShopGUI->setField('Сообщение перед оплатой', $PHPShopGUI->setTextarea('title_new', $data['title']));
    $Tab1.= $PHPShopGUI->setField('Сообщение предварительной проверки', $PHPShopGUI->setTextarea('title_sub_new', $data['title_sub']));
    
    $info = '<h4>Настройка модуля</h4>
       <ol>
        <li>Зарегистрироваться в <a href="http://net2pay.ru/" target="_blank">Net2pay.ru</a>.
        <li>"API Key" и "API Signature" (выдаются при подключени к Net2pay.ru) скопировать в одноименные поля настроек модуля.
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