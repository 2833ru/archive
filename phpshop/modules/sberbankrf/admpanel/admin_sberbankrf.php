<?php
/**
 * Функция вывода истории платежей
 */
function actionStart() {
    global $PHPShopInterface, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("Номер заказа", "30%"), array("Дата", "10%"), array("Статус", "60%"));

    $PHPShopOrm = new PHPShopOrm("phpshop_modules_sberbankrf_log");
    $PHPShopOrm->debug = false;


    $data = $PHPShopOrm->select(array('*'), $where = false, array('order' => 'id DESC'), array('limit' => 1000));

    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow(array('name' => $row['order_id'], 'link' => '?path=order&id=' . $row['order_id']), PHPShopDate::get($row['date'], true), $row['status']);
        }

    $PHPShopInterface->Compile();
}