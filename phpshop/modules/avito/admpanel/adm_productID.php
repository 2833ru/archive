<?php

include_once dirname(__FILE__) . '/../class/Avito.php';

function addAvitoProductTab($data) {
    global $PHPShopGUI;

    $tab = $PHPShopGUI->setField('Авито', $PHPShopGUI->setCheckbox('export_avito_new', 1, 'Включить экспорт в Авито', $data['export_avito']));

    $tab .= $PHPShopGUI->setField("Название товара:", $PHPShopGUI->setInput('text', 'name_avito_new', $data['name_avito'], 'left', 300));
    $tab .= $PHPShopGUI->setField('Состояние товара', $PHPShopGUI->setSelect('condition_avito_new', Avito::getConditions($data['condition_avito']),300,true), 1, 'Тег <condition>');
    $tab .= $PHPShopGUI->setField('Вариант платного размещения', $PHPShopGUI->setSelect('listing_fee_avito_new', Avito::getListingFee($data['listing_fee_avito']),300,true), 1, 'Тег <ListingFee>');
    $tab .= $PHPShopGUI->setField('Платная услуга', $PHPShopGUI->setSelect('ad_status_avito_new', Avito::getAdStatuses($data['ad_status_avito']),300), 1, 'Тег <AdStatus>');
    $tab .= $PHPShopGUI->setField('Вид объявления', $PHPShopGUI->setSelect('ad_type_avito_new', Avito::getAdTypes($data['ad_type_avito']),300), 1, 'Тег <AdType>');

    $PHPShopGUI->addTab(array("Авито", $tab, true));
}

function avitoUpdate($data)
{
    if (empty($_POST['export_avito_new']) and !isset($_REQUEST['ajax'])) {
        $_POST['export_avito_new'] = 0;
    }
}

$addHandler = array(
    'actionStart' => 'addAvitoProductTab',
    'actionDelete' => false,
    'actionUpdate' => 'avitoUpdate'
);
?>