<?php

$TitlePage = __("Способы оплат");

function actionStart() {
    global $PHPShopInterface, $TitlePage;

    $PHPShopInterface->setActionPanel($TitlePage, array('Удалить выбранные'), array('Добавить'), false);
    $PHPShopInterface->setCaption(array(null, "3%"), array("Название", "40%"), array("Цвет", "10%", array('sort' => 'none')), array("Платежный шлюз", "20%"), array("Приоритет", "10%", array('align' => 'center')), array("", "10%"), array("Статус", "10%", array('align' => 'right')));

    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment_systems']);
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {
            $color = '<span class="glyphicon glyphicon-text-background" style="color:' . $row['color'] . '"></span>';
            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=payment&id=' . $row['id'], 'align' => 'left'), $color, $row['path'], array('name' => $row['num'], 'align' => 'center'), array('action' => array('edit', '|', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл'))));
        }
    $PHPShopInterface->Compile();
}

?>