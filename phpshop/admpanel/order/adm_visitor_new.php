<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass("user");
PHPShopObj::loadClass("text");

// Подключение к БД
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopBase->chekAdmin();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

// Редактор GUI
PHPShopObj::loadClass("admgui");
$PHPShopGUI = new PHPShopGUI();
$PHPShopGUI->title = __("Новый Заказ");
$PHPShopGUI->reload = "top";
$PHPShopGUI->addJSFiles('/phpshop/lib/Subsys/JsHttpRequest/Js.js', '/phpshop/lib/JsHttpRequest/JsHttpRequest.js');

// SQL
PHPShopObj::loadClass("orm");
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);

// Модули
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

/**
 * Генерация номера заказа
 */
function setNum() {
    global $PHPShopBase;

    // Кол-во знаков в постфиксе заказа №_XX, по умолчанию 2
    $format = $PHPShopBase->getParam('my.order_prefix_format');
    if (empty($format))
        $format = 2;

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $row = $PHPShopOrm->select(array('uid'), false, array('order' => 'id desc'), array('limit' => 1));
    $last = $row['uid'];
    $all_num = explode("-", $last);
    $ferst_num = $all_num[0];
    $order_num = $ferst_num + 1;
    $order_num = $order_num . "-" . substr(abs(crc32(uniqid(session_id()))), 0, $format);
    return $order_num;
}

/**
 * Экшен загрузки форм редактирования
 */
