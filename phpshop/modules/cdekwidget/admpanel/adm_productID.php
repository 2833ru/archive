<?php

function addCdekTab($data) {
    global $PHPShopGUI;

    $Tab1 = $PHPShopGUI->setField('Длина, см.',
        '<input class="form-control input-sm " type="number" step="1" min="1" 
                    value="' . $data['cdek_length'] . '" name="cdek_length_new" style="width:300px;">');
    $Tab1 .= $PHPShopGUI->setField('Ширина, см.',
        '<input class="form-control input-sm " type="number" step="1" min="1" 
                    value="' . $data['cdek_width'] . '" name="cdek_width_new" style="width:300px;">');
    $Tab1 .= $PHPShopGUI->setField('Высота, см.',
        '<input class="form-control input-sm " type="number" step="1" min="1" 
                    value="' . $data['cdek_height'] . '" name="cdek_height_new" style="width:300px;">');

    $PHPShopGUI->addTab(array("Габариты", $Tab1, true));
}

$addHandler = array('actionStart'  => 'addCdekTab', 'actionDelete' => false, 'actionUpdate' => false);
?>