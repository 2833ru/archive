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
$PHPShopGUI->title = "Создание Нового Изображения для слайдера";
$PHPShopGUI->ajax = "'slider','','','core'";
$PHPShopGUI->alax_lib = true;
$PHPShopSystem = new PHPShopSystem();

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['slider']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopModules;

    $PHPShopGUI->dir = "../";

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Создание Нового изображения для Слайдера", "", $PHPShopGUI->dir . "img/i_select_another_account_med[1].gif");

    $Field1 = $PHPShopGUI->setInput("text", "image_new", $image, "left", 300) .
            $PHPShopGUI->setButton(__('Выбрать'), "../img/icon-move-banner.gif", "100px", '25px', "left", "ReturnPic('image_new');return false;") .
            $PHPShopGUI->setRadio("enabled_new", 1, "Показывать изображение", $enabled) . "<br>" .
            $PHPShopGUI->setRadio("enabled_new", 0, "Скрыть изображение", $enabled);

    $Field2 = $PHPShopGUI->setInput("text", "link_new", $link, "none", 300) . $PHPShopGUI->setLine("Пример: /pages/info.html или http://google.com");
    $Field3 = $PHPShopGUI->setInputText(false, 'num_new', $num, '50px') . "<br>";
    $Field4 = $PHPShopGUI->setTextarea("alt_new", $alt);


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField(__("Изображение:"), $Field1, "none") .
            $PHPShopGUI->setField(__("Ссылка перехода при клике на изображение:"), $Field2, "left") .
            $PHPShopGUI->setField(__("Номер по порядку:"), $Field3, "left") .
            $PHPShopGUI->setLine() .
            $PHPShopGUI->setField(__("Описание к изображению:"), $Field4);


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, null);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
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



