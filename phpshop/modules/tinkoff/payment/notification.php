<?php

session_start();
$_classPath = "../../../";
include $_classPath . "class/obj.class.php";
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("mail");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("modules");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('tinkoff');
$PHPShopSystem = new PHPShopSystem();
include_once dirname(__FILE__) . '/../hook/mod_option.hook.php';

class TinkoffPayment extends PHPShopPaymentResult
{
    function __construct()
    {
        $this->option();
        parent::__construct();
    }

    function option()
    {
        $this->payment_name = 'Tinkoff';
        $PHPShopTinkoffArray = new PHPShopTinkoffArray();
        $this->option = $PHPShopTinkoffArray->getArray();
    }

    function check()
    {
        $request = json_decode(file_get_contents("php://input"));
        $request->Success = $request->Success ? 'true' : 'false';
        $requestData = array();

        foreach ($request as $key => $item) {
            $requestData[$key] = $item;
        }

        if ($requestData['Token'] == $this->getToken($requestData)) {
            global $PHPShopOrm;
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
            $order = $PHPShopOrm->select(array('*'), array('uid' => '="' . $requestData['OrderId'] . '"'), false, array('limit' => 1));

            if ($order && (float) $requestData['Amount'] >= (float)$order['sum']) {
                $this->inv_id = str_replace("-", '', $requestData['OrderId']);
                $this->out_summ = $requestData['Amount'];

                if ($requestData['Status'] == 'CONFIRMED') {
                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['shopusers']);
                    $user = $PHPShopOrm->select(array('*'), array('id' => '="' . $order['user'] . '"'), false, array('limit' => 1));

                    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['order_status']);
                    $status = $PHPShopOrm->select(array('*'), array('id' => '="' . $order['statusi'] . '"'), false, array('limit' => 1));

                    $this->sendEmail($order['uid'], $status['name'], $user['mail']);
                    $this->sendEmail($order['uid'], $status['name']);

                    return true;
                } elseif (in_array($requestData['Status'], ['AUTHORIZED', 'CANCELED', 'REVERSED', 'PARTIAL_REFUNDED', 'REJECTED', 'REFUNDED'])) {
                    return true;
                }
            }
        }

        return false;
    }

    function done()
    {
        die('OK');
    }

    function error($type = 1)
    {
        die('NOTOK');
    }

    function getToken($data)
    {
        $data['Password'] = $this->option['secret_key'];
        ksort($data);

        if (isset($data['Token'])) {
            unset($data['Token']);
        }

        $values = implode('', array_values($data));

        return hash('sha256', $values);
    }

    function sendEmail($orderId, $status, $to = null)
    {
        global $PHPShopSystem;

        if (!$to) {
            $to = $PHPShopSystem->getParam('adminmail2');
        }

        $PHPShopMail = new PHPShopMail($to, $PHPShopSystem->getParam('adminmail2'), 'Оплата заказа #' . $orderId, '', false, true);
        $PHPShopMail->sendMailNow('Заказ #' . $orderId . ' оплачен, текущий статус заказа ' . $status . '.');
    }
}

new TinkoffPayment();