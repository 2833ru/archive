<?php

/**
 * Обработчик оповещения о платеже
 */
session_start();
header('Content-Type: text/html; charset=utf-8');

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("lang");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("string");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
$PHPShopSystem = new PHPShopSystem();
$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('payonline');


class Payment extends PHPShopPaymentResult {

    private $PayOnline;
    
    function __construct() {

        $this->payment_name = 'PayOnline';
        $this->log = true;
        include_once(dirname(__FILE__) . '/../class/PayOnline.php');

        $this->PayOnline = new PayOnline();
        
        $this->PayOnline->setOrderId($_REQUEST['OrderId']);
        $this->PayOnline->setAmount((float) $_REQUEST['Amount']);

        parent::__construct();
    }

    /**
     * Проверка crc
     * @return boolean
     */
    function check() {
       $key = $this->PayOnline->option['key'];

       return md5("DateTime=$_REQUEST[DateTime]&TransactionID=$_REQUEST[TransactionID]&OrderId=$_REQUEST[OrderId]&Amount=$_REQUEST[Amount]&Currency=$_REQUEST[Currency]&PrivateSecurityKey=$key") === $_REQUEST["SecurityKey"];
    }

    /**
     * Обновление данных по заказу
     */
    function updateorder() {

        if (!$this->check()) {
            $this->PayOnline->log($_REQUEST, $this->PayOnline->getOrderId(), 'Ошибка проверки crc', 'Уведомление о платеже');
            exit();
        }

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['orders']);
        $PHPShopOrm->debug = $this->debug;
        $row = $PHPShopOrm->select(array('*'), array('uid' => "='" . $this->PayOnline->getOrderId() . "'"), false, array('limit' => 1));

        if(empty($row))
            $this->PayOnline->log($_REQUEST, $this->PayOnline->getOrderId(), 'Заказ ' . $this->PayOnline->getOrderId() . ' не найден', 'Уведомление о платеже');

        $this->PayOnline->log($_REQUEST, $this->PayOnline->getOrderId(), 'Заказ оплачен', 'Уведомление о платеже');
        $this->setPaymentLog($row['uid']);

        $PHPShopOrm->debug = $this->debug;
        $PHPShopOrm->update(array('statusi_new' => $this->set_order_status_101(), 'paid_new' => 1), array('uid' => '="' . $row['uid'] . '"'));

        $this->ofd($row);

        exit();
    }

    private function setPaymentLog($id)
    {
        $uid = explode("-", $id);
        $PHPShopOrmPayment = new PHPShopOrm($GLOBALS['SysValue']['base']['payment']);
        $PHPShopOrmPayment->insert(array('uid_new' => $uid[0] . $uid[1], 'name_new' => 'PayOnline',
            'sum_new' => $this->PayOnline->getAmount(), 'datas_new' => time()));
    }
}

new Payment();