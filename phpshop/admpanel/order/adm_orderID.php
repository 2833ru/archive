<?php

PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass("user");
PHPShopObj::loadClass("text");

$TitlePage = __('Редактирование Заказа #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);

$PHPShopDelivery = new PHPShopDelivery();

$PHPShopValutaArray = new PHPShopValutaArray();

/**
 * Перерасчет скидки
 */
function updateDiscount($data) {
    global $link_db;

    // Статусы заказов
    //$PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    //$GetOrderStatusArray = $PHPShopOrderStatusArray->getArray();
    //Если нужно пересчитать персональную скидку пользователя
    //if ($GetOrderStatusArray[$_POST['statusi_new']]['cumulative_action'] == 1) {
    //Запрос статуса пользователя
    $sql_st = "SELECT * FROM `" . $GLOBALS['SysValue']['base']['table_name27'] . "` WHERE `id` =" . intval($data['user']) . " ";
    $query_st = mysqli_query($link_db, $sql_st);
    $row_st = mysqli_fetch_array($query_st);
    $status_user = $row_st['status'];


    //Запрос алгоритма расчета персональной скидки
    $sql_d = "SELECT * FROM `" . $GLOBALS['SysValue']['base']['table_name28'] . "` WHERE `id` =" . intval($status_user) . " ";
    $query_d = mysqli_query($link_db, $sql_d);
    $row_d = mysqli_fetch_array($query_d);
    $cumulative_array = unserialize($row_d['cumulative_discount']);
    $cumulative_array_check = $row_d['cumulative_discount_check'];
    if ($cumulative_array_check == 1) {
        //Список заказов
        $sql_order = "SELECT " . $GLOBALS['SysValue']['base']['table_name1'] . ".* FROM `" . $GLOBALS['SysValue']['base']['table_name1'] . "`
            LEFT JOIN `" . $GLOBALS['SysValue']['base']['table_name32'] . "` ON " . $GLOBALS['SysValue']['base']['table_name1'] . ".statusi=" . $GLOBALS['SysValue']['base']['table_name32'] . ".id
            WHERE " . $GLOBALS['SysValue']['base']['table_name1'] . ".user =  " . $data['user'] . "
            AND " . $GLOBALS['SysValue']['base']['table_name32'] . ".cumulative_action='1' ";
        $query_order = mysqli_query($link_db, $sql_order);
        $row_order = mysqli_fetch_array($query_order);
        $sum = '0'; //Очистка суммы
        do {
            $orders = unserialize($row_order['orders']);
            $sum += $orders['Cart']['sum'];
        } while ($row_order = mysqli_fetch_array($query_order));

        //Узнаем скидку
        $q_cumulative_discount = '0'; //Очистка скидки
        foreach ($cumulative_array as $key => $value) {
            if ($sum >= $value['cumulative_sum_ot'] and $sum <= $value['cumulative_sum_do']) {
                $q_cumulative_discount = $value['cumulative_discount'];
                break;
            }
        }
        //Обновляем скидку
        $sql_update = "UPDATE  `" . $GLOBALS['SysValue']['base']['table_name27'] . "` SET `cumulative_discount` =  '" . $q_cumulative_discount . "' WHERE `id` =" . intval($data['user']) . " ";
        mysqli_query($link_db, $sql_update);
    } else {
        $sql_update = "UPDATE  `" . $GLOBALS['SysValue']['base']['table_name27'] . "` SET `cumulative_discount` =  '0' WHERE `id` =" . intval($data['user']) . " ";
        mysqli_query($link_db, $sql_update);
    }
    //}
}

/**
 * Списывание со склада
 */
function updateStore($data) {
    global $PHPShopSystem, $PHPShopBase, $_classPath;

    // Статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $GetOrderStatusArray = $PHPShopOrderStatusArray->getArray();


    // SMS оповещение пользователю о смене статуса заказа
    if ($data['statusi'] != $_POST['statusi_new'] and $PHPShopSystem->ifSerilizeParam('admoption.sms_status_order_enabled')) {

        $phone = $_POST['tel_new'];
        $msg = strtoupper($_SERVER['SERVER_NAME']) . ': ' . $PHPShopBase->getParam('lang.sms_user') . $data['uid'] . " - " . $GetOrderStatusArray[$_POST['statusi_new']]['name'];

        // Проверка на первую 7 или 8
        $first_d = substr($phone, 0, 1);
        if ($first_d != 8)
            $phone = '7' . $phone;

        $lib = str_replace('./phpshop/', $_classPath, $PHPShopBase->getParam('file.sms'));
        include_once $lib;
        SendSMS($msg, $phone);
    }


    // Если новый статус Аннулирован, а был статус не Новый заказ, то мы не списываем, а добавляем обратно
    if ($data['statusi'] != 0 && $_POST['statusi_new'] == 1) {
        if (is_array($data)) {
            $order = unserialize($data['orders']);
            if (is_array($order['Cart']['cart']))
                foreach ($order['Cart']['cart'] as $val) {

                    // Данные по складу
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $product_row = $PHPShopOrm->select(array('items'), array('id' => '=' . intval($val['id'])), false, array('limit' => 1));
                    if (is_array($product_row)) {

                        // Склад
                        $product_update['items_new'] = $product_row['items'] + $val['num'];
                        $product_update['sklad_new'] = 0;
                        $product_update['enabled_new'] = 1;

                        // Обновляем данные
                        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                        $PHPShopOrm->debug = false;
                        $PHPShopOrm->update($product_update, array('id' => '=' . $val['id']));
                    }
                }
        }
    } else if ($GetOrderStatusArray[$_POST['statusi_new']]['sklad_action'] == 1 and $GetOrderStatusArray[$data['statusi']]['sklad_action'] != 1) {
        if (is_array($data)) {
            $order = unserialize($data['orders']);
            if (is_array($order['Cart']['cart']))
                foreach ($order['Cart']['cart'] as $val) {

                    // Данные по складу
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                    $product_row = $PHPShopOrm->select(array('items'), array('id' => '=' . intval($val['id'])), false, array('limit' => 1));
                    if (is_array($product_row)) {

                        // Склад
                        $product_update['items_new'] = $product_row['items'] - $val['num'];

                        // Списывание со склада
                        switch ($PHPShopSystem->getSerilizeParam('admoption.sklad_status')) {

                            case(3):
                                if ($product_update['items_new'] < 1) {
                                    $product_update['sklad_new'] = 1;
                                    $product_update['enabled_new'] = 1;
                                } else {
                                    $product_update['sklad_new'] = 0;
                                    $product_update['enabled_new'] = 1;
                                }
                                break;

                            case(2):
                                if ($product_update['items_new'] < 1) {
                                    $product_update['enabled_new'] = 0;
                                    $product_update['sklad_new'] = 0;
                                } else {
                                    $product_update['enabled_new'] = 1;
                                    $product_update['sklad_new'] = 0;
                                }
                                break;

                            default:
                                break;
                        }

                        // Обновляем данные
                        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
                        $PHPShopOrm->debug = false;
                        $PHPShopOrm->update($product_update, array('id' => '=' . intval($val['id'])));
                    }
                }
        }
    }
}

/**
 * Экшен загрузки форм редактирования
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm;

    // Выборка
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['id'])), false, array('limit' => 1));

    $PHPShopGUI->addJSFiles('./order/gui/order.gui.js');
    if (strlen($data['street']) > 5)
        $PHPShopGUI->addJSFiles('//api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU');

    $PHPShopGUI->action_select['Все заказы пользователя'] = array(
        'name' => 'Все заказы пользователя',
        'action' => 'order-list',
        'url' => '?path=' . $_GET['path'] . '&where[a.user]=' . $data['user']
    );

    $PHPShopGUI->action_select['Отчет по заказам'] = array(
        'name' => 'Отчет по заказам',
        'action' => 'order-list',
        'url' => '?path=report.statorder&where[a.user]=' . $data['user'].'&date_start=01-01-2010&date_end='.PHPShopDate::get()
    );


    // Библиотека заказа
    $PHPShopOrder = new PHPShopOrderFunction($data['id'], $data);

    $update_date = $PHPShopOrder->getStatusTime();
    if (!empty($update_date))
        $update_date = __(' / Изменен: ') . $update_date;

    // Знак рубля
    if ($PHPShopOrder->default_valuta_iso == 'RUB' or $PHPShopOrder->default_valuta_iso == 'RUR')
        $currency = ' <span class=rubznak>p</span>';
    else
        $currency = $PHPShopOrder->default_valuta_iso;


    $PHPShopGUI->setActionPanel(__("Заказ") . ' № ' . $data['uid'] . ' <span class="hidden-xs hidden-md">/ ' . PHPShopDate::dataV($data['datas']) . $update_date . ' / ' . __("Итого") . ': ' . $PHPShopOrder->getTotal(false, ' ') . $currency . '</span>', array('Сделать копию', 'Все заказы пользователя','Отчет по заказам', '|', 'Удалить'), array('Сохранить', 'Сохранить и закрыть'));

    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    $order = unserialize($data['orders']);
    $status = unserialize($data['status']);

    $house = $porch = $flat = null;
    if (!empty($data['house']))
        $house = ', д. ' . $data['house'];

    if (!empty($data['porch']))
        $porch = ', под. ' . $data['porch'];

    if (!empty($data['flat']))
        $flat = ', кв. ' . $data['flat'];

    // Информация о покупателе
    $sidebarleft[] = array('id' => 'user-data-1', 'title' => 'Информация о покупателе', 'icon' => 'user text-muted', 'name' => array('caption' => $data['fio'], 'link' => '?path=shopusers&return=order.' . $data['id'] . '&id=' . $data['user']), 'content' => array(array('caption' => $order['Person']['mail'], 'link' => 'mailto:' . $order['Person']['mail']), $data['tel']));

    // Адрес доставки
    $sidebarleft[] = array('id' => 'user-data-2', 'title' => 'Адрес доставки', 'icon' => 'road text-muted', 'name' => $data['fio'], 'content' => array($data['tel'], $data['street'] . $house . $porch . $flat));

    // Карта
    if (strlen($data['street']) > 5) {
        $map = '<div id="map" class="visible-lg" data-geocode="' . $data['city'] . ', ' . $data['street'] . ' ' . $data['house'] . '" data-title="Заказ №' . $data['uid'] . '"></div><div class="data-row"><a href="http://maps.yandex.ru/?&source=wizgeo&text=' . urlencode(PHPShopString::win_utf8($data['city'] . ', ' . $data['street'] . ' ' . $data['house'])) . '" target="_blank" class="text-muted"><span class="glyphicon glyphicon-map-marker"></span>Увеличить карту</a></div>';
        $sidebarleft[] = array('title' => 'Адрес доставки на карте', 'content' => array($map));
    }

    // Левый сайдбар
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2, true);

    // Статусы заказов
    PHPShopObj::loadClass('order');

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['statusi'], 'data-content="<span class=\'glyphicon glyphicon-text-background\' style=\'color:#35A6E8\'></span> ' . __('Новый заказ') . '"');
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $k => $order_status) {
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['statusi'], 'data-content="<span class=\'glyphicon glyphicon-text-background\' style=\'color:' . $order_status['color'] . '\'></span> ' . $order_status['name'] . '"');
        }

    // Статус заказа
    $status_dropdown = $PHPShopGUI->setSelect('statusi_new', $order_status_value, 180);
    $sidebarright[] = array('title' => 'Статус заказа', 'content' => $status_dropdown);

    // Время обработки заказа
    if (empty($status['time']))
        $status['time'] = PHPShopDate::dataV($data['datas'], true, false, ' ', true);

    // Доступые типы оплат
    $PHPShopPaymentArray = new PHPShopPaymentArray();
    $PaymentArray = $PHPShopPaymentArray->getArray();
    if (is_array($PaymentArray))
        foreach ($PaymentArray as $payment) {

            // Длинные наименования
            if (strpos($payment['name'], '.')) {
                $name = explode(".", $payment['name']);
                $payment['name'] = $name[0];
            }

            $payment_value[] = array($payment['name'], $payment['id'], $order['Person']['order_metod']);
        }

    // Тип оплаты
    $payment_dropdown = $PHPShopGUI->setSelect('person[order_metod]', $payment_value, 180);


    // Информация об оплате
    $sidebarright[] = array('title' => 'Информация об оплате', 'content' => $payment_dropdown);

    // время доставки под старый формат данных в заказе
    if (!empty($order['Person']['dos_ot']) OR !empty($order['Person']['dos_do']))
        $dost_ot = " От: " . $order['Person']['dos_ot'] . ", до: " . $order['Person']['dos_do'];

    // Печатные бланки
    $Tab_print = $PHPShopGUI->loadLib('tab_print', $data);

    // Доставка
    $PHPShopDeliveryArray = new PHPShopDeliveryArray();

    $DeliveryArray = $PHPShopDeliveryArray->getArray();
    if (is_array($DeliveryArray))
        foreach ($DeliveryArray as $delivery) {

            // Длинные наименования
            if (strpos($delivery['city'], '.')) {
                $name = explode(".", $delivery['city']);
                $delivery['city'] = $name[0];
            }

            $delivery_value[] = array($delivery['city'], $delivery['id'], $order['Person']['dostavka_metod'], 'data-subtext="' . $delivery['price'] . ' ' . $currency . '"');
        }

    $delivery_content[] = $PHPShopGUI->setSelect('person[dostavka_metod]', $delivery_value, 180);

    $sidebarright[] = array('title' => 'Информация о доставке', 'content' => $delivery_content);
    $sidebarright[] = array('title' => 'Печатные бланки', 'content' => $Tab_print, 'idelement' => 'letterheads');

    // Корзина
    $Tab2 = $PHPShopGUI->loadLib('tab_cart', $data);

    // Данные покупателя
    $Tab3 = $PHPShopGUI->loadLib('tab_userdata', $data);

    // Все заказы пользователя
    $Tab4 = $PHPShopGUI->loadLib('tab_userorders', $data, false, array('status' => $OrderStatusArray, 'currency' => $currency, 'color' => $OrderStatusArray));

    // Правый сайдбар
    $PHPShopGUI->setSidebarRight($sidebarright);

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Корзина"), $Tab2), array(__("Данные покупателя"), $Tab3), array(__("Заказы пользователя"), $Tab4));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.order.remove") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.order.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.order.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

/**
 * Оповещение пользователя о новом статусе
 * @param array $data массив данных заказа
 */
function sendUserMail($data) {
    global $PHPShopSystem;

    if ($data['statusi'] != $_POST['statusi_new']) {
        PHPShopObj::loadClass("parser");
        PHPShopObj::loadClass("mail");
        PHPShopParser::set('ouid', $data['uid']);
        PHPShopParser::set('date', PHPShopDate::dataV($data['datas']));

        // Доступные статусы заказов
        $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
        PHPShopParser::set('status', $PHPShopOrderStatusArray->getParam($_POST['statusi_new'] . '.name'));
        PHPShopParser::set('user', $data['user']);
        PHPShopParser::set('company', $PHPShopSystem->getParam('name'));
//        $title = $PHPShopSystem->getValue('name') . ' - статус заказа ' . $data['uid'] . ' изменен';
        $title = 'Cтатус заказа ' . $data['uid'] . ' изменен';
        $order = unserialize($data['orders']);

        PHPShopParser::set('mail', $order['Person']['mail']);
        PHPShopParser::set('user_name', $order['Person']['name_person']);


        $PHPShopMail = new PHPShopMail($order['Person']['mail'], $PHPShopSystem->getValue('adminmail2'), $title, '', true, true);
        $content = PHPShopParser::file('../lib/templates/order/status.tpl', true);
        if (!empty($content)) {
            $PHPShopMail->sendMailNow($content);
        }
    }
}

/**
 * Экшен обновления
 * @return bool
 */
function actionUpdate() {
    global $PHPShopModules, $PHPShopOrm;


    // Данные по заказу
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['rowID'])));
    $order = unserialize($data['orders']);

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Новые данные
    if (is_array($_POST['person']))
        foreach ($_POST['person'] as $k => $v)
            $order['Person'][$k] = $v;

    // Доставка
    $PHPShopCart = new PHPShopCart($order['Cart']['cart']);
    $PHPShopDelivery = new PHPShopDelivery($_POST['person']['dostavka_metod']);
    $order['Cart']['dostavka'] = $PHPShopDelivery->getPrice($PHPShopCart->getSum(false), $PHPShopCart->getWeight());

    // Сериализация данных заказа
    $_POST['orders_new'] = serialize($order);

    // Комментарий и время обработки
    $_POST['status']['time'] = PHPShopDate::dataV();
    $_POST['status_new'] = serialize($_POST['status']);
    $_POST['sum_new'] = $order['Cart']['sum'] + $order['Cart']['dostavka'];

    $PHPShopOrm->clean();

    // Списывание со склада из корзины
    updateStore($data);

    // Оповещение пользователя о новом статусе
    sendUserMail($data);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));

    // Персональная скидка
    //updateDiscount($data);

    return array('success' => $action);
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules, $PHPShopBase;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    // Проверка прав на удаление
    if ($PHPShopBase->Rule->CheckedRules('order', 'remove')) {

        $PHPShopOrm->debug = false;
        $action = $PHPShopOrm->delete(array('id' => '=' . intval($_POST['rowID'])));
    }
    else
        $action = false;

    return array('success' => $action);
}

