<?php

/**
 * Обработчик оповещения о платеже Яндекс.Касса
 * @author PHPShop Software
 * @version 1.0
 */
session_start();
header('Content-Type: text/html; charset=utf-8');

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("lang");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("system");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");

$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('yandexkassa');

class Payment extends PHPShopPaymentResult {

    function Payment() {
        $this->option();
        parent::PHPShopPaymentResult();
    }

    /**
     * Настройка модуля 
     */
    function option() {

        $this->payment_name = 'Yandexkassa';
        $this->log = true;
        include_once(dirname(__FILE__) . '/../hook/mod_option.hook.php');
        $this->PHPShopYandexkassaArray = new PHPShopYandexkassaArray();
        $this->option = $this->PHPShopYandexkassaArray->getArray();
    }

    /**
     * Проверка подписи
     * @return boolean 
     */
    function check() {
//        print_r($this->option);
        $this->my_crc = strtoupper(md5($_REQUEST['action'] . ';' . $_REQUEST['orderSumAmount'] . ';' . $_REQUEST['orderSumCurrencyPaycash'] . ';' . $_REQUEST['orderSumBankPaycash'] . ';' . $_REQUEST['shopId'] . ';' . $_REQUEST['invoiceId'] . ';' . $_REQUEST['customerNumber'] . ';' . $this->option['merchant_sig']));

        $this->inv_id = $_REQUEST['orderNumber'];
        $this->crc = $_REQUEST['md5'];

        if ($this->my_crc == $this->crc)
            return true;
    }

    /**
     * Обновление данных по заказу 
     */
    function updateorder() {

        if ($this->check()) {

            // Приверяем сущ. заказа
            $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
            $PHPShopOrm->debug = $this->debug;
            $row = $PHPShopOrm->select(array('uid'), array('uid' => "='" . $this->true_num($this->inv_id) . "'"), false, array('limit' => 1));
            if (!empty($row['uid'])) {
                $this->code = 0;
                // данные в лог
                $this->PHPShopYandexkassaArray->log($_REQUEST, $this->inv_id, 'заказ найден', 'запрос checkOrder c сервера яндекс кассы');
            } else {
                // данные в лог
                $this->PHPShopYandexkassaArray->log($_REQUEST, $this->inv_id, 'заказ не найден', 'запрос checkOrder c сервера яндекс кассы');
                $this->code = 100;
            }
        } else {
            // данные в лог
            $this->PHPShopYandexkassaArray->log($_REQUEST, $this->inv_id, 'ошибка проверки md5', 'запрос checkOrder c сервера яндекс кассы');
            $this->code = 1;
        }

//        $this->log();

        echo '<?xml version="1.0" encoding="UTF-8"?> 
<checkOrderResponse performedDatetime="' . date("c") . '" 
code="' . $this->code . '" invoiceId="' . $_REQUEST['invoiceId'] . '" 
shopId="' . $this->option['merchant_id'] . '"/>';
    }

}

new Payment();
?>