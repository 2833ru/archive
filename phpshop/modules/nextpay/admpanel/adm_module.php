<?php

PHPShopObj::loadClass('order');

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.nextpay.nextpay_system"));

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
    header('Location: ?path=modules&id=nextpay');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1.=$PHPShopGUI->setField('ID Продукта', $PHPShopGUI->setInputText(false, 'merchant_key_new', $data['merchant_key'], 250));
    $Tab1.=$PHPShopGUI->setField('Секретный ключ', $PHPShopGUI->setInputText(false, 'merchant_skey_new', $data['merchant_skey'], 250));

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField('Оплата при статусе', $PHPShopGUI->setSelect('status_new', $order_status_value, 250));

    $Tab1.= $PHPShopGUI->setField('Сообщение перед оплатой', $PHPShopGUI->setTextarea('title_new', $data['title']));
    $Tab1.= $PHPShopGUI->setField('Сообщение предварительной проверки', $PHPShopGUI->setTextarea('title_sub_new', $data['title_sub']));
    
    $info = '
<p>
Возможна работа с юридическими лицами, с заключением договора или без договора.
При работе без заключения договора в соответствии с 54-ФЗ для магазина отсутствует необходимость установки кассы. Подробнее о данном решении в статье <a href="https://www.nextpay.ru/faq54.php?p=phpshop" target="_blank">Решение для соответствия 54-ФЗ</a>. </p>

<h4>Настройка модуля</h4>
       <ol>
       <li>Зарегистрироваться в <a href="http://nextpay.ru/?p=phpshop" target="_blank">NextPay</a>. Для работы без принменения кассового оборудования при подаче заявки на регистрацию сайта выберите в поле "Правовая форма" опцию "Юридическое лицо/ИП (без заключения договора)" и заполните реквизиты вашей организации.
       <li>Создайте продукт в кабинете продавца в системе nextpay.ru в разделе <kbd>Каталоги</kbd> - <kbd>Создать продукт</kbd>
<li>В настройках продукта в поле "URL доставки заказа" указать <code>http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/nextpay/payment/result.php</code>
<li>В настройках продукта в поле "URL валидации заказа" указать <code>http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/nextpay/payment/check.php</code>
<li>В настройках продукта в поле "URL успеха" указать <code>http://'.$_SERVER['SERVER_NAME'].'/success/</code>
<li>В настройках продукта в поле "URL неудачи" указать  <code>http://'.$_SERVER['SERVER_NAME'].'/fail/</code>
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