/**
 * Экшен редакирования корзины из модального окна
 */
function actionValueEdit() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopSystem;

    // ИД заказа
    $orderID = intval($_REQUEST['id']);

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $orderID), false, array('limit' => 1));

    $order = unserialize($data['orders']);

    // ИД товара
    $productID = intval($_REQUEST['selectID']);

    if (empty($order['Cart']['cart'][$productID]['id'])) {
        foreach ($order['Cart']['cart'] as $key => $val)
            if ($val['id'] == $productID) {
                $productID = $key;
            }
    }


    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Название', $PHPShopGUI->setInputArg(array('name' => 'name_value', 'type' => 'text.required', 'value' => $order['Cart']['cart'][$productID]['name'])));
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Количество', $PHPShopGUI->setInputArg(array('name' => 'num_value', 'type' => 'text', 'value' => $order['Cart']['cart'][$productID]['num'], 'size' => 100)));
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Цена', $PHPShopGUI->setInputArg(array('name' => 'price_value', 'type' => 'text', 'value' => $order['Cart']['cart'][$productID]['price'], 'size' => 150, 'description' => $PHPShopSystem->getDefaultValutaCode())));

    $PHPShopGUI->_CODE.=$PHPShopGUI->setInputArg(array('name' => 'rowID', 'type' => 'hidden', 'value' => $productID));
    $PHPShopGUI->_CODE.=$PHPShopGUI->setInputArg(array('name' => 'orderID', 'type' => 'hidden', 'value' => $orderID));
    $PHPShopGUI->_CODE.=$PHPShopGUI->setInputArg(array('name' => 'parentID', 'type' => 'hidden', 'value' => $_REQUEST['parentID']));

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    exit($PHPShopGUI->_CODE . '<p class="clearfix"> </p>');
}

