<?php

function setOptionGUI($name, $format, $value) {
    global $PHPShopGUI;

    switch ($format) {

        case 'textarea':
            $result = $PHPShopGUI->setTextarea($name, $value);
            break;

        case 'radio':
            $result = $PHPShopGUI->setRadio($name, 1, 'Да', $value) . $PHPShopGUI->setRadio($name, 2, 'Нет', $value);
            break;

        default:
            $result = $PHPShopGUI->setInput('text', $name, $value,null,150);
            break;
    }

    return $result;
}

function addOption($data) {
    global $PHPShopGUI;
    $Tab10 = $PHPShopGUI->setField(__('Вес, кг'), setOptionGUI("option1_new", null, $data['option1']),'left');
    $Tab10.= $PHPShopGUI->setField(__('Ширина, см'), setOptionGUI("option2_new", null, $data['option2']),'left');
    $Tab10.= $PHPShopGUI->setField(__('Длина, см'), setOptionGUI("option3_new", null, $data['option3']),'left');
    $Tab10.= $PHPShopGUI->setField(__('Высота'), setOptionGUI("option4_new", null, $data['option4']),'left');
    $PHPShopGUI->addTab(array("DDelivery", $Tab10, 450));
}

$addHandler = array(
    'actionStart' => 'addOption',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>