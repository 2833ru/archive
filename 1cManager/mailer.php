<?php

// Библиотека работы с почтой
include_once('../phpshop/class/mail.class.php');

// Номер заказа для счет-фактуры
function GetNumOrders($cid) {
    global $link_db;
    
    $sql="select uid from ".$GLOBALS['SysValue']['base']['table_name9']." where cid='$cid'";
    $result=mysqli_query($link_db,$sql);
    @$row = mysqli_fetch_array(@$result);
    return $row['uid'];
}

// Сообщение пользователю
function SendMailUser($id,$flag="accounts") {
    global $link_db;

    // Счет-фактура, поиск номера заказа
    if($flag == "invoice") $id = GetNumOrders($id);

    $sql="select * from ".$GLOBALS['SysValue']['base']['table_name1']." where id=".intval($id);
    $result=mysqli_query($link_db,$sql);
    @$row = mysqli_fetch_array(@$result);
    $order=unserialize($row['orders']);
    $mail=$order['Person']['mail'];
    $name=$order['Person']['name_person'];
    $uid=$row['uid'];

    $PHPShopSystem = new PHPShopSystem();
    
    $content="Уважаемый(ая) пользователь ".$name.", по заказу №".$uid." стали доступны бухгалтерские документы в личном кабинете.

Вы можете проверить статус заказа, загрузить файлы, распечатать платежные 
документы он-лайн через 'Личный кабинет' или по ссылке http://".$_SERVER['SERVER_NAME'].$GLOBALS['SysValue']['dir']['dir']."/users/
    
Дата: ".date("d-m-y H:s a")."
    
";

    // Отправление сообщения
    $PHPShopMail = new PHPShopMail($mail,$PHPShopSystem->getParam('adminmail2'),"Бухгалтерские документы по заказу №".$row['uid'],'', 'text/plain', true, false);
    $PHPShopMail->sendMailNow($content);
}
?>