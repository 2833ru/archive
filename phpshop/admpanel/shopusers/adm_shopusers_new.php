<?php

$TitlePage = __('Создание пользователя');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
PHPShopObj::loadClass('user');

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $TitlePage, $PHPShopModules;


    // Начальные данные
    $data['enabled'] = 1;


    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }


    $PHPShopGUI->action_select['Создать заказ'] = array(
        'name' => 'Создать заказ',
        'url' => '?path=order&action=new&where[user]=' . $data['id']
    );

    $PHPShopGUI->action_select['Заказы пользователя'] = array(
        'name' => 'Заказы пользователя',
        'url' => '?path=order&where[user]=' . $data['id']
    );

    $PHPShopGUI->action_select['Отправить письмо'] = array(
        'name' => 'Отправить письмо',
        'url' => 'mailto:' . $data['login']
    );

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("Покупатели"). ' / '.$TitlePage, false, array('Сохранить и закрыть', 'Создать и редактировать'));
    $PHPShopGUI->addJSFiles('./js/validator.js');

    // Стытусы пользователей
    $PHPShopUserStatus = new PHPShopUserStatusArray();
    $PHPShopUserStatusArray = $PHPShopUserStatus->getArray();
    $user_status_value[] = array(__('Пользователь'), 0, $data['status']);
    if (is_array($PHPShopUserStatusArray))
        foreach ($PHPShopUserStatusArray as $user_status)
            $user_status_value[] = array($user_status['name'], $user_status['id'], $data['status']);

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse('Информация', $PHPShopGUI->setField("Имя", $PHPShopGUI->setInput('text.required', "name_new", $data['name'])) .
            $PHPShopGUI->setField("E-mail", $PHPShopGUI->setInput('email.required.6', "login_new", $data['login'])) .
            $PHPShopGUI->setField("Телефон", $PHPShopGUI->setInput('tel', "tel_new", $data['tel'])) .
            $PHPShopGUI->setField("Пароль", $PHPShopGUI->setInput("password.required.4", "password_new", $data['password'])) .
            $PHPShopGUI->setField("Подтверждение пароля", $PHPShopGUI->setInput("password.required.4", "password2_new", $data['password'])) .
            $PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("enabled_new", 1, "Вкл.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выкл.", $data['enabled']) . '&nbsp;&nbsp;' . $PHPShopGUI->setCheckbox('sendActivationEmail', 1, 'Оповестить пользователя', 0)) .
            $PHPShopGUI->setField("Статус", $PHPShopGUI->setSelect('status_new', $user_status_value)).
            $PHPShopGUI->setField("Накопительная скидка", $PHPShopGUI->setInput('text', "cumulative_discount_new", $data['cumulative_discount'],null,100,false, false, false, '%')) 
    );

    // Адреса доставок
    $Tab2 = $PHPShopGUI->loadLib('tab_addres', $data['data_adres']);

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Доставка и реквизиты", $Tab2));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.shopusers.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules,$PHPShopSystem;

    $_POST['password_new'] = base64_encode($_POST['password_new']);
    $_POST['mail_new']=$_POST['login_new'];

    // Оповещение пользователя
    if (!empty($_POST['enabled_new']) and !empty($_POST['sendActivationEmail'])) {

        PHPShopObj::loadClass("parser");
        PHPShopObj::loadClass("mail");

        PHPShopParser::set('user_name', $_POST['name_new']);
        PHPShopParser::set('login', $_POST['login_new']);
        PHPShopParser::set('password', $_POST['password2_new']);

        $zag_adm = __("Ваш аккаунт был успешно активирован Администратором");
        $PHPShopMail = new PHPShopMail($_POST['login_new'], $PHPShopSystem->getEmail(), $zag_adm, '', true, true);
        $content_adm = PHPShopParser::file('../lib/templates/users/mail_user_activation_by_admin_success.tpl', true);

        if (!empty($content_adm)) {
            $PHPShopMail->sendMailNow($content_adm);
        }
    }

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    if ($_POST['saveID'] == 'Создать и редактировать')
        header('Location: ?path=' . $_GET['path'] . '&id=' . $action);
    else
        header('Location: ?path=' . $_GET['path']);

    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>