<?php

function addYandexcartDelivery($data) {
    global $PHPShopGUI;

    if (empty($data['yandex_enabled']))
        $data['yandex_enabled'] = 1;
    if (empty($data['yandex_day']))
        $data['yandex_day'] = 2;
    $Tab3 = $PHPShopGUI->setField("Срок доставки дней", $PHPShopGUI->setInputText('от', 'yandex_day_min_new', $data['yandex_day_min'], 100,false,'left').$PHPShopGUI->set_(3).$PHPShopGUI->setInputText(null, 'yandex_day_new', $data['yandex_day'], 100,'до'));
     $Tab3 .= $PHPShopGUI->setField("Время увеличения доставки", $PHPShopGUI->setInputText('с', 'yandex_order_before_new', $data['yandex_order_before'], 150,'часов'),false,'Заказы после этого времени будут доставлены сроком +1 день. Число от 1 - 24.');
    $Tab3.=$PHPShopGUI->setField(__('Яндекс.Заказ'), $PHPShopGUI->setRadio('yandex_enabled_new', 1, __('Выключить'), $data['yandex_enabled'], false, 'text-warning') .
            $PHPShopGUI->setRadio('yandex_enabled_new', 2, __('Включить'), $data['yandex_enabled']));

    $Tab3.=$PHPShopGUI->setField(__('Только для локального региона'), $PHPShopGUI->setRadio('yandex_check_new', 1, __('Выключить'), $data['yandex_check'], false, 'text-warning') .
            $PHPShopGUI->setRadio('yandex_check_new', 2, __('Включить'), $data['yandex_check']));

    // Способ оплаты
    $payment_delivery_value[] = array('Наличный расчет + Банковской картой', 1, $data['yandex_payment']);
    $payment_delivery_value[] = array('Наличный расчет при получении', 2, $data['yandex_payment']);
    $payment_delivery_value[] = array('Только предоплата', 3, $data['yandex_payment']);
    $Tab3.= $PHPShopGUI->setField('Способы оплаты', $PHPShopGUI->setSelect('yandex_payment_new', $payment_delivery_value));

    // Тип доставки
    $delivery_value[] = array('Курьерская доставка', 1, $data['yandex_type']);
    $delivery_value[] = array('Самовывоз', 2, $data['yandex_type']);
    $delivery_value[] = array('Почта', 3, $data['yandex_type']);
    $Tab3.= $PHPShopGUI->setField('Способы доставки', $PHPShopGUI->setSelect('yandex_type_new', $delivery_value));

    // Точки
    $Tab3.=$PHPShopGUI->setField(__('Точки продаж и пункты выдачи'), $PHPShopGUI->setTextarea('yandex_outlet_new', $data['yandex_outlet'], "none", false, false, __('ID точек продаж через запятую')));

    $PHPShopGUI->addTab(array("Яндекс.Заказ", $Tab3, true));
}

$addHandler = array(
    'actionStart' => 'addYandexcartDelivery',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>