/**
 * Экшен обновления корзины из модального окна
 */
function actionCartUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    // ИД заказа
    $orderID = intval($_REQUEST['id']);

    // ИД товара
    $productID = PHPShopString::utf8_win1251($_REQUEST['selectID']);

    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $orderID), false, array('limit' => 1));
    if (is_array($data)) {
        $order = unserialize($data['orders']);
        $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);

        // Библиотека корзины
        $PHPShopCart = new PHPShopCart($order['Cart']['cart']);

        // Действие с корзиной
        switch ($_POST['selectAction']) {

            // Обновление скидки
            case "discount":

                $order['Person']['discount'] = floatval($_REQUEST['selectID']);

                // Сериализация данных заказа
                //$update['orders_new'] = serialize($order);
                //$PHPShopOrm->clean();
                //$PHPShopOrm->update($update, array('id' => '=' . $orderID));

                break;

            // Удаление товара из корзины
            case "delete":
                unset($order['Cart']['cart'][$productID]);
                break;

            // Добавление товара
            case "add":

                // Добавление товара по ID
                if (!empty($productID)) {


                    // Добавление
                    if (empty($_SESSION['selectCart'][$productID])) {


                        // Добавляем новый товар 1 шт по ID
                        if ($PHPShopCart->add($productID, abs($_REQUEST['selectNum']))) {

                            // Возвращаем массив измененной корзины
                            $order['Cart']['cart'] = $PHPShopCart->getArray();
                            $order['Cart']['num'] = $PHPShopCart->getNum();
                            $order['Cart']['sum'] = $PHPShopCart->getSum(false);
                        }
                    }

                    // Редактирование кол-во
                    else {

                        $PHPShopCart->edit($productID, abs($_REQUEST['selectNum']));

                        // Возвращаем массив измененной корзины
                        $order['Cart']['cart'] = $PHPShopCart->getArray();
                        $order['Cart']['num'] = $PHPShopCart->getNum();
                        $order['Cart']['sum'] = $PHPShopCart->getSum(false);
                    }
                }
                break;

            // Обновление цены и кол-ва
            default:
                // Имя товара
                if (!empty($_POST['name_value']))
                    $order['Cart']['cart'][$productID]['name'] = $_POST['name_value'];

                // Количество
                if (!empty($_POST['num_value']))
                    $order['Cart']['cart'][$productID]['num'] = $_POST['num_value'];

                // Цена
                if (!empty($_POST['price_value']))
                    $order['Cart']['cart'][$productID]['price'] = $_POST['price_value'];
        }

        $PHPShopOrder = new PHPShopOrderFunction(false, $order['Cart']['cart']);


        // Библиотека корзины
        $PHPShopCart = new PHPShopCart($order['Cart']['cart']);
        $order['Cart']['sum'] = $PHPShopOrder->returnSumma($PHPShopCart->getSum(false), $order['Person']['discount']);
        $order['Cart']['num'] = $PHPShopCart->getNum();
        $order['Cart']['weight'] = $PHPShopCart->getWeight();
        $order['Cart']['dostavka'] = $PHPShopDelivery->getPrice($PHPShopCart->getSum(false), $PHPShopCart->getWeight());

        // Сериализация данных заказа
        $update['orders_new'] = serialize($order);
        $update['sum_new'] = $order['Cart']['sum'] + $order['Cart']['dostavka'];
        $PHPShopOrm->clean();
        $PHPShopCart->clean();

        // Перехват модуля
        $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

        $action = $PHPShopOrm->update($update, array('id' => '=' . $orderID));

        return array('success' => $action);
    }
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>