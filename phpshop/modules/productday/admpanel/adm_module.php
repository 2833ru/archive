<?php

// SQL
//$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.productoption.productoption_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopModules;
    
    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $action = true;
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

function checkSelect($val) {
    $value[] = array('text', 'text', $val);
    $value[] = array('textarea', 'textarea', $val);
    //$value[] = array('checkbox', 'checkbox', $val);
    $value[] = array('radio', 'radio', $val);
    return $value;
}

function actionStart() {
    global $PHPShopGUI;
    
    $info = '<p>Модуль выводит товар дня на страницы сайта. При редактирование товара возможно установить галочку в закладке <kbd>Товар дня</kbd></p>
    <p>Для вывода блока на страницу используйте метку <mark>@productDay@</mark></p>';

    $Tab1 = $PHPShopGUI->setInfo($info);



    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("О Модуле", $PHPShopGUI->setPay()));

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
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>