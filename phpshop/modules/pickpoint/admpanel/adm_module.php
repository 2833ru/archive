<?php

include_once dirname(__DIR__) . '/class/PickPoint.php';

PHPShopObj::loadClass("order");
PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.pickpoint.pickpoint_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;

    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopSystem;

    // Выборка
    $data = $PHPShopOrm->select();

    $PHPShopGUI->addJSFiles('../modules/pickpoint/admpanel/gui/script.gui.js?v=1.5');
    // Подсказки
    if ($PHPShopSystem->ifSerilizeParam('admoption.dadata_enabled')) {
        $PHPShopGUI->addJSFiles('./js/jquery.suggestions.min.js', './order/gui/dadata.gui.js');
        $PHPShopGUI->addCSSFiles('./css/suggestions.min.css');
    }

    $type_service_value = array(
        array('10001 - стандарт, доставка предоплаченного товара без приема оплаты за товар', 10001, $data['type_service']),
        array('10003 - доставка с приемом оплаты за товар, т.е. наложенный платеж', 10003, $data['type_service'])
    );

    $type_reception_value = array(
        array('101 – вызов курьера', 101, $data['type_reception']),
        array('102 – в окне приема СЦ', 102, $data['type_reception'])
    );

    $Tab1 = $PHPShopGUI->setField('Логин', $PHPShopGUI->setInputText(false, 'login_new', $data['login'], 300));
    $Tab1 .= $PHPShopGUI->setField('Пароль', $PHPShopGUI->setInput("password", 'password_new', $data['password'], false, 300));
    $Tab1 .= $PHPShopGUI->setField('Номер контракта', $PHPShopGUI->setInputText(false, 'ikn_new', $data['ikn'], 300));
    $Tab1 .= $PHPShopGUI->setField('Доставка', $PHPShopGUI->setSelect('delivery_id_new', PickPoint::getDeliveryVariants($data['delivery_id']), 300));
    $Tab1 .= $PHPShopGUI->setField('Текст ссылки', $PHPShopGUI->setInputText(false, 'name_new', $data['name'], 300));
    $Tab1 .= $PHPShopGUI->setField('Типы услуг', $PHPShopGUI->setSelect('type_service_new', $type_service_value, 400, true));
    $Tab1 .= $PHPShopGUI->setField('Вид приема', $PHPShopGUI->setSelect('type_reception_new', $type_reception_value, 400, true));
    $Tab1 .= $PHPShopGUI->setField('Город сдачи отправления', $PHPShopGUI->setInputText(false, 'city_from_new', $data['city_from'], 300));
    $Tab1 .= $PHPShopGUI->setField('Регион города сдачи отправления', $PHPShopGUI->setInputText(false, 'region_from_new', $data['region_from'], 300));
    $Tab1 .= $PHPShopGUI->setField('Статус для отправки', $PHPShopGUI->setSelect('status_new', PickPoint::getStatusesVariants($data['status']), 300));
    $Tab1 .= $PHPShopGUI->setField('Добавить наценку', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="0.1" min="0" value="' . $data['fee'] . '" name="fee_new" style="width:300px;">');
    $Tab1 .= $PHPShopGUI->setField('Тип наценки', $PHPShopGUI->setSelect('fee_type_new', array(array('%', 1, $data['fee_type']), array('Руб.', 2, $data['fee_type'])), 300, null, false, $search = false, false, $size = 1));
    $Tab1 .= $PHPShopGUI->setCollapse('Вес и габариты по умолчанию', $PHPShopGUI->setField('Вес, гр.', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="1" min="1" value="' . $data['weight'] . '" name="weight_new" style="width:300px; ">') .
            $PHPShopGUI->setField('Ширина, см.', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="1" min="1" value="' . $data['width'] . '" name="width_new" style="width:300px;">') .
            $PHPShopGUI->setField('Высота, см.', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="1" min="1" value="' . $data['height'] . '" name="height_new" style="width:300px;">') .
            $PHPShopGUI->setField('Длина, см.', '<input class="form-control input-sm " onkeypress="pickpointvalidate(event)" type="number" step="1" min="1" value="' . $data['length'] . '" name="length_new" style="width:300px;">')
    );

    $info = '
        <h4>Настройка модуля</h4>
       <ol>
        <li>Зарегистрироваться в <a href="https://pickpoint.ru/" target="_blank">PickPoint</a>, заключить договор.</li>
        <li>Ввести логин и пароль к API PickPoint, заполнить номер контракта.</li>
        <li>Выбрать способ доставки для работы модуля.</li>
        <li>Ввести город и регион сдачи отправления, выбрать вид приема и типы услуг.</li>
        <li>Выбрать статус заказа для отправки в личный кабинет PickPoint.</li>
        <li>Настроить вес и габариты по умолчанию.</li>
        </ol>
        
       <h4>Настройка доставки</h4>
        <ol>
        <li>В карточке редактирования доставки в закладке <kbd>Изменение стоимости доставки</kbd> настроить дополнительный параметр сохранения стоимости доставки для модуля. Опция "Не изменять стоимость" должна быть активна.</li>
        <li>В карточке редактирования доставки в закладке <kbd>Адреса пользователя</kbd> отметить <kbd>ФИО</kbd> "Вкл." и "Обязательное"</li>
         <li>В карточке редактирования доставки в закладке <kbd>Адреса пользователя</kbd> отметить <kbd>Телефон</kbd> "Вкл." и "Обязательное"</li>
        </ol>
';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>