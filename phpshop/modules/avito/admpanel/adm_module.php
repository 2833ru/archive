<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.avito.avito_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate(number_format($option['version'], 1, '.', false));
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('Пароль защиты XML файла', $PHPShopGUI->setInputText(
        'http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/avito/xml/appliances.php?pas=', 'password_new', $data['password'], 500)
    );
    $Tab1 .= $PHPShopGUI->setField('ФИО менеджера', $PHPShopGUI->setInputText( false, 'manager_new', $data['manager'], 500));
    $Tab1 .= $PHPShopGUI->setField('Телефон менеджера', $PHPShopGUI->setInputText( false, 'phone_new', $data['phone'], 500));
    $Tab1 .= $PHPShopGUI->setField('Дополнение к описанию', $PHPShopGUI->setTextarea( 'additional_description_new', $data['additional_description'], true, 500));

    // Инструкция
    $Tab2 = $PHPShopGUI->loadLib('tab_info', $data,'../modules/'.$_GET['id'].'/admpanel/');

    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true),array("Инструкция", $Tab2), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $PHPShopOrm->debug = false;
    $_POST['region_data_new']=1;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>