<?php

function setModOptionGUI($name, $format, $value) {
    global $PHPShopGUI;
    
        switch ($format) {
            
            case 'textarea':
                $result = $PHPShopGUI->setTextarea($name,$value);
                break;

            case 'radio':
                $result = $PHPShopGUI->setRadio($name, 1, 'Да',$value).$PHPShopGUI->setRadio($name, 2, 'Нет',$value);
                break;

            default:
                $result = $PHPShopGUI->setInput($format, $name, $value);
                break;
        }

    return $result;
}

function addModOption($data) {
    global $PHPShopGUI, $PHPShopModules;

    // SQL
    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.productoption.productoption_system"));
    $m_data = $PHPShopOrm->select();
    $vendor = unserialize($m_data['option']);

    if (is_array($vendor)) {

        if (!empty($vendor['option_1_name']))
            $Tab10 = $PHPShopGUI->setField($vendor['option_1_name'], setModOptionGUI("option1_new", $vendor['option_1_format'], $data['option1']));

        if (!empty($vendor['option_2_name']))
            $Tab10.= $PHPShopGUI->setField($vendor['option_2_name'], setModOptionGUI("option2_new", $vendor['option_2_format'], $data['option2']));

        if (!empty($vendor['option_3_name']))
            $Tab10.= $PHPShopGUI->setField($vendor['option_3_name'], setModOptionGUI("option3_new", $vendor['option_3_format'], $data['option3']));

        if (!empty($vendor['option_4_name']))
            $Tab10.= $PHPShopGUI->setField($vendor['option_4_name'], setModOptionGUI("option4_new", $vendor['option_4_format'], $data['option4']));
        
        if (!empty($vendor['option_5_name']))
            $Tab10.= $PHPShopGUI->setField($vendor['option_5_name'], setModOptionGUI("option5_new", $vendor['option_5_format'], $data['option5']));
    }



    $PHPShopGUI->addTab(array("Дополнительно", $Tab10, 450));
}

$addHandler = array(
    'actionStart' => 'addModOption',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>