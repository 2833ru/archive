<?php
/**
 * ������� ���, ��������� ���������� ���������� �������
 * @param object $obj ������ �������
 * @param array $value ������ � ������
 */
function success_mod_sberbankrf_hook($obj, $value) {

    if (isset($_REQUEST["sberbankrf"]) && $_REQUEST["sberbankrf"] == "true") {

        $obj->order_metod = 'modules" and id="10010';

        $obj->message();

        // �������� ������ ������ � ����� ��� ������
        sm_check_status($obj, $_REQUEST["orderId"]);

        return true;
    }

    if (isset($_REQUEST["sberbankrf"]) && $_REQUEST["sberbankrf"] == "false") {

        $obj->error();

        // �������� ������ ������ � ����� ��� ������
        sm_check_status($obj, $_REQUEST["orderId"]);

        return true;
    }
}

/**
 * ������� �������� ������� ������� � ������� ��������� ������
 * @param object $obj ������ �������
 * @param string $id ����� ������
 */
function sm_check_status($obj, $id){

    $PHPShopOrm = new PHPShopOrm();

    // ��������� ������
    include_once(dirname(__FILE__) . '/mod_option.hook.php');
    $PHPShopSberbankRFArray = new PHPShopSberbankRFArray();
    $conf = $PHPShopSberbankRFArray->getArray();

    // �������� �������
    $params = array(
        "orderId" => $id,
        "userName" => $conf["login"],
        "password" => $conf["password"],
    );

    // ����� ���������� � ������ �����
    if($conf["dev_mode"] == 1)
        $url ='https://3dsec.sberbank.ru/payment/rest/getOrderStatus.do';
    else
        $url ='https://securepayments.sberbank.ru/payment/rest/getOrderStatus.do';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url . "?" . http_build_query($params)); // set url to post to
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    $r = json_decode(curl_exec($ch), true); // run the whole process
    curl_close($ch);

    $time = time();

    if($r["OrderStatus"] == 2) {
        $status = "������ ������� ��������";
        $orderNum = $r["OrderNumber"];
        $order_status = $obj->set_order_status_101();
        $PHPShopOrm->query("UPDATE `phpshop_orders` SET `statusi`='$order_status' WHERE `uid`='$orderNum'");
    }else{
        $status = "������ ���������� �������";
    }

    // ���
    $PHPShopOrm->query("UPDATE `phpshop_modules_sberbankrf_log` SET `date`='$time', `status`='$status' WHERE `order_id_sber`='$id'");

}
$addHandler = array('index' => 'success_mod_sberbankrf_hook');
?>