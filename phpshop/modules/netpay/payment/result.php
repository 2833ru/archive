<?php

/**
 * Обработчик оповещения о платеже NetPay
 */
session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("system");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
$PHPShopSystem = new PHPShopSystem();
$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('netpay');

class NetPayPayment extends PHPShopPaymentResult {

    function __construct() {

        $this->option();
        
        // Демо-режим
        if ($this->option['merchant_key'] == "") {
            $this->option['merchant_key'] = 'js4cucpn4kkc6jl1p95np054g2';
            $this->option['merchant_skey'] = 1;
        }
        parent::__construct();
    }

    /**
     * Настройка модуля 
     */
    function option() {
        $this->payment_name = 'NetPay';
        include_once('../hook/mod_option.hook.php');
        include_once('../class/netpay.class.php');
        $PHPShopNetPayArray = new PHPShopNetPayArray();
        $this->option = $PHPShopNetPayArray->getArray();
    }

    /**
     *  Ответ
     */
    function done($order = false, $transactionType = false, $error = false, $status = false) {
        if (!empty($order)) {
            echo '
<notification>
            <orderId>' . $order . '</orderId>
            <transactionType>' . $transactionType . '</transactionType>
            <status>' . $status . '</status>
            <error>' . $error . '</error>
</notification>';
            parent::log();
        }
    }

    /**
     * Проверка подписи
     * @return boolean 
     */
    function check() {

        // Проверка auth
        if ($_GET['auth'] == $this->option['merchant_skey']) {

            $netpay = new Netpay();
            $data = $netpay->getdata($_GET['data'], $_GET['expire'], $this->option['merchant_key']);

            if (is_array($data)) {

                // Ошибок нет
                if ($data['status'] == 'APPROVED') {
                    $this->inv_id = $data['orderID'];
                    $this->done($this->inv_id, $data['transactionType'], $data['error'], $data['status']);

                    //$this->out_summ = $_REQUEST['sum'];

                    $this->crc = true;
                    $this->my_crc = true;
                    return true;
                }
            } else {
                header('Location: http://' . $_SERVER['SERVER_NAME'] . '/fail/');
                exit;
            }
        } else {
            header('Location: http://' . $_SERVER['SERVER_NAME'] . '/fail/');
            exit;
        }
    }

}

new NetPayPayment();
?>
