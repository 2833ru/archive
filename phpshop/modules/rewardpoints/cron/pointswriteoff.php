<?php

/**
 * ���� �������� ������
 * Modules �������� �����
 */
// ���������
$enabled = false;

// �����������
if (empty($enabled))
    exit("������ �����������!");

$_classPath = "../../../";
$SysValue = parse_ini_file($_classPath . "inc/config.ini", 1);


// MySQL hostname
$host = $SysValue['connect']['host'];
//MySQL basename
$dbname = $SysValue['connect']['dbase'];
// MySQL user
$uname = $SysValue['connect']['user_db'];
// MySQL password
$upass = $SysValue['connect']['pass_db'];

$con = @mysql_connect($host, $uname, $upass) or die("Could not connect");
$db = @mysql_select_db($dbname, $con) or die("Could not select db");

//��������� ������
$select = "SELECT * FROM `phpshop_modules_rewardpoints_system`";
$sql = mysql_query($select);
$sys = mysql_fetch_array($sql);
//������ ��������
$days = $sys['days'];

$sql = 'select * from `phpshop_shopusers`';
$result = mysql_query($sql);
while (@$row = mysql_fetch_array(@$result)) {
    $sumPoints = 0;
    $pointBalance = $row['point'];
    $id_user = $row['id'];
    if($days!='0') {
        $select = "SELECT * FROM `phpshop_modules_rewardpoints_users_transaction` WHERE `id_users`='".$id_user."' and `confirmation`='1' and `cron`='0' and (date < NOW() - INTERVAL ".$days." DAY)";
        $que = mysql_query($select);
        $srok_transaction = mysql_fetch_array($que);
        do {
            //echo $srok_transaction['date'].($srok_transaction['operation']==1 ? '+'.$srok_transaction['number_points'] : '-'.$srok_transaction['number_points']).' ('.$srok_transaction['confirmation'].')<br>';
            //������� ������ �� ������ ��������� 90 ����
            if($srok_transaction['operation']==1) {
                //���� ���������� ������ �������
                $sumPoints = $sumPoints  + $srok_transaction['number_points'];
            }
            if($srok_transaction['operation']==0) {
                //���� �������� ������ �������
                $sumPoints = $sumPoints - $srok_transaction['number_points'];
            }
            //ID ������� �� ��������� cron
            $id_cron[] = $srok_transaction['id'];
        }
        while ($srok_transaction = mysql_fetch_array($que));
    }
    //���� ���� ��� ���������
    if($sumPoints>0) {
        //���� ������ ������ ��� ����� ��������
        if($pointBalance>=$sumPoints) {
            //������� ������ ��� cron ��� ������ ��
            foreach ($id_cron as $cro) {
                $where_cron .= ' OR id="'.$cro.'"';
            }
            mysql_query("UPDATE `phpshop_modules_rewardpoints_users_transaction` SET `cron` = '1' WHERE id=0 ".$where_cron);
            //���������� ��������
            $bal_upd = $pointBalance - $sumPoints;
            $titleInsert = '�������� �� ��������� ����� ������������� '.$days.' ��.)';
            //������� �� �����
            mysql_query("UPDATE `phpshop_shopusers` SET `point` = '".$bal_upd."' WHERE `id`=".$id_user);
            //��������� ��������
            mysql_query("INSERT INTO `phpshop_modules_rewardpoints_users_transaction` (
                    `id_users` , `operation` , `date` , `number_points` , `balance_points` , `id_order` , `sum_orders` , `type` , `comment_admin`, `confirmation`)
                    VALUES ('".$id_user."',  '0', CURRENT_TIMESTAMP ,  '".$sumPoints."',  '".$bal_upd."',  '',  '',  '2', '<b>�����: </b>".$titleInsert."', '1')");
            //�������� �����
            $subject = '�������� �� ����� '.$sumPoints.' ���. (������� ���� ������������� '.$days.' ��.)';
            $message = '����������� �������� �� ����� '.$sumPoints.' ���. �� ������� ��������� ����� ������������� '.$days.' ��.';
            mail($row['mail'], $subject, $message, "From: robots@".$_SERVER['HTTP_HOST']."\r\n");
        }
    }
}
?>