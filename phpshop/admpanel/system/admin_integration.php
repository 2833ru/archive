<?php

$TitlePage = __("Настройка интеграций с сервисами");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm,$PHPShopBase;

// Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

// Размер названия поля
    $PHPShopGUI->field_col = 3;


    $PHPShopGUI->setActionPanel($TitlePage, false, array('Сохранить'));

    // Демо-режим
    if ($PHPShopBase->getParam('template_theme.demo') == 'true') {
     $option['metrica_token']='';
    }

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка статистики посещений Яндекс.Метрика', $PHPShopGUI->setField('Токен', $PHPShopGUI->setInputText(false, 'option[metrica_token]', $option['metrica_token'], 370, '<a target="_blank" href="https://oauth.yandex.ru/authorize?response_type=token&client_id=78246cbd13f74fbd9cb2b48d8bff2559">Получить</a>')) .
            $PHPShopGUI->setField('ID сайта', $PHPShopGUI->setInputText(null, 'option[metrica_id]', $option['metrica_id'], 300, false, false, false, '31247043') .
                    $PHPShopGUI->setHelp('Отчеты доступны в разделе <a href="?path=metrica">Статистика посещений</a>')) .
            $PHPShopGUI->setField("Код счетчика", $PHPShopGUI->setCheckbox('option[metrica_enabled]', 1, 'Включить сбор статистики и разместить код счетчика', $option['metrica_enabled'])) .
            $PHPShopGUI->setField("Виджет", $PHPShopGUI->setCheckbox('option[metrica_widget]', 1, 'Включить виджет статистики в панель инструментов', $option['metrica_widget']))
            , 'in', false
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка безопасности reCAPTCHA', $PHPShopGUI->setField("reCAPTCHA", $PHPShopGUI->setCheckbox('option[recaptcha_enabled]', 1, 'Включить режим усиленной проверки от ботов', $option['recaptcha_enabled']), 1, 'Поддерживаются только новые шаблоны') .
            $PHPShopGUI->setField("Публичный ключ", $PHPShopGUI->setInputText(null, "option[recaptcha_pkey]", $option['recaptcha_pkey'], 300)) .
            $PHPShopGUI->setField("Секретный ключ", $PHPShopGUI->setInputText(null, "option[recaptcha_skey]", $option['recaptcha_skey'], 300) . $PHPShopGUI->setHelp('Персональные ключи для домена выдаются через <a href="https://www.google.com/recaptcha" target="_blank">Google.com</a>'))
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка подсказок DaData.ru', $PHPShopGUI->setField("Подсказки", $PHPShopGUI->setCheckbox('option[dadata_enabled]', 1, 'Включить подсказки DaData.ru', $option['dadata_enabled']), 1, 'Поддерживаются только новые шаблоны') .
            $PHPShopGUI->setField("Публичный ключ", $PHPShopGUI->setInputText(null, "option[dadata_token]", $option['dadata_token'], 300) . $PHPShopGUI->setHelp('Информация о сервисе, регистрация, получение ключей <a href="https://dadata.ru" target="_blank">DaData.ru</a>'))
    );

    $PHPShopGUI->_CODE.=$PHPShopGUI->setCollapse('Настройка sms уведомлений terasms.ru', $PHPShopGUI->setField("SMS оповещение", $PHPShopGUI->setCheckbox('option[sms_enabled]', 1, 'Уведомление о заказе администратору', $option['sms_enabled']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[sms_status_order_enabled]', 1, 'Уведомление о статусе заказа пользователю', $option['sms_status_order_enabled']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[notice_enabled]', 1, 'Уведомление о наличии товара пользователям', $option['notice_enabled']), 1, 'Используется сервис terasms.ru'
            ) .
            $PHPShopGUI->setField("Мобильный телефон", $PHPShopGUI->setInputText(null, "option[sms_phone]", $option['sms_phone'], 300), 1, 'Телефон для SMS уведомлений формата 79261234567') .
            $PHPShopGUI->setField("Пользователь", $PHPShopGUI->setInputText(null, "option[sms_user]", $option['sms_user'], 300), 1, 'Пользователь в системе terasms.ru') .
            $PHPShopGUI->setField("Пароль", $PHPShopGUI->setInput('password', "option[sms_pass]", $option['sms_pass'], null, 300), 1, 'Пароль в системе terasms.ru') .
            $PHPShopGUI->setField("Имя отправителя", $PHPShopGUI->setInputText(null, "option[sms_name]", $option['sms_name'], 300), 1, 'Зарегистрированное имя отправителя в terasms.ru')
    );


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.system.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $sidebarleft[] = array('title' => 'Категории', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './system/'));
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // Футер
    $PHPShopGUI->Compile(2);
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

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['admoption']);

    // Корректировка пустых значений
    $PHPShopOrm->updateZeroVars('option.recaptcha_enabled', 'option.dadata_enabled', 'option.sms_enabled', 'option.sms_status_order_enabled', 'option.notice_enabled', 'option.metrica_enabled', 'option.metrica_widget');

    if (is_array($_POST['option']))
        foreach ($_POST['option'] as $key => $val)
            $option[$key] = $val;


    $_POST['admoption_new'] = serialize($option);

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));


    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();
?>