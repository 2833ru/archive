<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexorder.yandexorder_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['img_new']))
        $_POST['img_new'] = $_POST['icon_new'];

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();
    

    // Содержание закладки
    $Info = 'Для работы модуля требуется зарегистрировать свой магазин в программе "Быстрый Заказ", для этого перейдите по ссылке
        <a href="http://partner.market.yandex.ru/delivery-registration.xml" target="_blank">
        http://partner.market.yandex.ru/delivery-registration.xml</a> и укажите в качестве имени сайта адрес
        <code>http://' . $_SERVER['SERVER_NAME'] . $GLOBALS['SysValue']['dir']['dir'] . '/order/</code>';

    $Tab1 = $PHPShopGUI->setField('Иконка:', $PHPShopGUI->setIcon($data['img'],false,false));
    $Tab1.= $PHPShopGUI->setField('Инструкция', $PHPShopGUI->setInfo($Info));

    // Форма регистрации
    $Tab2 = $PHPShopGUI->setPay();


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Описание", $Tab1), array("О Модуле", $Tab2));

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