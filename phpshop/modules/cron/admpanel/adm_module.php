<?php

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.cron.cron_system"));

/**
 * Обновление настроек
 * @return mixed
 */
function actionUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI;

    // Содержание закладки 2
    $Tab2 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("О Модуле", $Tab2), array("Обзор Задач", null, '?path=modules.dir.cron'), array("Журнал выполнения", null, '?path=modules.dir.cron.log'));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart');
?>