<?php

$TitlePage = __("Журнал операций");

function actionStart() {
    global $PHPShopInterface, $PHPShopModules, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("Дата", "20%"),  array("№ Заказа в магазине", "20%"),array("№ Заказа в Яндексе", "20%"), array("Действие", "20%",array('align'=>'right')));

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexturbo.yandexturbo_log"));
    $PHPShopOrm->debug = false;

    $data = $PHPShopOrm->getList(array('*'), $where = false, array('order' => 'id DESC'));

    foreach ($data as $row) {
        $PHPShopInterface->setRow(array('name' => PHPShopDate::get($row['date'], true), 'link' => '?path=modules.dir.yandexturbo&id=' . $row['id']), array('name' => $row['order_id'], 'link' => '?path=order&id=' . $row['order_id']), $row['yandex_order_id'],$row['path']);
    }
    $PHPShopInterface->Compile();
}
?>