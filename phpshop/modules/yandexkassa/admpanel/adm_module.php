<?php


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
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm,$PHPShopModules;
    
    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    if (is_array($_POST['pay_variants_new']))
        $_POST['pay_variants_new'] = serialize($_POST['pay_variants_new']);
    if (!isset($_POST['test_new']))
        $_POST['test_new'] = 0;

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id='.$_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;
    
    PHPShopObj::loadClass('order');

    // Выборка
    $data = $PHPShopOrm->select();


    $Tab1 = $PHPShopGUI->setField('Ссылка на оплату', $PHPShopGUI->setInputText(false, 'title_new', $data['title']));
    $Tab1 .= $PHPShopGUI->setField('Тестовый режим', $PHPShopGUI->setCheckbox('test_new', 1, 'Включить тестовый режим', $data['test']));
    $Tab1.=$PHPShopGUI->setField('ShopID', $PHPShopGUI->setInputText(false, 'merchant_id_new', $data['merchant_id'], 300));
    $Tab1.=$PHPShopGUI->setField('Scid', $PHPShopGUI->setInputText(false, 'merchant_scid_new', $data['merchant_scid'], 300));
    $Tab1.=$PHPShopGUI->setField('Секретное слово', $PHPShopGUI->setInputText(false, 'merchant_sig_new', $data['merchant_sig'], 300));

    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['status']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['status']);

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField('Оплата при статусе', $PHPShopGUI->setSelect('status_new', $order_status_value, 3000));

    $Tab1.=$PHPShopGUI->setField('Описание оплаты', $PHPShopGUI->setTextarea('title_end_new', $data['title_end']));

    // Настройки модуля
    require_once(dirname(__FILE__) . '/../hook/mod_option.hook.php');
    $PHPShopYandexkassaArray = new PHPShopYandexkassaArray();
    $value = $PHPShopYandexkassaArray->get_pay_variants_array(unserialize($data['pay_variants']));

    $Tab1.=$PHPShopGUI->setField('Способы оплаты', $PHPShopGUI->setSelect('pay_variants_new[]', $value,'100%', null, false, true, false, 1, true));


    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], false);

    $info = '
        <h4>Как подключиться к Яндекс.Кассе?</h4>
        <ol>
<li>Подайте заявку на подключение по ссылке <a href="https://money.yandex.ru/joinups/?source=phpshop" target="_blank">https://money.yandex.ru/joinups/?source=phpshop</a> и получите доступ в личный кабинет.</li>
<li>Заполните анкету.</li>
<li>Выберите способ подключения.</li>
<li>Подпишите договор.</li>
</ol>

<h4>Технические данные необходимые для регистрации и подключения к Яндекс.Касса</h4>
            <p>CheckOrder URL: <code>https://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/yandexkassa/payment/check.php</code> <br>
            PaymentAviso URL: <code>https://' . $_SERVER['SERVER_NAME'] . '/phpshop/modules/yandexkassa/payment/aviso.php</code><br>
            SuccessURL: <code>http://' . $_SERVER['SERVER_NAME'] . '/yandexkassa/?act=success</code><br>
            FailURL URL: <code>http://' . $_SERVER['SERVER_NAME'] . '/yandexkassa/?act=fail</code></p>
                <p>Используйте тестовый режим во вкладке "основное" для проведения тестовых платежей. Поле "Секретное слово" заполняется занными указанными в поле "shopPassword" при регистрации в системе. Поля "<b>ShopID</b>" и "<b>Scid</b>" вам пришлет сотрудник Яндекс.Кассы после регистрации.</p>
                <p>В настройка "Оплата при статусе" выберите статус заказа, при котором пользователю станет доступной возможность оплатить заказ данным способом. Если выбран статус "Новый заказ", пользователь сможет оплатить заказ сразу после оформления. Сообщение заданное в поле "Описание оплаты" выводится после оформления заказа в случае, когда статус заказа не совпадает со статусом указанным в настройке "Оплата при статусе".</p>
                <p>В списке "Способы оплаты" выберите те, которые хотите использовать на вашем сайте.</p>
                
        <h4>Настройка доставки</h4>
        <p>Параметр ставки НДС для доставки можно настроить в карточке редактирования доставки.
        </p>


                <h4>Шаблоны дизайна</h4>
                <p>Шаблон вывода информации о платёжной системе после офрмления: <code>phpshop/modules/yandexkassa/templates/payment_forma.tpl</code><br>
                Шаблон сообщения об успешной оплате: <code>phpshop/modules/yandexkassa/templates/success_forma.tpl</code><br>
                Шаблон сообщения об успешной оплате: <code>phpshop/modules/yandexkassa/templates/fail_forma.tpl</code></p>
    ';

    $Tab2 .= $PHPShopGUI->setInfo($info);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1,true), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>