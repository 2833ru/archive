<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.filial.filial_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $TitlePage, $select_name, $PHPShopOrm;

    $PHPShopGUI->setActionPanel($TitlePage, $select_name, array('Сохранить и закрыть'));
    
    // Выборка
    $data = $PHPShopOrm->select();
    
    // Форма регистрации
    $Tab1 = $PHPShopGUI->setInfo('Модуль добавляет настройку привязки страниц в главном меню для филиалов (Контакты, Реквизиты и т.д.)').$PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("О Модуле", $Tab1));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", 1) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>