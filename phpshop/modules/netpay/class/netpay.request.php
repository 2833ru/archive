<?php

class NetpayRequest {
	
	private $apikey, $auth, $order_id, $amount, $hold, $is_test, 
		$email, $phone, $desc, $currency, 
		$bill, $inn, $tax, $products, 
		$discount, $delivery, $delivery_title,
		$expired, $autosubmit;
	private $submit_title;
	private $params, $keys, $settings;
	
	function __construct($apikey, $auth, $order_id, $amount, $hold, $is_test,		 
		$bill = false, $inn = '', $tax = '', $products = array(), 
		$discount = 0, $delivery = false, $delivery_title = 'Delivery', 
		$expired = 99, $autosubmit = '1', $submit_title = '',
		$email ='', $phone = '', $desc = '', $currency = 'RUB' 
		) {
		$this->apikey = $apikey;
		$this->auth = $auth;
		$this->order_id = $order_id;
		$this->amount = $amount;
		$this->hold = $hold;
		$this->is_test = $is_test;	
		$this->email = $email;
		$this->phone = $phone;
		$this->desc = $desc;	
		$this->currency = $currency;
		$this->bill = $bill;
		$this->inn = $inn;
		$this->tax = $tax;
		$this->products  = $products;
		$this->discount  = $discount;
		$this->delivery  = $delivery;
		$this->delivery_title  = $delivery_title;
		$this->expired = $expired;
		$this->autosubmit = $autosubmit;
		$this->submit_title = $submit_title;
	}
	
	function prepare() {
		// Prepare params
		$this->params = array(
			'orderID' => $this->order_id,
			'amount' => $this->amount,
			'currency' => $this->currency,
			'email' => $this->email,
			'phone' => $this->phone,
			'successUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/',
			'failUrl' => 'http://' . $_SERVER['HTTP_HOST'] . '/fail/?from=netpay',
		);
		
		if ($this->desc) {
			$this->params['description'] = $this->desc;
		}
		
		if ($this->hold) {
			$this->params['isHold'] = 'true';
		}
		
		if ($this->is_test) {
			$this->params['orderID'] .= '-test-'.$_SERVER['HTTP_HOST'];
			$this->params['param1'] = 'callback';
			$this->params['value1'] = 'http'.(isset($_SERVER['HTTPS']) ? 's':'').'://'
				.$_SERVER['HTTP_HOST'].'/phpshop/modules/netpay/payment/result.php';
		}
		
		if ($this->bill) {
			require_once(dirname(__FILE__).'/netpay_atol.php');
			$netpayAtol = new NetpayAtol(
				$this->inn, 
				$this->email, 
				$this->amount,
				'', // host
				$this->phone,
				$this->is_test
				);
				
			foreach ($this->products as $product) {
				$netpayAtol->addItem(
					$product['name'], 
					$product['price'], 
					$product['num'], 
					$product['price'] * $product['num'], 
					$this->tax
					);
			}
			
			if ($this->discount) {
				$netpayAtol->addItem(
					'Скидка', 
					-abs($this->discount), 
					1, 
					-abs($this->discount), 
					'none'
					);
			}
			
			if ($this->delivery) {
				$netpayAtol->addShipping(
					$this->delivery_title, 
					$this->delivery
					); 
			}
			
			$this->params['cashbox_data'] = $netpayAtol->getJSON();
			//var_export($netpayAtol->getErrors());
		}
		
		// Prepare keys
		$this->keys = array(
			'api_key' => $this->apikey,
			'auth_signature' => $this->auth,
			);		
		if ($this->is_test) {
			$this->keys['api_key'] = $this->keys['auth_signature'] = '';
		}
		
		// Prepare settings
		$this->settings = array('expiredtime' => intval($this->expired));
		if ($this->autosubmit == 2)
			$this->settings['autosubmit'] = '0';
		else
			$this->settings['autosubmit'] = '1';
		$this->settings['target'] = '1';
		$this->settings['submitval'] = $this->submit_title ? $this->submit_title : 'Pay online';
	}
	
	function getButton() {
		$this->prepare();
		
		require_once(dirname(__FILE__).'/netpay.class.php');
		$netpay = new Netpay();
		return $netpay->getbutton($this->params, $this->keys, $this->settings);
	}
	
	function getLink() {
		$this->prepare();
		$this->settings['autosubmit'] = '0';
		
		require_once(dirname(__FILE__).'/netpay.class.php');
		$netpay = new Netpay();
		return $netpay->getlink($this->params, $this->keys, $this->settings);
	}
}
