<?php

$TitlePage = __('Создание скидки');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['discount']);
PHPShopObj::loadClass('user');

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage;

    // Начальные данные
    $data['enabled'] = 1;

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("Покупатели") . ' / ' . $TitlePage, false, array('Сохранить и закрыть', 'Создать и редактировать'));

    $action_value[] = array('Максимально возможная скидка', 1, $data['action']);
    $action_value[] = array('Суммировать с пользовательской скидкой', 2, $data['action']);

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), $PHPShopGUI->setField("Сумма", $PHPShopGUI->setInput('text.required', "sum_new", $data['sum'], null, 300)) .
            $PHPShopGUI->setField("Скидка", $PHPShopGUI->setInputText('%', "discount_new", $data['discount'], 100)) .
            $PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("enabled_new", 1, "Вкл.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выкл.", $data['enabled'])) .
            $PHPShopGUI->setField("Формула расчета", $PHPShopGUI->setSelect('action_new', $action_value, 300))
            , 'in', false);

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.shopusers.create");

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