<?php

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonrocket.ozonrocket_system"));

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
function actionUpdate()
{
    global $PHPShopModules;

    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    if ((int) $_POST['delivery_id_new'] > 0) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
        $PHPShopOrm->update(array('is_mod_new' => 2), array('id' => '=' . (int) $_POST['delivery_id_new']));
    }

    if (empty($_POST['hide_pvz_new'])) {
        $_POST['hide_pvz_new'] = 0;
    }
    if (empty($_POST['hide_postamat_new'])) {
        $_POST['hide_postamat_new'] = 0;
    }
    if (empty($_POST['show_delivery_time_new'])) {
        $_POST['show_delivery_time_new'] = 0;
    }
    if (empty($_POST['show_delivery_price_new'])) {
        $_POST['show_delivery_price_new'] = 0;
    }
    if (empty($_POST['dev_mode_new'])) {
        $_POST['dev_mode_new'] = 0;
    }
    
    $_POST['token_new'] = str_replace('=','%3D',$_POST['token_new']);
    
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonrocket.ozonrocket_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function actionStart()
{
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->addJSFiles('../modules/ozonrocket/admpanel/gui/script.gui.js');
    $PHPShopGUI->field_col = 4;

    // Выборка
    $data = $PHPShopOrm->select();

    // Статус
    $status[] = [__('Новый заказ'), 0, $data['status']];
    $statusArray = (new PHPShopOrm('phpshop_order_status'))->getList(['id', 'name']);
    foreach ($statusArray as $statusParam) {
        $status[] = [$statusParam['name'], $statusParam['id'], $data['status']];
    }

    // Доставка
    $PHPShopDeliveryArray = new PHPShopDeliveryArray(array('is_folder' => "!='1'", 'enabled' => "='1'"));

    $DeliveryArray = $PHPShopDeliveryArray->getArray();
    $deliveryValue[] = [__('Не выбрано'), 0, $data['delivery_id']];
    if (is_array($DeliveryArray)) {
        foreach ($DeliveryArray as $delivery) {
            if (strpos($delivery['city'], '.')) {
                $name = explode(".", $delivery['city']);
                $delivery['city'] = $name[0];
            }
            $deliveryValue[] = [$delivery['city'], $delivery['id'], $data['delivery_id']];
        }
    }

    $Tab1 = $PHPShopGUI->setField('Токен', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], 300));
    $Tab1.= $PHPShopGUI->setField('Client id', $PHPShopGUI->setInputText(false, 'client_id_new', $data['client_id'], 300));
    $Tab1.= $PHPShopGUI->setField('Client secret', $PHPShopGUI->setInputText(false, 'client_secret_new', $data['client_secret'], 300));
    $Tab1.= $PHPShopGUI->setField('Режим разработки', $PHPShopGUI->setCheckbox("dev_mode_new", 1,"Отправка данных на тестовую среду", $data["dev_mode"]));
    $Tab1.= $PHPShopGUI->setField('Доставка', $PHPShopGUI->setSelect('delivery_id_new', $deliveryValue, 300));
    $Tab1.= $PHPShopGUI->setField('Город на карте по умолчанию', $PHPShopGUI->setInputText(false, 'default_city_new', $data['default_city'], 300));
    $Tab1.= $PHPShopGUI->setField('Статус для отправки', $PHPShopGUI->setSelect('status_new', $status, 300));
    $Tab1.= $PHPShopGUI->setField('Пункты выдачи', $PHPShopGUI->setCheckbox("hide_pvz_new", 1,"Скрыть выбор пункта выдачи", $data["hide_pvz"]));
    $Tab1.= $PHPShopGUI->setField('Постаматы', $PHPShopGUI->setCheckbox("hide_postamat_new", 1, "Скрыть выбор постамата", $data["hide_postamat"]));
    $Tab1.= $PHPShopGUI->setField('Дата доставки', $PHPShopGUI->setCheckbox("show_delivery_time_new", 1, "Показывать дату доставки", $data["show_delivery_time"]));
    $Tab1.= $PHPShopGUI->setField('Цена доставки', $PHPShopGUI->setCheckbox("show_delivery_price_new", 1, "Показывать цену доставки", $data["show_delivery_price"]));
    $Tab1.= $PHPShopGUI->setField('Добавить наценку', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="0.1" min="0" value="' . $data['fee'] . '" name="fee_new" style="width:300px;">');
    $Tab1.= $PHPShopGUI->setField('Тип наценки', $PHPShopGUI->setSelect('fee_type_new', array(array('%', 1, $data['fee_type']), array(__('Руб.'), 2, $data['fee_type'])), 300, null, false, $search = false, false, $size = 1));
    $Tab1.= $PHPShopGUI->setField('Отправка заказа из', $PHPShopGUI->setSelect('type_transfer_new', array(array('Доставка отправителем на склад Ozon', 'DropOff', $data['type_transfer']), array('Забор со склада отправителя', 'PickUp', $data['type_transfer'])), 300,true, false, $search = false, false, $size = 1));
    $Tab1.= $PHPShopGUI->setField('Текст кнопки в товаре', $PHPShopGUI->setInputText(false, 'btn_text_new', $data['btn_text'], 300));
    $Tab1.= $PHPShopGUI->setField('Идентификатор склада отгрузки ', $PHPShopGUI->setInputText(false, 'from_place_id_new', $data['from_place_id'], 300));
    
    $Tab1= $PHPShopGUI->setCollapse('Настройки',$Tab1);
    $Tab1.= $PHPShopGUI->setCollapse('Вес и габариты по умолчанию',
        $PHPShopGUI->setField('Вес, гр.', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="1" min="1" value="' . $data['weight'] . '" name="weight_new" style="width:300px; ">') .
        $PHPShopGUI->setField('Ширина, см.', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="1" min="1" value="' . $data['width'] . '" name="width_new" style="width:300px;">') .
        $PHPShopGUI->setField('Высота, см.', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="1" min="1" value="' . $data['height'] . '" name="height_new" style="width:300px;">') .
        $PHPShopGUI->setField('Длина, см.', '<input class="form-control input-sm " onkeypress="ozonrocketvalidate(event)" type="number" step="1" min="1" value="' . $data['length'] . '" name="length_new" style="width:300px;">')
    );

    $info = '<h4>Настройка модуля</h4>
    <ol>
        <li>
        В личном кабинете OZON Rocket открыть <a href="https://rocket.ozon.ru/" target="_blank">Профиль</a>, выбрать в меню <b>Виджет доставки</b>. Нажать кнопку <b>Скопировать скрипт</b>. В скопированном коде 
            необходимо найти <b>token</b>, например <kbd>token=uT7SIKAUsWUNKjgSpUTgdg%3D%3D</kbd>. Необходимо скопировать значение <b>token</b>, например, <kbd>uT7SIKAUsWUNKjgSpUTgdg%3D%3D</kbd>, в поле 
            Токен настроек модуля.
        </li>
        <li>
        В личном кабинете OZON Rocket открыть <b>Профиль</b>, выбрать в меню <b>Интеграция API</b>. Создать новый API Key 
        и полученные <b>Client id</b> и <b>Client secret</b> вписать в соответствующие поля настроек модуля.
        </li>
        <li>Если вы используете тестовые учетные данные - необходимо установить галочку <b>Режим разработки</b></li>
        <li>Настроить вес и габариты по умолчанию. Они будут использоваться, если у товара не заданы вес и габариты.</li>
        <li>Заполнить остальные настройки по Вашему усмотрению.</li>
    </ol>';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab4 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true,false,true), array("Инструкция", $Tab2), array("О Модуле", $Tab4));

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
