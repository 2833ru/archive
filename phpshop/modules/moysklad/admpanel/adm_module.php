<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.moysklad.moysklad_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    return $action;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage, $select_name;

    $PHPShopGUI->action_button['Выгрузить'] = array(
        'name' => 'Выгрузить базу',
        'action' => '../modules/moysklad/admpanel/file.php',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-export'
    );
    
        $PHPShopGUI->action_button['Синхронизировать'] = array(
        'name' => 'Синхронизировать',
        'action' => '../modules/moysklad/cron/stock.php',
        'class' => 'btn  btn-default btn-sm navbar-btn btn-action-panel-blank',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-refresh'
    );
    
    $PHPShopGUI->setActionPanel($TitlePage, $select_name, array('Выгрузить', 'Синхронизировать', 'Сохранить и закрыть'));


    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1.=$PHPShopGUI->setField('Пользователь', $PHPShopGUI->setInputText(false, 'merchant_user_new', $data['merchant_user']));
    $Tab1.=$PHPShopGUI->setField('Пароль', $PHPShopGUI->setInput('password', 'merchant_pwd_new', $data['merchant_pwd']));

    // Опции синхронизации
    $stock_value[] = array('Все товары', 'ALL_STOCK', $data['stock_option']);
    $stock_value[] = array('Только положительные остатки', 'POSITIVE_ONLY', $data['stock_option']);
    $stock_value[] = array('Только положительные остатки, с учетом резерва', 'POSITIVE_INCLUDING_RESERVE_ONLY', $data['stock_option']);
    $stock_value[] = array('Только отрицательные значения', 'NEGATIVE_ONLY', $data['stock_option']);
    $stock_value[] = array('Отрицательные и положительные значения', 'NON_EMPTY', $data['stock_option']);
    $stock_value[] = array('Ниже неснижаемого остатка', 'UNDER_MINIMUM_BALANCE_ONLY', $data['stock_option']);
    $stock_value[] = array('С учетом резерва', 'USE_RESERVES', $data['stock_option']);

    $Tab1.=$PHPShopGUI->setField('Код организации в МойСклад', $PHPShopGUI->setInputText(false, 'org_code_new', $data['org_code'], 300));

    // НДС
    $Tab1.=$PHPShopGUI->setField('НДС', $PHPShopGUI->setInputText(false, 'nds_new', $data['nds'], 100, '%'));

    // Опции синхронизации
    $Tab1.= $PHPShopGUI->setField('Синхронизация склада', $PHPShopGUI->setSelect('stock_option_new', $stock_value, 310) . $PHPShopGUI->setHelp('Влияет на скорость и объем данных для синхронизации склада.'));

    $Info = "Для автоматической синхронизации требуется установить модуль 'PHPShop Cron' и добавить в него новую задачу с адресом
        исполняемого файла:  <code>phpshop/modules/moysklad/cron/stock.php</code>. Для создания новой задачи в <b>Unix Cron</b>' используйте команду:  <code>wget http://" . $_SERVER['SERVER_NAME'] . "/phpshop/modules/moysklad/cron/stock.php</code>";

    $Tab2= $PHPShopGUI->setInfo($Info, 100, '97%');

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

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