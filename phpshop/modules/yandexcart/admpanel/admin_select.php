<?php

function yandexcartAddOption()
{
    global $PHPShopInterface;

    $memory = $PHPShopInterface->getProductTableFields();

    $PHPShopInterface->_CODE .= __('������.������') . '<br>';
    $PHPShopInterface->_CODE .= $PHPShopInterface->setCheckbox('price_yandex_dbs', 1, '���� ������.������ DBS', $memory['catalog.option']['price_yandex_dbs']);
    $PHPShopInterface->_CODE .= $PHPShopInterface->setCheckbox('price_sbermarket', 1, '���� ����������', $memory['catalog.option']['price_sbermarket']);
}

$addHandler = [
    'actionOption' => 'yandexcartAddOption'
];
