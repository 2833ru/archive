<?php

$TitlePage = __('Редактирование сообщения #' . $_GET['id']);
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['messages']);
PHPShopObj::loadClass('user');

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $PHPShopOrm->sql = 'SELECT a.*, b.name, b.login FROM ' . $GLOBALS['SysValue']['base']['messages'] . ' AS a 
        JOIN ' . $GLOBALS['SysValue']['base']['shopusers'] . ' AS b ON a.UID = b.id     
            WHERE a.id=' . intval($_REQUEST['id']) . '  limit 1';

    $result = $PHPShopOrm->select();

    $data = $result[0];

    // Нет данных
    if (!is_array($data)) {
        header('Location: ?path=' . $_GET['path']);
    }

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("Покупатели") . ' / ' . __('Сообщение') . ' / ' . $data['name'], array('Удалить'), array('Сохранить и закрыть'));
    
    $user='<a class="btn btn-default btn-sm" href="?path=shopusers&id=' . $data['UID'].'&return='.$_GET['path'].'"><span class="glyphicon glyphicon-user"></span> '.$data['name'].' : '.$data['login'].'</a>';
    
    $message='<div class="well">'.strip_tags($data['Message'],'<b><hr><br>').'</div>';

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), 
            $PHPShopGUI->setField("Отправитель", $user) .
            $PHPShopGUI->setField("Тема", $PHPShopGUI->setInput('text.required', "Subject_new", $data['Subject'])) .
            $PHPShopGUI->setField("Переписка", $message).$PHPShopGUI->setInput('hidden', "Message_new", $data['Message']).
            $PHPShopGUI->setField("Ответ", $PHPShopGUI->setTextarea('respond', null, false, '100%', 100,false,'Текст сообщения...'))
    );


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['ID'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.shopusers.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.shopusers.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.shopusers.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);


    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
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

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    if(!empty($_POST['respond'])){
        $_POST['Message_new']='<b>Администрация</b>: '.$_POST['respond'].'<HR>'.$_POST['Message_new'];
        $_POST['enabled_new']=1;
    }
        

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
   return array('success' => $action);
}


// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>