<?php

/**
 * Обработчик оповещения о платеже NetPay
 */
session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
include_once($_classPath . "class/mail.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("lang");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("xml");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("payment");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("parser");
PHPShopObj::loadClass("string");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini", true, true);
$PHPShopSystem = new PHPShopSystem();
$PHPShopModules = new PHPShopModules($_classPath . "modules/");
$PHPShopModules->checkInstall('netpay');

class NetPayPayment extends PHPShopPaymentResult {

    function __construct() {
        $this->option();
        
        // Демо-режим
        if ($this->option['work'] != 1) {
            $this->option['apikey'] = 'js4cucpn4kkc6jl1p95np054g2';
            $this->option['auth'] = 1;
        }
        parent::__construct();
    }

    /**
     * Настройка модуля 
     */
    function option() {
        $this->payment_name = 'NetPay';
        include_once('../hook/mod_option.hook.php');
        $PHPShopNetPayArray = new PHPShopNetPayArray();
        $this->option = $PHPShopNetPayArray->getArray();
    }

    /**
     *  Ответ
     */
    function done($order = false, $transactionType = false, $error = false, $status = false) {
        if (!empty($order)) {
            echo 
'<notification>
	<orderId>' . htmlentities($order) . '</orderId>
	<transactionType>' . $transactionType . '</transactionType>
	<status>' . $status . '</status>
	<error>' . $error . '</error>
</notification>';
            //parent::log();
        }
    }

    /**
     * Проверка подписи
     * @return boolean 
     */
    function check() {
		parse_str($_SERVER['QUERY_STRING'], $query_arr);
		$query_arr = array_map('urldecode', $query_arr);
		$getData = $query_arr;
		
		foreach ($query_arr as $k => $v) {
			if ($k == 'orderID') break;
			unset($getData[$k]);
		}			
		$preToken = '';
		foreach ($getData as $k => $v) {
			if ($k !== 'token') $preToken .= $v.';';
		}			
		$token = md5($preToken.base64_encode(md5($this->option['apikey'], true)).';');
		
		$is_testmode = ($this->option['work'] != 1);
		
		if ($getData['auth'] == $this->option['auth']) {
			if ($token === $getData['token']) {
				if (in_array($getData['error'], array('000', '00', '0'))) {
					$order_id = $getData['orderID'];				
					if ($is_testmode) {
						$order_id = str_replace('-test-'.$_SERVER['HTTP_HOST'], '', $getData['orderID']);
					}
					if (isset($getData['orderNumber'])) {
						$order_id = $getData['orderNumber'];
					}
					
					$connect_cfg = $GLOBALS['SysValue']["connect"];
					$table_orders = $GLOBALS['SysValue']['base']['orders'];
					mysql_connect($connect_cfg['host'], $connect_cfg['user_db'], $connect_cfg['pass_db'])
					&& mysql_select_db($connect_cfg['dbase']);
					
					$this->inv_id = $order_id;
					$error = '';
					$status = $getData['status'];
					$trans_type = $getData['transactionType'];
					if ($status === 'APPROVED') { 
						if (in_array(
							$trans_type, 
							array('Capture', 'Sale', 'Sale_Qiwi', 'Sale_YaMoney', 'Sale_WebMoney')
							)) {
						    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment']);
							$PHPShopOrm->insert(array(
								'uid_new' => $this->inv_id, 
								'name_new' => $this->payment_name,
								'sum_new' => $getData['amount'], 
								'datas_new' => time()
								));
                            $order = (new PHPShopOrm($GLOBALS['SysValue']['base']['orders']))
                                ->getOne(['id', 'statusi'], ['uid' => sprintf('="%s"', $this->true_num($this->inv_id))]);

                            if($order) {
                                (new PHPShopOrderFunction((int) $order['id']))->changeStatus((int) $this->set_order_status_101(), $order['statusi']);
                            }
						}
						elseif (in_array($trans_type, 
							array('Cancel','Refund','Refund_Qiwi','Refund_WebMoney','Refund_YaMoney')
							)) {
							mysql_query("update `$table_orders` set statusi='{$this->option['status_refund']}' where uid='".mysql_escape_string($this->true_num($this->inv_id))."'");
							$this->done($this->inv_id, $trans_type, $error, $status);
							exit;
						}
						else {
							$error .= '| unknown transactionType | ';
						}
					}
					elseif (($status == 'WAITING') && ($trans_type == 'Authorize')) {
						//$this->inv_id = $order_id;						
						//$this->out_summ = $_REQUEST['sum'];
						//$this->crc = true;
						//$this->my_crc = true;
						mysql_query("update `$table_orders` set statusi='{$this->option['status_hold']}' where uid='".mysql_escape_string($this->true_num($this->inv_id))."'");
						$this->done($this->inv_id, $trans_type, $error, $status);
						exit;
					}
					else {
						$error .= '| unknown error | ';
					}
					if (strlen($error)) {
						$status = '0';
						$error = $error;
					}
					else {
						$status = '1';
						$error = '';
					}
				}
				else {
					$status = '0';
				}
			}
			else {
				$status = '0';
				$error = 'Error: token does not match';
			}
		}
		else {
			$status = 0;
			$error = 'Wrong auth value';
		}
		
		$this->done($this->inv_id, $trans_type, $error, $status);
		return strlen($error) ? false : true;
    }

}
error_reporting(0);
new NetPayPayment();
