<?php

function actionStart() {
    global $PHPShopInterface, $PHPShopModules;


    $PHPShopInterface->setCaption(array("", "1%"), array("Название", "20%"), array("Префикс", "40%"), array("Приоритет", "10%"), array("", "10%"), array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));


    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.multilanguages.multilanguages"));
    $PHPShopOrm->debug = false;
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'num'), array('limit' => 100));

    if (is_array($data))
        foreach ($data as $row) {

            $PHPShopInterface->setRow($row['id'], array('name' => $row['name'], 'link' => '?path=modules.dir.multilanguages&id=' . $row['id'], 'align' => 'left'), $row['prefix'], $row['num'], array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'), array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл'))));
        }
    $PHPShopInterface->Compile();
}

?>