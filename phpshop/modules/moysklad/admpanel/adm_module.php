<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("array");
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
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.moysklad.moysklad_system"));

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
    $PHPShopGUI->title = "Настройка модуля МойСклад";
    //$PHPShopGUI->size = "500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Мой Склад'", "Настройки подключения", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    $Tab1.=$PHPShopGUI->setField('Пользователь', $PHPShopGUI->setInputText(false, 'merchant_user_new', $merchant_user, 210), 'left');
    $Tab1.=$PHPShopGUI->setField('Пароль', $PHPShopGUI->setInput('password', 'merchant_pwd_new', $merchant_pwd, 210), 'left');

    // Опции синхронизации
    $stock_value[] = array('Все товары', 'ALL_STOCK', $stock_option);
    $stock_value[] = array('Только положительные остатки', 'POSITIVE_ONLY', $stock_option);
    $stock_value[] = array('Только положительные остатки, с учетом резерва', 'POSITIVE_INCLUDING_RESERVE_ONLY', $stock_option);
    $stock_value[] = array('Только отрицательные значения', 'NEGATIVE_ONLY', $stock_option);
    $stock_value[] = array('Отрицательные и положительные значения', 'NON_EMPTY', $stock_option);
    $stock_value[] = array('Ниже неснижаемого остатка', 'UNDER_MINIMUM_BALANCE_ONLY', $stock_option);
    $stock_value[] = array('С учетом резерва', 'USE_RESERVES', $stock_option);

    $Tab1.=$PHPShopGUI->setField('Код организации в МойСклад', $PHPShopGUI->setInputText(false, 'org_code_new', $org_code, 210), 'left');

    // НДС
    $Tab1.=$PHPShopGUI->setField('НДС', $PHPShopGUI->setInputText(false, 'nds_new', $nds, 30, '%'), 'left');
    
    // Запуск
    $Tab1.=$PHPShopGUI->setField('Запуск', $PHPShopGUI->setInput("button", "", "Загрузить остатки", "right", 160, "return window.open('../cron/stock.php');", "but"), 'left');
    
     // Опции синхронизации
    $Tab1.= $PHPShopGUI->setField('Синхронизация склада', $PHPShopGUI->setSelect('stock_option_new', $stock_value, 310).$PHPShopGUI->setLine().$PHPShopGUI->setImage($_classPath.'admpanel/icon/icon_info.gif', 16, 16) .'Опция влияет на скорость и объем данных для синхронизации склада.', 'none');
    
    
    
      $Info = "Для автоматической синхронизации требуется установить модуль 'PHPShop Cron' и добавить в него новую задачу с адресом
        исполняемого файла:  <b>phpshop/modules/moysklad/cron/stock.php</b>.<p> Для создания новой задачи в 'Unix Cron' используйте команду:  <b>wget http://".$_SERVER['SERVER_NAME']."/phpshop/modules/moysklad/cron/stock.php</b></p>";
      
    $Tab1.= $PHPShopGUI->setInfo($Info, 100, '97%');

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay($serial);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 320), array("О Модуле", $Tab3, 320));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
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