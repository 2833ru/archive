<?php

$_classPath = "../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
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
PHPShopObj::loadClass("lang");

$PHPShopLang = new PHPShopLang();

$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('yandexturbo');
$PHPShopSystem = new PHPShopSystem();
$PHPShopValutaArray = new PHPShopValutaArray();

// Лог
function setYandexcartLog($data) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexturbo']['yandexturbo_log']);

    $log = array(
        'message_new' => serialize($data),
        'order_id_new' => $data['parameters']['order']['id'],
        'date_new' => time(),
        'status_new' => $data['order']['status'],
        'path_new' => $_SERVER["PATH_INFO"]
    );

    $PHPShopOrm->insert($log);
}

// Настройки модуля
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexturbo']['yandexturbo_system']);
$option = $PHPShopOrm->select();
$jsonOptions = unserialize($option['options']);

// Входящие данные
$data = json_decode(file_get_contents('php://input'), true);

// Авторизация
$headers = apache_request_headers();
if ($option['auth_token'] !== $headers['Authorization']) {
    $data['order']['status'] = 'Invalid token';
    setYandexcartLog($data);
    header("HTTP/1.1 403 Unauthorized");
    die('Invalid token');
}

// Роутер
switch ($_SERVER["PATH_INFO"]) {

    case "/order/accept":
        $sum = 0;
        $weight = 0;
        // Корзина
        if (is_array($data['order']['items']))
            foreach ($data['order']['items'] as $product) {
                $sum += $product['price'] * $product['count'];

                $PHPShopProduct = new PHPShopProduct($product['offerId']);

                $order["Cart"]["cart"][$product["offerId"]] = [
                    'id' => $product['offerId'],
                    'name' => $PHPShopProduct->getName(),
                    'price' => $product['price'],
                    'uid' => $PHPShopProduct->getParam('uid'),
                    'num' => $product['count'],
                    'pic_small' => $PHPShopProduct->getParam('pic_small'),
                    'category' => $PHPShopProduct->getParam('category')
                ];
                $weight += (float) $PHPShopProduct->getParam('weight');
            }
        $order["Cart"]["num"] = count($order["Cart"]["cart"]);
        $order["Cart"]["sum"] = $sum;

        // Доставка
        $deliveryId = $option['delivery_id'];
        $orm = new PHPShopOrm($GLOBALS['SysValue']['base']['delivery']);
        $delivery = $orm->getOne(['*'], ['id' => '="' . $option['delivery_id'] . '"']);
        $order["Cart"]["dostavka"] = yandexDeliveryPrice($delivery, $sum, $weight);

        // Номер заказа
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $row = $PHPShopOrm->select(array('id'), false, array('order' => 'id desc'), array('limit' => 1));

        switch ($data['order']['paymentMethod']) {
            case 'YANDEX':
                $payment = $payments['yandex'];
                break;
            case 'APPLE_PAY':
                $payment = $payments['apple_pay'];
                break;
            case 'GOOGLE_PAY':
                $payment = $payments['google_pay'];
                break;
            case 'CREDIT':
                $payment = $payments['credit'];
                break;
            case 'EXTERNAL_CERTIFICATE':
                $payment = $payments['certificate'];
                break;
            case 'CARD_ON_DELIVERY':
                $payment = $payments['card_on_delivery'];
                break;
            default:
                $payment = $payments['cash_on_delivery'];
        }

        $orderNum = (int) $row['id'] + 1 . "-" . rand(10, 99);

        // Данные покупателя
        $order["Person"] = array(
            "ouid" => $orderNum,
            "data" => date("U"),
            "time" => date("H:s a"),
            "mail" => __('market@yandex.ru'),
            "name_person" => __('Яндекс.Турбо'),
            "org_name" => null,
            "org_inn" => null,
            "org_kpp" => null,
            "tel_code" => null,
            "tel_name" => null,
            "adr_name" => null,
            "dostavka_metod" => $deliveryId,
            "discount" => 0,
            "user_id" => null,
            "dos_ot" => null,
            "dos_do" => null,
            "order_metod" => $payment
        );

        $insert['fio_new'] = __('Яндекс.Турбо');
        $insert['datas_new'] = time();
        $insert['uid_new'] = $orderNum;
        $insert['orders_new'] = serialize($order);
        $insert['yandex_order_id_new'] = $data['order']['id'];
        $insert['sum_new'] = $sum + $order["Cart"]["dostavka"];
        $insert['dop_info_new'] = PHPShopString::utf8_win1251('Яндекс.Турбо Заказ №'.$data['order']['id'].'
'.$data['order']['notes']);

        // Запись заказа в БД
        $data['order']['id'] = $PHPShopOrm->insert($insert);

        $result['order'] = array(
            'accepted' => true,
            'id' => $orderNum,
        );

        setYandexcartLog([
            'parameters' => $data,
            'response' => $result,
        ]);

        break;

    case "/order/status":

        // Проведен
        if ($data['order']['status'] === 'PROCESSING') {

            $row = (new PHPShopOrm($GLOBALS['SysValue']['base']['orders']))
                    ->getOne(['*'], ['yandex_order_id' => sprintf("='%s'", $data['order']['id'])]);

            // Статус заказа подтвержден клиентом 
            $update['statusi_new'] = $jsonOptions['statuses'];
            $update['fio_new'] = PHPShopString::utf8_win1251($data['order']['buyer']['name']);
            $update['tel_new'] = $data['order']['buyer']['phone'];
            
            $order = unserialize($row['orders']);
            $order["Person"]['mail'] = $data['order']['buyer']['email'];
            $update['orders_new'] = serialize($order);

            (new PHPShopOrm($GLOBALS['SysValue']['base']['orders']))
                    ->update($update, array('id' => sprintf('="%s"', $row['id'])));

            // Сообщение о новом заказе администрации
            new PHPShopMail($PHPShopSystem->getEmail(), $PHPShopSystem->getEmail(), 'Поступил заказ №' . $row['uid'], 'Заказ оформлен на Яндекс.Турбо', false, false);
        }

        $data['order']['id'] = $row['id'];

        setYandexcartLog([
            'parameters' => $data
        ]);

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

function yandexDeliveryPrice($delivery, $sum, $weight) {

    if ($delivery['price_null_enabled'] == 1 and $sum >= $delivery['price_null']) {
        return 0;
    }

    if ($delivery['taxa'] > 0) {
        $addweight = $weight - 500;
        if ($addweight < 0) {
            $addweight = 0;
        }
        $addweight = ceil($addweight / 500) * $delivery['taxa'];
        $endprice = $delivery['price'] + $addweight;

        return $endprice;
    }

    return $delivery['price'];
}

header("HTTP/1.1 200");
header("Content-Type: application/json");
echo json_encode($result);
?>