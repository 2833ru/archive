<?php

$TitlePage = __('Редактирование покупателя').' #' . $_GET['id'];
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
PHPShopObj::loadClass('user');

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['id'])));

    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    $PHPShopGUI->action_select['Создать заказ'] = array(
        'name' => 'Создать заказ',
        'url' => '?path=order&action=new&user=' . $data['id']
    );

    $PHPShopGUI->action_select['Заказы пользователя'] = array(
        'name' => 'Заказы пользователя',
        'url' => '?path=order&where[a.user]=' . $data['id']
    );

    $PHPShopGUI->action_select['Сообщения пользователя'] = array(
        'name' => 'Сообщения пользователя',
        'url' => '?path=shopusers.messages&where[a.UID]=' . $data['id']
    );

    $PHPShopGUI->action_select['Отправить письмо'] = array(
        'name' => 'Отправить письмо',
        'url' => 'mailto:' . $data['login']
    );

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("Покупатели") . '<span class="hidden-xs"> / ' . $data['name'] . '</span>', array('Отправить письмо', 'Создать заказ', 'Заказы пользователя', 'Сообщения пользователя', '|', 'Удалить'), array('Сохранить', 'Сохранить и закрыть'));
    $PHPShopGUI->addJSFiles('./js/validator.js','./js/jquery.suggestions.min.js','./order/gui/dadata.gui.js');
    $PHPShopGUI->addCSSFiles('./css/suggestions.min.css');

    // Статусы пользователей
    $PHPShopUserStatus = new PHPShopUserStatusArray();
    $PHPShopUserStatusArray = $PHPShopUserStatus->getArray();
    $user_status_value[] = array(__('Пользователь'), 0, $data['status']);
    if (is_array($PHPShopUserStatusArray))
        foreach ($PHPShopUserStatusArray as $user_status)
            $user_status_value[] = array($user_status['name'], $user_status['id'], $data['status']);

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse('Информация', $PHPShopGUI->setField("Имя", $PHPShopGUI->setInput('text.required', "name_new", $data['name'])) .
            $PHPShopGUI->setField("E-mail", $PHPShopGUI->setInput('email.required.6', "login_new", $data['login'])) .
            $PHPShopGUI->setField("Пароль", $PHPShopGUI->setInput("password.required.6", "password_new", base64_decode($data['password']))) .
            $PHPShopGUI->setField("Подтверждение пароля", $PHPShopGUI->setInput("password.required.6", "password2_new", base64_decode($data['password']))) .
            $PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("enabled_new", 1, "Вкл.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выкл.", $data['enabled']) . '&nbsp;&nbsp;' . $PHPShopGUI->setCheckbox('sendActivationEmail', 1, 'Оповестить пользователя', 0)) .
            $PHPShopGUI->setField("Статус", $PHPShopGUI->setSelect('status_new', $user_status_value))
    );

    // Адреса доставок
    $Tab2 = $PHPShopGUI->loadLib('tab_addres', $data['data_adres']);

    // Карта
    $PHPShopGUI->addJSFiles('./shopusers/gui/shopusers.gui.js', '//api-maps.yandex.ru/2.0/?load=package.standard&lang=ru-RU'); 

    $mass = unserialize($data['data_adres']);
    if (strlen($mass['list'][$mass['main']]['street_new']) > 5) {
        $map = '<div id="map" data-geocode="' . $mass['list'][$mass['main']]['city_new'] . ', ' . $mass['list'][$mass['main']]['street_new'] . ' ' . $mass['list'][$mass['main']]['house_new'] . '"></div>';

        $sidebarright[] = array('title' => 'Адрес доставки на карте', 'content' => array($map));

        // Правый сайдбар
        $PHPShopGUI->setSidebarRight($sidebarright, 2);
        $PHPShopGUI->sidebarLeftRight = 2;
    }

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Доставка и реквизиты", $Tab2));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.shopusers.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.shopusers.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.shopusers.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" => $action);
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
    global $PHPShopOrm, $PHPShopModules, $PHPShopSystem;

    if (is_array($_POST['mass']))
        foreach ($_POST['mass'] as $k => $v) {

            // Кодировка windows 1251
            $mass_decode[$k] = @array_map("urldecode", $v);

            // Управление адресами
            if (!empty($_POST['mass'][$k]['default']))
                $_POST['data_adres_new']['main'] = $k;

            if (!empty($_POST['mass'][$k]['delete']))
                unset($mass_decode[$k]);
        }


    // Оповещение пользователя
    if (!empty($_POST['enabled_new']) and !empty($_POST['sendActivationEmail'])) {

        PHPShopObj::loadClass("parser");
        PHPShopObj::loadClass("mail");

        PHPShopParser::set('user_name', $_POST['name_new']);
        PHPShopParser::set('login', $_POST['login_new']);
        PHPShopParser::set('password', $_POST['password_new']);

        $zag_adm = __("Ваш аккаунт был успешно активирован Администратором");
        $PHPShopMail = new PHPShopMail($_POST['login_new'], $PHPShopSystem->getParam('adminmail2'), $zag_adm, '', true, true);
        $content_adm = PHPShopParser::file('../lib/templates/users/mail_user_activation_by_admin_success.tpl', true);

        if (!empty($content_adm)) {
            $PHPShopMail->sendMailNow($content_adm);
        }
    }

    if(!empty($mass_decode))
    $_POST['data_adres_new']['list'] = $mass_decode;
    
    if(is_array($_POST['data_adres_new']))
    $_POST['data_adres_new'] = serialize($_POST['data_adres_new']);
    
    if(!empty($_POST['password_new']))
    $_POST['password_new'] = base64_encode($_POST['password_new']);

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>