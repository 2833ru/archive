<?php

function tab_userdata($data, $order) {
    global $PHPShopGUI;
    
    
    $PHPShopGUI->field_col=3;
    
    $help='<p class="text-muted hidden-xs data-row">Дополнительные поля в заказе и требование к их обязательному заполнению можно настроить в разделе  <a href="?path=delivery&id='.$order['Person']['dostavka_metod'].'&tab=1"><span class="glyphicon glyphicon-share-alt"></span>Управление доставкой</a></p><hr>';
    
    if(empty($data['fio'])){
        $data['fio']=$order['Person']['name_person'];
    }

    // Данные покупателя
    $disp1 = $PHPShopGUI->setField(__("ФИО"), $PHPShopGUI->setInputText('', 'fio_new', $data['fio'] )) .
            $PHPShopGUI->setField(__("Телефон"), $PHPShopGUI->setInputText('', 'tel_new', $data['tel'])) .
            $PHPShopGUI->setField(__("Страна"), $PHPShopGUI->setInputText('', 'country_new', $data['country'])) .
            $PHPShopGUI->setField(__("Регион/штат"), $PHPShopGUI->setInputText('', 'state_new', $data['state'])) .
            $PHPShopGUI->setField(__("Город"), $PHPShopGUI->setInputText('', 'city_new', $data['city'])) .
            $PHPShopGUI->setField(__("Индекс"), $PHPShopGUI->setInputText('', 'index_new', $data['index'])) .
            $PHPShopGUI->setField(__("Улица"), $PHPShopGUI->setInputText('', 'street_new', $data['street'] . $order['Person']['adr_name'])) .
            $PHPShopGUI->setField(__("Дом"), $PHPShopGUI->setInputText('', 'house_new', $data['house'])) .
            $PHPShopGUI->setField(__("Подъезд"), $PHPShopGUI->setInputText('', 'porch_new', $data['porch'])) .
            $PHPShopGUI->setField(__("Код домофона"), $PHPShopGUI->setInputText('', 'door_phone_new', $data['door_phone'])) .
            $PHPShopGUI->setField(__("Квартира"), $PHPShopGUI->setInputText('', 'flat_new', $data['flat']));

    // Юр. данные покупателя
    $disp2 = $PHPShopGUI->setField(__("Компания"), $PHPShopGUI->setInputText('', 'org_name_new', $data['org_name'] . $order['Person']['org_name'])) .
            $PHPShopGUI->setField(__("ИНН "), $PHPShopGUI->setInputText('', 'org_inn_new', $data['org_inn'])) .
            $PHPShopGUI->setField(__("КПП"), $PHPShopGUI->setInputText('', 'org_kpp_new', $data['org_kpp'])) .
            $PHPShopGUI->setField(__("Юр. адрес"), $PHPShopGUI->setInputText('', 'org_yur_adres_new', $data['org_yur_adres'])) .
            $PHPShopGUI->setField(__("Факт. адрес"), $PHPShopGUI->setInputText('', 'org_fakt_adres_new', $data['org_fakt_adres'])) .
            $PHPShopGUI->setField(__("Рас. счёт"), $PHPShopGUI->setInputText('', 'org_ras_new', $data['org_ras'])) .
            $PHPShopGUI->setField(__("Банк"), $PHPShopGUI->setInputText('', 'org_bank_new', $data['org_bank'])) .
            $PHPShopGUI->setField(__("Кор. счёт"), $PHPShopGUI->setInputText('', 'org_kor_new', $data['org_kor'])) .
            $PHPShopGUI->setField(__("БИК"), $PHPShopGUI->setInputText('', 'org_bik_new', $data['org_bik'])) .
            $PHPShopGUI->setField(__("Город"), $PHPShopGUI->setInputText('', 'org_city_new', $data['org_city'])).
            $PHPShopGUI->setField(__("Время доставки"), $PHPShopGUI->setInputText('', 'delivtime_new', $data['delivtime']));


    return $help.$PHPShopGUI->setGrid(array($disp1, 6), array($disp2, 6));
}

?>