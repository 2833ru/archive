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

// Выбор языка
function GetLocaleList($skin) {
    global $PHPShopGUI;
    $dir = "../locale/";

    $locale_array = array(
        'russian' => 'Русский',
        'ukrainian' => 'Український',
        'belarusian' => 'Беларускі',
        'english' => 'English'
    );

    if (is_dir($dir)) {
        if (@$dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {

                $name = $locale_array[$file];
                if (empty($name))
                    $name = $file;

                if ($skin == $file)
                    $sel = "selected";
                else
                    $sel = "";

                if ($file != "." and $file != ".." and !strpos($file, '.'))
                    $value[] = array($name, $file, $sel, 'data-content="<img src=\'' . $dir . '/' . $file . '/icon.png\'/> ' . $name . '"');
            }
            closedir($dh);
        }
    }

    return $PHPShopGUI->setSelect('lang_new', $value);
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $TitlePage, $PHPShopModules, $PHPShopSystem;

    PHPShopObj::loadClass(array('valuta', 'user'));

    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->setActionPanel($TitlePage, false, array('Сохранить и закрыть'));

    // Выборка
    $data['name'] = __('Новая витрина');
    $data['enabled'] = 1;

    $Tab1 = $PHPShopGUI->setField("Название", $PHPShopGUI->setInputText(null, "name_new", $data['name']));
    $Tab1 .= $PHPShopGUI->setField("Адрес", $PHPShopGUI->setInputText('http://', "host_new", $data['host']));
    $Tab1 .= $PHPShopGUI->setField(
            array("Телефоны", "E-mail оповещение"), array($PHPShopGUI->setInputText(null, "tel_new", $data['tel']),
        $PHPShopGUI->setInputText(null, "adminmail_new", $data['adminmail'])), array(array(2, 4), array(2, 4)));
    $Tab1.=$PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("enabled_new", 1, "Вкл.", $data['enabled']) . $PHPShopGUI->setRadio("enabled_new", 0, "Выкл.", $data['enabled']));
    $Tab1.=$PHPShopGUI->setField("Логотип", $PHPShopGUI->setIcon($data['logo'], "logo_new", false));
    $Tab1.=$PHPShopGUI->setField('Заголовок (Title)', $PHPShopGUI->setTextarea('title_new', $data['title'], false, false, 100));
    $Tab1.=$PHPShopGUI->setField('Описание (Description)', $PHPShopGUI->setTextarea('descrip_new', $data['descrip'], false, false, 100));
    $Tab2 .= $PHPShopGUI->setField("Наименование организации", $PHPShopGUI->setInputText(null, "company_new", $data['company']));
    $Tab2 .= $PHPShopGUI->setField("Фактический адрес", $PHPShopGUI->setInputText(null, "adres_new", $data['adres']));

    // Валюты
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            $currency_value[] = array($val['name'], $val['id'], $PHPShopSystem->getDefaultValutaId());
        }

    if (empty($data['skin']))
        $data['skin'] = $PHPShopSystem->getParam('skin');

    if (empty($data['lang']))
        $data['lang'] = $PHPShopSystem->getSerilizeParam('admoption.lang');

    $Tab2 .= $PHPShopGUI->setField(array('Валюта', 'Дизайн', 'Язык'), array($PHPShopGUI->setSelect('currency_new', $currency_value), GetSkinList($data['skin']), GetLocaleList($data['lang'])), array(array(2, 2), array(1, 2), array(1, 2)));

    $sql_value[] = array('Включить полное зеркало', 'on', 'on');
    $sql_value[] = array('Выключить полное зеркало', 'off', 1);

    $Tab2.=$PHPShopGUI->setField("Пакетная обработка", $PHPShopGUI->setSelect('sql', $sql_value, false, true));

    // Статусы
    $PHPShopUserStatusArray = new PHPShopUserStatusArray();
    $userstatus_array = $PHPShopUserStatusArray->getArray();

    $userstatus_value[] = array(__('Авторизованный пользователь'), 0, $option['user_status']);
    if (is_array($userstatus_array))
        foreach ($userstatus_array as $val) {
            $userstatus_value[] = array($val['name'], $val['id'], $option['user_status']);
        }

    $Tab2.= $PHPShopGUI->setField("Регистрация пользователей", $PHPShopGUI->setCheckbox('option[user_mail_activate]', 1, 'Активация через E-mail', $option['user_mail_activate']) . '<br>' . $PHPShopGUI->setCheckbox('option[user_mail_activate_pre]', 1, 'Ручная активация администратором', $option['user_mail_activate_pre']) . '<br>' . $PHPShopGUI->setCheckbox('option[user_price_activate]', 1, 'Регистрация для просмотра цен', $option['user_price_activate'])) . $PHPShopGUI->setField("Статус после регистрации", $PHPShopGUI->setSelect('option[user_status]', $userstatus_value));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Дополнительно", $Tab2, true), array("Инструкция", $PHPShopGUI->loadLib('tab_showcase', false, './system/')));

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
    $_POST['code_new'] = md5($License['License']['Serial'] . str_replace('www.', '', getenv('SERVER_NAME')) . $_POST['host_new'] . $PHPShopBase->getParam("connect.host") . $PHPShopBase->getParam("connect.user_db") . $PHPShopBase->getParam("connect.pass_db"));

    $_POST['icon_new'] = iconAdd();

    if (is_array($_POST['option']))
        foreach ($_POST['option'] as $key => $val)
            $option[$key] = $val;

    $_POST['admoption_new'] = serialize($option);

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $action = $PHPShopOrm->insert($_POST);

    // Команды
    $set_on=' set `servers`=CONCAT("i' . $_POST['rowID'] . 'ii1000i", `servers` )';
    $set_off=' set `servers`=REPLACE(`servers`,"i' . $_POST['rowID'] . 'i",  "")';
    switch ($_POST['sql']) {

        case "on":
            $PHPShopOrmCat = new $PHPShopOrm();
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['categories'] . $set_on);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['page'] . $set_on);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['menu'] . $set_on);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['slider'] . $set_on);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['news'] . $set_on);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['delivery'] . $set_on);
            break;
        
        case "off":
            $PHPShopOrmCat = new $PHPShopOrm();
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['categories'] . $set_off);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['page'] . $set_off);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['menu'] . $set_off);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['slider'] . $set_off);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['news'] . $set_off);
            $PHPShopOrmCat->query('update ' . $GLOBALS['SysValue']['base']['delivery'] . $set_off);
            break;
    }


    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Добавление изображения 
function iconAdd($name = 'icon_new') {

    // Папка сохранения
    $path = '/UserFiles/Image/';

    // Копируем от пользователя
    if (!empty($_FILES['file']['name'])) {
        $_FILES['file']['ext'] = PHPShopSecurity::getExt($_FILES['file']['name']);
        if (in_array($_FILES['file']['ext'], array('gif', 'png', 'jpg'))) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'])) {
                $file = $GLOBALS['dir']['dir'] . $path . $_FILES['file']['name'];
            }
        }
    }

    // Читаем файл из URL
    elseif (!empty($_POST['furl'])) {
        $file = $_POST[$name];
    }

    // Читаем файл из файлового менеджера
    elseif (!empty($_POST[$name])) {
        $file = $_POST[$name];
    }

    if (empty($file))
        $file = '';

    return $file;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>
