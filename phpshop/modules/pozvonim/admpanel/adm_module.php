<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
include("../class/pcurl.php");
include("../class/pozvonim.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL

$PHPShopSysOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['system']);
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.pozvonim.pozvonim_system"));

// Функция регистрации
function actionRegister()
{
    global $PHPShopOrm, $PHPShopBase;
    $p = new Pozvonim();
    $data = array();
    foreach (array('email', 'phone', 'host', 'code', 'reset', 'token', 'restore') as $field) {
        if (isset($_POST[$field])) {
            $data[$field] = $_POST[$field];
        }
    }
    $oldData = $PHPShopOrm->select();
    if ($oldData['appId'] > 1) {
        echo 'Плагин уже зарегистрирован';
        return false;
    }
    if (empty($data['token']) && $oldData['token']) {
        $data['token'] = $oldData['token'];
    }
    if ($result = $p->update($data)) {
        if ($PHPShopOrm->update($result, false, '')) {
            echo 'ok';
        } else {
            echo 'Ошибка';
        }
    } else {
        echo $p->errorMessage ? $p->errorMessage : 'Ошибка сохранения';
    }
    return false;
}

// Функция установки кода
function actionCode()
{
    global $PHPShopOrm, $PHPShopBase;
    $p = new Pozvonim();
    if (!isset($_POST['code'])) {
        echo 'Необходимо указать код виджета';
        return false;
    }
    $code = $_POST['code'];

    $data = $PHPShopOrm->select();
    $data['code'] = $code;

    if ($data = $p->update($data)) {
        if ($PHPShopOrm->update($data, false, '')) {
            echo 'ok';
        } else {
            echo 'Ошибка установки кода';
        }
    } else {
        echo $p->errorMessage;
    }
    return false;
}

// Функция установки кода
function actionRestore()
{
    $p = new Pozvonim();
    if (!isset($_POST['email'])) {
        echo 'Необходимо указать email';
        return false;
    }
    if ($p->restoreTokenToEmail($_POST['email'])) {
        echo 'Секретный код отправлен на ' . htmlspecialchars($_POST['email']);
    } else {
        echo $p->errorMessage;
    }
    return false;
}

