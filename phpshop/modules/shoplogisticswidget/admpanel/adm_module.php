<?php

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.shoplogisticswidget.shoplogisticswidget_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

// Функция обновления
function actionUpdate() {
    global $PHPShopModules;


    // Доставки
    if (isset($_POST['delivery_id_new'])) {
        if (is_array($_POST['delivery_id_new'])) {
            foreach ($_POST['delivery_id_new'] as $val) {
                $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
                $PHPShopOrm->update(array('is_mod_new' => 2), array('id' => '=' . intval($val)));
            }
            $_POST['delivery_id_new'] = @implode(',', $_POST['delivery_id_new']);
        }
    }
    if(empty($_POST['delivery_id_new']))
        $_POST['delivery_id_new'] = '';

    if (empty($_POST["dev_mode_new"]))
        $_POST["dev_mode_new"] = 0;
    
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.shoplogisticswidget.shoplogisticswidget_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();

    $status[] = array(__('Новый заказ'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status) {
            $status[] = array($order_status['name'], $order_status['id'], $data['status']);
        }

    // Доставка
    $PHPShopDeliveryArray = new PHPShopDeliveryArray(array('is_folder' => "!='1'", 'enabled' => "='1'"));

    $DeliveryArray = $PHPShopDeliveryArray->getArray();
    if (is_array($DeliveryArray)) {
        foreach ($DeliveryArray as $delivery) {

            if (strpos($delivery['city'], '.')) {
                $name = explode(".", $delivery['city']);
                $delivery['city'] = $name[0];
            }

            if (in_array($delivery['id'], @explode(",", $data['delivery_id'])))
                $delivery_id = $delivery['id'];
            else
                $delivery_id = null;

            $delivery_value[] = array($delivery['city'], $delivery['id'], $delivery_id);
        }
        foreach ($DeliveryArray as $delivery) {

            if (strpos($delivery['city'], '.')) {
                $name = explode(".", $delivery['city']);
                $delivery['city'] = $name[0];
            }

            if (in_array($delivery['id'], @explode(",", $data['express_delivery_id'])))
                $express_delivery_id = $delivery['id'];
            else
                $express_delivery_id = null;

            $express_delivery_value[] = array($delivery['city'], $delivery['id'],  $express_delivery_id);
        }
    }

    $Tab1 = $PHPShopGUI->setField('API Key', $PHPShopGUI->setInputText(false, 'api_id_new', $data['api_id'], 300));
    $Tab1.= $PHPShopGUI->setField('Ключ', $PHPShopGUI->setInputText(false, 'key_new', $data['key'], 300));
    $Tab1.= $PHPShopGUI->setField('Статус для отправки', $PHPShopGUI->setSelect('status_new', $status, 300));
    $Tab1.= $PHPShopGUI->setField('Доставка', $PHPShopGUI->setSelect('delivery_id_new[]', $delivery_value, 300, null, false, $search = false, false, $size = 1, $multiple = true));
    $Tab1.= $PHPShopGUI->setField('Режим разработки', $PHPShopGUI->setCheckbox("dev_mode_new", 1, "Отправка данных на тестовую среду", $data["dev_mode"]));
    $Tab1.= $PHPShopGUI->setCollapse('Вес и габариты по умолчанию',
        $PHPShopGUI->setField('Вес, гр.', $PHPShopGUI->setInputText('', 'weight_new', $data['weight'],300)) .
        $PHPShopGUI->setField('Ширина, см.', $PHPShopGUI->setInputText('', 'width_new', $data['width'],300)) .
        $PHPShopGUI->setField('Высота, см.', $PHPShopGUI->setInputText('', 'height_new', $data['height'],300)) .
        $PHPShopGUI->setField('Длина, см.', $PHPShopGUI->setInputText('', 'length_new', $data['length'],300))
    );

    $info =
       '<h4>Настройка модуля</h4>
        <ol>
        <li>Зарегистрироваться в <a href="http://shop-logistics.ru" target="_blank">Shop-Logistics</a>, заключить договор.</li>
        <li>Скопировать из <a href="https://client-shop-logistics.ru/index.php?route=account/account" target="_blank">Ваши данные</a> API ID в соответствующее поле настроек модуля.</li>
        <li>Перейти в <a href="https://client-shop-logistics.ru/index.php?route=calculate/options" target="_blank">Настройки калькулятора</a> добавить и настроить новый калькулятор.</li>
        <li>Скопировать Ключ добавленного калькулятора в соответствующее поле настроек модуля.</li>
        <li>Выбрать статус для передачи заказа в личный кабинет Shop-Logistics.</li>
        <li>Настроить параметры веса и габаритов по умолчанию.</li>
        </ol>
        
       <h4>Настройка доставки</h4>
        <ol>
        <li>В карточке редактирования доставки в закладке <kbd>Изменение стоимости доставки</kbd> настроить дополнительный параметр сохранения стоимости доставки для модуля. Опция "Не изменять стоимость" должна быть активна.</li>
        <li>В карточке редактирования доставки выбрать <kbd>Только Регионы и города РФ</kbd></li>
         <li>В карточке редактирования доставки в закладке <kbd>Адреса пользователя</kbd> отметить <kbd>Телефон</kbd> "Вкл." и "Обязательное"</li>
         <li>В карточке редактирования доставки в закладке <kbd>Адреса пользователя</kbd> отметить <kbd>Улица</kbd> "Вкл." и "Обязательное"</li>
         <li>В карточке редактирования доставки в закладке <kbd>Адреса пользователя</kbd> отметить <kbd>Дом</kbd> "Вкл." и "Обязательное"</li>
        <li>Перейти в База/Резервное копирование, выбрать <kbd>citylist_install.sql</kbd> и выбрать <kbd>Восстановить</kbd></li>
        </ol>

';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab4 = $PHPShopGUI->setPay($serial = false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Инструкция", $Tab2), array("О Модуле", $Tab4));

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