<?php

// ���������� ������ � ������
include_once('../phpshop/class/mail.class.php');

// ����� ������ ��� ����-�������
function GetNumOrders($cid) {
    global $link_db;
    
    $sql="select uid from ".$GLOBALS['SysValue']['base']['table_name9']." where cid='$cid'";
    $result=mysqli_query($link_db,$sql);
    @$row = mysqli_fetch_array(@$result);
    return $row['uid'];
}

// ��������� ������������
function SendMailUser($id,$flag="accounts") {
    global $link_db;

    // ����-�������, ����� ������ ������
    if($flag == "invoice") $id = GetNumOrders($id);

    $sql="select * from ".$GLOBALS['SysValue']['base']['table_name1']." where id=".intval($id);
    $result=mysqli_query($link_db,$sql);
    @$row = mysqli_fetch_array(@$result);
    $order=unserialize($row['orders']);
    $mail=$order['Person']['mail'];
    $name=$order['Person']['name_person'];
    $uid=$row['uid'];
    $zag="������������� ��������� �� ������ �".$row['uid'];
    $from="robot@".str_replace("www.","",$_SERVER['SERVER_NAME']);
    $content="���������(��) ������������ ".$name.", �� ������ �".$uid." ����� �������� ������������� ��������� � ������ ��������.

�� ������ ��������� ������ ������, ��������� �����, ����������� ��������� 
��������� ��-���� ����� '������ �������' ��� �� ������ http://".$_SERVER['SERVER_NAME'].$GLOBALS['SysValue']['dir']['dir']."/users/
    
����: ".date("d-m-y H:s a")."
    
";

    // ����������� ���������
    new PHPShopMail($mail,$from,$zag,$content);
}
?>