function actionStart()
{
    global $PHPShopGUI, $PHPShopSystem, $SysValue, $_classPath, $PHPShopOrm, $PHPShopSysOrm;

    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля обратного звонка";
    $PHPShopGUI->size = "500,450";

    //Выборка
    $data = $PHPShopOrm->select();
    $sysData = $PHPShopSysOrm->select();
    $isRegistered = $data['appId'] > 0;

    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Сервис обратного звонка Pozvonim.com'", "Настройки", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    $Tab1 = '<fieldset ' . ($isRegistered ? 'disabled="disabled"' : '') . ' >';
    $Tab1 .= $PHPShopGUI->setField('Email', $PHPShopGUI->setInputText(false, 'email', $data['email'] ? $data['email'] : $sysData['adminmail2']));
    $Tab1 .= $PHPShopGUI->setField('Телефон', $PHPShopGUI->setInputText(false, 'phone', $data['phone'] ? $data['phone'] : $sysData['tel']));
    $Tab1 .= $PHPShopGUI->setField('Домен', $PHPShopGUI->setInputText(false, 'host', $data['host'] ? $data['host'] : $_SERVER['HTTP_HOST']));
    $Tab1 .= $PHPShopGUI->setField('Секретный код',
        $PHPShopGUI->setInputText(false, 'token', $data['token'] ? $data['token'] : md5(uniqid('', true)))
    );
    if (!$isRegistered) {
        $Tab1 .= $PHPShopGUI->setButton('Зарегистрировать плагин', '/phpshop/admpanel/icon/user_add.gif', 200, 50, 'left', 'pozvonim.register(this);return false;');
        $Tab1 .= $PHPShopGUI->setButton('Восстановить секретный код', '/phpshop/admpanel/icon/email_open_image.gif', 200, 50, 'left', 'pozvonim.restore(this);return false;');
    } else {
        $Tab1 .= '<br/>';
        $link = $PHPShopGUI->setLink(
            'http://appspozvonim.com/phpshop/login?id=' . $data['appId'] . '&token=' . md5($data['appId'] . $data['token']),
            'Открыть личный кабинет my.pozvonim.com'
        );
        $Tab1 .= $PHPShopGUI->setInfo('Плагин зарегистрирован и привязан к аккаунту <b>' . $data['email'] . '</b><br/>' .$link, false, '400px');
    }
    $Tab1 .= '</fieldset>';

    $Tab12 = '<fieldset>' . $PHPShopGUI->setField('Код виджета', $PHPShopGUI->setTextarea('code', $data['code']));;
    if ($data['code']) {
        $Tab12 .= $PHPShopGUI->setInfo('<span style="font-weight:bold;color:green;">Плагин использует указанный код виджета</span>', false, '300px');
    }
    $Tab12 .= '<br/>' . $PHPShopGUI->setButton($data['code'] ? 'Сохранить' : 'Установить', '/phpshop/admpanel/icon/page_save.gif', 120, 50, 'none', 'pozvonim.saveCode(this);return false;');
    $Tab12 .= '</fieldset>';

    $Tab2 = $PHPShopGUI->setInfo('
           <b>Для работы плагина достаточно зарегистрировать его</b> через форму регистрации во вкладке "регистрация".<br/>
           <br/>
           В случае <b>если вы регистрировали плагин, но потеряли свой секретный код</b> (создается автоматически перед регистрацией),
           вы можете восстановить секретный код заполнив поле email и нажав кнопку "Восстановить секретный код".<br/>
           <br/>
           Если вы уже зарегистрированы в сервисе pozvonim.com и <b>хотите использовать существующий код виджета</b>.
           Вы можете указать код виджета в соотвествующей вкладке.
             <br/> <br/>
           По умолчанию виджет выводится в переменную <b>@leftMenu@</b>.
           Если в шаблоне нет переменной <b>@leftMenu@</b> то необходимо добавить переменную <b>@pozvonim@</b> в активный шаблон.<br/>
           Положение и внешний вид виджета редактируются в личном кабинете pozvonim.com.<br/>
           В личный кабинет можно попасть по ссылке отображаемой после регистрации плагина.<br/>
           <p>Для отключения вывода в переменную <b>@leftMenu@</b> закомментируйте 46 строку в phpshop/modules/pozvonim/inc/pozvonim.inc.php </p>
   ', 270, '96%'
    );
    
        // Форма регистрации
    $Tab3=$PHPShopGUI->setPay($serial,false);

    // Вывод формы закладки
    if (isset($data['code']) && $data['code'] != '') {
        $PHPShopGUI->setTab(array("Код виджета", $Tab12, 300), array("Регистрация", $Tab1, 300), array("Инструкция", $Tab2, 300),array("О Модуле",$Tab3,300));
    } else {
        $PHPShopGUI->setTab(array("Регистрация", $Tab1, 300), array("Код виджета", $Tab12, 300), array("Инструкция", $Tab2, 300),array("О Модуле",$Tab3,300));
    }

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
        $PHPShopGUI->setInput("hidden", "newsID", $data['id'], "right", 70, "", "but") .
        $PHPShopGUI->setInput("button", "", "Закрыть", "right", 70, "return onCancel();", "but");

    //$ContentFooter .= $PHPShopGUI->setInput("submit", "editID", "ОK", "right", 70, "", "but");//, "actionUpdate"
    $ContentFooter .= '<script src="/phpshop/modules/pozvonim/js/js.js"></script>';
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

if ($UserChek->statusPHPSHOP < 2) {

    // Вывод формы при старте
    $PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

    // Обработка событий
    $PHPShopGUI->getAction();

} else {
    $UserChek->BadUserFormaWindow();
}

?>