<?php
/**
 * ������� ���, ����������� ������ � ��������� ����� ��������� ���������� ���������, ������������� �� ��������� �����
 * @param object $obj ������ �������
 * @param array $value ������ � ������
 * @param string $rout ����� ��������� ����
 */
function send_sberbankrf_hook($obj, $value, $rout) {
    global $PHPShopSystem;

    if ($rout == 'MIDDLE' and $value['order_metod'] == 10010) {

        // ��������� ������
        include_once(dirname(__FILE__) . '/mod_option.hook.php');

        $PHPShopSberbankRFArray = new PHPShopSberbankRFArray();
        $option = $PHPShopSberbankRFArray->getArray();

        // �������� ������ �� ������� ������
        if (empty($option['status'])) {

            // ����� �������
            $out_summ = number_format($obj->get('total'), 2, '.', '')*100;

            // ����������� ������ � ��������� �����
            $params = array(
                "userName" => $option["login"],
                "password" => $option["password"],
                "orderNumber" => $value['ouid'],
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

            sm_log($value['ouid'], $result["orderId"]);

            header('Location: '. $result["formUrl"]);
        }else{

            $obj->set('mesageText', $option['title_sub'] );

            $forma = ParseTemplateReturn($GLOBALS['SysValue']['templates']['order_forma_mesage']);

            $obj->set('orderMesage', $forma);
        }
    }
}

/**
 * ������� ������ � ������ ������������������� � ��������� ����� ������
 * @param string $order_id ����� ������ � ��������-��������
 * @param string $order_id_sber ����� ������ � ��������� �����
 */
function sm_log($order_id, $order_id_sber){
    $PHPShopOrm = new PHPShopOrm("phpshop_modules_sberbankrf_log");

    $log = array(
        'order_id_new' => $order_id,
        'order_id_sber_new' => $order_id_sber
    );

    $PHPShopOrm->insert($log);
}

$addHandler = array('send_to_order' => 'send_sberbankrf_hook');
?>