<?php

/**
 * Адрес
 */
function order_hook_full_adres() {
    $Arg = func_get_args();
    $str = null;
    foreach ($Arg as $val) {
        if (!empty($val))
            $str.=$val . ', ';
    }
    return substr($str, 0, strlen($str) - 2);
}

/**
 * Добавление кнопки быстрого заказа
 */
function order_yandexorder_hook($obj, $row, $rout) {

    if ($rout == 'MIDDLE') {
        $callback = urlencode('http://' . $_SERVER['SERVER_NAME'] . $obj->getValue('dir.dir') . '/order/');
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexorder']['yandexorder_system']);
        $data = $PHPShopOrm->select();

        if (empty($data['img']))
            $button_img = 'https://help.yandex.ru/help.yandex.ru/partnermarket/image/4.png';
        else
            $button_img = $data['img'];

        $button = '<br><a href="http://market.yandex.ru/addresses.xml?callback=' . $callback . '&type=json"><img src="' . $button_img . '" border="0" /></a>';

        $order_action_add = '
<script>

    // YandexOrder PHPShop Module
    $(document).ready(function() {
        $(\'' . $button . '\').insertAfter("#dop_info");
    });            
';



        // Форма личной информации по заказу
        $cart_min = $obj->PHPShopSystem->getSerilizeParam('admoption.cart_minimum');
        if ($cart_min <= $obj->PHPShopCart->getSum(false)) {




            // Заполнеям данными из Яндекса
            if (isset($_POST['operation_id'])) {
                $_SESSION['setYandexOrderVal'] = true;

                // подгружаем данные пользователя из яндекса
                if (isset($_POST['operation_id'])) {
                    $addressArr = json_decode(urldecode($_POST['address']));
                    foreach ($addressArr as $k => $v) {
                        $_POST[$k] = PHPShopString::utf8_win1251($v);
                    }
                }

                $adres = order_hook_full_adres($_POST['city'], $_POST['street'], 'д.' . $_POST['building'], 'корпус ' . $_POST['suite'], 'подъезд ' . $_POST['entrance'], 'квартира ' . $_POST['flat'], 'этаж ' . $_POST['floor'], 'метро ' . $_POST['metro'], $_POST['comment']);
                $obj->set('UserTel', $_POST['phone']);
                $obj->set('UserTelCode', $_POST['phone-extra']);
                $obj->set('UserName', PHPShopString::utf8_win1251($_POST['firstname'] . ' ' . $_POST['lastname']));
                $obj->set('UserMail', $_POST['email']);
                $obj->set('UserAdres', PHPShopString::utf8_win1251($adres));

                $order_action_add.='                 
    function setYandexOrderVal(){
       $("input[name=\'mail\']").val("' . $_POST['email'] . '");
       $("input[name=\'name_new\']").val("' . PHPShopString::utf8_win1251($_POST['firstname']) . '");
       $("input[name=\'fio_new\']").val("' . PHPShopString::utf8_win1251($_POST['firstname'] . ' ' . $_POST['lastname']) . '");
       $("input[name=\'tel_new\']").val("' . PHPShopString::utf8_win1251($_POST['phone']) . '");
       $("input[name=\'dop_info\']").val("' . PHPShopString::utf8_win1251($_POST['comment']) . '");
       $("input[name=\'index_new\']").val("' . PHPShopString::utf8_win1251($_POST['zip']) . '");
       $("input[name=\'street_new\']").val("' . PHPShopString::utf8_win1251($_POST['street']) . '");
       $("input[name=\'house_new\']").val("' . PHPShopString::utf8_win1251($_POST['building'] . ' ' . $_POST['suite']) . '");
       $("input[name=\'porch_new\']").val("' . PHPShopString::utf8_win1251($_POST['entrance']) . '");
       $("input[name=\'flat_new\']").val("' . PHPShopString::utf8_win1251($_POST['flat']) . '");
    }

';
            }

            $order_action_add.='</script>';

            // Добавляем JS в форму заказа
            $obj->set('order_action_add', $order_action_add, true);
        }
    }
}

$addHandler = array
    (
    'order' => 'order_yandexorder_hook'
);
?>