<?php

if (!defined("OBJENABLED"))
    exit(header('Location: /?error=OBJENABLED'));


class AddToTemplateRewElement extends PHPShopElements {

    var $debug = false;

    function display() {
        global $PHPShopModules,$PHPShopUser;

        $this->set('messagePointsComStart', '<!--');
        $this->set('messagePointsComEnd', '-->');
        

        if($_SESSION['UsersId']!='') {
            //Вызов данных пользователя через класс
            $obj->PHPShopUserMin = new PHPShopUser($_SESSION['UsersId']);
            $balancePoint = $obj->PHPShopUserMin->getParam('point');

            //Текущий баланс баллов
            if($balancePoint=='')
                    $balancePoint = 0;
            $this->set('pointsBalance', $balancePoint);


            ///////////сообщение вверх о баллах///////////////////
            //Вызов данных пользователя через класс

            //Срок использования
            $PHPShopSys = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
            $sys = $PHPShopSys->select(array('*'), false);
            $daysSys = $sys['days'];
            //За последние 10 дней
            $daysInterval = $sys['daysInterval'];
            $days = $daysSys - $daysInterval;
            if($days!='') {
                $select = "SELECT * FROM `phpshop_modules_rewardpoints_users_transaction` WHERE `id_users`='".$_SESSION['UsersId']."' and `confirmation`='1' and `cron`='0' and (date < NOW() - INTERVAL ".$days." DAY)";
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
                //Узнаем остаток
                //echo $sumPoints;
                //Через 10 дней
                $dateIn = date('d.m.Y', strtotime("+".$daysInterval." day"));

            }

            //Информация о списаниях
            if($sumPoints>0) {
                if($balancePoint>=$sumPoints) {
                    if($_SESSION['messagesNoView']!=1) {
                        $this->set('messagePoints', 'Через <b>'.$daysInterval.' дн.</b> с вашего счета будет списано '.$sumPoints.' бал. Успейте воспользоваться ими!');
                        $this->set('messagePointsComStart', '');
                        $this->set('messagePointsComEnd', '');

                        $script = "<script>
                            $(document).ready(function() {
                                $('.message-points').show('slow');
                                $('.close-m').click(function() {
                                    $( '.message-points' ).animate({
                                        height: '0px'
                                      }, 250, function() {
                                        $('.message-points').hide();
                                      });
                                    $.ajax({
                                        url: 'phpshop/modules/rewardpoints/ajax/message-point.php',
                                        type: 'post',
                                        data: 'messages=1&type=json',
                                        dataType: 'json',
                                        success: function(json) {
                                        }
                                    });
                                });
                            });
                        </script>";
                        $this->set('scriptModulesRewardpoints', $script);
                    }
                }
            }
        }

    }

}

$AddToTemplateRewElement = new AddToTemplateRewElement();
$AddToTemplateRewElement->display();
?>