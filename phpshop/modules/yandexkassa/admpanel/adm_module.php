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

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");


// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");


// Редактор
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexkassa.yandexkassa_system"));

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

    if (is_array($_POST['pay_variants_new']))
        $_POST['pay_variants_new'] = serialize($_POST['pay_variants_new']);
    if (!isset($_POST['test_new']))
        $_POST['test_new'] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $_classPath, $PHPShopOrm;


    $PHPShopGUI->dir = $_classPath . "admpanel/";
    $PHPShopGUI->title = "Настройка модуля Яндекс.Деньги";
    $PHPShopGUI->size = "500,450";

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader("Настройка модуля 'Яндекс.Касса'", "Настройки подключения", $PHPShopGUI->dir . "img/i_display_settings_med[1].gif");

    $Tab1 = $PHPShopGUI->setField('Наименование типа оплаты', $PHPShopGUI->setInputText(false, 'title_new', $title));
    $Tab1 .= $PHPShopGUI->setField('Тестовый режим', $PHPShopGUI->setCheckbox('test_new', 1, 'Включить/выключить тестовый режим', $test), 'left');
    $Tab1.=$PHPShopGUI->setField('ShopID', $PHPShopGUI->setInputText(false, 'merchant_id_new', $merchant_id, 210), 'left');
    $Tab1.=$PHPShopGUI->setField('Scid', $PHPShopGUI->setInputText(false, 'merchant_scid_new', $merchant_scid, 210), 'left');
    $Tab1.=$PHPShopGUI->setField('Секретное слово', $PHPShopGUI->setInputText(false, 'merchant_sig_new', $merchant_sig, 210), 'left');

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $status);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $status);

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField('Оплата при статусе', $PHPShopGUI->setSelect('status_new', $order_status_value, 210), 'left');

    $Tab1.=$PHPShopGUI->setField('Описание оплаты', $PHPShopGUI->setTextarea('title_end_new', $title_end, "left", "209px", "30px"), 'left');

    // Настройки модуля
    require_once(dirname(__FILE__) . '/../hook/mod_option.hook.php');
    $PHPShopYandexkassaArray = new PHPShopYandexkassaArray();
    $value = $PHPShopYandexkassaArray->get_pay_variants_array(unserialize($pay_variants));

    $Tab1.=$PHPShopGUI->setField('Способы оплаты (выбрать через Ctrl)', $PHPShopGUI->setSelect('pay_variants_new[]', $value, '450px', false, FALSE, false, "50px", false, true),"left");


    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay($serial, false, $version, true);

    $info = 'Технические данные необходимые для регистрации и подключения к Яндекс.Касса:
            <p>CheckOrder URL: <ins>https://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/yandexkassa/payment/check.php</ins>. <br>
            PaymentAviso URL: <ins>https://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/yandexkassa/payment/aviso</ins>.php. <br>
            SuccessURL: <ins>http://' . $_SERVER['SERVER_NAME'] . '/yandexkassa/?act=success</ins>. <br>
            FailURL URL: <ins>http://' . $_SERVER['SERVER_NAME'] . '/yandexkassa/?act=fail</ins>. </p>
                <p>Используйте тестовый режим во вкладке "основное" для проведения тестовых платежей. Поле "Секретное слово" заполняется занными указанными в поле "shopPassword" при регистрации в системе. Поля "ShopID" и "Scid" вам пришлет сотрудник Яндекс.Кассы после регистрации.</p>
                <p>В настройка "Оплата при статусе" выберите статус заказа, при котором пользователю станет доступной возможность оплатить заказ данным способом. Если выбран статус "Новый заказ", пользователь сможет оплатить заказ сразу после оформления. Сообщение заданное в поле "Описание оплаты" выводится после оформления заказа в случае, когда статус заказа не совпадает со статусом указанным в настройке "Оплата при статусе".</p>
                <p>В списке "Способы оплаты" выберите те, которые хотите использовать на вашем сайте.</p>
                <p>Шаблон вывода информации о платёжной системе после офрмления: phpshop/modules/yandexkassa/templates/payment_forma.tpl</p>
                <p>Шаблон сообщения об успешной оплате: phpshop/modules/yandexkassa/templates/success_forma.tpl</p>
                <p>Шаблон сообщения об успешной оплате: phpshop/modules/yandexkassa/templates/fail_forma.tpl</p>
    ';

    $Tab2 .= $PHPShopGUI->setButton("Перейти к подробной инструкции по подключению Яндекс.Касса", "../templates/logo.png", "440px", "50px",null,"window.open('http://faq.phpshop.ru/page/yandex-kassa.html','_blank');");
    $Tab2 .= $PHPShopGUI->setInfo($info, 200, '96%');

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, 302), array("Инструкция", $Tab2, 302), array("О Модуле", $Tab3, 302));

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