<?php

$TitlePage=__("Баннеры");

function actionStart() {
    global $PHPShopInterface;
    $PHPShopInterface->size="630,530";
    $PHPShopInterface->link="banner/adm_banerID.php";
    $PHPShopInterface->setCaption(array("&plusmn;","5%"),array("Название","50%"));


// SQL
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['banner']);
    $data = $PHPShopOrm->select(array('*'),false,array('order'=>'id DESC'),array("limit"=>"1000"));
    if(is_array($data))
        foreach($data as $row) {
            extract($row);

            if($datas != date("d.m.y")) $count_today=0;

            $PHPShopInterface->setRow($id,$PHPShopInterface->icon($flag),$name);
        }

    $PHPShopInterface->setAddItem('banner/adm_baner_new.php');
    $PHPShopInterface->Compile();
}
?>
