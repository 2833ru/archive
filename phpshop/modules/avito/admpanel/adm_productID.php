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
    $tab .= $PHPShopGUI->setField("Номер детали OEM:", $PHPShopGUI->setInput('text', 'oem_avito_new', $data['oem_avito'], 'left', 300), 1, 'Для прайс-листа "Запчасти и аксессуары"');

    $tiers = unserialize($data['tiers_avito']);
    if(!is_array($tiers)) {
        $tiers = [];
    }
    $tab .= $PHPShopGUI->setCollapse("Шины, диски и колёса",
        $PHPShopGUI->setField("Диаметр дюймы:", $PHPShopGUI->setInput('text', 'tiers[diameter]', isset($tiers['diameter']) ? $tiers['diameter'] : null, 'left', 300)) .
        $PHPShopGUI->setField("Ширина обода, дюймов:", $PHPShopGUI->setInput('text', 'tiers[rim-width]', isset($tiers['rim-width']) ? $tiers['rim-width'] : null, 'left', 300)) .
        $PHPShopGUI->setField("Количество отверстий под болты:", $PHPShopGUI->setInput('text', 'tiers[rim-bolts]', isset($tiers['rim-bolts']) ? $tiers['rim-bolts'] : null, 'left', 300)) .
        $PHPShopGUI->setField("Диаметр расположения отверстий под болты:", $PHPShopGUI->setInput('text', 'tiers[rim-bolts-diameter]', isset($tiers['rim-bolts-diameter']) ? $tiers['rim-bolts-diameter'] : null, 'left', 300)) .
        $PHPShopGUI->setField("Вылет (ET):", $PHPShopGUI->setInput('text', 'tiers[rim-offset]', isset($tiers['rim-offset']) ? $tiers['rim-offset'] : null, 'left', 300)) .
        $PHPShopGUI->setField("Сезонность шин или колес:", $PHPShopGUI->setSelect('tiers[tier-type]', Avito::getTierTypes(isset($tiers['tier-type']) ? $tiers['tier-type'] : null), 300)) .
        $PHPShopGUI->setField("Ось мотошины:", $PHPShopGUI->setSelect('tiers[wheel-axle]', Avito::getWheelAxle(isset($tiers['wheel-axle']) ? $tiers['wheel-axle'] : null), 300)) .
        $PHPShopGUI->setField("Тип диска:", $PHPShopGUI->setSelect('tiers[rim-type]', Avito::getRimTypes(isset($tiers['rim-type']) ? $tiers['rim-type'] : null), 300)) .
        $PHPShopGUI->setField("Ширина профиля шины:", $PHPShopGUI->setSelect('tiers[tire-section-width]', Avito::getTireSectionWidth(isset($tiers['tire-section-width']) ? $tiers['tire-section-width'] : null), 300)) .
        $PHPShopGUI->setField("Высота профиля шины:", $PHPShopGUI->setSelect('tiers[tire-aspect-ratio]', Avito::getTireAspectRatio(isset($tiers['tire-aspect-ratio']) ? $tiers['tire-aspect-ratio'] : null), 300))
    );

    $PHPShopGUI->addTab(array("Авито", $tab, true));
}

function avitoUpdate($data)
{
    $_POST['tiers_avito_new'] = serialize($_POST['tiers']);

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