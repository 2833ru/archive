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

        if (!empty($vendor['option_6_name']))
            $Tab10 = $PHPShopGUI->setField($vendor['option_6_name'], setModOptionGUI("option6_new", $vendor['option_6_format'], $data['option6']));

        if (!empty($vendor['option_7_name']))
            $Tab10.= $PHPShopGUI->setField($vendor['option_7_name'], setModOptionGUI("option7_new", $vendor['option_7_format'], $data['option7']));

        if (!empty($vendor['option_8_name']))
            $Tab10.= $PHPShopGUI->setField($vendor['option_8_name'], setModOptionGUI("option8_new", $vendor['option_8_format'], $data['option8']));

        if (!empty($vendor['option_9_name']))
            $Tab10.= $PHPShopGUI->setField($vendor['option_9_name'], setModOptionGUI("option9_new", $vendor['option_9_format'], $data['option9']));
        
        if (!empty($vendor['option_10_name']))
            $Tab10.= $PHPShopGUI->setField($vendor['option_10_name'], setModOptionGUI("option10_new", $vendor['option_10_format'], $data['option10']));
    }



    $PHPShopGUI->addTab(array("Дополнительно", $Tab10, 450));
}

$addHandler = array(
    'actionStart' => 'addModOption',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>