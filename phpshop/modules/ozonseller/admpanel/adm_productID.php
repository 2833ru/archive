<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';

function addOzonsellerProductTab($data) {
    global $PHPShopGUI;

    // ������ �������� ����
    $PHPShopGUI->field_col = 4;

    $tab = $PHPShopGUI->setField(null, $PHPShopGUI->setCheckbox('export_ozon_new', 1, '�������� ������� � OZON', $data['export_ozon']));
    $tab .= $PHPShopGUI->setInput("hidden", "export_ozon_task_id", $data['export_ozon_task_id']);

    // ���� �������� 
    if ($data['export_ozon_task_id'] > 1680000000)
        $date = PHPShopDate::get($data['export_ozon_task_id'], true);
    else
        $date = null;


    $status = ['imported' => '<span class="text-success">' . __('��������') . ' ' . $date . '</span>', 'failed' => '<a class="text-warning" href="?path=modules.dir.ozonseller.product&uid=' . $data['id'] . '" target="_blank">' . __('������') . '</a>', 'pending' => '<span class="text-muted">' . __('� ��������') . '</span>'];

    if (!empty($data['export_ozon_task_status']))
        $tab .= $PHPShopGUI->setField('������ ������', $PHPShopGUI->setText($status[$data['export_ozon_task_status']]));

    // ������
    $PHPShopValutaArray = new PHPShopValutaArray();
    $valuta_array = $PHPShopValutaArray->getArray();
    if (is_array($valuta_array))
        foreach ($valuta_array as $val) {
            if ($data['baseinputvaluta'] == $val['id']) {
                $valuta_def_name = $val['code'];
            }
        }

    $tab .= $PHPShopGUI->setField('���� OZON', $PHPShopGUI->setInputText(null, 'price_ozon_new', $data['price_ozon'], 150, $valuta_def_name), 2);
    $tab .= $PHPShopGUI->setField("��������", $PHPShopGUI->setInputText(null, 'barcode_ozon_new', $data['barcode_ozon'], 150));


    if (!empty($data['export_ozon']))
    $tab .= $PHPShopGUI->setField('OZON ID', $PHPShopGUI->setInputText(null, 'export_ozon_id_new', $data['export_ozon_id'], 150));

    $PHPShopGUI->addTab(array("OZON", $tab, true));
}

function OzonsellerUpdate($post) {

    // ���������� Ozon
    if (!isset($_POST['export_ozon_new']) and ! empty($_POST['name_new'])) {
        $_POST['export_ozon_new'] = 0;
        $_POST['export_ozon_task_id_new'] = 0;
        $_POST['export_ozon_task_status_new'] = '';
    }
    
    //if(empty($_POST['export_ozon_id_new']))
       // unset($_POST['export_ozon_id_new']);

    // ��������� ������
    if (isset($_POST['enabled_new'])) {

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
        $data = $PHPShopOrm->getOne(['*'], ['id' => '=' . (int) $_POST['rowID']]);

        // ����� ��� OZON
        if (!empty($data['export_ozon']) or !empty($_POST['export_ozon_new'])) {

            $OzonSeller = new OzonSeller();

            // ����� ��� �� ��������
            if (empty($data['export_ozon_id'])) {

                // ��������
                if (empty($data['export_ozon_task_id'])) {
                    $result = $OzonSeller->sendProducts($data);
                    $task_id = $data['export_ozon_task_id'] = $result['result']['task_id'];
                    $error = $result['message'];
                } else
                    $task_id = $data['export_ozon_task_id'];

                if (!empty($task_id) and empty($error)) {

                    // �������� ������� ��������
                    $info = $OzonSeller->sendProductsInfo($data)['result']['items'][0];

                    // ����� ����������
                    if (!empty($info['product_id'])) {
                        $PHPShopOrm->update(['export_ozon_task_status_new' => $info['status'], 'export_ozon_task_id_new' => time(), 'export_ozon_id_new' => $info['product_id']], ['id' => '=' . (int) $data['id']]);
                        $OzonSeller->clean_log($data['id']);
                    }
                    // ������
                    elseif (is_array($info['errors']) and count($info['errors']) > 0) {

                        if (empty($info['status']))
                            $info['status'] = 'error';

                        foreach ($info['errors'] as $k => $er) {

                            // ������
                            $er['description'] = preg_replace("~(http|https|ftp|ftps)://(.*?)(\s|\n|[,.?!](\s|\n)|$)~", '<a href="$1://$2" target="_blank">[������]</a>$3', $er['description']);
                            $error .= ($k + 1) . ' - ' . PHPShopString::utf8_win1251($er['description']) . '<br>';
                        }

                        $PHPShopOrm->update(['export_ozon_task_status_new' => $info['status']], ['id' => '=' . (int) $data['id']]);
                    }
                    // � ��������
                    elseif ($info['status'] == 'pending') {
                        $error = __('����� ��������� � ������� �� ������, ������ OZON �������� �����. ��������� ��������� ����������� ������ ��� ���������� ��������.');
                        $PHPShopOrm->update(['export_ozon_task_status_new' => $info['status'], 'export_ozon_task_id_new' => $task_id], ['id' => '=' . (int) $data['id']]);
                    }

                    if (!empty($error))
                        $OzonSeller->export_log($error, $data['id'], $data['name'], $data['pic_small']);
                }
                // ������ 
                elseif (!empty($error)) {
                    $OzonSeller->export_log($error, $data['id'], $data['name'], $data['pic_small']);
                }
            }
            // ����� ��������, ���������� ��� � ��������
            else {

                if (isset($_POST['items_new']))
                    $data['items'] = $_POST['items_new'];

                if (isset($_POST['price_new']))
                    $data['price'] = $_POST['price_new'];


                if (isset($_POST['enabled_new']))
                    $data['enabled'] = $_POST['enabled_new'];

                // �����
                if (is_array($OzonSeller->warehouse))
                    foreach ($OzonSeller->warehouse as $warehouse) {
                        $result = $OzonSeller->setProductStock([$data], $warehouse['id'])['result']['items'][0]['product_id'];
                        if (!empty($result))
                            $product_id = $result;
                    }

                // ����
                $OzonSeller->setProductPrice([$data]);

                // ������ ����������, �� ������ OZON ID, ���������� �������
                if (empty($product_id)) {
                    $PHPShopOrm->update(['export_ozon_task_status_new' => '', 'export_ozon_task_id_new' => ''], ['id' => '=' . $_POST['rowID']]);
                }
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