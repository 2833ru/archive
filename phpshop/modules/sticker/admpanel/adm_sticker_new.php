<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.sticker.sticker_forms"));

// Выбор шаблона дизайна
function GetSkinList($skin) {
    global $PHPShopGUI;
    $dir = "../templates/";
    
    $value[] = array(__('Не выбрано'), '', '');

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if (file_exists($dir . '/' . $file . "/main/index.tpl")) {

                    if ($skin == $file)
                        $sel = "selected";
                    else
                        $sel = "";

                    if ($file != "." and $file != ".." and !strpos($file, '.'))
                        $value[] = array($file, $file, $sel);
                }
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('skin_new', $value);
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;

    $action = $PHPShopOrm->insert($_POST);
    
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem;

    // Выборка
    $data['name'] = 'Новый стикер';
    $data['enabled']=1;



    $Tab1 = $PHPShopGUI->setField('Название:', $PHPShopGUI->setInputText(false, 'name_new', $data['name'], 300));
    $Tab1.=$PHPShopGUI->setField('Маркер:', $PHPShopGUI->setInputText('@sticker_', 'path_new', $data['path'], 200, '@'));
    $Tab1.=$PHPShopGUI->setField('Опции:', $PHPShopGUI->setCheckbox('enabled_new', 1, 'Вывод на сайте', $data['enabled']));
    $Tab1.=$PHPShopGUI->setField('Привязка к страницам:', $PHPShopGUI->setInputText(false, 'dir_new', $data['dir']) . $PHPShopGUI->setHelp('Пример: /page/about.html,/page/company.html'));
    $Tab1.=$PHPShopGUI->setField('Дизайн', GetSkinList($data['skin']));


    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"), true);
    $oFCKeditor = new Editor('content_new', true);
    $oFCKeditor->Height = '320';
    $oFCKeditor->ToolbarSet = 'Normal';
    $oFCKeditor->Value = $data['content'];

    $Tab2 = $oFCKeditor->AddGUI();


    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Содержание", $Tab2, true));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter=$PHPShopGUI->setInput("submit","saveID","Сохранить","right",false,false,false,"actionInsert.modules.create");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>