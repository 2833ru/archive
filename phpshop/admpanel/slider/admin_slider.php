<?php

$TitlePage = __("Слайдер");

function actionStart() {
    global $PHPShopInterface;
    $PHPShopInterface->size = "630,530";
    $PHPShopInterface->link = "slider/adm_sliderID.php";
    $PHPShopInterface->setCaption(array("&plusmn;", "5%"), array("Превью", "30%"), array("Изображение", "10%"), array("Ссылка", "10%"), array("Номер по порядку", "10%"), array("Описание", "10%"));


// SQL
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['slider']);
    $data = $PHPShopOrm->select(array('*'), false, array('order' => 'num, id DESC'), array("limit" => "1000"));
    if (is_array($data))
        foreach ($data as $row) {
            extract($row);
            $PHPShopInterface->setRow($id, $PHPShopInterface->icon($enabled), "<img src='$image' style='max-width:200px; max-height:100px'>", $image, $link, $num, $alt);
        }

    $PHPShopInterface->setAddItem('slider/adm_slider_new.php');
    $PHPShopInterface->Compile();
}

?>