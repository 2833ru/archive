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
            //����� ������ ������������ ����� �����
            $obj->PHPShopUserMin = new PHPShopUser($_SESSION['UsersId']);
            $balancePoint = $obj->PHPShopUserMin->getParam('point');

            //������� ������ ������
            if($balancePoint=='')
                    $balancePoint = 0;
            $this->set('pointsBalance', $balancePoint);


            ///////////��������� ����� � ������///////////////////
            //����� ������ ������������ ����� �����

            //���� �������������
            $PHPShopSys = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
            $sys = $PHPShopSys->select(array('*'), false);
            $daysSys = $sys['days'];
            //�� ��������� 10 ����
            $daysInterval = $sys['daysInterval'];
            $days = $daysSys - $daysInterval;
            if($days!='') {
                $select = "SELECT * FROM `phpshop_modules_rewardpoints_users_transaction` WHERE `id_users`='".$_SESSION['UsersId']."' and `confirmation`='1' and `cron`='0' and (date < NOW() - INTERVAL ".$days." DAY)";
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
                }
                while ($srok_transaction = mysql_fetch_array($que));
                //������ �������
                //echo $sumPoints;
                //����� 10 ����
                $dateIn = date('d.m.Y', strtotime("+".$daysInterval." day"));

            }

            //���������� � ���������
            if($sumPoints>0) {
                if($balancePoint>=$sumPoints) {
                    if($_SESSION['messagesNoView']!=1) {
                        $this->set('messagePoints', '����� <b>'.$daysInterval.' ��.</b> � ������ ����� ����� ������� '.$sumPoints.' ���. ������� ��������������� ���!');
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