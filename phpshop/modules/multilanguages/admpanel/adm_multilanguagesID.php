<?php

$TitlePage = __('Редактирование записи #' . intval($_GET['id']));

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.multilanguages.multilanguages"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if($_POST['content_multilanguages_ini']!='') {
        // Пишем содержимое обратно в файл
        if($_POST['prefix_new']!='')
            file_put_contents($_SERVER['DOCUMENT_ROOT'].'/phpshop/modules/multilanguages/inc/lang_'.$_POST['prefix_new'].'.ini', $_POST['content_multilanguages_ini']);
    }

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']));
    return array('success'=>$action);
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopSystem, $PHPShopBase;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['id'])));

    $PHPShopGUI->field_col = 2;
    $Tab1 = $PHPShopGUI->setField('Название', $PHPShopGUI->setInputText(false, 'name_new', $data['name']));
    $Tab1 .= $PHPShopGUI->setField('Обозначение', $PHPShopGUI->setInputText(false, 'prefix_new', $data['prefix']).$PHPShopGUI->setHelp('Конфиг перевода должен находиться в файле /phpshop/modules/multilanguages/inc/lang_'.$data['prefix'].'.ini'),false,'Код языка в нанавигации (en/fr)');
    //$Tab1 .= $PHPShopGUI->setField('Иконка', $PHPShopGUI->setInputText(false, 'icon_new', $data['icon']));

    $Tab1.= $PHPShopGUI->setField('Приоритет', $PHPShopGUI->setInputText('№', 'num_new', $data['num'], '100') .
            $PHPShopGUI->setCheckbox('enabled_new', 1, 'Вкл.', $data['enabled']));
    

    //Полное описание
    if($data['prefix']!='') {
        $file = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/phpshop/modules/multilanguages/inc/lang_'.$data['prefix'].'.ini', FILE_USE_INCLUDE_PATH);
        $fileu = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/phpshop/modules/multilanguages/inc/lang.ini', FILE_USE_INCLUDE_PATH);
    }

    $PHPShopGUI->setEditor('ace', true);
    $oFCKeditor = new Editor('content_multilanguages_ini');
    $oFCKeditor->Height = '550';
    $oFCKeditor->Config['EditorAreaCSS'] = chr(47) . "phpshop" . chr(47) . "templates" . chr(47) . $PHPShopSystem->getValue('skin') . chr(47) . $PHPShopBase->getParam('css.default');
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $file;
    $info = $oFCKeditor->AddGUI();

    $oFCKeditorUni = new Editor('content_multilanguages_ini_uni');
    $oFCKeditorUni->Height = '550';
    $oFCKeditorUni->Config['EditorAreaCSS'] = chr(47) . "phpshop" . chr(47) . "templates" . chr(47) . $PHPShopSystem->getValue('skin') . chr(47) . $PHPShopBase->getParam('css.default');
    $oFCKeditorUni->ToolbarSet = 'Normal';
    $oFCKeditorUni->Value = $fileu;
    $infouni = $oFCKeditorUni->AddGUI();
    $Tab2.='<div class="row"><div class="col-md-6"><h4>Перевод</h4>'.$info.'</div>'; 
    $Tab2.='<div class="row"><div class="col-md-6"><h4>Оригинал</h4>'.$infouni.'</div>'; 


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Переводы", $Tab2,true));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "", "but", "actionDelete.modules.edit") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.modules.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
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

// Функция удаления
function actionDelete() {
    global $PHPShopOrm;
    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array("success" =>  $action);
}


// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');

?>