function actionStart() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm, $PHPShopSystem, $PHPShopBase;

    // Тип окна
    if ($_COOKIE['winOpenType'] == 'default')
        $dot = ".";
    else
        $dot = false;

    // Создаем копию заказа
    if (!empty($_REQUEST['orderAdd'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $PHPShopOrm->debug = false;
        $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['orderAdd'])));
        $data['id'] = null;
        $order = unserialize($data['orders']);
        unset($order['Person']['discount']);
        unset($order['Cart']);
        $data['orders'] = serialize($order);
    } elseif (!empty($_REQUEST['userAdd'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
        $PHPShopOrm->debug = false;
        $user_row = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_GET['userAdd'])));
        $data['user'] = $user_row['id'];
        $order['Person']['mail'] = $user_row['mail'];
        $order['Person']['org_name'] = $user_row['company'];
        $order['Person']['adr_name'] = $user_row['adres'];
        $order['Person']['name_person'] = $user_row['name'];
        $order['Person']['tel_code'] = $user_row['tel_code'];
        $order['Person']['tel_name'] = $user_row['tel'];
        $order['Person']['org_inn'] = $user_row['inn'];
        $order['Person']['org_kpp'] = $user_row['kpp'];

        // данные под новую структуру таблицу заказов. Учитывает структуру старых записей.
        $data_adres = unserialize($user_row['data_adres']);
        if (is_array($data_adres) AND is_array($data_adres['list'][$data_adres['main']]))
            foreach ($data_adres['list'][$data_adres['main']] as $key => $value) {
                $key = str_replace("_new", "", $key);
                switch ($key) {
                    case "fio":
                        if (empty($value))
                            $value .= $user_row['name'];
                        else
                            $order['Person']['name_person'] = "";
                        break;
                    case "street":
                        $value .= $user_row['adres'];
                        break;
                    case "org_name":
                        $value .= $user_row['company'];
                        break;
                    case "tel":
                        $value .= $user_row['tel_code'] . $user_row['tel'];
                        break;
                    case "org_inn":
                        $value .= $user_row['inn'];
                        break;
                    case "org_kpp":
                        $value .= $user_row['kpp'];
                        break;

                    default:
                        break;
                }
                $data[$key] = $value;
            }
        // Библиотека пользователей для расчета скидки
        $PHPShopUser = new PHPShopUser($user_row['id'], $user_row);
        if ($discount = $PHPShopUser->getDiscount())
            $order['Person']['discount'] = $discount;
        else
            $order['Person']['discount'] = 0;
        $data['orders'] = serialize($order);
    }

    // Данные нового заказа
    $data['datas'] = time();
    $data['uid'] = setNum();
    $data['statusi'] = 0;

    // Запись пустого заказа для получения идентификатора заказа
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $PHPShopOrm->insert($data, '');
    $_REQUEST['visitorID'] = mysql_insert_id();

    // Выборка данных по новому заказу
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['visitorID'])));


    $PHPShopGUI->dir = "../";
    //$PHPShopGUI->size = "700,650";
    // Библиотека заказа
    $PHPShopOrder = new PHPShopOrderFunction($_REQUEST['visitorID'], $data);


    // Графический заголовок окна
    $PHPShopGUI->setHeader(__('Новый Заказ № ') . $data['uid'] . ' / ' . PHPShopDate::dataV(), __(""), $PHPShopGUI->dir . "img/i_commercemanager_med[1].gif");

    // Нет данных
    if (!is_array($data)) {
        $PHPShopGUI->setFooter($PHPShopGUI->setInput("button", "", "Закрыть", "center", 100, "return onCancel();", "but"));
        return true;
    }

    $order = unserialize($data['orders']);
    $status = unserialize($data['status']);
    // Доступые статусы заказов
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('Новый заказ'), 0, $data['statusi']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $data['statusi']);

    // Время обработки заказа
    if (empty($status['time']))
        $status['time'] = PHPShopDate::dataV($data['datas'], true, false, ' ', true);


    // данные пользователя - фио, email
    if (!empty($data['user']))
        $userLink = "<a href='' onclick=\"return miniWin('../shopusers/adm_userID.php?id=" . $data['user'] . "',550,580)\">" . $order['Person']['mail'] . "</a>";
    else
        $userLink = $order['Person']['mail'];

    $Tab1 .= PHPShopText::div(PHPShopText::b("Покупатель:" . "<br>") . PHPShopText::p($userLink . "<br>" . PHPShopText::b($data['fio'] . $order['Person']['name_person'])), "left", "float:left;padding:5px;margin-left:0px;height:39px; width:200px;");

    // Дополнительная информация от пользователя к заказу
    $Tab1 .= $PHPShopGUI->setField(__("Дополнительная информация к заказу от пользователя:"), $PHPShopGUI->setTextarea('dop_info_new', $data['dop_info'], 'none', '393px'), "left", 'left');

    // Статус заказа
    $Tab1.= $PHPShopGUI->setField(__("Статус заказа"), $PHPShopGUI->setSelect('statusi_new', $order_status_value, 288), 'left');

    // Доступые типы оплат
    $PHPShopPaymentArray = new PHPShopPaymentArray();
    $PaymentArray = $PHPShopPaymentArray->getArray();
    if (is_array($PaymentArray))
        foreach ($PaymentArray as $payment)
            $payment_value[] = array($payment['name'], $payment['id'], $order['Person']['order_metod']);

    // Тип оплаты
    $Tab1.= $PHPShopGUI->setField(__("Способ оплаты"), $PHPShopGUI->setSelect('person[order_metod]', $payment_value, 291), 'left') . $PHPShopGUI->setLine();

    // время доставки под старый формат данных в заказе
    if (!empty($order['Person']['dos_ot']) OR !empty($order['Person']['dos_do']))
        $dost_ot = " От: " . $order['Person']['dos_ot'] . ", до: " . $order['Person']['dos_do'];

    // выводим сгрупппированные данные пользователя
    if ($data['fio'] OR $order['Person']['name_person'])
        $adr_info .= ", ФИО: " . $data['fio'] . $order['Person']['name_person'];
    if ($data['tel'] or $_POST['person']['tel_code'] or $_POST['person']['tel_name'])
        $adr_info .= ", тел.: " . $data['tel'] . $_POST['person']['tel_code'] . $_POST['person']['tel_name'];
    if ($data['country'])
        $adr_info .= ", страна: " . $data['country'];
    if ($data['state'])
        $adr_info .= ", регион/штат: " . $data['state'];
    if ($data['city'])
        $adr_info .= ", город: " . $data['city'];
    if ($data['index'])
        $adr_info .= ", индекс: " . $data['index'];
    if ($data['street'] OR $order['Person']['adr_name'])
        $adr_info .= ", улица: " . $data['street'] . $order['Person']['adr_name'];
    if ($data['house'])
        $adr_info .= ", дом: " . $data['house'];
    if ($data['porch'])
        $adr_info .= ", подъезд: " . $data['porch'];
    if ($data['door_phone'])
        $adr_info .= ", код домофона: " . $data['door_phone'];
    if ($data['flat'])
        $adr_info .= ", квартира: " . $data['flat'];
    if ($data['delivtime'])
        $adr_info .= ", время доставки: " . $data['delivtime'] . $dost_ot;

    $adr_info = substr($adr_info, 2);
    $Tab1.= $PHPShopGUI->setField(__("Данные покупателя"), PHPShopText::div($adr_info, "left", "float:left;padding:5px;margin-left:0px;height:60px; width:288px; background-color:white;overflow:auto"), "left");

    // Выводим сгруппированные Юр. данные пользователя.
    if ($data['org_name'] OR $order['Person']['org_name'])
        $yur_info .= ", Наименование организации:" . $data['org_name'] . $order['Person']['org_name'];
    if ($data['org_inn'])
        $yur_info .= ", ИНН:" . $data['org_inn'];
    if ($data['org_kpp'])
        $yur_info .= ", КПП" . $data['org_kpp'];
    if ($data['org_yur_adres'])
        $yur_info .= ", Юридический адрес:" . $data['org_yur_adres'];
    if ($data['org_fakt_adres'])
        $yur_info .= ", Фактический адрес:" . $data['org_fakt_adres'];
    if ($data['org_ras'])
        $yur_info .= ", Расчётный счёт:" . $data['org_ras'];
    if ($data['org_bank'])
        $yur_info .= ", Наименование банка:" . $data['org_bank'];
    if ($data['org_kor'])
        $yur_info .= ", Корреспондентский счёт:" . $data['org_kor'];
    if ($data['org_bik'])
        $yur_info .= ", БИК:" . $data['org_bik'];
    if ($data['org_city'])
        $yur_info .= ", Город:" . $data['org_city'];
    $yur_info = substr($yur_info, 2);
    $Tab1.= $PHPShopGUI->setField(__("Юр. данные покупателя:"), PHPShopText::div($yur_info, "left", "float:left;padding:5px;margin-left:0px;height:60px; width:291px; background-color:white;overflow:auto"), "left") . $PHPShopGUI->setLine();



    // Печатные бланки
    $Tab1_1 = $PHPShopGUI->loadLib('tab_print', $data);

    // Дополнительные опции
    $Tab1_2 = $PHPShopGUI->loadLib('tab_advance', $data);

    // Пометка менеджера
    $Tab1_2 .=$PHPShopGUI->setField(__("Пометка менеджера (видна заказчику в ЛК)"), $PHPShopGUI->setTextarea('status[maneger]', $status['maneger'], 'none', '290px'), 'left') . $PHPShopGUI->setLine();

    // Платежные документы
    $PHPShopInterface = new PHPShopInterface('_pretab2_');
    $PHPShopInterface->setTab(array(__("Печатные бланки"), $Tab1_1, 75), array(__("Дополнительно"), $Tab1_2, 75));
    $Tab1.=$PHPShopGUI->setDiv('left', $PHPShopInterface->getContent(), 'float:left;padding-left:0px; width:630px;');

    // Корзина
    $Tab2 = $PHPShopGUI->loadLib('tab_cart', $data);


    // Данные покупателя
    $Tab3 = $PHPShopGUI->setField(__("ФИО"), $PHPShopGUI->setInputText('', 'fio_new', $data['fio'] . $order['Person']['name_person'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Телефон"), $PHPShopGUI->setInputText('', 'tel_new', $data['tel'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Страна"), $PHPShopGUI->setInputText('', 'country_new', $data['country'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Регион/штат"), $PHPShopGUI->setInputText('', 'state_new', $data['state'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Город"), $PHPShopGUI->setInputText('', 'city_new', $data['city'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Индекс"), $PHPShopGUI->setInputText('', 'index_new', $data['index'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Улица"), $PHPShopGUI->setInputText('', 'street_new', $data['street'] . $order['Person']['adr_name'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Дом"), $PHPShopGUI->setInputText('', 'house_new', $data['house'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Подъезд"), $PHPShopGUI->setInputText('', 'porch_new', $data['porch'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Код домофона"), $PHPShopGUI->setInputText('', 'door_phone_new', $data['door_phone'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Квартира"), $PHPShopGUI->setInputText('', 'flat_new', $data['flat'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Время доставки"), $PHPShopGUI->setInputText('', 'delivtime_new', $data['delivtime'] . $dost_ot, '190', false, 'left'), 'left');

    // Юр. данные покупателя
    $Tab4 = $PHPShopGUI->setField(__("Наименование организации "), $PHPShopGUI->setInputText('', 'org_name_new', $data['org_name'] . $order['Person']['org_name'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("ИНН "), $PHPShopGUI->setInputText('', 'org_inn_new', $data['org_inn'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("КПП"), $PHPShopGUI->setInputText('', 'org_kpp_new', $data['org_kpp'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Юридический адрес"), $PHPShopGUI->setInputText('', 'org_yur_adres_new', $data['org_yur_adres'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Фактический адрес"), $PHPShopGUI->setInputText('', 'org_fakt_adres_new', $data['org_fakt_adres'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Расчётный счёт"), $PHPShopGUI->setInputText('', 'org_ras_new', $data['org_ras'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Наименование банка"), $PHPShopGUI->setInputText('', 'org_bank_new', $data['org_bank'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Корреспондентский счёт"), $PHPShopGUI->setInputText('', 'org_kor_new', $data['org_kor'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("БИК"), $PHPShopGUI->setInputText('', 'org_bik_new', $data['org_bik'], '190', false, 'left'), 'left') .
            $PHPShopGUI->setField(__("Город"), $PHPShopGUI->setInputText('', 'org_city_new', $data['org_city'], '190', false, 'left'), 'left');

//            .
//            $PHPShopGUI->setField(__(""), 
//            $PHPShopGUI->setInputText('', '_new', $data[''], '190', false, 'left'), 'left')
    // Вывод формы закладки
    $PHPShopGUI->setTab(array(__("Основное"), $Tab1, 350), array(__("Корзина"), $Tab2, 350), array(__("Изменить данные покупателя"), $Tab3, 350), array(__("Изменить юр. данные покупателя"), $Tab4, 350));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $data);

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "visitorID", $data['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("hidden", "pole1", $_GET['pole1'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("hidden", "pole2", $_GET['pole2'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("button", "", "Отмена", "right", 70, "return onCancel();", "but") .
            $PHPShopGUI->setInput("button", "delID", "Удалить", "right", 70, "return onDelete('" . __('Вы действительно хотите удалить?') . "')", "but", "actionDelete.visitor.all") .
            $PHPShopGUI->setInput("submit", "editID", "Сохранить", "right", 70, "", "but", "actionUpdate.visitor.edit") .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionSave.visitor.edit");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * Экшен сохранения
 */
function actionSave() {
    global $PHPShopGUI;

    // Сохранение данных
    actionUpdate();

    $PHPShopGUI->setAction($_POST['visitorID'], 'actionStart', 'none');
}

/**
 * Экшен обновления
 * @return bool 
 */
function actionUpdate() {
    global $PHPShopModules, $PHPShopOrm;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);

    // Данные по заказу
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['visitorID'])));
    $order = unserialize($data['orders']);

    // Новые данные
    if (is_array($_POST['person']))
        foreach ($_POST['person'] as $k => $v)
            $order['Person'][$k] = $v;

    // Сериализация данных заказа
    $_POST['orders_new'] = serialize($order);

    // Комментарий и время обработки
    $_POST['status']['time'] = PHPShopDate::dataV();
    $_POST['status_new'] = serialize($_POST['status']);
    $_POST['seller_new'] = 0;

    $PHPShopOrm->clean();

    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['visitorID']));
    $PHPShopOrm->clean();

    return $action;
}

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler($_SERVER["SCRIPT_NAME"], __FUNCTION__, $_POST);
    $action = $PHPShopOrm->delete(array('id' => '=' . intval($_POST['visitorID'])));

    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['visitorID'], 'actionStart');

// Обработка событий
$PHPShopGUI->getAction();
?>