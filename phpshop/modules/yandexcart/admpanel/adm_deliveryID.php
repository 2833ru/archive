<?php

function addYandexcartDelivery($data) {
    global $PHPShopGUI;

    if (empty($data['yandex_enabled']))
        $data['yandex_enabled'] = 1;
    if (empty($data['yandex_day']))
        $data['yandex_day'] = 2;
    $Tab3 = $PHPShopGUI->setField("Срок доставки дней", $PHPShopGUI->setInputText('от', 'yandex_day_min_new', $data['yandex_day_min'], 100,false,'left').$PHPShopGUI->set_(3).$PHPShopGUI->setInputText(null, 'yandex_day_new', $data['yandex_day'], 100,__('до')));
     $Tab3 .= $PHPShopGUI->setField("Время увеличения доставки", $PHPShopGUI->setInputText('с', 'yandex_order_before_new', $data['yandex_order_before'], 150,__('часов')),false,'Заказы после этого времени будут доставлены сроком +1 день. Число от 1 - 24.');
    $Tab3.=$PHPShopGUI->setField('Яндекс.Заказ', $PHPShopGUI->setRadio('yandex_enabled_new', 1, 'Выключить', $data['yandex_enabled'], false, 'text-warning') .
            $PHPShopGUI->setRadio('yandex_enabled_new', 2, 'Включить', $data['yandex_enabled']));

    $Tab3.=$PHPShopGUI->setField('Только для локального региона', $PHPShopGUI->setRadio('yandex_check_new', 1, 'Выключить', $data['yandex_check'], false, 'text-warning') . $PHPShopGUI->setRadio('yandex_check_new', 2, 'Включить', $data['yandex_check']));


    // Тип доставки
    $delivery_value[] = array(__('Курьерская доставка'), 1, $data['yandex_type']);
    $delivery_value[] = array(__('Самовывоз'), 2, $data['yandex_type']);
    $delivery_value[] = array(__('Почта'), 3, $data['yandex_type']);
    $Tab3.= $PHPShopGUI->setField('Способы доставки', $PHPShopGUI->setSelect('yandex_type_new', $delivery_value));
    
    // Текст о доставке товара в наличии
    $Tab3.=$PHPShopGUI->setField("Сообщение товар в наличии",$PHPShopGUI->setTextarea('yandex_mail_instock_new',$data['yandex_mail_instock'],true, false, '100px').$PHPShopGUI->setHelp('Переменные: <code>@dataFrom@</code> - расчетная дата доставки от, <code>@dataTo@</code> - рассчетная дата доставки до'));
    
    // Текст о доставке товара под заказ
    $Tab3.=$PHPShopGUI->setField("Сообщение товар под заказ",$PHPShopGUI->setTextarea('yandex_mail_outstock_new',$data['yandex_mail_outstock'],true, false, '100px').$PHPShopGUI->setHelp('Переменные: <code>@dataFrom@</code> - расчетная дата доставки от, <code>@dataTo@</code> - рассчетная дата доставки до'));

    if(empty($data['is_folder']))
    $PHPShopGUI->addTab(array("Яндекс.Заказ", $Tab3, true));
}

$addHandler = array(
    'actionStart' => 'addYandexcartDelivery',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>