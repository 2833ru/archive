<?php

/**
 * Авто списание баллов
 * Modules Бонусные баллы
 */
// Включение
$enabled = false;

// Авторизация
if (empty($enabled))
    exit("Ошибка авторизации!");

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

//Настройки модуля
$select = "SELECT * FROM `phpshop_modules_rewardpoints_system`";
$sql = mysql_query($select);
$sys = mysql_fetch_array($sql);
$daysSys = $sys['days'];
//За последние 10 дней
$daysInterval = $sys['daysInterval'];
$days = $daysSys - $daysInterval;

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
            //Подсчет баллов не считая последних 90 дней
            if($srok_transaction['operation']==1) {
                //Если начисление узнаем сколько
                $sumPoints = $sumPoints  + $srok_transaction['number_points'];
            }
            if($srok_transaction['operation']==0) {
                //Если списание узнаем сколько
                $sumPoints = $sumPoints - $srok_transaction['number_points'];
            }
        }
        while ($srok_transaction = mysql_fetch_array($que));

        $dateIn = date('d.m.Y', strtotime("+".$daysInterval." day"));
    }
    //Информация о списаниях
    if($sumPoints>0) {
        if($pointBalance>=$sumPoints) {
            //Отправка почты
            $subject = $dateIn.' (через '.$daysInterval.' дн.) с вашего счета будет списано '.$sumPoints.' бал.';
            $message = $dateIn.' (через '.$daysInterval.' дн.) с вашего счета будет списано '.$sumPoints.' бал. Успейте потратить их!';
            mail($row['mail'], $subject, $message, "From: robots@".$_SERVER['HTTP_HOST']."\r\n");
        }
    }
}
?>