<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("string");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

$PHPShopSystem = new PHPShopSystem();

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->debug_close_window = false;
$PHPShopGUI->reload = 'top';
$PHPShopGUI->ajax = "'modules','paypal'";
$PHPShopGUI->includeJava = '<SCRIPT language="JavaScript" src="../../../lib/Subsys/JsHttpRequest/Js.js"></SCRIPT>';
$PHPShopGUI->dir = $_classPath . "admpanel/";

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.moysklad.moysklad_log"));

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Операция с Moysklad";
    $PHPShopGUI->size = "630,450";


    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . $_GET['id']));


    // Графический заголовок окна
    $PHPShopGUI->setHeader('Оплата заказа №"' . $data[order_id] . '" от ' . PHPShopDate::get($data[date]), "Укажите данные для записи в базу.", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    // Переводим в читаемый вид
    ob_start();
    print_r(unserialize($data['message']));
    $log = ob_get_clean();


    $Tab1 = $PHPShopGUI->setTextarea(null, PHPShopString::utf8_win1251($log), $float = "none", $width = '99%', $height = '340');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Информация о заказе", $Tab1, 370));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("button", "", "Закрыть", "right", 70, "return onCancel();", "but");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

    // Обработка событий
    $PHPShopGUI->getAction();
}
else
    $UserChek->BadUserFormaWindow();
?>


