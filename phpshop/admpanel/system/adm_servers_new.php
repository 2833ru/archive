<?php

$TitlePage = __('Создание Витрины');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['servers']);

// Выбор шаблона дизайна
function GetSkinList($skin) {
    global $PHPShopGUI;
    $dir = "../templates/";

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

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $PHPShopSystem;

    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel(__("Создание Витрины"), false, array('Сохранить и закрыть'));

    // Выборка
    $data['name'] = 'Новая витрина';
    $data['enabled'] = 1;

    $Tab1 = $PHPShopGUI->setField("Название:", $PHPShopGUI->setInputText(null, "name_new", $data['name'], 300));
    $Tab1 .= $PHPShopGUI->setField("Адрес:", $PHPShopGUI->setInputText('http://', "host_new", $data['host'], 300), false);
    $Tab1.=$PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("enabled_new", 1, "Вкл.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выкл.", $data['enabled']));
       $Tab1.=$PHPShopGUI->setField('Заголовок (Title)', $PHPShopGUI->setTextarea('title_new', $data['title'], false, false, 100));
    $Tab1.=$PHPShopGUI->setField('Описание (Description)', $PHPShopGUI->setTextarea('descrip_new', $data['descrip'], false, false, 100));

    if (empty($data['skin']))
        $data['skin'] = $PHPShopSystem->getParam('skin');
    $Tab1.=$PHPShopGUI->setField('Дизайн', GetSkinList($data['skin']));

    $sql_value[] = array('Не выбрано', 0, 0);
    $sql_value[] = array('Включить все каталоги', 1, 0);
    $sql_value[] = array('Выключить все каталоги', 2, 0);

    $Tab1.=$PHPShopGUI->setField("Пакетная обработка", $PHPShopGUI->setSelect('sql', $sql_value));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true),array("Инструкция", $PHPShopGUI->loadLib('tab_showcase', false, './system/')));


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.servers.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules, $PHPShopBase;

    $License = @parse_ini_file_true("../../license/" . PHPShopFile::searchFile("../../license/", 'getLicense'), 1);
    $_POST['code_new'] = md5($License['License']['Serial'] . str_replace('www.','',getenv('SERVER_NAME')) . $_POST['host_new'] . $PHPShopBase->getParam("connect.host") . $PHPShopBase->getParam("connect.user_db") . $PHPShopBase->getParam("connect.pass_db"));

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->insert($_POST);

    // Команды
    switch ($_POST['sql']) {

        case 1:
            $PHPShopOrmCat = new $PHPShopOrm();
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['categories'] . ' set `servers`=CONCAT("i' . $action . 'i", `servers` )');
            break;

        case 2:
            $PHPShopOrmCat = new $PHPShopOrm();
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['categories'] . ' set `servers`=REPLACE(`servers`,"i' . $action . 'i",  "")');
            break;
    }


    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>
