<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

function addOzonsellerProductTab($data) {
    global $PHPShopGUI;

    // ������ �������� ����
    $PHPShopGUI->field_col = 4;

    $tab = $PHPShopGUI->setField(null, $PHPShopGUI->setCheckbox('export_ozon_new', 1, '�������� ������� � OZON', $data['export_ozon']));
    $tab .= $PHPShopGUI->setInput("hidden", "export_ozon_task_id", $data['export_ozon_task_id']);
    $status = ['imported' => '<span class="text-success">��������</span>', 'error' => '<span class="text-warning">������</span>'];
    $error = null;
    
    // ����� ��� �� ��������
    if (!empty($data['export_ozon']) and empty($data['export_ozon_id'])) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

        $OzonSeller = new OzonSeller();
        if (empty($data['export_ozon_task_id'])) {

            $products[] = $data;

            $result = $OzonSeller->sendProducts($products);
            $task_id = $data['export_ozon_task_id'] = $result['result']['task_id'];

            if (!empty($task_id)) {
                $PHPShopOrm->update(['export_ozon_task_id_new' => $task_id], ['id' => '=' . $data['id']]);
            } else
                $error = $result['message'];
        }

        $info = $OzonSeller->sendProductsInfo($data)['result']['items'][0];
        $PHPShopOrm->update(['export_ozon_task_status_new' => $info['status'], 'export_ozon_id_new' => $info['product_id']], ['id' => '=' . $data['id']]);

        if (empty($info['status']))
            $info['status'] = 'error';

        if (is_array($info['errors'])) {
            foreach ($info['errors'] as $k => $er) {

                // ������
                $er['description'] = preg_replace("~(http|https|ftp|ftps)://(.*?)(\s|\n|[,.?!](\s|\n)|$)~", '<a href="$1://$2" target="_blank">[������]</a>$3', $er['description']);
                $error .= ($k + 1) . ' - ' . PHPShopString::utf8_win1251($er['description']) . '<br>';
            }
        }
    }
    // ��������� export_ozon_id ���� ��� �� ����
    else if (empty($data['export_ozon_id']) and $data['export_ozon_task_status'] == 'imported') {

        $OzonSeller = new OzonSeller();
        $product_id = $OzonSeller->sendProductsInfo($data)['result']['items'][0]['product_id'];

        (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->update(['export_ozon_id_new' => $product_id], ['id' => '=' . $data['id']]);
    }

    if (empty($info['status']))
        $info['status'] = $data['export_ozon_task_status'];

    if (!empty($info['status']))
        $tab .= $PHPShopGUI->setField('������ ������', $PHPShopGUI->setText($status[$info['status']]));

    // ������
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $valuta_def_name = $val['code'];
            }
        }

    if (!empty($error))
        $tab .= $PHPShopGUI->setField('������', $PHPShopGUI->setText($error, "left", false, false));

    $tab .= $PHPShopGUI->setField('���� OZON', $PHPShopGUI->setInputText(null, 'price_ozon_new', $data['price_ozon'], 150, $valuta_def_name), 2);
    $tab .= $PHPShopGUI->setField("��������", $PHPShopGUI->setInputText(null, 'barcode_ozon_new', $data['barcode_ozon']));
    $tab .= $PHPShopGUI->setField('OZON ID', $PHPShopGUI->setInputText(null, 'export_ozon_id_new', $data['export_ozon_id']));


    $PHPShopGUI->addTab(array("OZON", $tab, true));
}

function OzonsellerUpdate($data) {

    // ���������� Ozon
    if (!isset($_POST['export_ozon_new']) and !isset($_POST['ajax'])) {
        $_POST['export_ozon_new'] = 0;
        $_POST['export_ozon_task_id_new'] = 0;
        $_POST['export_ozon_task_status_new'] = '';
    }

    // ��������� ������
    if (isset($_POST['enabled_new'])) {

        $PHPShopProduct = new PHPShopProduct($_POST['rowID']);
        $data['export_ozon_id'] = $PHPShopProduct->getParam('export_ozon_id');
        $data['items'] = $PHPShopProduct->getParam('items');
        $data['enabled'] = $PHPShopProduct->getParam('enabled');
        $data['id'] = $PHPShopProduct->getParam('id');
        $data['export_ozon'] = $PHPShopProduct->getParam('export_ozon');

        if (!empty($data['export_ozon_id']) and !empty($data['export_ozon'])) {
            $OzonSeller = new OzonSeller();

            if (isset($_POST['items_new']))
                $data['items'] = $_POST['items_new'];

            if (isset($_POST['enabled_new']))
                $data['enabled'] = $_POST['enabled_new'];

            $product_id = $OzonSeller->setProductStock($data)['result']['items'][0]['product_id'];

            // ������ ����������, �� ������ OZON ID, ���������� �������
            if (empty($product_id)) {
                (new PHPShopOrm($GLOBALS['SysValue']['base']['products']))->update(['export_ozon_task_status_new' => '', 'export_ozon_task_id_new' => ''], ['id' => '=' . $_POST['rowID']]);
            }
        }
    }
}

$addHandler = array(
    'actionStart' => 'addOzonsellerProductTab',
    'actionDelete' => false,
    'actionUpdate' => 'OzonsellerUpdate'
);
?>