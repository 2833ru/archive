<?php

function tab_userdata($data, $order) {
    global $PHPShopGUI;
    
    
    $PHPShopGUI->field_col=3;
    
    $help='<p class="text-muted hidden-xs data-row">'.__('Дополнительные поля в заказе и требование к их обязательному заполнению можно настроить в разделе').'  <a href="?path=delivery&id='.$order['Person']['dostavka_metod'].'&tab=1"><span class="glyphicon glyphicon-share-alt"></span>'.__('Управление доставкой').'</a></p><hr>';
    
    if(empty($data['fio'])){
        $data['fio']=$order['Person']['name_person'];
    }

    // Данные покупателя
    $disp1 = $PHPShopGUI->setField("ФИО", $PHPShopGUI->setInputText('', 'fio_new', $data['fio'] )) .
            $PHPShopGUI->setField("Телефон", $PHPShopGUI->setInputText('', 'tel_new', $data['tel'])) .
            $PHPShopGUI->setField("Страна", $PHPShopGUI->setInputText('', 'country_new', $data['country'])) .
            $PHPShopGUI->setField("Регион/штат", $PHPShopGUI->setInputText('', 'state_new', $data['state'])) .
            $PHPShopGUI->setField("Город", $PHPShopGUI->setInputText('', 'city_new', $data['city'])) .
            $PHPShopGUI->setField("Индекс", $PHPShopGUI->setInputText('', 'index_new', $data['index'])) .
            $PHPShopGUI->setField("Улица", $PHPShopGUI->setInputText('', 'street_new', $data['street'] . $order['Person']['adr_name'])) .
            $PHPShopGUI->setField("Дом", $PHPShopGUI->setInputText('', 'house_new', $data['house'])) .
            $PHPShopGUI->setField("Подъезд", $PHPShopGUI->setInputText('', 'porch_new', $data['porch'])) .
            $PHPShopGUI->setField("Код домофона", $PHPShopGUI->setInputText('', 'door_phone_new', $data['door_phone'])) .
            $PHPShopGUI->setField("Квартира", $PHPShopGUI->setInputText('', 'flat_new', $data['flat']));

    // Юр. данные покупателя
    $disp2 = $PHPShopGUI->setField("Компания", $PHPShopGUI->setInputText('', 'org_name_new', $data['org_name'] . $order['Person']['org_name'])) .
            $PHPShopGUI->setField("ИНН", $PHPShopGUI->setInputText('', 'org_inn_new', $data['org_inn'])) .
            $PHPShopGUI->setField("КПП", $PHPShopGUI->setInputText('', 'org_kpp_new', $data['org_kpp'])) .
            $PHPShopGUI->setField("Юр. адрес", $PHPShopGUI->setInputText('', 'org_yur_adres_new', $data['org_yur_adres'])) .
            $PHPShopGUI->setField("Факт. адрес", $PHPShopGUI->setInputText('', 'org_fakt_adres_new', $data['org_fakt_adres'])) .
            $PHPShopGUI->setField("Рас. счёт", $PHPShopGUI->setInputText('', 'org_ras_new', $data['org_ras'])) .
            $PHPShopGUI->setField("Банк", $PHPShopGUI->setInputText('', 'org_bank_new', $data['org_bank'])) .
            $PHPShopGUI->setField("Кор. счёт", $PHPShopGUI->setInputText('', 'org_kor_new', $data['org_kor'])) .
            $PHPShopGUI->setField("БИК", $PHPShopGUI->setInputText('', 'org_bik_new', $data['org_bik'])) .
            $PHPShopGUI->setField("Город", $PHPShopGUI->setInputText('', 'org_city_new', $data['org_city'])).
            $PHPShopGUI->setField("Время доставки", $PHPShopGUI->setInputText('', 'delivtime_new', $data['delivtime']));


    return $help.$PHPShopGUI->setGrid(array($disp1, 6), array($disp2, 6));
}

?>