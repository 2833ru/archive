<?php

$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.visualcart.visualcart_memory"));

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));
    return array('success' => $action);
}

/**
 * Экшен сохранения
 */
function actionUpdate() {
    global $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_POST['rowID'])));

    if (empty($data['name']))
        $name = 'Имя не указано';
    else
        $name = PHPShopSecurity::TotalClean($data['name'], 2);

    if (empty($data['tel']))
        $phone = 'тел. не указан';
    else
        $phone = PHPShopSecurity::TotalClean($data['tel'], 2);

    $mail = PHPShopSecurity::TotalClean($data['mail'], 2);

    $cart = unserialize($data['cart']);
    $PHPShopCart = new PHPShopCart($cart);

    $order['Cart']['cart'] = $PHPShopCart->getArray();
    $order['Cart']['num'] = $PHPShopCart->getNum();
    $order['Cart']['sum'] = $PHPShopCart->getSum(true);
    $order['Cart']['weight'] = $PHPShopCart->getWeight();
    $order['Cart']['dostavka'] = '';

    $order['Person']['ouid'] = order_num();
    $order['Person']['data'] = time();
    $order['Person']['time'] = '';
    $order['Person']['mail'] = $mail;
    $order['Person']['name_person'] = $name;
    $order['Person']['org_name'] = '';
    $order['Person']['org_inn'] = '';
    $order['Person']['org_kpp'] = '';
    $order['Person']['tel_code'] = '';
    $order['Person']['tel_name'] = '';
    $order['Person']['adr_name'] = '';
    $order['Person']['dostavka_metod'] = '';
    $order['Person']['discount'] = 0;
    $order['Person']['user_id'] = '';
    $order['Person']['dos_ot'] = '';
    $order['Person']['dos_do'] = '';
    $order['Person']['order_metod'] = '';
    $insert['dop_info_new'] = '';

    // данные для записи в БД
    $insert['datas_new'] = time();
    $insert['uid_new'] = order_num();
    $insert['orders_new'] = serialize($order);
    $insert['fio_new'] = $name;
    $insert['tel_new'] = $phone;
    $insert['user_new'] = $data['user'];
    $insert['statusi_new'] = 0;
    $insert['status_new'] = serialize(array("maneger" => 'Брошенная корзина'));

    // Запись в базу
    $PHPShopOrmOrder = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $action = $PHPShopOrmOrder->insert($insert);
    if(!empty($action))
        $action=true;

    $PHPShopOrm->delete(array('id' => '=' . intval($_POST['rowID'])));
    return array('success' => $action);
}

// номер заказа
function order_num() {
    // Рассчитываем номер заказа
    $PHPShopOrm = new PHPShopOrm();
    $res = $PHPShopOrm->query("select uid from " . $GLOBALS['SysValue']['base']['orders'] . " order by id desc LIMIT 0, 1");
    $row = mysqli_fetch_array($res);
    $last = $row['uid'];
    $all_num = explode("-", $last);
    $ferst_num = $all_num[0];

    if ($ferst_num < 100)
        $ferst_num = 100;
    $order_num = $ferst_num + 1;

    // Номер заказа
    $ouid = $order_num . "-" . substr(abs(crc32(uniqid(session_id()))), 0, 3);
    return $ouid;
}

// Обработка событий
$PHPShopGUI->getAction();

?>
