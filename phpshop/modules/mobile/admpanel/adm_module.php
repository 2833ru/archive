<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.mobile.mobile_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $action = $PHPShopOrm->update($_POST);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля";
    $PHPShopGUI->size = "500,450";

    // Выборка
    $data = $PHPShopOrm->select();

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Mobile'", "Настройки", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    $Tab1 = $PHPShopGUI->setField(__('Сообщение'), $PHPShopGUI->setTextarea('message_new', $data['message']));

    // Иконка
    $Tab1.= $PHPShopGUI->setField(__('Изображение в шапке'), $PHPShopGUI->setInputText(false, "logo_new", $data['logo'], '330px', false, 'left') .
            $PHPShopGUI->setButton(__('Выбрать'), $PHPShopGUI->dir . "img/icon-move-banner.gif", "100px", '25px', "right", "miniWin('".$PHPShopGUI->dir."/editor3/assetmanager/assetmanager.php?name=".$data['logo']."&tip=logo_new', 680, 500);return false;"));
    
    // Заголовок
    $returncall_value[] = array('телефон', 1, $data['returncall']);
    $returncall_value[] = array('обратный звонок', 2, $data['returncall']);
    $Tab1.=$PHPShopGUI->setField(__("Заголовок:"), $PHPShopGUI->setSelect('returncall_new', $returncall_value, 120), 'left');

    $Tab2 = $PHPShopGUI->setPay($data['serial'], false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270), array("О Модуле", $Tab2, 270));

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


