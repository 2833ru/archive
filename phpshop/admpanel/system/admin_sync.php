<?php

$TitlePage = __("Документооборот");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm;

// Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['1c_option']);

// Размер названия поля
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./system/gui/system.gui.js');
    $PHPShopGUI->setActionPanel($TitlePage, false, array('Сохранить'));


    $PHPShopGUI->_CODE = '<p></p>' . $PHPShopGUI->setField(__("Бухгалтерские документы"), $PHPShopGUI->setCheckbox('1c_load_accounts_new', 1, 'Оригинальный счет с печатью и подписями из 1С', $data['1c_load_accounts']) . '<br>' . $PHPShopGUI->setCheckbox('1c_load_invoice_new', 1, 'Оригинальная счет-фактура с печатью из 1С', $data['1c_load_invoice']), 1, 'Оригинальные документы выгружаются из 1С при синхронизации заказов.');

    $PHPShopGUI->_CODE .= $PHPShopGUI->setField(__("Данные для синхронизации номенклатуры"), $PHPShopGUI->setCheckbox('option[update_name]', 1, 'Наименование номенклатуры', $option['update_name']) . '<br>' .
            $PHPShopGUI->setCheckbox('option[update_description]', 1, 'Краткое описание', $option['update_description']) . '<br>' .
            $PHPShopGUI->setCheckbox('option[update_content]', 1, 'Подробное описание', $option['update_content']) . '<br>' .
            $PHPShopGUI->setCheckbox('option[update_category]', 1, 'Родительская категория', $option['update_category']) . '<br>' .
            $PHPShopGUI->setCheckbox('option[update_sort]', 1, 'Характериcтики и свойства', $option['update_sort'])
    );


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

// header('Location: ?path=' . $_GET['path']);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['1c_option']);

    if (is_array($_POST['option']))
        foreach ($_POST['option'] as $key => $val)
            $option[$key] = $val;

    // Поиск нулевых значений
    if (is_array($_POST['option']))
        $option_null = array_diff_key($option, $_POST['option']);
    else
        $option_null = $option;

    if (is_array($option_null)) {
        foreach ($option_null as $key => $val)
            $option[$key] = 0;
    }

    $_POST['1c_load_accounts_new'] = $_POST['1c_load_accounts_new'] ? 1 : 0;
    $_POST['1c_load_invoice_new'] = $_POST['1c_load_invoice_new'] ? 1 : 0;
    $_POST['1c_option_new'] = serialize($option);

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));


    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();
?>