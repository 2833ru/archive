<?php

function actionStart() {
    global $PHPShopInterface, $PHPShopModules, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("������", "7%"), array("��������", "30%"), array("ID", "5%"), array("������", "45%"), array("����", "10%"));

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ozonseller.ozonseller_export"));
    $PHPShopOrm->debug = false;

    if (!empty($_GET['uid']))
        $where = ['product_id' => '=' . (int) $_GET['uid']];
    else
        $where = false;

    $data = $PHPShopOrm->select(array('*'), $where, array('order' => 'id DESC'), array('limit' => 1000));

    if (is_array($data))
        foreach ($data as $row) {

            if (empty($row['order_id']))
                $row['order_id'] = null;

            if (!empty($row['product_image']))
                $icon = '<img src="' . $row['product_image'] . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="media-object">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            $PHPShopInterface->setRow($icon, array('name' => $row['product_name'], 'link' => '?path=product&id=' . $row['product_id']), array('name' => $row['product_id'], 'link' => '?path=modules.dir.ozonseller&uid=' . $row['product_id']), array('name' => $row['message']), PHPShopDate::get($row['date'], true));
        }
    $PHPShopInterface->Compile();
}

function _actionStart() {
    global $PHPShopInterface, $PHPShopModules, $TitlePage, $select_name;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("������", "7%"), array("��������", "40%"), array("������", "30%"), array("������", "20%"));

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $PHPShopOrm->debug = false;

    $data = $PHPShopOrm->select(array('*'), array('export_ozon' => "='1'"), array('order' => 'export_ozon_task_status DESC'), array('limit' => 10000));
    $OzonSeller = new OzonSeller();
    $import_count = 0;

    $status = [
        'imported' => '<span class="text-success">' . __('��������') . '</span>',
        'error' => '<span class="text-warning">' . __('������') . '</span>',
        'wait' => '<span class="text-mutted">' . __('� ������� �� ��������') . '</span>',
        'pending' => '<span class="text-mutted">' . __('� ��������') . '</span>',
    ];
    if (is_array($data))
        foreach ($data as $row) {

            $error = null;
            $info = $er = $result = [];

            if (!empty($row['pic_small']))
                $icon = '<img src="' . $row['pic_small'] . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="media-object">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            // ������ ��������
            $info['status'] = $row['export_ozon_task_status'];

            if (empty($row['export_ozon_task_id'])) {

                if ($import_count < 10) {

                    $products[] = $row;
                    $result = $OzonSeller->sendProducts($products);
                    $task_id = $data['export_ozon_task_id'] = $result['result']['task_id'];
                    $import_count++;

                    if (!empty($task_id)) {
                        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);

                        // ��������� OZON ID
                        $product_id = $OzonSeller->sendProductsInfo($row)['result']['items'][0]['product_id'];

                        $PHPShopOrm->update(['export_ozon_task_id_new' => $task_id, 'export_ozon_task_status_new' => 'imported', 'export_ozon_id_new' => $product_id], ['id' => '=' . $row['id']]);

                        if (!empty($product_id))
                            $info['status'] = 'imported';
                        else
                            $info['status'] = 'error';

                        $info['errors'][] = ['description' => $result['message']];
                    } else {

                        $info['errors'][] = ['description' => $result['message']];
                    }
                } else {
                    $info['status'] = 'wait';
                }
            } else
                $info['status'] = 'imported';


            if (empty($info['status']))
                $info['status'] = 'error';

            if (is_array($info['errors'])) {

                foreach ($info['errors'] as $k => $er) {

                    // ������
                    if (!empty($er['description'])) {
                        $er['description'] = preg_replace("~(http|https|ftp|ftps)://(.*?)(\s|\n|[,.?!](\s|\n)|$)~", '<a href="$1://$2" target="_blank">[������]</a>$3', $er['description']);
                        $error .= ($k + 1) . ' - ' . PHPShopString::utf8_win1251($er['description']) . '<br>';
                    }
                }
            } else {
                $error = $result['message'];
            }


            // �������
            if (!empty($row['uid']))
                $uid = '<div class="text-muted">' . __('���') . ' ' . $row['uid'] . '</div>';
            else
                $uid = null;


            $PHPShopInterface->setRow($icon, array('name' => $row['name'], 'addon' => $uid, 'link' => '?path=product&id=' . $row['id']), $error, $status[$info['status']]);
        }
    $PHPShopInterface->Compile();
}
