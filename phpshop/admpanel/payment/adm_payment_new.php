<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = "Создание Способа Оплаты";
$PHPShopGUI->ajax = "'payment','','','core'";
$PHPShopGUI->alax_lib = true;

$PHPShopSystem = new PHPShopSystem();

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment_systems']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm, $PHPShopModules;

    $PHPShopGUI->dir = "../";
    //$PHPShopGUI->size="630,530";
    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание Способа Оплаты", "", $PHPShopGUI->dir . "img/i_select_another_account_med[1].gif");


    $Field1 = $PHPShopGUI->setInput("text", "name_new", "Новый способ оплаты", "none", 280) .
            $PHPShopGUI->setRadio("enabled_new", 1, "Показывать", 1) .
            $PHPShopGUI->setRadio("enabled_new", 0, "Скрыть",1);

    $Field2 = $PHPShopGUI->setSelect("path_new", $PHPShopGUI->loadLib(GetTipPayment, false), 280, "left") . $PHPShopGUI->setLine() .
            $PHPShopGUI->setInputText('Сортировка:', "num_new", 0, '50px', false, "left") .
            $PHPShopGUI->setCheckbox("yur_data_flag_new", 1, "Требовать юр. данные", 0, "left");

    $Field3 = $PHPShopGUI->setInput("text", "message_header_new", false, "none", 280);
    $Field4 = $PHPShopGUI->setInputText(false, "icon_new", false, '165px', false, 'left') .
            $PHPShopGUI->setButton(__('Выбрать'), "../img/icon-move-banner.gif", "100px", '25px', "right", "ReturnPic('icon_new');return false;");



    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('message_new');
    $oFCKeditor->Height = '300';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = null;
    $oFCKeditor->Config['EditorAreaCSS'] = $_classPath . "templates" . chr(47) . $PHPShopSystem->getParam("skin") . chr(47) . $SysValue['css']['default'];

    // Содержание закладки 2
    $editor = $oFCKeditor->AddGUI();

// Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Наименование:", $Field1, "left") .
            $PHPShopGUI->setField("Тип подключения:", $Field2, "left") .
            $PHPShopGUI->setField("Заголовок сообщения после оплаты:", $Field3, "left") .
            $PHPShopGUI->setField("Иконка:", $Field4, "left");

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350), array("Сообщение", $editor, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("reset", "", "Сбросить", "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionInsert.baner.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// Обработка событий
$PHPShopGUI->getAction();
?>



