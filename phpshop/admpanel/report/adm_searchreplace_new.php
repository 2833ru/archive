<?php

PHPShopObj::loadClass('delivery');


$TitlePage = __('Создание Переадресации Поиска');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['search_base']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;


    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("Переадресация поиска"), false, array('Сохранить и закрыть'));
    
    // Передача данных
    $data=$_GET['data'];


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Запрос:", $PHPShopGUI->setInputText(false, "name_new", str_replace(array('i', 'ii'), array('', ','), $data['name'])) . $PHPShopGUI->setRadio("enabled_new", 1, "Показывать", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Скрыть", $data['enabled']));
    $Tab1.= $PHPShopGUI->setField("ID Товаров:", $PHPShopGUI->setInputText(false, "uid_new", $data['uid']) . $PHPShopGUI->setHelp('Введите идентификаторы (ID) товаров через запятую без пробела (100,101)'));


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 350));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.order.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    if (strpos($_POST['name_new'], ',')) {
        $name_new = null;
        $name = explode(",", $_POST['name_new']);
        foreach ($name as $v)
            $name_new.="i" . $v . "i";

        $_POST['name_new'] = $name_new;
    }
    else
        $_POST['name_new'] = "i" . $_POST['name_new'] . "i";

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    header('Location: ?path=' . $_GET['path']);

    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>