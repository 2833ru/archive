<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("text");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("mail");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("delivery");

$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('yandexcart');
$PHPShopSystem = new PHPShopSystem();
$PHPShopValutaArray = new PHPShopValutaArray();

// Лог
function setYandexcartLog($data) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexcart']['yandexcart_log']);

    $log = array(
        'message_new' => serialize($data),
        'order_id_new' => $data['order']['id'],
        'date_new' => time(),
        'status_new' => $data['order']['status'],
        'path_new' => $_SERVER["PATH_INFO"]
    );

    $PHPShopOrm->insert($log);
}

// Проверка пользователя в базе
function checkUser($data) {

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
    $row = $PHPShopOrm->select(array('id'), array('login' => "='" . $data['order']['buyer']['email'] . "'"), false, array('limit' => 1));
    $userId = $row['id'];
    if (empty($userId)) {
        $PHPShopOrm->clean();
        $insert['login_new'] = $insert['name_new'] = $data['order']['buyer']['email'];
        $insert['name_new'] = iconv("utf-8", "cp1251", $data['order']['buyer']['firstName']) . ' ' . iconv("utf-8", "cp1251", $data['order']['buyer']['lastName']);
        $insert['tel_new'] = $data['order']['buyer']['phone'];
        $insert['password_new'] = base64_encode($data['order']['id']);
        $insert['enabled_new'] = 1;
        $userId = $PHPShopOrm->insert($insert);
    }

    return $userId;
}

// Настройки модуля
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexcart']['yandexcart_system']);
$option = $PHPShopOrm->select();

// Входящие данные
$data = json_decode($HTTP_RAW_POST_DATA, true);

// Авторизация
$our_token = $option['password'];
$rtoken = $_REQUEST['auth-token'];
if ($our_token != $rtoken) {
    $data['order']['status'] = 'Invalid token';
    setYandexcartLog($data);
    header("HTTP/1.1 403 Unauthorized");
    die('Invalid token');
}



