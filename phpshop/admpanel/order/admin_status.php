<?php

$TitlePage = __("Статусы заказов");


function actionStart() {
    global $PHPShopInterface;
    
    $status=array('<span class="glyphicon glyphicon-remove text-muted"></span>','<span class="glyphicon glyphicon-ok"></span>');


    $PHPShopInterface->setActionPanel(__("Статусы заказов"), array('Удалить выбранные'), array('Добавить'));
        $PHPShopInterface->setCaption(array(null, "3%"), array("Название", "30%"), array("Цвет", "20%"), array("Списание", "10%", array('align' => 'center','tooltip'=>'Списание со склада')), array("", "10%"), array("Учет скидки &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));

    // Таблица с данными
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['order_status']);
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'id DESC'), array('limit' => 1000));
    if (is_array($data))
        foreach ($data as $row) {
             $color='<span class="glyphicon glyphicon-text-background" style="color:' . $row['color'] . '"></span>';
             $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=order.status&id=' . $row['id'], 'align' => 'left'), $color, array('name' => $status[intval($row['sklad_action'])], 'align' => 'center'), array('action' => array('edit','|', 'delete', 'id' => $row['id']), 'align' => 'center'), array('name' => $status[intval($row['cumulative_action'])], 'align' => 'center'));
             
        }
    $PHPShopInterface->Compile();
}
?>