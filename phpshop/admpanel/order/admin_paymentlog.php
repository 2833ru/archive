<?php

$TitlePage = __("Электронные платежи");

function actionStart() {
    global $PHPShopInterface,$PHPShopSystem;


    $PHPShopInterface->setActionPanel(__("Электронные платежи"), null, null);
    $PHPShopInterface->checkbox_action = false;
    $format = $PHPShopSystem->getSerilizeParam("admoption.price_znak");
    $PHPShopInterface->setCaption(array("№ Заказа", "20%"), array("Поступление", "20%"), array("Платежная система", "20%"), array("Сумма ".$PHPShopSystem->getDefaultValutaCode(), "20%",array('align' => 'right')));

    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment']);
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'uid DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow(array('name' => $row['uid'], 'align' => 'left'), PHPShopDate::get($row['datas'], true), $row['name'], array('name'=>number_format($row['sum'], $format, '.', ' '),'align'=>'right'));
        }
    $PHPShopInterface->Compile();
}

?>