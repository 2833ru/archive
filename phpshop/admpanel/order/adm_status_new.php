<?php

$TitlePage = __('Создание Статуса');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['order_status']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules;

    // Начальные данные
    $data['name'] = 'Новый статус';
    $data['color'] = '#000000';


    // bootstrap-colorpicker
    $PHPShopGUI->addCSSFiles('./css/bootstrap-colorpicker.min.css');
    $PHPShopGUI->addJSFiles('./js/bootstrap-colorpicker.min.js');

    $PHPShopGUI->setActionPanel(__("Создание Статуса"), false, array('Создать и редактировать', 'Сохранить и закрыть'));

    $Field1 = $PHPShopGUI->setInput("text", "name_new", $data['name'], null, 500) .
            $PHPShopGUI->setCheckbox("sklad_action_new", 1, "Списание со склада товаров в заказе", $data['sklad_action']) .
            $PHPShopGUI->setCheckbox("cumulative_action_new", 1, "Учет скидки покупателя", $data['cumulative_action']);


    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Название:", $Field1);


    $Tab1.=$PHPShopGUI->setField('Цвет', '<div class="input-group color" style="width:200px">
    <input type="text" name="color_new" value="' . $data['color'] . '" class="form-control input-sm">
    <span class="input-group-addon input-sm"><i></i></span></div>');

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.order.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    
    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);

    if ($_POST['saveID'] == 'Создать и редактировать')
        header('Location: ?path=' . $_GET['path'] . '&id=' . $action);
    else
        header('Location: ?path=' . $_GET['path']);

    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>
