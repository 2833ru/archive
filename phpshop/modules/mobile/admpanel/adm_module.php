<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.mobile.mobile_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $action = $PHPShopOrm->update($_POST);
    if($action)
    header('Location: ?path=modules&install=check');
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField(__('Сообщение'), $PHPShopGUI->setTextarea('message_new', $data['message']),1,'Пустое поле не показывает окно запроса на переход');

    // Иконка
    $Tab1.= $PHPShopGUI->setField(__('Изображение в шапке'), $PHPShopGUI->setInputText(false, "logo_new", $data['logo']));

    // Заголовок
    $returncall_value[] = array('телефон', 1, $data['returncall']);
    $returncall_value[] = array('обратный звонок', 2, $data['returncall']);
    $Tab1.=$PHPShopGUI->setField(__("Заголовок:"), $PHPShopGUI->setSelect('returncall_new', $returncall_value, 200));

    $Tab2 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true), array("О Модуле", $Tab2));

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
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>