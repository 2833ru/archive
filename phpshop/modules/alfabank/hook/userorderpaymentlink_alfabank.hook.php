<?php

/**
 * Функция хук, вывод кнопки оплаты в ЛК и регистрация регистрация заказа в платежном шлюзе Альфабанка
 * @param object $obj объект функции
 * @param array $PHPShopOrderFunction данные о заказе
 */
function userorderpaymentlink_mod_alfabank_hook($obj, $PHPShopOrderFunction) {
    global $PHPShopSystem;

    // Настройки модуля
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    $PHPShopAlfabankArray = new PHPShopAlfabankArray();
    $option = $PHPShopAlfabankArray->getArray();

    // Оплата
    if ($_REQUEST["paynow"] == "Y") {

        // Сумма покупки
        $out_summ = $PHPShopOrderFunction->getTotal() * 100;

        // Номер заказа
        $uid = $PHPShopOrderFunction->objRow['uid'];

        // Префикс
        $order_pref = alfabank_log_check($uid);

        // НДС
        if ($PHPShopSystem->getParam('nds_enabled') == 1){
            if ($PHPShopSystem->getParam('nds') == 0)
                $tax = 1;
            elseif ($PHPShopSystem->getParam('nds') == 10)
                $tax = 2;
            elseif ($PHPShopSystem->getParam('nds') == 18)
                $tax = 3;
        } else
            $tax = 0;

        if (!empty($order_pref))
            $orderNum = $uid . '#' . $order_pref;

        $order = $PHPShopOrderFunction->unserializeParam('orders');

        // Содержимое корзины
        $i = 0;
        foreach ($order['Cart']['cart'] as $key => $arItem) {

            $amount = (floatval($arItem['price']) * intval($arItem['num'])) * 100;
            $price = floatval($arItem['price']) * 100;

            $aItem[] = array(
                "positionId"    => $i,
                "name"          => PHPShopString::win_utf8($arItem['name']),
                "itemPrice"     => $price,
                "quantity"      => array ("value" => $arItem['num'], "measure" => PHPShopString::win_utf8($arItem['ed_izm'])),
                "itemAmount"    => $amount,
                "itemCode"      => $arItem['id'],
                "tax"           => array("taxType" => $tax)
            );
            $i++;
        }

        // Доставка
        if (!empty($order['Cart']['dostavka'])) {

            PHPShopObj::loadClass('delivery');
            $PHPShopDelivery = new PHPShopDelivery($order['Person']['dostavka_metod']);

            switch ($PHPShopDelivery->getParam('ofd_nds')) {
                case 0:
                    $tax_delivery = 1;
                    break;
                case 10:
                    $tax_delivery = 2;
                    break;
                case 18:
                    $tax_delivery = 3;
                    break;
                default: $tax_delivery = $tax;
            }

            $delivery_price = floatval($out_summ) - (floatval($order['Cart']['sum']) * 100);

            $aItem[] = array(
                "positionId"    => $i + 1,
                "name"          => PHPShopString::win_utf8('Доставка'),
                "itemPrice"     => floatval($delivery_price),
                "quantity"      => array ("value" => 1, "measure" => PHPShopString::win_utf8('ед.')),
                "itemAmount"    => floatval($delivery_price),
                "itemCode"      => $i + 1,
                "tax"           => array("taxType" => $tax_delivery)
            );

        }

        $array = array(
            "customerDetails" => array("email" => $_POST["mail"]),
            "cartItems" => array("items" => $aItem));

        $orderBundle = json_encode($array);

        // Регистрация заказа в платежном шлюзе
        $params = array(
            "userName"  => $option["login"],
            "password"  => $option["password"],
            "orderNumber" => $orderNum,
            "amount"    => $out_summ,
            "returnUrl" => 'http://' . $_SERVER['HTTP_HOST'] . '/success/?uid=' . $uid,
            "failUrl"   => 'http://' . $_SERVER['HTTP_HOST'] . '/success/?uid=' . $uid,
            "orderBundle" => $orderBundle,
            "taxSystem" => intval($option["taxationSystem"])
        );

        // Режим разработки и боевой режим
        if($option["dev_mode"] == 0)
            $url ='https://pay.alfabank.ru/payment/rest/register.do';
        else
            $url ='https://web.rbsuat.com/ab/rest/register.do';

        $rbsCurl = curl_init();
        curl_setopt_array($rbsCurl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query($params, '', '&')
        ));

        $result =json_decode(curl_exec($rbsCurl), true);

        curl_close($rbsCurl);

        // Запись лога
        if(isset($result["formUrl"]))
            $PHPShopAlfabankArray->log($result, $uid, 'Заказ зарегистрирован', 'register');
        else {
            $result['errorMessage'] = PHPShopString::utf8_win1251($result['errorMessage']);
            $PHPShopAlfabankArray->log($result, $uid, 'Ошибка регистрации заказа', 'register');
        }

        header('Location: '. $result["formUrl"]);
    }

    // Контроль оплаты от статуса заказа
    if ($PHPShopOrderFunction->order_metod_id == 10021)
        if ($PHPShopOrderFunction->getParam('statusi') == $option['status'] or empty($option['status'])) {

            $order_uid = $PHPShopOrderFunction->objRow['uid'];

            $return = PHPShopText::a("/users/order.html?order_info=$order_uid&paynow=Y#Order", 'Оплатить сейчас', 'Оплатить сейчас', false, false, '_blank', 'btn btn-success pull-right');
        } elseif ($PHPShopOrderFunction->getSerilizeParam('orders.Person.order_metod') == 10010)
            $return = ', Заказ обрабатывается менеджером';

    return $return;
}

/**
 * Номер попытки создания ссылки
 * @param string $order_id
 * @return int
 */
function alfabank_log_check($order_id) {
    $PHPShopOrm = new PHPShopOrm("phpshop_modules_alfabank_log");
    $result = $PHPShopOrm->select(array('id'), array('order_id' => '="' . $order_id . '"', 'type' => '="register"'), array('order' => 'id desc'), array('limit' => 1));
    if (is_array($result))
        return $result['id']++;
}

$addHandler = array('userorderpaymentlink' => 'userorderpaymentlink_mod_alfabank_hook');
?>