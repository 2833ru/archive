<?php

$TitlePage = __("Реквизиты");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();
    $bank = unserialize($data['bank']);

    // Размер названия поля
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./system/gui/system.gui.js');
    $PHPShopGUI->setActionPanel($TitlePage, false, array('Сохранить'));


    $PHPShopGUI->_CODE = '<p></p>' . $PHPShopGUI->setField(__("Название магазина"), $PHPShopGUI->setInputText(null, "name_new", $data['name']));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("Владелец"), $PHPShopGUI->setInputText(null, "company_new", $data['company']));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("Телефоны"), $PHPShopGUI->setInputText(null, "tel_new", $data['tel']));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("Наименование организации"), $PHPShopGUI->setInputText(null, "bank[org_name]", $bank['org_name']));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("Юридический адрес"), $PHPShopGUI->setInputText(null, "bank[org_ur_adres]", $bank['org_ur_adres']));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("Фактический адрес"), $PHPShopGUI->setInputText(null, "bank[org_adres]", $bank['org_adres']));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("ИНН"), $PHPShopGUI->setInputText(null, "bank[org_inn]", $bank['org_inn'], 300));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("КПП"), $PHPShopGUI->setInputText(null, "bank[org_kpp]", $bank['org_kpp'], 300));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("№ Счета организации"), $PHPShopGUI->setInputText(null, "bank[org_schet]", $bank['org_schet'], 300));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setLine() . $PHPShopGUI->setField(__("Наименование банк"), $PHPShopGUI->setInputText(null, "bank[org_bank]", $bank['org_bank'], 300));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("БИК"), $PHPShopGUI->setInputText(null, "bank[org_bic]", $bank['org_bic'], 300));
    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("№ Счета банка"), $PHPShopGUI->setInputText(null, "bank[org_bank_schet]", $bank['org_bank_schet'], 300));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.system.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.system.edit");

    $PHPShopGUI->setFooter($ContentFooter);

    $sidebarleft[] = array('title' => 'Категории', 'content' => $PHPShopGUI->loadLib('tab_menu', false, './system/'));
    $PHPShopGUI->setSidebarLeft($sidebarleft, 2);

    // Футер
    $PHPShopGUI->Compile(2);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select();
    $bank = unserialize($data['bank']);


    if (is_array($_POST['bank']))
        foreach ($_POST['bank'] as $key => $val)
            $bank[$key] = $val;

    $_POST['bank_new'] = serialize($bank);


    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));


    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();
?>