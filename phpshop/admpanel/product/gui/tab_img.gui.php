<?php

/**
 * Панель изображений товара
 * @param array $row массив данных
 * @return string 
 */
function tab_img($data) {
    global $PHPShopGUI, $PHPShopSystem;

    $img_width = $PHPShopSystem->getSerilizeParam('admoption.img_tw');


    // Маленькое изображение
    $Tab6_1 = $PHPShopGUI->setInput('hidden', "pic_small_new", $data['pic_small']);

    // Большое изображение
    $Tab6_1.= $PHPShopGUI->setInput('hidden', "pic_big_new", $data['pic_big']);


    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['foto']);
    $data_pic = $PHPShopOrm->select(array('*'), array('parent' => '=' . intval($data['id'])), array('order' => 'num,id'), array('limit' => 100));
    $i = 1;
    $count = 0;

    $img_list = null;
    if (is_array($data_pic))
        foreach ($data_pic as $row) {

            $path_parts = pathinfo($row['name']);

            if ($i == 1)
                $img_list.='<div class="row">';

            if ($row['name'] == $data['pic_big'])
                $main = "btn-success";
            else
                $main = "btn-default";

            if (strlen($path_parts['basename']) > 20)
                $basename = substr($path_parts['basename'], 0, 20) . '..';
            else $basename=$path_parts['basename'];
            

            $num = $PHPShopGUI->setSelectValue($row['num'], 10);

            $select = $PHPShopGUI->setSelect("foto_num_new[" . $row['id'] . "]", $num, 45, null, false, false, false, false, false, $row['id'], 'selectpicker pull-right img-num ', false, 'btn btn-default btn-xs hidden-xs');


            $img_list.='<div class="col-md-3 data-row"><div class="panel panel-default"><div class="panel-heading" title="' . $path_parts['basename'] . '">' . $basename. '<span class="glyphicon glyphicon-remove pull-right btn btn-default btn-xs img-delete" data-id="' . $row['id'] . '" data-toggle="tooltip" data-placement="top" title="' . __('Удалить') . '"></span><span class="pull-right">&nbsp;</span><span class="glyphicon glyphicon-heart pull-right btn ' . $main . ' btn-xs img-main" data-path="' . $row['name'] . '" data-path-s="' . str_replace('.', 's.', $row['name']) . '"  data-toggle="tooltip" data-placement="top" title="' . __('Главное превью товара') . '"></span><span class="pull-right">&nbsp;</span>' . $select . '</div><div class="panel-body text-center"><a href="' . $row['name'] . '" target="_blank"><img style="max-width:250px" src="' . str_replace(array('.png','.jpg','.gif','.jpeg'),array('s.png','s.jpg','s.gif','s.jpeg'), $row['name']) . '"></a></div></div></div>';

            if ($i == 4) {
                $img_list.='</div>';
                $i = 1;
            }
            else
                $i++;

            $count++;
        }


    if (count($data_pic) % 4 != 0)
        $img_list.='</div>';


    $img_add = '<div class="panel panel-default"><div class="panel-body">' . $PHPShopGUI->setIcon(false, "img_new", false, array('load' => true, 'server' => true, 'url' => true, 'multi' => true), $img_width) . '</div></div>';



    $disp = $PHPShopGUI->setCollapse('Добавить изображение', $img_add);

    if (!empty($img_list))
        $disp.= $PHPShopGUI->setCollapse('Дополнительные изображения', $img_list);

    return $disp;
}

?>
