<?php

/**
 * �������� �����
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("order");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("user");


// ���������� ���������� ��������� JsHttpRequest
if($_REQUEST['type'] != 'json'){
require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
}
else{
    $_REQUEST['okpoints']=PHPShopString::utf8_win1251($_REQUEST['okpoints']);
}

// ������� ��� ������
$PHPShopOrder = new PHPShopOrderFunction();
// ������
$PHPShopModules = new PHPShopModules($_classPath . "modules/");
// ��������� ���������
$PHPShopSystem = new PHPShopSystem();

$numc = 3; //��� ��������� ������� �������
foreach ($_SESSION['cart'] as $cartjs) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name2']);
    $products = $PHPShopOrm->select(array('*'), array('id' => '='.$cartjs['id']), array('order' => 'id desc'), array('limit' => 1));

    $pointscart[$cartjs['id']]['n'] = $numc;
    $pointscart[$cartjs['id']]['point'] = $products['point']*$cartjs['num'];

    if($products['check_pay']==1) {
        $sumIto += $cartjs['price']*$cartjs['num'];
    }
    $sum += $cartjs['price']*$cartjs['num'];
    $sumpoints += $products['point']*$cartjs['num'];

}

//������
$PHPShopUserBal = new PHPShopUser($_SESSION['UsersId']);
$pointBalance = $PHPShopUserBal->getParam('point');
if($pointBalance=='') {
    $pointBalance = 0;
    $success=0;
}
else {
    $success=1;
}
//����� ����� ������� ����� ������ �� �����
$PHPShopOrmRew = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
$system = $PHPShopOrmRew->select(array('*'), false, false, array('limit' => 1));
$percent = $system['percent'];

//���������� ����� ������� ����� ���������
$pointEqv = ($sumIto * ($percent*0.01) );

//���� �����
$PHPShopOrmValutaf = new PHPShopOrm($GLOBALS['SysValue']['base']['currency']);
$currency = $PHPShopOrmValutaf->select(array('*'));
foreach ($currency as $cur) {
	if($cur['kurs']==1) {
		$price_point = $cur['price_point'];
		$valutaCode = $cur['code'];
	}
}
$pointOk = round($pointEqv/$price_point);

if($_REQUEST['pointrat']<$pointOk) {
    $pointOk = $_REQUEST['pointrat'];
}

//���� ������ ������ ��� ����� ���������
if($pointBalance<$pointOk)
	$pointOk = $pointBalance;

//��������� ����� � �����
$sumitogb = $pointOk*$price_point;
//������� �������
$sumitog = $sum - $sumitogb;

if($_REQUEST['okpoints']==1) {
    if($success==1) {
    	$_SESSION['pointOk'] = $pointOk;
    	$_SESSION['sumitog'] = $sumitog;
    }
}
else {
	unset($_SESSION['pointOk']);
	unset($_SESSION['sumitog']);
}

// ���������
$_RESULT = array(
    'pointOk'=> $pointOk.' ���.',
    'sumitog' => $sumitog.' '.$valutaCode,
    'pointscart' => $pointscart,
    'sumpoints' => $sumpoints,
    'success' => $success
);


// JSON 
if($_REQUEST['type'] == 'json') {
    $_RESULT['pointOk']=PHPShopString::win_utf8($_RESULT['pointOk']);
    $_RESULT['sumitog']=PHPShopString::win_utf8($_RESULT['sumitog']);
}
    echo json_encode($_RESULT);
?>