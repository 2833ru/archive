<?php

/**
 * Бонусные баллы
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

// Подключаем библиотеку поддержки JsHttpRequest
if($_REQUEST['type'] != 'json'){
require_once $_classPath . "/lib/Subsys/JsHttpRequest/Php.php";
$JsHttpRequest = new Subsys_JsHttpRequest_Php("windows-1251");
}
else{
    $_REQUEST['rewardpoints']=PHPShopString::utf8_win1251($_REQUEST['rewardpoints']);
}

// Функции для заказа
$PHPShopOrder = new PHPShopOrderFunction();
// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");
// Системные настройки
$PHPShopSystem = new PHPShopSystem();



//Баланс
$PHPShopUserBal = new PHPShopUser($_SESSION['UsersId']);
$pointBalance = $PHPShopUserBal->getParam('point');
if($pointBalance=='') {
    $pointBalance = 0;
}

$pointBalance = $pointBalance.' бал.';

//Какую часть покупки можно купить за баллы
$PHPShopOrmRew = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
$system = $PHPShopOrmRew->select(array('*'), false, false, array('limit' => 1));
$percent = $system['percent'];
$percent_add = $system['percent_add']/100;

//Эквивалент суммы которую можно портатить
$pointEqv = round($sum * ($percent*0.01) );

//Курс балла
$PHPShopOrmValutaf = new PHPShopOrm($GLOBALS['SysValue']['base']['currency']);
$currency = $PHPShopOrmValutaf->select(array('*'));
foreach ($currency as $cur) {
	if($cur['kurs']==1) {
		$price_point = $cur['price_point'];
		$valutaCode = $cur['code'];
	}
}

//соберем массив скидок для JS
$numc = 3; //для пересчета таблицы корзины
foreach ($_SESSION['cart'] as $cartjs) {
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name2']);
    $products = $PHPShopOrm->select(array('*'), array('id' => '='.$cartjs['id']), array('order' => 'id desc'), array('limit' => 1));



    //Начисленные
    $pointsAccrued = round( ($products['price']*$percent_add)/$price_point );
    //Проверка на ручной ввод 
    if($pointsAccrued<$products['point'])
        $pointsAccrued = $products['point'];

    $pointscart[$cartjs['id']]['n'] = $numc;
    $pointscart[$cartjs['id']]['point'] = $pointsAccrued*$cartjs['num'];

    if($products['check_pay']==1) {
        $sum += $cartjs['price']*$cartjs['num'];
    }
    $sumpoints += $pointsAccrued*$cartjs['num'];
    
}

$pointOk = round($pointEqv/$price_point);
$pointEqv = $pointOk*$cur['price_point'];

// Результат
$_RESULT = array(
    'pointscart' => $pointscart,
    'pointOkNo'=> $pointOk,
    'pointBalance'=> $pointBalance,
    'pointOk'=> $pointOk.' бал.',
    'sumpoints' => $sumpoints,
    'pointEqv' => $pointEqv.' '.$valutaCode,
    'success' => 1
);


// JSON 
if($_REQUEST['type'] == 'json') {
    $_RESULT['pointBalance']=PHPShopString::win_utf8($_RESULT['pointBalance']);
    $_RESULT['pointOk']=PHPShopString::win_utf8($_RESULT['pointOk']);
    $_RESULT['pointEqv']=PHPShopString::win_utf8($_RESULT['pointEqv']);
}
    echo json_encode($_RESULT);
?>