<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("valuta");


$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.smsfly.smsfly_system"));

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

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля Sms Fly";
    $PHPShopGUI->size = "500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Sms Fly'", "Настройки подключения", $PHPShopGUI->dir."img/i_display_settings_med[1].gif");

    $Tab1 = $PHPShopGUI->setField('№ Телефона для SMS', $PHPShopGUI->setInputText(false, 'phone_new', $phone,false,' * В формате 380631234567'));
    $Tab1.=$PHPShopGUI->setField('Пользователь', $PHPShopGUI->setInputText(false, 'merchant_user_new', $merchant_user, 210), 'left');
    $Tab1.=$PHPShopGUI->setField('Пароль', $PHPShopGUI->setInputText(false, 'merchant_pwd_new', $merchant_pwd, 210), 'left');
     $Tab1.=$PHPShopGUI->setField('Отправитель (Alfaname)', $PHPShopGUI->setInputText(false, 'alfaname_new', $alfaname, 210), 'left');
    
    
    // Sandbox
    $sandbox_value[] = array('Включен', 1, $sandbox);
    $sandbox_value[] = array('Выключен', 2, $sandbox);
    $Tab1.= $PHPShopGUI->setField('Тестовый режим', $PHPShopGUI->setSelect('sandbox_new', $sandbox_value), 'left');
   
 
    $Tab2 = $PHPShopGUI->setPay();

   
    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Авторизация", $Tab1, 270), array("О Модуле", $Tab2, 270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();
}
else
    $UserChek->BadUserFormaWindow();
?>