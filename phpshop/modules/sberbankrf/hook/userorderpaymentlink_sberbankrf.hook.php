<?php
/**
 * ������� ���, ����� ������ ������ � �� � ����������� ����������� ������ � ��������� ����� ��������� ���������� ���������
 * @param object $obj ������ �������
 * @param array $PHPShopOrderFunction ������ � ������
 */
function userorderpaymentlink_mod_sberbankrf_hook($obj, $PHPShopOrderFunction) {

    // ��������� ������
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    $PHPShopSberbankRFArray = new PHPShopSberbankRFArray();
    $option = $PHPShopSberbankRFArray->getArray();

    // ������
    if($_REQUEST["paynow"] == "Y"){
        // ����� �������
        $out_summ = $PHPShopOrderFunction->getTotal()*100;

        // ����������� ������ � ��������� �����
        $params = array(
            "userName" => $option["login"],
            "password" => $option["password"],
            "orderNumber" => $PHPShopOrderFunction->objRow['uid'],
            "amount" => $out_summ,
            "returnUrl" => 'http://' . $_SERVER['HTTP_HOST'] . '/success/?sberbankrf=true',
            "failUrl" => 'http://' . $_SERVER['HTTP_HOST'] . '/success/?sberbankrf=false',
        );

        // ����� ���������� � ������ �����
        if($option["dev_mode"] == 1)
            $url ='https://3dsec.sberbank.ru/payment/rest/register.do';
        else
            $url ='https://securepayments.sberbank.ru/payment/rest/register.do';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($params)); // set url to post to
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        $result = json_decode(curl_exec($ch), true); // run the whole process
        curl_close($ch);

        sberbank_log($PHPShopOrderFunction->objRow['uid'], $result["orderId"]);
        header('Location: '. $result["formUrl"]);
    }

    // �������� ������ �� ������� ������
    if ($PHPShopOrderFunction->order_metod_id == 10010)

        if ($PHPShopOrderFunction->getParam('statusi') == $option['status'] or empty($option['status'])) {

        $uid = $PHPShopOrderFunction->objRow['uid'];

        $return = PHPShopText::a("/users/order.html?order_info=$uid&paynow=Y#Order", '�������� ������', '�������� ������', false, false, '_blank', 'btn btn-success pull-right');

    } elseif ($PHPShopOrderFunction->getSerilizeParam('orders.Person.order_metod') == 10010)
        $return = ', ����� �������������� ����������';

    return $return;
}

/**
 * ������� ������ � ������ ������������������� � ��������� ����� ������
 * @param string $order_id ����� ������ � ��������-��������
 * @param string $order_id_sber ����� ������ � ��������� �����
 */
function sberbank_log($order_id, $order_id_sber){

    $PHPShopOrm = new PHPShopOrm("phpshop_modules_sberbankrf_log");

    $log = array(
        'order_id_new' => $order_id,
        'order_id_sber_new' => $order_id_sber
    );

    $PHPShopOrm->insert($log);
}
$addHandler = array('userorderpaymentlink' => 'userorderpaymentlink_mod_sberbankrf_hook');
?>