<?php

/**
 * Запись заказа в базу партнера
 */
function write_moysklad_hook($obj, $newAdres, $rout) {

    if ($rout == 'END') {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['moysklad']['moysklad_system']);
        $option = $PHPShopOrm->select();

        // Общие данные заказа
        $order_array = unserialize($obj->order);

        // Корзина
        $data['cart'] = $order_array['Cart']['cart'];

        // Заказ
        $data['ouid'] = $order_array['Person']['ouid'];
        $data['discount'] = $order_array['Person']['discount'];
        $data['delivery_id'] = $order_array['Person']['dostavka_metod'];
        $data['delivery_price'] = $obj->delivery;

        // Покупатель
        $data['email'] = $newAdres['mail'];

        //если авторизован, имя берём из сессии, иначе из формы.
        if (!empty($_SESSION['UsersId']) and PHPShopSecurity::true_num($_SESSION['UsersId']))
             $data['name']= $_SESSION['UsersName'];
        elseif (!empty($_POST['name_new']))
            $data['name']= $_POST['name_new'];
        else
             $data['name']= $_POST['name_person'];

        $data['legalTitle'] = $newAdres['org_name_new'];
        $data['legalAddress'] = $newAdres['org_yur_adres_new'];
        $data['actualAddress'] = $newAdres['org_fakt_adres_new'];
        $data['bic'] = $newAdres['org_bik_new'];
        $data['inn'] = $newAdres['org_inn_new'];
        $data['kpp'] = $newAdres['org_kpp_new'];
        $data['accountNumber'] = $newAdres['org_ras_new'];
        $data['bankName'] = $newAdres['org_bank_new'];
        $data['correspondentAccount'] = $newAdres['org_kor_new'];
        $data['correspondentAccount'] = $newAdres['org_kor_new'];
        $data['addres'] = $newAdres['index_new'] . ' ' . $newAdres['city_new'] . ' ' . $newAdres['street_new'] . ' ' . $newAdres['house_new'] . ' ' . $newAdres['flat_new'] . ' ' . $newAdres['dop_info_new'];
        $data['phones'] = $newAdres['tel_new'];

        // Библиотека
        include_once($GLOBALS['SysValue']['class']['moysklad']);

        $Rest = new MoyskladRest();
        $Rest->_credentials['USER'] = $option['merchant_user'];
        $Rest->_credentials['PWD'] = $option['merchant_pwd'];
        $Rest->_credentials['VAT'] = $option['nds'];
        $Rest->_credentials['ORG'] = $option['org_code'];
        $result = $Rest->addOrder($data);

        // Запись лога
        $Rest->log(array('Отчет' => $result), 'Загружен заказ № '.$data['ouid']);
    }
}

$addHandler = array
    (
    'write' => 'write_moysklad_hook'
);
?>