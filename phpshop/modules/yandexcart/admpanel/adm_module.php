<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexcart.yandexcart_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate(number_format($option['version'], 1, '.', false));
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->field_col = 4;
    PHPShopObj::loadClass("order");

    // Выборка
    $data = $PHPShopOrm->select();

    $payment_delivery_value[] = array('Наличный расчет + Банковской картой', 1, $data['payment_delivery']);
    $payment_delivery_value[] = array('Наличный расчет при получении', 2, $data['payment_delivery']);


    $Tab1 = $PHPShopGUI->setField('Токен для работы с Маркетом', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], 400,'<a target="_blank" href="https://oauth.yandex.ru/authorize?response_type=token&client_id=5b5057ed29784d83a5ba85c7c2cae9b9">Получить</a>'));
    $Tab1.= $PHPShopGUI->setField('ID Компании в Маркете', $PHPShopGUI->setInputText('11-', 'campaign_new', intval($data['campaign']), 400));
    $Tab1.= $PHPShopGUI->setField('Пароль авторизации внешних запросов', $PHPShopGUI->setInputText(false, 'password_new', $data['password'], 400));
    //$Tab1.= $PHPShopGUI->setField('Способы оплаты', $PHPShopGUI->setSelect('payment_delivery_new', $payment_delivery_value));
    $Tab1.=$PHPShopGUI->setLine();
    //$Tab1.= $PHPShopGUI->setField('Сброс данных по региону доставки', $PHPShopGUI->setCheckbox('region_data_new', 1,'Включить',0));
    

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();

    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status) {
            $status_processing_value[] = array($order_status['name'], $order_status['id'], $data['status_processing']);
            $status_cancelled_value[] = array($order_status['name'], $order_status['id'], $data['status_cancelled']);
            $status_delivery_value[] = array($order_status['name'], $order_status['id'], $data['status_delivery']);
            $status_delivered_value[] = array($order_status['name'], $order_status['id'], $data['status_delivered']);
            $status_cancelled_ucm_value[] = array($order_status['name'], $order_status['id'], $data['status_cancelled_ucm']);
            $status_cancelled_urd_value[] = array($order_status['name'], $order_status['id'], $data['status_cancelled_urd']);
            $status_cancelled_urp_value[] = array($order_status['name'], $order_status['id'], $data['status_cancelled_urp']);
            $status_cancelled_urq_value[] = array($order_status['name'], $order_status['id'], $data['status_cancelled_urq']);
            $status_cancelled_uu_value[] = array($order_status['name'], $order_status['id'], $data['status_cancelled_uu']);
        }

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField('Статус подтвержден клиентом', $PHPShopGUI->setSelect('status_processing_new', $status_processing_value));
    $Tab1.= $PHPShopGUI->setField('Статус передан в службу доставки', $PHPShopGUI->setSelect('status_delivery_new', $status_delivery_value));
    $Tab1.= $PHPShopGUI->setField('Статус доставлен', $PHPShopGUI->setSelect('status_delivered_new', $status_delivered_value));
    $Tab1.= $PHPShopGUI->setField('Статус отменен. Магазин не может выполнить заказ', $PHPShopGUI->setSelect('status_cancelled_new', $status_cancelled_value));
    $Tab1.= $PHPShopGUI->setField('Статус отменен. Покупатель отменил заказ по собственным причинам', $PHPShopGUI->setSelect('status_cancelled_ucm_new', $status_cancelled_ucm_value));
    $Tab1.= $PHPShopGUI->setField('Статус отменен. Покупателя не устраивают условия доставки', $PHPShopGUI->setSelect('status_cancelled_urd_new', $status_cancelled_urd_value));
    $Tab1.= $PHPShopGUI->setField('Статус отменен. Покупателю не подошел товар', $PHPShopGUI->setSelect('status_cancelled_urp_new', $status_cancelled_urp_value));
    $Tab1.= $PHPShopGUI->setField('Статус отменен. Покупателя не устраивает качество товара', $PHPShopGUI->setSelect('status_cancelled_urq_new', $status_cancelled_urq_value));
    $Tab1.= $PHPShopGUI->setField('Статус отменен. Не удалось связаться с покупателем', $PHPShopGUI->setSelect('status_cancelled_uu_new', $status_cancelled_uu_value));
    //$Tab1.= $PHPShopGUI->setField('Максимальное количество дней доставки', $PHPShopGUI->setInputText(false, 'delivery_day_new', intval($data['delivery_day']), 220));

    //$Tab1.=$PHPShopGUI->setField(__('Точки продаж и пункты выдачи'), $PHPShopGUI->setTextarea('outlet_new', $data['outlet'], "none", false, false, __('ID точек продаж через запятую')));

    // Инструкция
    $Tab2 = $PHPShopGUI->loadLib('tab_info', $data,'../modules/'.$_GET['id'].'/admpanel/');

    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Инструкция", $Tab2), array("О Модуле", $Tab3), array("Журнал операций", null, '?path=modules.dir.yandexcart'));

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
    $_POST['region_data_new']=1;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>