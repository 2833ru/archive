<?php

$TitlePage = __('Создание Новости');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['news']);

function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopSystem, $TitlePage;

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    
    // Выборка
    $data['datas'] = PHPShopDate::get();
    $data['zag'] = __('Новость за ') . $data['datas'];

    // datetimepicker
    $PHPShopGUI->addJSFiles('./js/jquery.tagsinput.min.js', './js/bootstrap-datetimepicker.min.js', './js/jquery.waypoints.min.js', './news/gui/news.gui.js');
    $PHPShopGUI->addCSSFiles('./css/jquery.tagsinput.css', './css/bootstrap-datetimepicker.min.css');

    $PHPShopGUI->setActionPanel($TitlePage, false, array('Сохранить и закрыть'));

    // Редактор 1
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('kratko_new');
    $oFCKeditor->Height = '270';
    $oFCKeditor->Value = $data['kratko'];

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Дата", $PHPShopGUI->setInputDate("datas_new", $data['datas'])) .
            $PHPShopGUI->setField("Заголовок", $PHPShopGUI->setInput("text", "zag_new", $data['zag']));

    $Tab1.=$PHPShopGUI->setField("Анонс", $oFCKeditor->AddGUI());


    // Редактор 2
    $oFCKeditor2 = new Editor('podrob_new');
    $oFCKeditor2->Height = '550';
    $oFCKeditor2->Value = $data['podrob'];

    $Tab1.=$PHPShopGUI->setField("Подробно", $oFCKeditor2->AddGUI());
    
    $Tab2.=$PHPShopGUI->setField("Начало показа", $PHPShopGUI->setInputDate("datau_new", $data['datas']));

    // Рекомендуемые товары
    $Tab2 .= $PHPShopGUI->setField('Рекомендуемые товары', $PHPShopGUI->setTextarea('odnotip_new', $data['odnotip'], false, false, 300, __('Укажите ID товаров или воспользуйтесь <a href="#" data-target="#odnotip_new"  class="btn btn-sm btn-default tag-search"><span class="glyphicon glyphicon-search"></span> поиском товаров</a>')));

    $Tab2.=$PHPShopGUI->setField("Витрины", $PHPShopGUI->loadLib('tab_multibase', $data, 'catalog/'));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Дополнительно", $Tab2, true));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.news.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    if (!empty($_POST['datau_new']))
        $_POST['datau_new'] = PHPShopDate::GetUnixTime($_POST['datau_new']);
    else
        $_POST['datau_new'] = PHPShopDate::GetUnixTime($_POST['datas_new']);

    // Мультибаза
    $_POST['servers_new'] = "";
    if (is_array($_POST['servers']))
        foreach ($_POST['servers'] as $v)
            if ($v != 'null' and !strstr($v, ','))
                $_POST['servers_new'].="i" . $v . "i";

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>
