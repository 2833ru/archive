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
//Период списания
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
            //Подсчет баллов не считая последних 90 дней
            if($srok_transaction['operation']==1) {
                //Если начисление узнаем сколько
                $sumPoints = $sumPoints  + $srok_transaction['number_points'];
            }
            if($srok_transaction['operation']==0) {
                //Если списание узнаем сколько
                $sumPoints = $sumPoints - $srok_transaction['number_points'];
            }
            //ID записей на обработку cron
            $id_cron[] = $srok_transaction['id'];
        }
        while ($srok_transaction = mysql_fetch_array($que));
    }
    //Если есть что списывать
    if($sumPoints>0) {
        //Если баланс больше чем сумма списания
        if($pointBalance>=$sumPoints) {
            //Обновим статус для cron что обошли их
            foreach ($id_cron as $cro) {
                $where_cron .= ' OR id="'.$cro.'"';
            }
            mysql_query("UPDATE `phpshop_modules_rewardpoints_users_transaction` SET `cron` = '1' WHERE id=0 ".$where_cron);
            //Производим списание
            $bal_upd = $pointBalance - $sumPoints;
            $titleInsert = 'Списание по окончанию срока использования '.$days.' дн.)';
            //Удаляем со счета
            mysql_query("UPDATE `phpshop_shopusers` SET `point` = '".$bal_upd."' WHERE `id`=".$id_user);
            //Добавляем списание
            mysql_query("INSERT INTO `phpshop_modules_rewardpoints_users_transaction` (
                    `id_users` , `operation` , `date` , `number_points` , `balance_points` , `id_order` , `sum_orders` , `type` , `comment_admin`, `confirmation`)
                    VALUES ('".$id_user."',  '0', CURRENT_TIMESTAMP ,  '".$sumPoints."',  '".$bal_upd."',  '',  '',  '2', '<b>Робот: </b>".$titleInsert."', '1')");
            //Отправка почты
            $subject = 'Списание со счета '.$sumPoints.' бал. (Окончен срок использования '.$days.' дн.)';
            $message = 'Произведено списание со счета '.$sumPoints.' бал. по причине окончания срока использования '.$days.' дн.';
            mail($row['mail'], $subject, $message, "From: robots@".$_SERVER['HTTP_HOST']."\r\n");
        }
    }
}
?>