<?php

include_once dirname(__FILE__) . '/../class/OzonSeller.php';
$TitlePage = __('������ �� Ozon');
PHPShopObj::loadClass("product");

// ����
$OzonSeller = new OzonSeller();

// ��������� ������� ��������
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopInterface, $OzonSeller;

    // ������ �� ������ ����
    if ($_GET['type'] == 'FBS')
        $order_info = $OzonSeller->getOrderFbs($_GET['id']);
    else
        $order_info = $OzonSeller->getOrderFbo($_GET['id']);

    $PHPShopGUI->field_col = 4;

    if(!empty($order_info['result']['order_id']))
    $PHPShopGUI->action_button['��������� �����'] = array(
        'name' => '��������� �����',
        'locale' => true,
        'action' => 'saveID',
        'class' => 'btn  btn-default btn-sm navbar-btn' . $GLOBALS['isFrame'],
        'type' => 'submit',
        'icon' => 'glyphicon glyphicon-save'
    );

    
    $PHPShopGUI->setActionPanel(__('�����') . ' &#8470;' . $_GET['id'], false, array('��������� �����'));

    // ���� �����
    if ($PHPShopSystem->getDefaultValutaIso() == 'RUB' or $PHPShopSystem->getDefaultValutaIso() == 'RUR')
        $currency = ' <span class="rubznak hidden-xs">p</span>';
    else
        $currency = $PHPShopSystem->getDefaultValutaCode();

    // ��������� � �������� ���
    ob_start();
    print_r($order_info);
    $log = ob_get_clean();

    $Tab3 = $PHPShopGUI->setTextarea(null, PHPShopString::utf8_win1251($log), $float = "none", $width = '100%', $height = '500');
    $Tab1 = $PHPShopGUI->setField("&#8470; �����������", $PHPShopGUI->setText($order_info['result']['posting_number']));
    $Tab1 .= $PHPShopGUI->setField("ID ������", $PHPShopGUI->setText($order_info['result']['order_id']));
    $Tab1 .= $PHPShopGUI->setField("&#8470; ������", $PHPShopGUI->setText($order_info['result']['order_number']));
    $Tab1 .= $PHPShopGUI->setField("������", $PHPShopGUI->setText($OzonSeller->getStatus($order_info['result']['status'])));
    $Tab1 .= $PHPShopGUI->setField("���� �����������", $PHPShopGUI->setText($order_info['result']['in_process_at']));
    $Tab1 .= $PHPShopGUI->setField("���� ��������", $PHPShopGUI->setText($order_info['result']['shipment_date']));
    $Tab1 .= $PHPShopGUI->setField("��������", $PHPShopGUI->setText(PHPShopString::utf8_win1251($order_info['result']['delivery_method']['name']),"left", false, false));

    $Tab1 = $PHPShopGUI->setCollapse('������', $Tab1);
    $Tab3 = $PHPShopGUI->setCollapse('JSON ������', $Tab3);

    $PHPShopInterface->checkbox_action = false;
    $PHPShopInterface->setCaption(array("������������", "50%"), array("����", "15%"), array("���-��", "10%"), array("�����", "15%", array('align' => 'right')));

    $data = $order_info['result']['products'];
    if (is_array($data))
        foreach ($data as $row) {

            $product = new PHPShopProduct($row['offer_id']);

            if (!empty($product->getValue('pic_small')))
                $icon = '<img src="' . $product->getValue('pic_small') . '" onerror="this.onerror = null;this.src = \'./images/no_photo.gif\'" class="media-object">';
            else
                $icon = '<img class="media-object" src="./images/no_photo.gif">';

            $name = '
<div class="media">
  <div class="media-left">
    <a href="?path=product&id=' . $row['offer_id'] . '" >
      ' . $icon . '
    </a>
  </div>
   <div class="media-body">
    <div class="media-heading"><a href="?path=product&id=' . $row['offer_id'] . '&return=modules.dir.ozonseller" >' . PHPShopString::utf8_win1251($row['name']) . '</a></div>
    ' . __('���') . ': ' . $row['sku'] . '
  </div>
</div>';

            $PHPShopInterface->setRow($name, (1 * $row['price']), array('name' => $row['quantity'], 'align' => 'center'), array('name' => number_format($row['price'] * $row['quantity'], 0, '', ' ') . $currency, 'align' => 'right'));
        }

    $Tab2 = $PHPShopGUI->setCollapse("�������", '<table class="table table-hover cart-list">' . $PHPShopInterface->getContent() . '</table>');

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("����������", $Tab1 . $Tab2, true, false, true), array('�������������', $Tab3));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $_GET['id'], "right", 70, "", "but") .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionSave.order.edit");

    // �����
    $PHPShopGUI->setFooter($ContentFooter);

    return true;
}

