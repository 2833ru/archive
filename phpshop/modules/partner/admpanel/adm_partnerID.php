<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.partner.partner_users"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $_POST['date_done_new'] = time();
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopGUI;


    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $PHPShopGUI->field_col = 1;
    $Tab1 = $PHPShopGUI->setField('Логин', $PHPShopGUI->setInputText(false, 'login', $data['login']));
    $Tab1.=$PHPShopGUI->setField('E-mail', $PHPShopGUI->setInputText(false, 'mail_new', $data['mail']));
    $Tab1.=$PHPShopGUI->setField('Статус', $PHPShopGUI->setCheckbox('enabled_new', 1, 'Активирован', $data['enabled']));

    // Дополнительные поля
    $content = unserialize($data['content']);
    $dop = null;

    if (is_array($content))
        foreach ($content as $k => $v) {
            $name = str_replace('dop_', '', $k);
            $dop.=$name . ': ' . $v . '
';
        }
    $dop = substr($dop, 0, strlen($dop) - 1);

    $Tab2 = $PHPShopGUI->setTextarea('dop', $dop, 'none', '100%', '200px');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1), array("Дополнительно", $Tab2));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" => $action);
}

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

// Обработка событий
$PHPShopGUI->getAction();
?>