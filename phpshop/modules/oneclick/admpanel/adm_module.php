<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.oneclick.oneclick_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;
    
    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();


    // Вывод
    $e_value[] = array('кнопка купить', 0, $data['enabled']);
    $e_value[] = array('слева', 1, $data['enabled']);
    $e_value[] = array('справа', 2, $data['enabled']);

    // Тип вывода
    $w_value[] = array('форма', 0, $data['windows']);
    $w_value[] = array('всплывающее окно', 1, $data['windows']);
    
    // Место вывода
    $d_value[] = array('подробное описание', 0, $data['display']);
    $d_value[] = array('подробное и краткое описание', 1, $data['display']);


    $Tab1 = $PHPShopGUI->setField('Заголовок', $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    $Tab1.=$PHPShopGUI->setField('Сообщение', $PHPShopGUI->setTextarea('title_end_new', $data['title_end']));
    $Tab1.=$PHPShopGUI->setField('Место вывода', $PHPShopGUI->setSelect('enabled_new', $e_value, 250));
    $Tab1.=$PHPShopGUI->setField('Тип вывода', $PHPShopGUI->setSelect('windows_new', $w_value, 250));
    $Tab1.=$PHPShopGUI->setField('Вывод', $PHPShopGUI->setSelect('display_new', $d_value, 250));


    $info = 'Для произвольной вставки элемента, следует выбрать параметр вывода "Кнопка купить" и вставить переменную
        <kbd>@oneclick@</kbd> в свой шаблон в нужное вам место.
        <p>Для персонализации формы вывода, отредактируйте шаблоны <code>phpshop/modules/oneclick/templates/</code></p>
';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true), array("Инструкция", $Tab2), array("О Модуле", $Tab3),array("Обзор заявок", 0,'?path=modules.dir.oneclick'));

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