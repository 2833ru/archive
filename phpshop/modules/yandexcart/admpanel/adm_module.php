<?php

PHPShopObj::loadClass("delivery");

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.yandexcart.yandexcart_system"));

// ���������� ������ ������
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate(number_format($option['version'], 1, '.', false));
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $TitlePage, $select_name;

    $PHPShopGUI->field_col = 3;
    PHPShopObj::loadClass("order");

    // �������
    $data = $PHPShopOrm->select();

    $options = unserialize($data['options']);

    if ($data['model'] === 'FBS') {
        $PHPShopGUI->action_button['�������������� ������'] = [
            'name' => '�������������� ������',
            'action' => 'ymImportProducts',
            'class' => 'btn btn-default btn-sm navbar-btn',
            'type' => 'submit',
            'icon' => 'glyphicon glyphicon-import'
        ];

        $PHPShopGUI->addJSFiles('../modules/yandexcart/admpanel/gui/script.gui.js?v=2.9');
        $PHPShopGUI->setActionPanel($TitlePage, $select_name, ['�������������� ������', '��������� � �������']);
    }

    if($data['model'] === 'FBS' || $data['model'] === 'DBS') {
        isset($options['statuses']) && is_array($options['statuses']) ? $statuses = $options['statuses'] : $statuses = [];
        isset($options['payments']) && is_array($options['payments']) ? $payments = $options['payments'] : $payments = [];

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
            }
        }
    }

    $models = [
        ['FBS (������� � ������������ ��������)', 'FBS', $data['model']],
        ['DBS (������� � ��������� ��������)', 'DBS', $data['model']],
        ['ADV (������� �������� �����������)', 'ADV', $data['model']]
    ];

    if (empty($_SESSION['mod_pro']))
        $models = [
            ['FBS (�������� � ������ Pro)', 'ADV', $data['model']],
            ['DBS (�������� � ������ Pro)', 'ADV', $data['model']],
            ['ADV (������� �������� �����������)', 'ADV', $data['model']],
        ];

    $Tab1 = $PHPShopGUI->setField('������ ������', $PHPShopGUI->setSelect('model_new', $models,300));

    if ($data['model'] === 'ADV' || $data['model'] === 'DBS') {
        $Tab1 .= $PHPShopGUI->setField('������ ������ �����', $PHPShopGUI->setInputText('http://' . $_SERVER['SERVER_NAME'] . '/yml/?pas=', 'password_new', $data['password'], 534));
        $Tab1 .= $PHPShopGUI->setField('SSL', $PHPShopGUI->setCheckbox('options[ssl]', 1, '���� ���������� HTTPS ��������', $options['ssl']));
        $Tab1 .= $PHPShopGUI->setField('����� �������������', $PHPShopGUI->setCheckbox('use_params_new', 1, '�������� ����� ������������� � YML', $data['use_params']));
    }

    if ($data['model'] === 'FBS' || $data['model'] === 'DBS') {
        $Tab1 .= $PHPShopGUI->setField('������������� ��������', $PHPShopGUI->setInputText('xx-', 'campaign_id_new', $data['campaign_id'], 534));
        $Tab1 .= $PHPShopGUI->setField('��������������� ����� API', $PHPShopGUI->setInputText(null, 'auth_token_new', $data['auth_token'], 534));
        $Tab1 .= $PHPShopGUI->setField('ID ���������� ������.OAuth', $PHPShopGUI->setInputText(null, 'client_id_new', $data['client_id'], 534));
        $Tab1 .= $PHPShopGUI->setField('OAuth-�����', $PHPShopGUI->setInputText(null, 'client_token_new', $data['client_token'], 534));

        if($data['model'] === 'FBS') {
            $Tab1 .= $PHPShopGUI->setField('�������� ��� ������� � �������', $PHPShopGUI->setSelect('delivery_id_new', $delivery_value, 300, null));
            if ((int) $data['import_from'] > 0) {
                $fromCaption = '� ��� ������ 5 000 ������� ����������� � ������.������. ������� �������� ������ �� ��������� �����. 
            ������ ����� ������ �������� ������� ���������� ���������� � ���������� ��������. ���� ��� ���������� ������ ������� �������, 
            ���������� �������� 0, ����� ������������� �� �������� ��� ��������.';
                $Tab1 .= $PHPShopGUI->setField('���������� ������ �', $PHPShopGUI->setInputText(null, 'import_from_new', $data['import_from'], 100), 1, $fromCaption);
            }
        }
    }

    $Tab1 .= $PHPShopGUI->setField('������ ��������� ��������', '<div id="yandexDescriptionShablon">
<textarea class="form-control yandex-shablon" name="description_template_new" rows="3" style="max-width: 534px;height: 70px;">' . $data['description_template'] . '</textarea>
    <div class="btn-group" role="group" aria-label="...">
    <input  type="button" value="' . __('��������') . '" onclick="yandexShablonAdd(\'@Content@\')" class="btn btn-default btn-sm">
    <input  type="button" value="' . __('������� ��������') . '" onclick="yandexShablonAdd(\'@Description@\')" class="btn btn-default btn-sm">
    <input  type="button" value="' . __('��������������') . '" onclick="yandexShablonAdd(\'@Attributes@\')" class="btn btn-default btn-sm">
<input  type="button" value="' . __('�������') . '" onclick="yandexShablonAdd(\'@Catalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="' . __('����������') . '" onclick="yandexShablonAdd(\'@Subcatalog@\')" class="btn btn-default btn-sm">
<input  type="button" value="' . __('�����') . '" onclick="yandexShablonAdd(\'@Product@\',)" class="btn btn-default btn-sm">
    </div>
</div>
<script>function yandexShablonAdd(variable) {
    var shablon = $(".yandex-shablon").val() + " " + variable;
    $(".yandex-shablon").val(shablon);
}</script>', 1, '�������������� � �������� ������� �������������� ��������. ������������� ������������ ������ ��� ������ ���������� ���������� �������.');

    $Tab1.= $PHPShopGUI->setCollapse('��������� ���',
        $PHPShopGUI->setField('������� ��� ������.������', $PHPShopGUI->setSelect('options[price]', $PHPShopGUI->setSelectValue($options['price'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_fee]', $options['price_fee'], 100, '%')) .

        $PHPShopGUI->setField('������� ��� ������.������ DBS', $PHPShopGUI->setSelect('options[price_dbs]', $PHPShopGUI->setSelectValue($options['price_dbs'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_dbs_fee]', $options['price_dbs_fee'], 100, '%')) .

        $PHPShopGUI->setField('������� ��� Google Merchant', $PHPShopGUI->setSelect('options[price_google]', $PHPShopGUI->setSelectValue($options['price_google'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_google_fee]', $options['price_google_fee'], 100, '%')) .

        $PHPShopGUI->setField('������� ��� ����.������', $PHPShopGUI->setSelect('options[price_cdek]', $PHPShopGUI->setSelectValue($options['price_cdek'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_cdek_fee]', $options['price_cdek_fee'], 100, '%')) .

        $PHPShopGUI->setField('������� ��� AliExpress', $PHPShopGUI->setSelect('options[price_ali]', $PHPShopGUI->setSelectValue($options['price_ali'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_ali_fee]', $options['price_ali_fee'], 100, '%')) .

        $PHPShopGUI->setField('������� ��� ����������', $PHPShopGUI->setSelect('options[price_sbermarket]', $PHPShopGUI->setSelectValue($options['price_sbermarket'], 5), 100)) .
        $PHPShopGUI->setField('�������', $PHPShopGUI->setInputText(null, 'options[price_sbermarket_fee]', $options['price_sbermarket_fee'], 100, '%')),
        true
    );

    if (empty($data['model'])) {
        $Tab1 .= sprintf('<div class="alert alert-info" role="alert">%s</div>', __('�������� ������ ������ � ������� "���������" ��� ������� � ����������.'));
    }

    if ($data['model'] === 'FBS' or $data['model'] === 'DBS') {

        // �������� ������� �������
        $PHPShopOrderStatusArray = new PHPShopOrderStatusArray();
        $OrderStatusArray = $PHPShopOrderStatusArray->getArray();

        if (is_array($OrderStatusArray))
            foreach ($OrderStatusArray as $status) {
                $status_delivered_value[] = [
                    $status['name'], $status['id'], isset($statuses['delivered']) ? $statuses['delivered'] : null
                ];
                $status_delivery_value[] = [
                    $status['name'], $status['id'], isset($statuses['delivery']) ? $statuses['delivery'] : null
                ];
                $status_pickup_value[] = [
                    $status['name'], $status['id'], isset($statuses['pickup']) ? $statuses['pickup'] : null
                ];
                $status_unpaid_value[] = [
                    $status['name'], $status['id'], isset($statuses['unpaid']) ? $statuses['unpaid'] : null
                ];
                $status_started_value[] = [
                    $status['name'], $status['id'], isset($statuses['processing_started']) ? $statuses['processing_started'] : null
                ];
                $status_delivery_service_undelivered_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_delivery_service_undelivered']) ? $statuses['cancelled_delivery_service_undelivered'] : null
                ];
                $status_processing_expired_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_processing_expired']) ? $statuses['cancelled_processing_expired'] : null
                ];
                $status_replacing_order_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_replacing_order']) ? $statuses['cancelled_replacing_order'] : null
                ];
                $status_reservation_expired_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_reservation_expired']) ? $statuses['cancelled_reservation_expired'] : null
                ];
                $status_reservation_failed_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_reservation_failed']) ? $statuses['cancelled_reservation_failed'] : null
                ];
                $status_shop_failed_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_shop_failed']) ? $statuses['cancelled_shop_failed'] : null
                ];
                $status_user_changed_mind_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_user_changed_mind']) ? $statuses['cancelled_user_changed_mind'] : null
                ];
                $status_user_not_paid_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_user_not_paid']) ? $statuses['cancelled_user_not_paid'] : null
                ];
                $status_user_refused_delivery_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_user_refused_delivery']) ? $statuses['cancelled_user_refused_delivery'] : null
                ];
                $status_user_refused_product_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_refused_product']) ? $statuses['cancelled_refused_product'] : null
                ];
                $status_user_refused_quality_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_refused_quality']) ? $statuses['cancelled_refused_quality'] : null
                ];
                $status_user_unreachable_value[] = [
                    $status['name'], $status['id'], isset($statuses['cancelled_unreachable']) ? $statuses['cancelled_unreachable'] : null
                ];
            }

        // ������ ������
        $Tab1 .= $PHPShopGUI->setCollapse('������� ������',
            $PHPShopGUI->setField('������ ���������', $PHPShopGUI->setSelect('statuses[delivered]', $status_delivered_value),
                1, 'DELIVERED'
                ) .
                $PHPShopGUI->setField('������ ������� � ������ ��������', $PHPShopGUI->setSelect('statuses[delivery]', $status_delivery_value),
                    1, 'DELIVERY'
                ) .
                $PHPShopGUI->setField('����� ��������� � ����� ����������', $PHPShopGUI->setSelect('statuses[pickup]', $status_pickup_value),
                    1, 'PICKUP'
                ) .
                $PHPShopGUI->setField('����� ��������, �� ��� �� �������', $PHPShopGUI->setSelect('statuses[unpaid]', $status_unpaid_value),
                    1, 'UNPAID'
                ) .
                $PHPShopGUI->setField('����� �����������, ��� ����� ������ ������������', $PHPShopGUI->setSelect('statuses[processing_started]', $status_started_value),
                    1, 'PROCESSING STARTED'
                ) .
                $PHPShopGUI->setField('������ �������� �� ������ ��������� �����', $PHPShopGUI->setSelect('statuses[cancelled_delivery_service_undelivered]', $status_delivery_service_undelivered_value),
                    1, 'CANCELLED DELIVERY_SERVICE_UNDELIVERED'
                ) .
                $PHPShopGUI->setField('������� �� ��������� ����� � ������� ���� ����', $PHPShopGUI->setSelect('statuses[cancelled_processing_expired]', $status_processing_expired_value),
                    1, 'CANCELLED PROCESSING_EXPIRED'
                ) .
                $PHPShopGUI->setField('���������� ����� �������� ����� ������ �� ����������� ����������', $PHPShopGUI->setSelect('statuses[cancelled_replacing_order]', $status_replacing_order_value),
                    1, 'CANCELLED REPLACING_ORDER'
                ) .
                $PHPShopGUI->setField('���������� �� �������� ���������� ������������������ ������ � ������� 10 �����', $PHPShopGUI->setSelect('statuses[cancelled_reservation_expired]', $status_reservation_expired_value),
                    1, 'CANCELLED RESERVATION_EXPIRED'
                ) .
                $PHPShopGUI->setField('������� �� ����������, ��� ����� ������� �����', $PHPShopGUI->setSelect('statuses[cancelled_reservation_failed]', $status_reservation_failed_value),
                    1, 'CANCELLED RESERVATION_FAILED'
                ) .
                $PHPShopGUI->setField('������� �� ����� ��������� �����', $PHPShopGUI->setSelect('statuses[cancelled_shop_failed]', $status_shop_failed_value),
                    1, 'CANCELLED SHOP_FAILED'
                ) .
                $PHPShopGUI->setField('���������� ������� ����� �� ����������� ��������', $PHPShopGUI->setSelect('statuses[cancelled_user_changed_mind]', $status_user_changed_mind_value),
                    1, 'CANCELLED USER_CHANGED_MIND'
                ) .
                $PHPShopGUI->setField('���������� �� ������� ����� (��� ���� ������ PREPAID) � ������� ���� �����', $PHPShopGUI->setSelect('statuses[cancelled_user_not_paid]', $status_user_not_paid_value),
                    1, 'CANCELLED USER_NOT_PAID'
                ) .
                $PHPShopGUI->setField('���������� �� ���������� ������� ��������', $PHPShopGUI->setSelect('statuses[cancelled_user_refused_delivery]', $status_user_refused_delivery_value),
                    1, 'CANCELLED USER_REFUSED_DELIVERY'
                ) .
                $PHPShopGUI->setField('���������� �� ������� �����', $PHPShopGUI->setSelect('statuses[cancelled_refused_product]', $status_user_refused_product_value),
                    1, 'CANCELLED USER_REFUSED_PRODUCT'
                ) .
                $PHPShopGUI->setField('���������� �� ���������� �������� ������', $PHPShopGUI->setSelect('statuses[cancelled_refused_quality]', $status_user_refused_quality_value),
                    1, 'CANCELLED USER_REFUSED_QUALITY'
                ) .
                $PHPShopGUI->setField('�� ������� ��������� � �����������', $PHPShopGUI->setSelect('statuses[cancelled_unreachable]', $status_user_unreachable_value),
                    1, 'CANCELLED USER_UNREACHABLE'
                ), null);

        // ������� ������
        $paymentOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment_systems']);
        $paymentsArr = $paymentOrm->getList();

        foreach ($paymentsArr as $payment) {
            $payment_yandex_value[] = [
                $payment['name'], $payment['id'], isset($payments['yandex']) ? $payments['yandex'] : null
            ];
            $payment_apple_pay_value[] = [
                $payment['name'], $payment['id'], isset($payments['apple_pay']) ? $payments['apple_pay'] : null
            ];
            $payment_google_pay_value[] = [
                $payment['name'], $payment['id'], isset($payments['google_pay']) ? $payments['google_pay'] : null
            ];
            $payment_credit_value[] = [
                $payment['name'], $payment['id'], isset($payments['credit']) ? $payments['credit'] : null
            ];
            $payment_certificate_value[] = [
                $payment['name'], $payment['id'], isset($payments['certificate']) ? $payments['certificate'] : null
            ];
            $payment_card_on_delivery_value[] = [
                $payment['name'], $payment['id'], isset($payments['card_on_delivery']) ? $payments['card_on_delivery'] : null
            ];
            $payment_cash_on_delivery_value[] = [
                $payment['name'], $payment['id'], isset($payments['cash_on_delivery']) ? $payments['cash_on_delivery'] : null
            ];
        }

        $Tab1 .= $PHPShopGUI->setCollapse('������� ������', $PHPShopGUI->setField('���������� ������ ��� ���������� ������', $PHPShopGUI->setSelect('payments[yandex]', $payment_yandex_value)
                ) .
                $PHPShopGUI->setField('Apple Pay', $PHPShopGUI->setSelect('payments[apple_pay]', $payment_apple_pay_value)
                ) .
                $PHPShopGUI->setField('Google Pay', $PHPShopGUI->setSelect('payments[google_pay]', $payment_google_pay_value)
                ) .
                $PHPShopGUI->setField('� ������', $PHPShopGUI->setSelect('payments[credit]', $payment_credit_value)
                ) .
                $PHPShopGUI->setField('���������� ����������', $PHPShopGUI->setSelect('payments[certificate]', $payment_certificate_value)
                ) .
                $PHPShopGUI->setField('���������� ������ ��� ��������� ������', $PHPShopGUI->setSelect('payments[card_on_delivery]', $payment_card_on_delivery_value)
                ) .
                $PHPShopGUI->setField('���������', $PHPShopGUI->setSelect('payments[cash_on_delivery]', $payment_cash_on_delivery_value)
                ), null);
    }

    // ����������
    $Tab2 = $PHPShopGUI->loadLib('tab_info', $data, '../modules/' . $_GET['id'] . '/admpanel/');

    $Tab3 = $PHPShopGUI->setPay(false, false, $data['version'], true);

    // ����� ����� ��������
    $PHPShopGUI->setTab(array("��������", $Tab1, true), array("����������", $Tab2), array("� ������", $Tab3));

    // ����� ������ ��������� � ����� � �����
    $ContentFooter = $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "���������", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
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
    $_POST['region_data_new'] = 1;
    if (empty($_POST["use_params_new"]))
        $_POST["use_params_new"] = 0;

    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&id=' . $_GET['id']);
    return $action;
}

// ��������� �������
$PHPShopGUI->getAction();

// ����� ����� ��� ������
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>