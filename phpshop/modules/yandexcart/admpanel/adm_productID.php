<?php

function addYandexcartCPA($data) {
    global $PHPShopGUI;

    $PHPShopGUI->addJSFiles('../modules/yandexcart/admpanel/gui/yandexcart.gui.js');

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['yandexcart']['yandexcart_system']);
    $options = $PHPShopOrm->select();

    $Tab3 = '';
    if($options['model'] === 'DBS') {
        $Tab3 .=  $PHPShopGUI->setField('CPA ������',
            $PHPShopGUI->setRadio('cpa_new', 1,'��������', $data['cpa']) .
            $PHPShopGUI->setRadio('cpa_new', 0,'���������', $data['cpa']) .
            $PHPShopGUI->setRadio('cpa_new', 2,'�� ������������ CPA', $data['cpa'], false, 'text-muted')
        );
    }

    // ������
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $valuta_def_name = $val['code'];
            }
        }

    if((int) $data['yml'] === 1) {
        $Tab3 .= $PHPShopGUI->setField('���� ������.������ DBS', $PHPShopGUI->setInputText(null, 'price_yandex_dbs_new', $data['price_yandex_dbs'], 150, $valuta_def_name), 2);
    }
    if((int) $data['sbermarket'] === 1) {
        $Tab3 .= $PHPShopGUI->setField('���� ����������', $PHPShopGUI->setInputText(null, 'price_sbermarket_new', $data['price_sbermarket'], 150, $valuta_def_name), 2);
    }

    $Tab3 .= $PHPShopGUI->setField("������", $PHPShopGUI->setInputText(null, 'model_new', $data['model'], 300), 1, '��� model');
    $Tab3 .= $PHPShopGUI->setField('��������', $PHPShopGUI->setRadio('manufacturer_warranty_new', 1, '��������', $data['manufacturer_warranty']) . $PHPShopGUI->setRadio('manufacturer_warranty_new', 2, '���������', $data['manufacturer_warranty'], false, 'text-muted'), 1, '��� manufacturer_warranty');

    $Tab3 .= $PHPShopGUI->setField("��� �������������", $PHPShopGUI->setInputText(null, 'vendor_name_new', $data['vendor_name'], 300), 1, '��� vendor');

    $Tab3 .= $PHPShopGUI->setField("��� �������������", $PHPShopGUI->setInputText(null, 'vendor_code_new', $data['vendor_code'], 300), 1, '��� vendorCode');

    $Tab3 .= $PHPShopGUI->setField("�������� �������������, ����� � ���. ����� (���� ����)", $PHPShopGUI->setInputText(null, 'manufacturer_new', $data['manufacturer'], 300), 1, '��� manufacturer');
    
    $Tab3 .= $PHPShopGUI->setField("��������", $PHPShopGUI->setInputText(null, 'barcode_new', $data['barcode'], 300), 1, '��� barcode');

    $Tab3 .= $PHPShopGUI->setField("�����������", $PHPShopGUI->setInputText(null, 'sales_notes_new', $data['sales_notes'], 300), 1, '��� sales_notes');

    $Tab3 .= $PHPShopGUI->setField("������ ������������", $PHPShopGUI->setInputText(null, 'country_of_origin_new', $data['country_of_origin'], 300), 1, '��� country_of_origin');

    $Tab3 .= $PHPShopGUI->setField("������������� ������ �� �������", $PHPShopGUI->setInputText(null, 'market_sku_new', $data['market_sku'], 300), 1, '��� market-sku ��� ������ FBS, ����� �������� � ������ �������� ������.�������');

    $Tab3 .= $PHPShopGUI->setField('����� ��� ��������', $PHPShopGUI->setRadio('adult_new', 1, '��������', $data['adult']) . $PHPShopGUI->setRadio('adult_new', 2, '���������', $data['adult'], false, 'text-muted'), 1, '��� adult');

    $condition[] = array(__('����� �����'), 1, $data['yandex_condition']);
    $condition[] = array(__('��� �����'), 2, $data['yandex_condition']);
    $condition[] = array(__('�����������'), 3, $data['yandex_condition']);

    $Tab3 .= $PHPShopGUI->setField('��������� ������', $PHPShopGUI->setSelect('yandex_condition_new', $condition,300), 1, '��� condition');
    
    $Tab3 .= $PHPShopGUI->setField('������� ������', $PHPShopGUI->setTextarea('yandex_condition_reason_new', $data['yandex_condition_reason']), 1, '��� reason');

    $Tab3 .= $PHPShopGUI->setField('���������� ��������', $PHPShopGUI->setRadio('delivery_new', 1, '��������', $data['delivery']) . $PHPShopGUI->setRadio('delivery_new', 2, '���������', $data['delivery'], false, 'text-muted'), 1, '��� delivery');

    $Tab3 .= $PHPShopGUI->setField('���������', $PHPShopGUI->setRadio('pickup_new', 1, '��������', $data['pickup']) . $PHPShopGUI->setRadio('pickup_new', 2, '���������', $data['pickup'], false, 'text-muted'), 1, '��� pickup');

    $Tab3 .= $PHPShopGUI->setField('������� � ��������� ��������', $PHPShopGUI->setRadio('store_new', 1, '��������', $data['store']) . $PHPShopGUI->setRadio('store_new', 2, '���������', $data['store'], false, 'text-muted'), 1, '��� store');

    $Tab3 .= $PHPShopGUI->setField("����������� ����������", $PHPShopGUI->setInputText(null, 'yandex_min_quantity_new', $data['yandex_min_quantity'], 100), 1, ' ����������� ���������� ������ � ����� ������');

    $Tab3 .= $PHPShopGUI->setField("����������� ���", $PHPShopGUI->setInputText(null, 'yandex_step_quantity_new', $data['yandex_step_quantity'], 100), 1, ' ���������� ������, ����������� � ������������');
    $Tab3 .= $PHPShopGUI->setField('<a href="/rss/google.php" target="_blank" title="������� ����">Google Merchant</a>', $PHPShopGUI->setCheckbox('google_merchant_new', 1, '����� � Google Merchant', $data['google_merchant']));
    $Tab3 .= $PHPShopGUI->setField('<a href="/yml/?cdek" target="_blank" title="������� ����">����.������</a>', $PHPShopGUI->setCheckbox('cdek_new', 1, '����� � ����.������', $data['cdek']));
    $Tab3 .= $PHPShopGUI->setField('<a href="/yml/?aliexpress" target="_blank" title="������� ����">AliExpress</a>', $PHPShopGUI->setCheckbox('aliexpress_new', 1, '����� � AliExpress', $data['aliexpress']));
    $Tab3 .= $PHPShopGUI->setField('<a href="/yml/?sbermarket" target="_blank" title="������� ����">����������</a>', $PHPShopGUI->setCheckbox('sbermarket_new', 1, '����� � ����������', $data['sbermarket']));

    $PHPShopGUI->addTab(array("������", $Tab3, true));
}

function updateYandexCart($product) {
    if(empty($_POST['google_merchant_new'])) {
        $_POST['google_merchant_new'] = 0;
    }
    if(empty($_POST['cdek_new'])) {
        $_POST['cdek_new'] = 0;
    }
    if(empty($_POST['aliexpress_new'])) {
        $_POST['aliexpress_new'] = 0;
    }
    if(empty($_POST['sbermarket_new'])) {
        $_POST['sbermarket_new'] = 0;
    }
}

function addYandexCartOptions($data) {
    global $PHPShopGUI;

    $PHPShopGUI->field_col = 3;

    $Tab = $PHPShopGUI->setField("��������", $PHPShopGUI->setInputText(null, 'barcode_new', $data['barcode'], 300), 1, '��� barcode');
    $Tab .= $PHPShopGUI->setField("��� �������������", $PHPShopGUI->setInputText(null, 'vendor_code_new', $data['vendor_code'], 300), 1, '��� vendorCode');

    $PHPShopGUI->addTab(["������", $Tab, true]);
}

$addHandler = array(
    'actionStart' => 'addYandexcartCPA',
    'actionDelete' => false,
    'actionUpdate' => 'updateYandexCart',
    'actionOptionEdit' => 'addYandexCartOptions'
);
?>