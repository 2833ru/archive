<?php

function actionStart() {
    global $PHPShopInterface,$PHPShopModules,$TitlePage, $select_name;
    
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);


    $PHPShopInterface->setCaption(array("", "1%"),array("Логин","30%"),array("E-mail","30%"),array("Дата","10%"),array("Баланс","15%"),array("", "10%"),array("Статус &nbsp;&nbsp;&nbsp;", "10%", array('align' => 'right')));


    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.partner.partner_users"));
    $data = $PHPShopOrm->select(array('*'),false,array('order'=>'id DESC'),array('limit'=>100));
    if(is_array($data))
        foreach($data as $row) {
            $PHPShopInterface->setRow($row['id'],array('name'=>$row['login'],'link'=>'?path=modules.dir.partner&id=' . $row['id']),array('name'=>$row['mail'],'link'=>'mailto:' . $row['mail']),$row['date'],$row['money'],array('action' => array('edit', 'delete', 'id' => $row['id']), 'align' => 'center'),array('status' => array('enable' => $row['enabled'], 'align' => 'right', 'caption' => array('Выкл', 'Вкл'))));
        }

    $PHPShopInterface->Compile();
}
?>