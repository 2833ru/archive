<?php

function actionStart() {
    global $PHPShopInterface, $PHPShopModules,$TitlePage,$select_name;

    $PHPShopInterface->setActionPanel($TitlePage, $select_name, null);

    $PHPShopInterface->setCaption(array("", "1%"), array("Имя", "20%"), array("Дата", "10%"), array("Наименование", "40%"), array("Цена", "10%"), array(null, "10%"), array("Статус", "10%"));
    $PHPShopInterface->dropdown_action_form = true;



    $status_array = array(
        1 => 'Новая заявка',
        2 => 'Перезвонить',
        3 => 'Недоcтупен',
        4 => 'Выполнен'
    );

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.oneclick.oneclick_jurnal"));
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array("limit" => "1000"));
    if (is_array($data))
        foreach ($data as $row) {
            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=modules.dir.oneclick&id=' . $row['id']), PHPShopDate::get($row['date'], false), array('name' => $row['product_name'], 'link' => '?path=product&id=' . $row['product_id']), $row['product_price'], array('action' => array('edit', '|', 'delete', 'id' => $row['id']), 'align' => 'center'), $status_array[$row['status']]);
        }

    $PHPShopInterface->Compile();
}

?>