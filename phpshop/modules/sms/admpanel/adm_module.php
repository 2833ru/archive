<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("valuta");


$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.paypal.paypal_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    return $action;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля PayPal";
    $PHPShopGUI->size = "500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'PayPal'", "Настройки подключения", "../install/logo_header.png");

    $Tab1 = $PHPShopGUI->setField('Наименование типа оплаты', $PHPShopGUI->setInputText(false, 'title_new', $title));
    $Tab1.=$PHPShopGUI->setField('Пользователь', $PHPShopGUI->setInputText(false, 'merchant_id_new', $merchant_id, 210), 'left');
    $Tab1.=$PHPShopGUI->setField('Пароль', $PHPShopGUI->setInputText(false, 'merchant_pwd_new', $merchant_pwd, 210), 'left');
    $Tab1.=$PHPShopGUI->setField('Подпись', $PHPShopGUI->setInputText(false, 'merchant_sig_new', $merchant_sig, 210), 'left');

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $status);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $status);

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField('Оплата при статусе', $PHPShopGUI->setSelect('status_new', $order_status_value, 210), 'left');

    // Ссылка
    $Tab1.= $PHPShopGUI->setLine() . $PHPShopGUI->setField('Текст ссылки на оплату', $PHPShopGUI->setInputText(null, 'link_new', $link, 210), 'left');

    // Sandbox
    $sandbox_value[] = array('Включен', 1, $sandbox);
    $sandbox_value[] = array('Выключен', 2, $sandbox);
    $Tab1.= $PHPShopGUI->setField('Тестовый режим', $PHPShopGUI->setSelect('sandbox_new', $sandbox_value), 'left');

    // Логотип
    $logo_value[] = array('Слева', 1, $logo_enabled);
    $logo_value[] = array('Справа', 2, $logo_enabled);
    $logo_value[] = array('Выключен', 3, $logo_enabled);
    $Tab1.= $PHPShopGUI->setField('Логотип PayPal', $PHPShopGUI->setSelect('logo_enabled_new', $logo_value), 'left');

    // Валюты
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    $valuta_area = null;
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['currency_id'] == $val['id']) {
                $check = 'checked';
                $valuta_def_name = $val['code'];
            }
            else
                $check = false;
            $valuta_area.=$PHPShopGUI->setRadio('currency_id_new', $val['id'], $val['name'], $check);
        }
    $Tab1.= $PHPShopGUI->setLine().$PHPShopGUI->setField('Валюта расчета',$valuta_area);    

    $Tab4 = $PHPShopGUI->setField('Сообщение об отложенном платеже', $PHPShopGUI->setTextarea('title_end_new', $title_end));
    $Tab4.=$PHPShopGUI->setField('Заголовок сообщения после оплаты', $PHPShopGUI->setInputText(null, 'message_header_new', $message_header, '100%'));
    $Tab4.=$PHPShopGUI->setField('Cообщения после оплаты', $PHPShopGUI->setTextarea('message_new', $message));


    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay(false, false, $version, true);

    $info = 'Для работы модуля требуется зарегистрироваться в PayPal по ссылке: <a href="https://www.paypal.com/ru/webapps/mpp/solutions" target="_blank">https://www.paypal.com/ru/webapps/mpp/solutions</a>. 
                <p>
В поля "Пользователь", "Пароль" и "Подпись" внести одноименные данные, полученные после регистрации Бизнес аккаунта в PayPal.</p> <p>
Для тестирования модуля используйте опцию "Тестовый режим" в закладке "Авторизация". Для отложенного платежа следует выбрать нужный статус заказа в закладке "Авторизация". </p><p>Опция "Логотип PayPal" показывает обязательный логотип платежной системы. Шаблон логотипа находится в файле phpshop/modules/paypal/templates/paypal_logo.tpl. Дополнительные обязательные логотипы доступны по ссылке: <a href="https://www.paypal.com/ru/webapps/mpp/logos" target="_blank">https://www.paypal.com/ru/webapps/mpp/logos</a>.</p> <p> Шаблон описания платежной системы: phpshop/modules/paypal/templates/paypal_forma.tpl</p><p>IPN обработчик оплаты: http://'.$_SERVER['SERVER_NAME'].'/phpshop/modules/paypal/payment/ipn.php</p>';

    $Tab2 = $PHPShopGUI->setInfo($info, 230, '96%');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Авторизация", $Tab1, 270), array("Сообщения", $Tab4, 270), array("Инструкция", $Tab2, 270), array("О Модуле", $Tab3, 270));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "newsID", $id, "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("submit", "editID", "ОК", "right", 70, "", "but", "actionUpdate");

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