<?php

$TitlePage = __("Документооборот");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $TitlePage, $PHPShopOrm;

    PHPShopObj::loadClass('order');

    // Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['1c_option']);
    $data = $PHPShopGUI->valid($data, 'update_name', 'update_descriptio', 'update_content');

    $PHPShopGUI->action_button['CRM Журнал'] = array(
        'name' => __('Журнал операций'),
        'action' => 'report.crm',
        'class' => 'btn btn-default btn-sm navbar-btn btn-action-panel',
        'type' => 'button',
        'icon' => 'glyphicon glyphicon-hourglass'
    );

    // Размер названия поля
    $PHPShopGUI->field_col = 3;
    $PHPShopGUI->addJSFiles('./system/gui/system.gui.js');
    $PHPShopGUI->setActionPanel($TitlePage, false, array('CRM Журнал', 'Сохранить'));

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Не используется'), 0, $option['1c_load_status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $option['1c_load_status']);

    // Тит загрузки характеристик
    $sort_value[] = array(__('Отдельные характеристики для каталогов'), 0, $option['update_sort_type']);
    $sort_value[] = array(__('Общие характеристики для каталогов'), 1, $option['update_sort_type']);

    $PHPShopGUI->_CODE = $PHPShopGUI->setCollapse('Данные', $PHPShopGUI->setField("Данные для синхронизации номенклатуры", $PHPShopGUI->setCheckbox('option[update_name]', 1, 'Наименование номенклатуры', $option['update_name']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_description]', 1, 'Краткое описание', $option['update_description']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_content]', 1, 'Подробное описание', $option['update_content']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_category]', 1, 'Родительская категория', $option['update_category']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_sort]', 1, 'Характериcтики и свойства', $option['update_sort']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_option]', 1, 'Подтипы', $option['update_option']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_price]', 1, 'Цены', $option['update_price']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[update_item]', 1, 'Склад', $option['update_item']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[seo_update]', 1, 'SEO ссылка', $option['seo_update'])
            ) .
            $PHPShopGUI->setField("Характериcтики и свойства", $PHPShopGUI->setSelect('option[update_sort_type]', $sort_value, 300)
            ) .
            $PHPShopGUI->setField("Статус заказа", $PHPShopGUI->setSelect('option[1c_load_status]', $order_status_value, 300)
            , 1, 'Заказы выгружаются только при определенном статусе'));



    $PHPShopGUI->_CODE .= $PHPShopGUI->setCollapse('Обмен с сайтом', $PHPShopGUI->setField("Бухгалтерские документы", $PHPShopGUI->setCheckbox('1c_load_accounts_new', 1, 'Оригинальный счет с печатью и подписями из 1С', $data['1c_load_accounts']) . '<br>' .
                    $PHPShopGUI->setCheckbox('1c_load_invoice_new', 1, 'Оригинальная счет-фактура с печатью из 1С', $data['1c_load_invoice']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[1c_load_status_email]', 1, 'E-mail оповещение покупателя о новых загруженных бухгалтерских документах из 1С', $option['1c_load_status_email'])
                    , 1, 'Оригинальные документы выгружаются из 1С при синхронизации заказов с помощью PHPShop Exchange.')
    );

    // Ключ обновления
    $key_value[] = array(__('Артикул'), 'uid', $option['exchange_key']);
    $key_value[] = array(__('Внешний код'), 'external', $option['exchange_key']);

    // Авторизация
    $auth_value[] = array(__('Логин и пароль'), 0, $option['exchange_auth']);
    $auth_value[] = array(__('Имя файла'), 1, $option['exchange_auth']);

    if (!empty($_SERVER['HTTPS']) && 'off' !== strtolower($_SERVER['HTTPS'])) {
        $protocol = 'https://';
    } else
        $protocol = 'http://';

    $PHPShopGUI->_CODE .= $PHPShopGUI->setCollapse('CommerceML', $PHPShopGUI->setField("Ключ обновления", $PHPShopGUI->setSelect('option[exchange_key]', $key_value, 300) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_zip]', 1, 'Сжатие данных ZIP', $option['exchange_zip']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_create]', 1, 'Создавать новые товары', $option['exchange_create']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_create_category]', 1, 'Создавать новые каталоги', $option['exchange_create_category']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_image]', 1, 'Создавать новые изображения', $option['exchange_image']) . '<br>' .
                    $PHPShopGUI->setCheckbox('option[exchange_log]', 1, 'Журнал соединений', $option['exchange_log']) . '<br>'
            ) .
            $PHPShopGUI->setField("Авторизация", $PHPShopGUI->setSelect('option[exchange_auth]', $auth_value, 300)) .
            $PHPShopGUI->setField("Имя файла", $PHPShopGUI->setInputText($protocol . $_SERVER['SERVER_NAME'] . '/1cManager/', 'option[exchange_auth_path]', $option['exchange_auth_path'], 400, '.php', false, false, 'secret_cml_path')));


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
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
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data = $PHPShopOrm->select();
    $option = unserialize($data['1c_option']);
    $_POST['option']['exchange_auth_path'] = substr($_POST['option']['exchange_auth_path'], 0, 10);

    if ($_POST['option']['exchange_image'] == 1) {
        $_POST['option']['exchange_key'] = 'external';
        $_POST['option']['exchange_zip'] = 1;
    }

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

    // Переименование
    if (!empty($option['exchange_auth']) and ! empty($option['exchange_auth_path']) and ! file_exists($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/1cManager/' . $option['exchange_auth_path'] . '.php')) {
        copy($_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/1cManager/cml.php', $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['SysValue']['dir']['dir'] . '/1cManager/' . $option['exchange_auth_path'] . '.php');
    }

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));

    return array("success" => $action);
}

// Обработка событий
$PHPShopGUI->getAction();
?>