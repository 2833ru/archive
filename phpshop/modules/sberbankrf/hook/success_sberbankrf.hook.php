<?php

include_once dirname(__DIR__) . '/class/Sberbank.php';

/**
 * Функция хук, обратотка результата выполнения платежа
 * @param object $obj объект функции
 * @param array $value данные о заказе
 */
function success_mod_sberbankrf_hook($obj, $value) {
    if (isset($_REQUEST['uid'])) {

        // Номер заказа
        if(strstr($_REQUEST['uid'], "#")){
            $orderArray = explode ("#", $_REQUEST['uid']);
            $orderNum = $orderArray[0];
        }
        else  $orderNum = $_REQUEST['uid'];

        // Проверям статус оплаты и пишем лог модуля
        $status = sberbankrf_check($obj, $orderNum, $_REQUEST['orderId']);

        if($status == 2){
            $obj->order_metod = 'modules" and id="10010';

            $mrh_ouid = explode("-", $orderNum);
            $obj->inv_id = $mrh_ouid[0] . $mrh_ouid[1];

            $obj->ofd();

            $obj->message();

            return true;
        } else
            $obj->error();
    }
}

/**
 * Функция проверки статуса платежа в системе Сбербанк России
 * @param object $obj объект функции
 * @param string $id номер заказа
 */
function sberbankrf_check($obj, $id, $merchant_order_id){

    $PHPShopOrm = new PHPShopOrm();
    $Sberbank = new Sberbank();

    // Проверка статуса
    $params = array(
        "orderId" => $merchant_order_id,
        "userName" => $Sberbank->options["login"],
        "password" => $Sberbank->options["password"],
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Sberbank->getApiUrl() . 'getOrderStatus.do' . "?" . http_build_query($params));
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    $r = json_decode(curl_exec($ch), true); // run the whole process
    curl_close($ch);

    // Ошибка запроса
    if($r['ErrorCode'] != 0) {
        $r['errorMessage'] = PHPShopString::utf8_win1251($r['errorMessage']);
        $Sberbank->log($r, $id, 'Ошибка проведения платежа', 'Запрос состояния заказа');

        return $r['OrderStatus'];

        // Ошибка проведения платежа
    }elseif($r['OrderStatus'] != 2){

        $code_description = PHPShopString::utf8_win1251($r['actionCodeDescription']);
        $Sberbank->log($r, $id, $code_description, 'Запрос состояния заказа');

        return $r['OrderStatus'];
    }else{
        $order_status = $obj->set_order_status_101();
        $PHPShopOrm->query("UPDATE `phpshop_orders` SET `statusi`='$order_status', `paid` = 1 WHERE `uid`='$id'");

        $Sberbank->log($r, $id, 'Платеж проведен', 'Запрос состояния заказа');

        return $r['OrderStatus'];
    }

}
$addHandler = array('index' => 'success_mod_sberbankrf_hook');
?>