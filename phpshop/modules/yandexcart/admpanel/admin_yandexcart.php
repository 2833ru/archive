<?php

$TitlePage = "Журнал операций";

function actionStart() {
    global $PHPShopInterface, $PHPShopModules, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    //$PHPShopInterface->setCaption(array("Функция", "50%"), array("ID Заказа", "10%"), array("Дата", "10%"), array("Статус", "20%"));
    $PHPShopInterface->setCaption(array("Дата", "20%"),  array("№ Заказа", "20%"),array("Действие", "50%"));

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexcart.yandexcart_log"));
    $PHPShopOrm->debug = false;


    $data = $PHPShopOrm->select(array('*'), $where = false, array('order' => 'id DESC'), array('limit' => 1000));

    if (is_array($data))
        foreach ($data as $row) {
        //$PHPShopInterface->setRow($row['id'], PHPShopDate::get($row['date'], true), $row['order_id'],$row['path']);

            $PHPShopInterface->setRow(array('name' => PHPShopDate::get($row['date'], true), 'link' => '?path=modules.dir.yandexcart&id=' . $row['id']), array('name' => $row['order_id'], 'link' => '?path=order&id=' . $row['order_id']), $row['path']);
        }
    $PHPShopInterface->Compile();
}
?>