// Роутер
switch ($_SERVER["PATH_INFO"]) {

    // Добавление в корзину
    case('/cart'):

        // Корзина
        $items = $data['cart']['items'];
        $priceTotal = 0;
        if (is_array($items))
            foreach ($items as $item) {
                $mi["feedId"] = $item["feedId"];
                $mi["offerId"] = $item["offerId"];

                $PHPShopProduct = new PHPShopProduct($item["offerId"]);
                $PHPShopProduct->debug = false;
                $mi["price"] = intval(PHPShopProductFunction::GetPriceValuta($item["offerId"], $PHPShopProduct->getPrice()));
                $mi["count"] = $PHPShopProduct->getValue('items');

                if ($PHPShopSystem->getSerilizeParam('admoption.sklad_status') != 1 and $mi["count"] < 0)
                    $mi["count"] = 0;
                else
                    $mi["count"] = 10;

                $priceTotal += $mi["price"] * $item['count'];
                $mi["delivery"] = true;
                $result["cart"]["items"][] = $mi;
            }


        // Доставка
        $result["cart"]["deliveryOptions"] = array();
        $PHPShopDeliveryArray = new PHPShopDeliveryArray(array('enabled' => "='1'", 'is_folder' => "!='1'", 'PID' => '=1'));
        $deliveryPrice = $PHPShopDeliveryArray->getArray();

        // Проверка на бесплатную доставку
        //if (!empty($deliveryPrice['price_null_enabled']) and $deliveryPrice['price_null_enabled'] and $priceTotal > $deliveryPrice['price_null'])
        //$deliveryPrice = 0;
        
        if (is_array($deliveryPrice))
            foreach ($deliveryPrice as $delivery) {
                if ($delivery['id'])
                    $result["cart"]["deliveryOptions"][] = array(
                        "id" => $delivery['id'],
                        "type" => "DELIVERY",
                        "serviceName" => iconv("cp1251", "utf-8", substr($delivery['city'], 0, 50)),
                        "price" => intval($delivery['price']),
                        "dates" => array("fromDate" => date("d-m-Y"),'toDate'=>date("d-m-Y", strtotime("+2 day")))
                    );
            }

        $paymentMethods[0] = array('CASH_ON_DELIVERY', 'CARD_ON_DELIVERY');
        $paymentMethods[1] = array('CASH_ON_DELIVERY', 'CARD_ON_DELIVERY');
        $paymentMethods[2] = array('CASH_ON_DELIVERY');

        $result["cart"]["paymentMethods"] = $paymentMethods[intval($option['payment_delivery'])];

        setYandexcartLog($data);

        break;

    case "/order/accept":
        $items = $data['order']['items'];
        $result["order"]["items"] = array();
        $sum = 0;

        // Корзина
        if (is_array($items))
            foreach ($items as $product_id => $product) {
                $product_id = $product["offerId"];
                $product_name = $product["offerName"];
                $product_price = $product["price"];
                $product_num = $product["count"];
                $sum += $product_price * $product_num;

                $PHPShopProduct = new PHPShopProduct($product_id);

                $rcart[$product_id] = array(
                    'id' => $product_id,
                    'name' => iconv("utf-8", "cp1251", $product_name),
                    'price' => $product_price,
                    'uid' => $product_id,
                    'num' => $product_num,
                    'pic_small' => $PHPShopProduct->getParam("pic_small")
                );
            }
        $result["Cart"]["cart"] = $rcart;
        $result["Cart"]["num"] = count($items);
        $result["Cart"]["sum"] = $sum;

        // Доставка        
        //$delivery_method = $data['order']['delivery']['id'];
        $delivery_price = $data['order']['delivery']['price'];
        $result["Cart"]["dostavka"] = $delivery_price;


        // Номер заказа
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $row = $PHPShopOrm->select(array('uid'), false, array('order' => 'id desc'), array('limit' => 1));
        $last = $row['uid'];
        $all_num = explode("-", $last);
        $ferst_num = $all_num[0];
        $order_num = $ferst_num + 1;
        $order_num = $order_num . "-" . substr(abs(crc32(uniqid(session_id()))), 0, 2);

        // Данные покупателя
        $result["Person"] = array(
            "ouid" => $order_num,
            "data" => date("U"),
            "time" => date("H:s a"),
            "mail" => 'market@yandex.ru',
            "name_person" => 'Яндекс.Маркет',
            "org_name" => null,
            "org_inn" => null,
            "org_kpp" => null,
            "tel_code" => null,
            "tel_name" => null,
            "adr_name" => null,
            "dostavka_metod" => intval($data['order']['delivery']['id']),
            "discount" => 0,
            "user_id" => null,
            "dos_ot" => null,
            "dos_do" => null,
            "order_metod" => $option[$data['order']['paymentMethod']]
        );

        $update['fio_new'] = 'Яндекс.Маркет';
        $update['street_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['street']);
        $update['country_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['country']);
        $update['city_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['city']);
        $update['house_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['house']);

        $update['door_phone_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['entryphone']);
        $update['tel_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['phone']);
        $update['porch_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['entrance']);
        $update['flat_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['apartment']);

        $insert['datas_new'] = time();
        $insert['uid_new'] = $order_num;
        $insert['orders_new'] = serialize($result);
        $insert['status_new'] = serialize(array('maneger' => iconv("utf-8", "cp1251", $data['order']['notes'])));
        $insert['dop_info_new'] = 'yandex' . $data['order']['id'];


        // Запись заказа в БД
        $PHPShopOrm->insert($insert);

        unset($result);

        $result['order'] = array(
            'accepted' => true,
            'id' => $order_num,
        );

        setYandexcartLog($data);

        break;

    case "/order/status":

        $order_uid = $data['order']['id'];

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);

        // Проведен
        if ($data['order']['status'] == 'PROCESSING') {

            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
            $row = $PHPShopOrm->select(array('*'), array('dop_info' => "='yandex" . $order_uid . "'"));
            if (is_array($row)) {
                $orders = unserialize($row['orders']);
                $orders["Person"]['name_person'] = null;
                $orders["Person"]['mail'] = $data['order']['buyer']['email'];
                $orders["Person"]['tel_name'] = null;

                $update['orders_new'] = serialize($orders);
            }

            $update['fio_new'] = iconv("utf-8", "cp1251", $data['order']['buyer']['firstName']) . ' ' . iconv("utf-8", "cp1251", $data['order']['buyer']['lastName']) . ' ';
            $update['street_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['street']);
            $update['country_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['country']);
            $update['city_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['city']);
            $full_adress = $data['order']['delivery']['address']['house'];

            // Корпус 
            if (!empty($data['order']['delivery']['address']['block']))
                $full_adress.= ' k' . $data['order']['delivery']['address']['block'];

            $update['house_new'] = iconv("utf-8", "cp1251", $full_adress);

            $update['tel_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['phone']);
            $update['porch_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['entrance']);
            $update['flat_new'] = iconv("utf-8", "cp1251", $data['order']['delivery']['address']['entryphone']);
            $update['user_new'] = checkUser($data);


            //$update['status_new'] = serialize('maneger' => 'YANDEX '.$data['order']['status']));
            $PHPShopOrm->clean();

            if (!empty($data['order']['id'])) {
                $PHPShopOrm->update($update, array('dop_info' => "='yandex" . $data['order']['id'] . "' limit 1"));

                // Сообщение о новом заказе администрации
                new PHPShopMail($PHPShopSystem->getEmail(), $data['order']['buyer']['email'], 'Поступил заказ №' . $order_uid, 'Заказ оформлен на Яндекс.Маркет');
            }
        }

        // Отменен пользователем
        if ($data['order']['status'] == 'CANCELLED') {

            $update['statusi_new'] = $option['status_cancelled'];

            if (!empty($data['order']['id'])) {

                $PHPShopOrm->update($update, array('dop_info' => "='yandex" . $data['order']['id'] . "' limit 1"));
            }
        }

        setYandexcartLog($data);

        header("HTTP/1.1 200");
        die('OK');
        exit();
        break;

    default:
        $data['order']['status'] = 'Bad Request';
        setYandexcartLog($data);
        header("HTTP/1.1 400 Bad Request");
        die('Bad Request');
}

header("HTTP/1.1 200");
header("Content-Type: application/json");
echo json_encode($result);
?>