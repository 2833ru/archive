<?php

$TitlePage="Бонусные баллы - управление транзакциями";

function actionStart() {
    global $PHPShopInterface,$_classPath;

    $PHPShopInterface->size="630,580";
    $PHPShopInterface->link="../modules/rewardpoints/admpanel/adm_userModulesID.php";
    $PHPShopInterface->setCaption(array("&plusmn;","5%"),array("Email","40%"),array("Имя","35%"),array("Баллы","20%"));

    // Настройки модуля
    PHPShopObj::loadClass("modules");
    $PHPShopModules = new PHPShopModules($_classPath."modules/");


    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
    $PHPShopOrm->debug=false;
    $data = $PHPShopOrm->select(array('*'),$where,array('order'=>'id DESC'),array('limit'=>2000));


    if(is_array($data))
        foreach($data as $row) {
            extract($row);
            $PHPShopInterface->setRow($id,$PHPShopInterface->icon($enabled),$mail,$name,$point);
        }
    //$PHPShopInterface->setAddItem('../modules/rewardpoints/admpanel/adm_userModules_new.php');
    $PHPShopInterface->Compile();
}
?>