<?php
PHPShopObj::loadClass('order');

// SQL
$PHPShopOrm = new PHPShopOrm("phpshop_modules_sberbankrf_system");
// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    $PHPShopOrm->debug = false;

    if (empty($_POST["dev_mode_new"]))
        $_POST["dev_mode_new"] = 0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();
    @extract($data);

    $Tab1 = $PHPShopGUI->setInfo('<p>Модуль интеграции интернет-магазина с платежным шлюзом Сбербанка России, позволяет проводить оплату заказа картой через Сбербанк России.
 Перед началом работы, необходимо произвести необходимые настройки на соответствующей вкладке. Режим разработки позволяет отправлять запросы на тестовую среду Сбербанка России https://3dsec.sberbank.ru</p>');

    $Tab2 = $PHPShopGUI->setField('Логин магазина', $PHPShopGUI->setInputText(false, 'login_new', $data['login'], 300));
    $Tab2 .= $PHPShopGUI->setField('Пароль магазина', $PHPShopGUI->setInput("password", 'password_new', $data['password'], false, 300));

    // Система налогообложения
    $tax_system = array (
        array("Общая система налогообложения", 0, $data["taxationSystem"]),
        array("Упрощенная система налогообложения (Доход)", 1, $data["taxationSystem"]),
        array("Упрощенная система налогообложения (Доход минус Расход)", 2, $data["taxationSystem"]),
        array("Единый налог на вмененный доход", 3, $data["taxationSystem"]),
        array("Единый сельскохозяйственный налог", 4, $data["taxationSystem"]),
        array("Патентная система налогообложения", 5, $data["taxationSystem"])
    );
    $Tab2 .= $PHPShopGUI->setField('Cистема налогообложения', $PHPShopGUI->setSelect('taxationSystem_new', $tax_system, 300));

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);

    // Статус заказа
    $Tab2 .= $PHPShopGUI->setField('Оплата при статусе', $PHPShopGUI->setSelect('status_new', $order_status_value, 250));

    $Tab2 .= $PHPShopGUI->setField('Режим разработки', $PHPShopGUI->setCheckbox("dev_mode_new", 1, "Отправка данных на тестовую среду Сбербанка РФ", $data["dev_mode"]));

    $Tab2 .= $PHPShopGUI->setField('Сообщение предварительной проверки', $PHPShopGUI->setTextarea('title_sub_new', $data['title_sub']));

    // Инструкция
    $info = '
        <h4>Настройка модуля</h4>
        <ol>
<li>Предоставить необходимые документы и заключить договор со Сбербанком РФ</li>
<li>На закладке настройки ввести предоставленные Сбербанком РФ Логин API магазина (*********-api) и Пароль магазина.</li>
<li>Во время тестирования включить "Режим разработки", данные будут отправляться на тестовую среду Сбербанка РФ</li>
<li>Для перевода модуля в рабочий режим, выключить "Режим разработки"</a></li>
</ol>
';

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Настройки", $Tab2, true), array("Инструкция", $info, true), array("О Модуле", $Tab1));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>