<?php

include_once dirname(__FILE__) . '/../class/Avito.php';

function addAvitoTab($data) {
    global $PHPShopGUI;

    // ��������� �� ������� ������ ����, ������� ���� ������ � ��������� �������. ����� ������� ���������� � � ��������� �������
    if(isset($data['skin_enabled'])) {

        $PHPShopGUI->addJSFiles('../modules/avito/admpanel/gui/script.js?v=1.0');

        $tab = $PHPShopGUI->setField('�����-����', $PHPShopGUI->setSelect('xml_price_avito', Avito::getAvitoCategoryTypes($data['category_avito']),300));
        $tab .= $PHPShopGUI->setField('��������� ������', $PHPShopGUI->setSelect('category_avito_new', Avito::getAvitoCategories(null, $data['category_avito']),300));
        $tab .= $PHPShopGUI->setField('��� ������', $PHPShopGUI->setSelect('type_avito_new', Avito::getCategoryTypes($data['category_avito'], $data['type_avito']),300,true));
        $tab .= $PHPShopGUI->setField('��� ������ (������ ��� ���������������)', $PHPShopGUI->setSelect('subtype_avito_new', Avito::getCategorySubTypes($data['subtype_avito']),300,true));

        $PHPShopGUI->addTab(array("�����", $tab, true));
    }
}

$addHandler = array(
    'actionStart' => 'addAvitoTab',
    'actionDelete' => false,
    'actionUpdate' => false
);
?>