/**
 * ����� �������� ������
 */
function actionSave() {
    global $OzonSeller;

    // ������ �� ������ ����
    $order_info = $OzonSeller->getOrderFbs($_POST['rowID']);

    $name = 'OZON';
    $phone = null;
    $mail = null;
    $comment = null;

    // ������� �������
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
    $qty = $sum = $weight = 0;

    $data = $order_info['result']['products'];
    if (is_array($data))
        foreach ($data as $row) {

            $product = new PHPShopProduct($row['offer_id']);
            $order['Cart']['cart'][$row['offer_id']]['id'] = $product->getParam('id');
            $order['Cart']['cart'][$row['offer_id']]['uid'] = $product->getParam("uid");
            $order['Cart']['cart'][$row['offer_id']]['name'] = $product->getName();
            $order['Cart']['cart'][$row['offer_id']]['price'] = $row['price'];
            $order['Cart']['cart'][$row['offer_id']]['num'] = $row['quantity'];
            $order['Cart']['cart'][$row['offer_id']]['weight'] = '';
            $order['Cart']['cart'][$row['offer_id']]['ed_izm'] = '';
            $order['Cart']['cart'][$row['offer_id']]['pic_small'] = $product->getImage();
            $order['Cart']['cart'][$row['offer_id']]['parent'] = 0;
            $order['Cart']['cart'][$row['offer_id']]['user'] = 0;
            $qty += $row['quantity'];
            $sum += $row['price'] * $row['quantity'];
            $weight += $product->getParam('weight');
        }

    $order['Cart']['num'] = $qty;
    $order['Cart']['sum'] = $sum;
    $order['Cart']['weight'] = $weight;
    $order['Cart']['dostavka'] = $order_info['result']['delivery_price'];

    $order['Person']['ouid'] = '';
    $order['Person']['data'] = time();
    $order['Person']['time'] = '';
    $order['Person']['mail'] = $mail;
    $order['Person']['name_person'] = $name;
    $order['Person']['org_name'] = '';
    $order['Person']['org_inn'] = '';
    $order['Person']['org_kpp'] = '';
    $order['Person']['tel_code'] = '';
    $order['Person']['tel_name'] = '';
    $order['Person']['adr_name'] = '';
    $order['Person']['dostavka_metod'] = '';
    $order['Person']['discount'] = 0;
    $order['Person']['user_id'] = '';
    $order['Person']['dos_ot'] = '';
    $order['Person']['dos_do'] = '';
    $order['Person']['order_metod'] = '';
    $insert['dop_info_new'] = $comment;

    // ������ ��� ������ � ��
    $insert['datas_new'] = time();
    $insert['uid_new'] = $OzonSeller->setOrderNum();
    $insert['orders_new'] = serialize($order);
    $insert['fio_new'] = $name;
    $insert['tel_new'] = $phone;
    $insert['statusi_new'] = $OzonSeller->status;
    $insert['status_new'] = serialize(array("maneger" => __('OZON ����� &#8470;' . $_POST['rowID'])));
    $insert['sum_new'] = $order['Cart']['sum'];
    $insert['ozonseller_order_data_new'] = $_POST['rowID'];

    // ������ � ����
    $orderId = $PHPShopOrm->insert($insert);

    header('Location: ?path=order&id=' . $orderId . '&return=' . $_GET['path']);
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>