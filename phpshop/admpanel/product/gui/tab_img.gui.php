<?php

/**
 * Панель изображений товара
 * @param array $row массив данных
 * @return string 
 */
function tab_img($data) {
    global $PHPShopGUI, $PHPShopSystem;

    // Добавление изображения в галерею
    $Tab6 = $PHPShopGUI->setField(__('Добавить изображение в галерею'), $PHPShopGUI->setInputText(false, "pic_resize", null, '500px', false, 'left') .
            $PHPShopGUI->setButton(__('Выбрать'), "../img/icon-move-banner.gif", "100px", '25px', "left", "ReturnPicResize(" . $data['id'] . ");return false;"));

    // Маленькое изображение
    $Tab6_1 = $PHPShopGUI->setInput('hidden', "pic_small_new", $data['pic_small']);

    // Большое изображение
    $Tab6_1.= $PHPShopGUI->setInput('hidden', "pic_big_new", $data['pic_big']);

    // Таблица изображений в фотогалереи
    $PHPShopInterfacePic = new PHPShopInterface();
    $PHPShopInterfacePic->size = "500,500";
    $PHPShopInterfacePic->window = true;
    $PHPShopInterfacePic->imgPath = "../img/";
    $PHPShopInterfacePic->link = $dot . "./adm_galeryID.php";
    $PHPShopInterfacePic->setCaption(array("№", "10%"), array(__("Размещение"), "50%"), array(__("Превью"), "20%"), array(__("Главное"), "10%"), array(__("№ п/п"), "10%"));
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
    $data_pic = $PHPShopOrm->select(array('*'), array('parent' => '=' . $data['id']), array('order' => 'id'), array('limit' => 100));
    $i = 1;
    $img_list = null;
    if (is_array($data_pic))
        foreach ($data_pic as $row) {

            if ($row['name'] == $data['pic_big'])
                $main = "+";
            else
                $main = "";
            $preview = $PHPShopGUI->setImage($row['name'], '', '50');
            $PHPShopInterfacePic->setRow($row['id'], $i, $row['name'], $preview, $main, $row['num']);

            if ($i < 6)
                $img_list.=$PHPShopGUI->setImage($row['name'], $PHPShopSystem->getSerilizeParam('admoption.img_tw'), $PHPShopSystem->getSerilizeParam('admoption.img_tw'), false, false, 'cursor:pointer;padding:3px', 'miniWin(\'./adm_galeryID.php?id=' . $row['id'] . '\',500,500)');

            $i++;
        }

    //$Tab6_1.=$img_list;
    // Галерея
    $PHPShopInterface = new PHPShopInterface('_foto_');
    $PHPShopInterfacePic->razmer = "height:280px;";
    $PHPShopInterface->setTab(array(__("Фотогалерея"), $Tab6_1 . $PHPShopInterfacePic->Compile('fotolist'), 300));
    $Tab6.=$PHPShopInterface->getContent();

    return $Tab6;
}

?>
