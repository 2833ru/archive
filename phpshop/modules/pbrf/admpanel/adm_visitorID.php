<?php

function startPbrf($data) {
	global $PHPShopGUI;
	// Библиотека заказа
  //$PHPShopOrder = new PHPShopOrderFunction($data['id']);

	//$order = unserialize($data['orders']);
	//$Person = $order['Person'];
	//$Cart = $order['Cart'];



  $Tab5 = $PHPShopGUI->loadLib('tab_pbrf_new', $data, '../../modules/pbrf/admpanel/');
  $PHPShopGUI->addTab(array("Печатные бланки",$Tab5,270));




}

$addHandler=array(
        'actionStart'=>'startPbrf',
        'actionDelete'=>false,
        'actionUpdate'=>false
);

?>