<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexcart.yandexcart_system"));

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

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;
    
    PHPShopObj::loadClass("order");

    // Выборка
    $data = $PHPShopOrm->select();

    $payment_delivery_value[] = array('Наличный расчет + Банковской картой', 1, $data['payment_delivery']);
    $payment_delivery_value[] = array('Наличный расчет при получении', 2, $data['payment_delivery']);


    $Tab1 = '<hr>'.$PHPShopGUI->setField('Токен для работы с Маркетом', $PHPShopGUI->setInputText(false, 'token_new', $data['token'],400));
    $Tab1.= $PHPShopGUI->setField('ID Компании в Маркете', $PHPShopGUI->setInputText(false, 'campaign_new', $data['campaign'],400));
    $Tab1.= $PHPShopGUI->setField('Пароль авторизации внешних запросов', $PHPShopGUI->setInputText(false, 'password_new', $data['password'],400));
    $Tab1.= $PHPShopGUI->setField('Способы оплаты', $PHPShopGUI->setSelect('payment_delivery_new', $payment_delivery_value));
    $Tab1.=$PHPShopGUI->setLine();

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();

    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status){
            $status_processing_value[] = array($order_status['name'], $order_status['id'], $data['status_processing']);
            $status_cancelled_value[] = array($order_status['name'], $order_status['id'], $data['status_cancelled']);
            $status_delivery_value[] = array($order_status['name'], $order_status['id'], $data['status_delivery']);
        }

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField('Статус подтвержден на доставку', $PHPShopGUI->setSelect('status_processing_new', $status_processing_value));
    $Tab1.= $PHPShopGUI->setField('Статус отменен', $PHPShopGUI->setSelect('status_cancelled_new', $status_cancelled_value));
    $Tab1.= $PHPShopGUI->setField('Статус передан в службу доставки', $PHPShopGUI->setSelect('status_delivery_new', $status_delivery_value));

    $Info = '<p><h4>Получение токена приложения</h4>
        <ol>
        <li>Авторизоваться в Яндексе.
        <li>Перейти по ссылке  <a target="_blank" href="https://oauth.yandex.ru/authorize?response_type=token&client_id=5b5057ed29784d83a5ba85c7c2cae9b9">https://oauth.yandex.ru</a>.
        <li>Разрешить приложению <b>PHPShop Яндекс.Заказ</b> получить доступ к вашим данным на Яндексе.
        <li>Полученный Токен скопировать в поле настройки модуля "Токен для работы с Маркетом".
        </ol>
        
      <h4>Настройки API заказа</h4>
        <ol>
        <li>В поле "URL API" указать: <b>https://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/yandexcart/api.php</b>.
        <li>В поле "SHA1 fingerprint" указать SHA1 подпись вашего SSL сертификата.
        <li>В поле "Авторизационный токен" сгенерировать токен и указать в поле настройки модуля "Пароль авторизации внешних запросов".
        <li>В поле "Тип авторизации" выбрать вариант "URL"
        <li>В поле "Формат данных" выбрать вариант "JSON"
        </ol>
        </p>';

    $Tab2 = $PHPShopGUI->setInfo($Info, 280, '98%');

    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], false);

    // История изменений
    $Tab3.= $PHPShopGUI->setLine('<br>') . $PHPShopGUI->setHistory();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 320), array("Инструкция", $Tab2, 320), array("О Модуле", $Tab3, 320),array("Журнал операций", null,'?path=modules.dir.yandexcart'));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>