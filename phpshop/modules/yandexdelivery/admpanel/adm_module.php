<?php

include_once dirname(__DIR__) . '/class/include.php';

// SQL
$PHPShopOrm = new PHPShopOrm('phpshop_modules_yandexdelivery_system');

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

    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

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

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexdelivery.yandexdelivery_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $PHPShopGUI->addJSFiles('../modules/yandexdelivery/admpanel/gui/script.gui.js?v=1.0');

    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('Авторизационный ключ', $PHPShopGUI->setInputText(false, 'api_key_new', $data['api_key'], 300));
    $Tab1.= $PHPShopGUI->setField('Токен Яндекс.OAuth', $PHPShopGUI->setInputText(false, 'token_new', $data['token'], 300));
    $Tab1.= $PHPShopGUI->setField('Идентификатор магазина', $PHPShopGUI->setInputText(false, 'sender_id_new', $data['sender_id'], 300));
    $Tab1.= $PHPShopGUI->setField('Идентификатор склада', $PHPShopGUI->setInputText(false, 'warehouse_id_new', $data['warehouse_id'], 300));
    $Tab1.= $PHPShopGUI->setField('Статус для отправки', $PHPShopGUI->setSelect('status_new', YandexDelivery::getDeliveryStatuses($data['status']), 300));
    $Tab1.= $PHPShopGUI->setField('Доставка', $PHPShopGUI->setSelect('delivery_id_new', YandexDelivery::getDeliveryVariants($data['delivery_id']), 300));
    $Tab1.= $PHPShopGUI->setField('Объявленная ценность', $PHPShopGUI->setInputText('От цены товара', 'declared_percent_new', $data['declared_percent'], 300,'%'));
    $Tab1.= $PHPShopGUI->setField('Добавить наценку', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="0.1" min="0" value="' . $data['fee'] . '" name="fee_new" style="width:300px;">');
    $Tab1.= $PHPShopGUI->setField('Тип наценки', $PHPShopGUI->setSelect('fee_type_new', array(array('%', 1, $data['fee_type']), array('Руб.', 2, $data['fee_type'])), 300, null, false, $search = false, false, $size = 1));
    $Tab1.= $PHPShopGUI->setCollapse('Вес и габариты по умолчанию',
        $PHPShopGUI->setField('Вес, гр.', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="1" min="1" value="' . $data['weight'] . '" name="weight_new" style="width:300px; ">') .
        $PHPShopGUI->setField('Ширина, см.', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="1" min="1" value="' . $data['width'] . '" name="width_new" style="width:300px;">') .
        $PHPShopGUI->setField('Высота, см.', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="1" min="1" value="' . $data['height'] . '" name="height_new" style="width:300px;">') .
        $PHPShopGUI->setField('Длина, см.', '<input class="form-control input-sm " onkeypress="yadeliveryvalidate(event)" type="number" step="1" min="1" value="' . $data['length'] . '" name="length_new" style="width:300px;">')
    );

    $info = '<h4>Получение учетных данных и настройка</h4>
       <ol>
        <li>Зарегистрируйтесь в <a href="https://delivery.yandex.ru" target="_blank">Яндекс.Доставка</a> заполните все необходимые данные и создайте магазин. Откройте Настройки/Магазины, скопируйте "Идентификатор" в поле "Идентификатор магазина".</li>
        <li>Получите <a href="https://yandex.ru/support/delivery-3/widgets/widgets.html#begin__api-key" target="_blank">авторизационный ключ</a>, впишите его в поле "Авторизационный ключ" настроек модуля.</li>
        <li>Получите  <a href="https://yandex.ru/dev/delivery-3/doc/dg/concepts/access.html#access__token" target="_blank">Токен Яндекс.OAuth</a> и впишите его в поле "Токен Яндекс.OAuth".</li>
        <li>В личном кабинете Яндекс.Доставка откройте Настройки/Склады, скопируйте "Идентификатор" склада в поле "Идентификатор склада" настроек модуля.</li>
        <li>Выбрать способ доставки для работы модуля.</li>
        <li>Выбрать статус для передачи заказа в личный кабинет Яндекс.Доставки.</li>
        </ol>
        
       <h4>Настройка доставки</h4>
        <ol>
        <li>В карточке редактирования доставки в закладке <kbd>Изменение стоимости доставки</kbd> настроить дополнительный параметр сохранения стоимости доставки для модуля. Опция "Не изменять стоимость" должна быть активна.</li>
         <li>В карточке редактирования доставки в закладке <kbd>Адреса пользователя</kbd> отметить <kbd>Телефон</kbd> "Вкл." и "Обязательное"</li>
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