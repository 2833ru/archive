<?php

include_once dirname(__FILE__) . '/../class/WbSeller.php';
PHPShopObj::loadClass('category');
$TitlePage = __('������ ��  Wildberries');

function actionStart() {
    global $PHPShopInterface, $TitlePage, $select_name, $PHPShopModules;

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setActionPanel($TitlePage, $select_name, false);
    $PHPShopInterface->setCaption(array("������", "5%"), array("��������", "40%"), array("���������", "30%"), array("������", "15%"));

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $PHPShopOrm->debug = false;

    $data = $PHPShopOrm->select(array('*'), array('export_wb' => "='1'"), array('order' => 'DESC'), array('limit' => 10000));
    $WbSeller = new WbSeller();

    // ��������� ��
    $PHPShopCategoryArray = new PHPShopCategoryArray();
    $PHPShopCategory = $PHPShopCategoryArray->getArray();

    if ($_GET['status'] == 'NEW') {
        $_GET['status'] = 'ALL';
        $new = true;
    }

    // ������
    $products = $WbSeller->getProductList($_GET['search'], $_GET['limit']);

    if ($WbSeller->type == 2) {
        $type_name = __('���');
        $type = 'uid';
    } else {
        $type_name = 'ID';
        $type = 'id';
    }

    if (is_array($products['data']['cards'])) {
        foreach ($products['data']['cards'] as $products_list) {

            // �������� ������ � ��������� ����
            $PHPShopProduct = new PHPShopProduct(PHPShopString::utf8_win1251($products_list['vendorCode']), $type);
            if (!empty($PHPShopProduct->getName())) {

                // ����������
                if (!empty($new))
                    continue;

                $data[$products_list['product_id']] = $PHPShopProduct->getArray();
                $data[$products_list['product_id']]['status'] = 'imported';
                $data[$products_list['nmID']]['vendorCode'] = $products_list['vendorCode'];
                $data[$products_list['product_id']]['image'] = $PHPShopProduct->getImage();
                $data[$products_list['product_id']]['link'] = '?path=product&id=' . $PHPShopProduct->getParam("id");

                $data[$products_list['product_id']]['category'] = $PHPShopCategory[$PHPShopProduct->getParam("category")]['name'];
            } else {

                // ������ ���������
                $vendorCodes[] = $products_list['vendorCode'];
            }
        }
    }

    if (is_array($vendorCodes)) {


        $products = $WbSeller->getProduct($vendorCodes);

        if (is_array($products['data']))
            foreach ($products['data'] as $products_list) {

                // ����� �����
                if (is_array($products_list['characteristics']))
                    foreach ($products_list['characteristics'] as $characteristics) {
                    
                    if(!empty($characteristics[PHPShopString::win_utf8('������������')]))
                         $data[$products_list['nmID']]['name'] = $characteristics[PHPShopString::win_utf8('������������')]; 
                    
                    if(!empty($characteristics[PHPShopString::win_utf8('�������')]))
                         $data[$products_list['nmID']]['category'] = $characteristics[PHPShopString::win_utf8('�������')]; 
                    
                    }
                    
                $data[$products_list['nmID']]['status'] = 'wait';
                $data[$products_list['nmID']]['link'] = '?path=modules.dir.wbseller.import&id=' . $products_list['vendorCode'];
                $data[$products_list['nmID']]['image']=$products_list['mediaFiles'][0];
                $data[$products_list['nmID']]['vendorCode'] = $products_list['vendorCode'];
            }
    }


    $status = [
        'imported' => '<span class="text-mutted">' . __('��������') . '</span>',
        'wait' => '<span class="text-success">' . __('����� � ��������') . '</span>',
    ];



    if (is_array($data))
        foreach ($data as $row) {


            if (empty($row['name']))
            continue;


            if (!empty($row['image']))
                $icon = '<img src="' . $row['image'] . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="media-object">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            // �������
            if (!empty($row['vendorCode']))
                $uid = '<div class="text-muted">' . $type_name . ' ' . PHPShopString::utf8_win1251($row['vendorCode']) . '</div>';
            else
                $uid = '<div class="text-muted">' . $type_name . ' ' . PHPShopString::utf8_win1251($row['uid']) . '</div>';


            $PHPShopInterface->setRow($icon, array('name' => PHPShopString::utf8_win1251($row['name'], true), 'addon' => $uid, 'link' => $row['link']), PHPShopString::utf8_win1251($row['category']), $status[$row['status']]);
        }


    $searchforma .= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'search', 'placeholder' => '�������', 'value' => $_GET['search']));

    if (empty($_GET['limit']))
        $_GET['limit'] = 50;

    $searchforma .= $PHPShopInterface->setInputArg(array('type' => 'text', 'name' => 'limit', 'placeholder' => '����� �������', 'value' => $_GET['limit']));

    if (isset($_GET['search']))
        $searchforma .= $PHPShopInterface->setButton('�����', 'remove', 'btn-order-cancel pull-left', false, 'javascript:window.location.replace(\'?path=modules.dir.wbseller.import\')');

    $searchforma .= $PHPShopInterface->setButton('��������', 'search', 'btn-order-search pull-right', false, 'document.avito_search.submit()');
    $searchforma .= $PHPShopInterface->setInput("hidden", "path", $_GET['path'], "right", 70, "", "but");

    $sidebarright[] = array('title' => '������', 'content' => $PHPShopInterface->setForm($searchforma, false, "avito_search", false, false, 'form-sidebar'));

    $PHPShopInterface->setSidebarRight($sidebarright, 2, 'hidden-xs');

    $PHPShopInterface->Compile(2);
}
