<?php
PHPShopObj::loadClass("delivery");
PHPShopObj::loadClass('order');

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexturbo.yandexturbo_system"));

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

// ������� ����������
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // ��������� �������
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $_POST['options']['statuses'] = $_POST['statuses'];
    $_POST['options']['payments'] = $_POST['payments'];

    $_POST['options_new'] = serialize($_POST['options']);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // �������
    $data = $PHPShopOrm->select();
    $options = unserialize($data['options']);

    isset($options['payments']) && is_array($options['payments']) ? $payments = $options['payments'] : $payments = [];

    // �������� ������� �������
    $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
    $OrderStatusArray = $PHPShopOrderStatusArray->getArray();
    $order_status_value[] = array(__('����� �����'), 0, $options['statuses']);
    if (is_array($OrderStatusArray))
        foreach ($OrderStatusArray as $order_status)
            $order_status_value[] = array($order_status['name'], $order_status['id'], $options['statuses']);
    
    
    // ��������
    $PHPShopDeliveryArray = new PHPShopDeliveryArray(array('is_folder' => "!='1'", 'enabled' => "='1'"));
    $DeliveryArray = $PHPShopDeliveryArray->getArray();
    if (is_array($DeliveryArray)) {
        foreach ($DeliveryArray as $delivery) {
            if (strpos($delivery['city'], '.')) {
                $name = explode(".", $delivery['city']);
                $delivery['city'] = $name[0];
            }
            $delivery_value[] = array($delivery['city'], $delivery['id'], $data['delivery_id']);
            $delivery_pickup_value[] = array($delivery['city'], $delivery['id'], $data['delivery_id_pickup']);
            $delivery_post_value[] = array($delivery['city'], $delivery['id'], $data['delivery_id_post']);
        }
    }

    $Tab1 = $PHPShopGUI->setField('��������������� ����� API', $PHPShopGUI->setInputText(null, 'auth_token_new', $data['auth_token'], 300));
    $Tab1 .= $PHPShopGUI->setField('������ ������', $PHPShopGUI->setSelect('statuses', $order_status_value, 300));
    $Tab1 .= $PHPShopGUI->setField('�������� ��������', $PHPShopGUI->setSelect('delivery_id_new', $delivery_value, 300));
    $Tab1 .= $PHPShopGUI->setField('���������', $PHPShopGUI->setSelect('delivery_id_pickup_new', $delivery_pickup_value, 300));
    $Tab1 .= $PHPShopGUI->setField('�������� ������', $PHPShopGUI->setSelect('delivery_id_post_new', $delivery_post_value, 300));

    $Tab1 = $PHPShopGUI->setCollapse('���������', $Tab1);

    // ������� ������
    $paymentOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment_systems']);
    $paymentsArr = $paymentOrm->getList();

    foreach ($paymentsArr as $payment) {
        $payment_yandex_value[] = [
            $payment['name'], $payment['id'], isset($payments['yandex']) ? $payments['yandex'] : null
        ];
        $payment_card_on_delivery_value[] = [
            $payment['name'], $payment['id'], isset($payments['card_on_delivery']) ? $payments['card_on_delivery'] : null
        ];
        $payment_cash_on_delivery_value[] = [
            $payment['name'], $payment['id'], isset($payments['cash_on_delivery']) ? $payments['cash_on_delivery'] : null
        ];
    }

    $Tab1 .= $PHPShopGUI->setCollapse('������� ������', $PHPShopGUI->setField('���������� ������ ��� ���������� ������', $PHPShopGUI->setSelect('payments[yandex]', $payment_yandex_value, 300)
            ) .
            $PHPShopGUI->setField('���������� ������ ��� ��������� ������', $PHPShopGUI->setSelect('payments[card_on_delivery]', $payment_card_on_delivery_value, 300)
            ) .
            $PHPShopGUI->setField('���������', $PHPShopGUI->setSelect('payments[cash_on_delivery]', $payment_cash_on_delivery_value, 300)
            ), null);


    $Tab2 = $PHPShopGUI->setInfo($info);

    // ����� �����������
    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true), array("� ������", $Tab3));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>