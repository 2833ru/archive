<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

$PHPShopSystem = new PHPShopSystem();

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->dir = $_classPath . "admpanel/";
$PHPShopGUI->title = "Настройка модуля Seo Pult";
$PHPShopGUI->size = "500,450";
$PHPShopGUI->ajax = "'modules','seopult'";
$PHPShopGUI->addJSFiles('../../../lib/Subsys/JsHttpRequest/Js.js');

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.seopult.seopult_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;


    $params = array(
        'login' => $_POST['login_new'],
        'url' => $_SERVER['SERVER_NAME'],
        'email' => $_POST['email_new'],
        'hash' => md5($_SERVER['SERVER_NAME'] . time()),
        'partner' => '7a52518f2d1b22983a51a2fbf2a8ec75'
    );

    $request = http_build_query($params);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://i.seopult.pro/iframe/getCryptKeyWithUserReg?" . $request);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CRLF, true);

    $json = curl_exec($ch);

    $result = json_decode($json, true);

    // Регистрация нового пользователя
    if ($result['status']['code'] == 0) {

        $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.seopult.seopult_system"));
        $PHPShopOrm->debug = false;
        $params['cryptkey'] = $result['data']['cryptKey'];
        $PHPShopOrm->update($params, false, '');
    }


    if (curl_error($ch) != '' || $json == false) {
        echo "Error: " . curl_error($ch);
        curl_close($ch);
        die;
    }

    curl_close($ch);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Seo Pult'", "Настройки подключения", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");
    
    if(empty($login)) $login=$_SERVER['SERVER_NAME'].'_'.rand(0,100);
    if(empty($email)) $email=$PHPShopSystem->getParam('adminmail2');

    $Tab1 = $PHPShopGUI->setField('Пользователь', $PHPShopGUI->setInputText(false, 'login_new', $login));
    $Tab1.=$PHPShopGUI->setField('E-mail', $PHPShopGUI->setInputText(false, 'email_new', $email));

    $Tab2 = $PHPShopGUI->setInfo($info, 200, '96%');

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay($serial, false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 270), array("О Модуле", $Tab3, 270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "editID", "Регистрация", "right", 120, "", "but", "actionUpdate");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();
}
else
    $UserChek->BadUserFormaWindow();
?>