<?php

$TitlePage = __("Покупатели");
PHPShopObj::loadClass('user');

function actionStart() {
    global $PHPShopInterface,$TitlePage;

    $PHPShopInterface->action_button['Добавить Пользователя'] = array(
        'name' => '',
        'action' => 'addNew',
        'class' => 'btn btn-default btn-sm navbar-btn',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-plus',
        'tooltip' => 'data-toggle="tooltip" data-placement="left" title="'.__('Добавить Пользователя').'"'
    );

    $PHPShopInterface->action_title['order'] = 'Новый заказ';

    $PHPShopInterface->addJSFiles('./shopusers/gui/shopusers.gui.js','./shopusers/gui/shopusers.ajax.js');
    $PHPShopInterface->setActionPanel($TitlePage, array('CSV', '|','Удалить выбранные'), array('Добавить Пользователя'));
    $PHPShopInterface->setCaption(array(null, "2%"), array("Имя", "25%"), array("E-mail", "20%"), array("Статус", "20%"), array("Скидка %", "10%"), array("Вход", "10%"), array("", "1%"),array("Статус", "10%", array('align' => 'right')));
    $PHPShopInterface->Compile();
}

// Обработка событий
$PHPShopInterface->getAction();
?>