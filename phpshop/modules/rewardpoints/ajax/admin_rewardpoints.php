<?php

/**
 * ����������
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
    $_REQUEST['rewardpoints']=PHPShopString::utf8_win1251($_REQUEST['rewardpoints']);
    $_REQUEST['operation']=PHPShopString::utf8_win1251($_REQUEST['operation']);
    $_REQUEST['point']=PHPShopString::utf8_win1251($_REQUEST['point']);
    $_REQUEST['comment_admin']=PHPShopString::utf8_win1251($_REQUEST['comment_admin']);
    $_REQUEST['id_users']=PHPShopString::utf8_win1251($_REQUEST['id_users']);
    $_REQUEST['mail_users']=PHPShopString::utf8_win1251($_REQUEST['mail_users']);
}

$success = 1;

// ������� ��� ������
$PHPShopOrder = new PHPShopOrderFunction();
// ������
$PHPShopModules = new PHPShopModules($_classPath . "modules/");
// ��������� ���������
$PHPShopSystem = new PHPShopSystem();

//������
$PHPShopUserBala = new PHPShopUser($_REQUEST['id_users']);
$pointBalance = $PHPShopUserBala->getParam('point');
if($pointBalance=='') {
    $pointBalance = 0;
}
//���� ����������
if($_REQUEST['operation']==1)
    $pointResult = $pointBalance+$_REQUEST['point'];
//���� ��������
if($_REQUEST['operation']==0) {
    $pointResult = $pointBalance-$_REQUEST['point'];
    if($pointBalance==0) {
        //������ �� ������
        $falseInsert = 1;
        $success = 0;
    }
}

if($falseInsert!=1) {
    //������� ������
    mysql_query("INSERT INTO `phpshop_modules_rewardpoints_users_transaction` (
    `id_users` , `operation` , `date` , `number_points` , `balance_points` , `id_order` , `sum_orders` , `type` , `comment_admin`, `confirmation`)
    VALUES ('".$_REQUEST['id_users']."',  '".$_REQUEST['operation']."', CURRENT_TIMESTAMP ,  '".$_REQUEST['point']."',  '".$pointResult."',  '',  '',  '1',  '<b>�������������:</b> ".$_REQUEST['comment_admin']."', '1')");
    //������� ������
    mysql_query("UPDATE `phpshop_shopusers` SET `point` = '".$pointResult."' WHERE `id`=".$_REQUEST['id_users']);


    //�������� �����
    //���� ����������
    if($_REQUEST['operation']==1)
        $titleMailShopuser = "���������� �� ���� ".$_REQUEST['point']." ���. �������������� ��������";
    //���� ��������
    if($_REQUEST['operation']==0)
        $titleMailShopuser = "�������� �� ����� ".$_REQUEST['point']." ���. �������������� ��������";

    $PHPShopMail = new PHPShopMail($_REQUEST['mail_users'], $PHPShopSystem->getParam('adminmail2'), $titleMailShopuser, '', true, true);
    $content =  $titleMailShopuser.". ����������� ��������������: ".$_REQUEST['comment_admin'];
    $PHPShopMail->sendMailNow($content);
}

// ���������
$_RESULT = array(
    'success' => $success
);


// JSON 
if($_REQUEST['type'] == 'json') {
    //$_RESULT['pointBalance']=PHPShopString::win_utf8($_RESULT['pointBalance']);
}
    echo json_encode($_RESULT);
?>