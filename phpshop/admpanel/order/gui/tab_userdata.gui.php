<?php

function tab_userdata($data, $order) {
    global $PHPShopGUI;
    
    
    $PHPShopGUI->field_col=3;
    
    $help='<p class="text-muted hidden-xs data-row">'.__('Дополнительные поля в заказе и требование к их обязательному заполнению можно настроить в разделе').'  <a href="?path=delivery&id='.$order['Person']['dostavka_metod'].'&tab=1"><span class="glyphicon glyphicon-share-alt"></span>'.__('Управление доставкой').'</a></p><hr>';
    
    if(empty($data['fio'])){
        $data['fio']=$order['Person']['name_person'];
    }
    
    if(empty($data['user']))
        $user='<div class="form-group form-group-sm ">
        <label class="col-sm-3 control-label">'.__('ФИО').':</label><div class="col-sm-9">
        <input data-set="3" name="fio_new" maxlength="50" class="search_user form-control input-sm" type="search" data-trigger="manual" data-container="body" data-toggle="popover" data-placement="bottom" data-html="true"  data-content="" placeholder="'.__('Найти...').'" value="'.$data['fio'].'">
        <input name="user_new" type="hidden">
     </div></div> ';
    else $user=$PHPShopGUI->setField("ФИО", $PHPShopGUI->setInputText('', 'fio_new', $data['fio'] ));
    

    $order['Person']=$PHPShopGUI->valid($order['Person'],'adr_name','org_name','mail');

    // Данные покупателя
    $disp1 = $user .
            $PHPShopGUI->setField("Телефон", $PHPShopGUI->setInputText('', 'tel_new', $data['tel'])) .
            $PHPShopGUI->setField("E-mail", $PHPShopGUI->setInputText('', 'person[mail]', $order['Person']['mail'])).
            $PHPShopGUI->setField("Страна", $PHPShopGUI->setInputText('', 'country_new', $data['country'])) .
            $PHPShopGUI->setField("Регион", $PHPShopGUI->setInputText('', 'state_new', $data['state'])) .
            $PHPShopGUI->setField("Город", $PHPShopGUI->setInputText('', 'city_new', $data['city'])) .
            $PHPShopGUI->setField("Индекс", $PHPShopGUI->setInputText('', 'index_new', $data['index'])) .
            $PHPShopGUI->setField("Улица", $PHPShopGUI->setInputText('', 'street_new', $data['street'] . $order['Person']['adr_name'])) .
            $PHPShopGUI->setField("Дом", $PHPShopGUI->setInputText('', 'house_new', $data['house'])) .
            $PHPShopGUI->setField("Подъезд", $PHPShopGUI->setInputText('', 'porch_new', $data['porch'])) .
            $PHPShopGUI->setField("Домофон", $PHPShopGUI->setInputText('', 'door_phone_new', $data['door_phone'])) .
            $PHPShopGUI->setField("Квартира", $PHPShopGUI->setInputText('', 'flat_new', $data['flat']));

    // Юр. данные покупателя
    $disp2 = $PHPShopGUI->setField("Компания", $PHPShopGUI->setInputText('', 'org_name_new', $data['org_name'] . $order['Person']['org_name'])) .
            $PHPShopGUI->setField("ИНН", $PHPShopGUI->setInputText('', 'org_inn_new', $data['org_inn'])) .
            $PHPShopGUI->setField("КПП", $PHPShopGUI->setInputText('', 'org_kpp_new', $data['org_kpp'])) .
            $PHPShopGUI->setField("Юр. адрес", $PHPShopGUI->setInputText('', 'org_yur_adres_new', $data['org_yur_adres'])) .
            $PHPShopGUI->setField("Адрес", $PHPShopGUI->setInputText('', 'org_fakt_adres_new', $data['org_fakt_adres'])) .
            $PHPShopGUI->setField("Р/С", $PHPShopGUI->setInputText('', 'org_ras_new', $data['org_ras'])) .
            $PHPShopGUI->setField("Банк", $PHPShopGUI->setInputText('', 'org_bank_new', $data['org_bank'])) .
            $PHPShopGUI->setField("К/С", $PHPShopGUI->setInputText('', 'org_kor_new', $data['org_kor'])) .
            $PHPShopGUI->setField("БИК", $PHPShopGUI->setInputText('', 'org_bik_new', $data['org_bik'])) .
            $PHPShopGUI->setField("Город", $PHPShopGUI->setInputText('', 'org_city_new', $data['org_city'])).
            $PHPShopGUI->setField("Время", $PHPShopGUI->setInputText('', 'delivtime_new', $data['delivtime'])).
            $PHPShopGUI->setField("Tracking", $PHPShopGUI->setInputText('', 'tracking_new', $data['tracking']));

    return $help.$PHPShopGUI->setGrid(array($disp1, 6), array($disp2, 6));
}

?>