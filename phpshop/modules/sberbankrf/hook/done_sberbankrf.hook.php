<?php
/**
 * Функция хук, регистрация заказа в платежном шлюзе Сбербанка Российской Федерации, переадресация на платежную форму
 * @param object $obj объект функции
 * @param array $value данные о заказе
 * @param string $rout место внедрения хука
 */
function send_sberbankrf_hook($obj, $value, $rout) {
    global $PHPShopSystem;

    if ($rout == 'MIDDLE' and $value['order_metod'] == 10010) {

        // Настройки модуля
        include_once(dirname(__FILE__) . '/mod_option.hook.php');

        $PHPShopSberbankRFArray = new PHPShopSberbankRFArray();
        $option = $PHPShopSberbankRFArray->getArray();

        $aCart = $obj->PHPShopCart->getArray();

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

        // Контроль оплаты от статуса заказа
        if (empty($option['status'])) {

            // Сумма покупки
            $out_summ = number_format($obj->get('total'), 2, '.', '') * 100;

            // Содержимое корзины
            $i = 0;
            foreach ($aCart as $key => $arItem) {

                // Скидка
                if($obj->discount > 0)
                    $price = ($arItem['price']  - ($arItem['price']  * $obj->discount  / 100)) * 100;
                else
                    $price = $arItem['price'] * 100;

                $amount = (floatval($price) * intval($arItem['num']));

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
            if ($obj->delivery > 0) {

                switch ($obj->PHPShopDelivery->getParam('ofd_nds')) {
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

                $cartSum = $obj->PHPShopCart->getSum();

                $delivery_price = floatval($out_summ) - (floatval($cartSum) * 100);

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

            // Префикс
            $order_pref = sberbankrf_log_check($value['ouid']);
            $orderNum = $value['ouid'];

            if (!empty($order_pref))
                $orderNum = $value['ouid'] . '#' . $order_pref;

            // Регистрация заказа в платежном шлюзе
            $params = array(
                "userName"  => $option["login"],
                "password"  => $option["password"],
                "orderNumber" => $orderNum,
                "amount"    => $out_summ,
                "returnUrl" => 'http://' . $_SERVER['HTTP_HOST'] . '/success/?uid=' . $value['ouid'],
                "failUrl"   => 'http://' . $_SERVER['HTTP_HOST'] . '/success/?uid=' . $value['ouid'],
                "orderBundle" => $orderBundle,
                "taxSystem" => intval($option["taxationSystem"])
            );

            // Режим разработки и боевой режим
            if($option["dev_mode"] == 1)
                $url ='https://3dsec.sberbank.ru/payment/rest/register.do';
            else
                $url ='https://securepayments.sberbank.ru/payment/rest/register.do';

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
                $PHPShopSberbankRFArray->log($result, $value['ouid'], 'Заказ зарегистрирован', 'register');
            else {
                $result['errorMessage'] = PHPShopString::utf8_win1251($result['errorMessage']);
                $PHPShopSberbankRFArray->log($result, $value['ouid'], 'Ошибка регистрации заказа', 'register');
            }

            header('Location: '. $result["formUrl"]);
        }else{

            $obj->set('mesageText', $option['title_sub'] );

            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['order_forma_mesage']);

            $obj->set('orderMesage', $forma);
        }
    }
}

/**
 * Номер попытки создания ссылки
 * @param string $order_id
 * @return int
 */
function sberbankrf_log_check($order_id) {
    $PHPShopOrm = new PHPShopOrm("phpshop_modules_sberbankrf_log");
    $result = $PHPShopOrm->select(array('id'), array('order_id' => '="' . $order_id . '"', 'type' => '="register"'), array('order' => 'id desc'), array('limit' => 1));
    if (is_array($result))
        return $result['id']++;
}
$addHandler = array('send_to_order' => 'send_sberbankrf_hook');
?>