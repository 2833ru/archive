<?php

/**
 * ���������� ����� ��� ������ �������� �����
 * @param array $obj ������
 * @param array $data ������ ������
 * @param string $rout ������ ����� ������ ������ [START|MIDDLE|END]
 */
function rewardpoints_write($obj, $data, $rout) {
    global $PHPShopModules;

    if ($PHPShopModules->checkKeyBase('rewardpoints'))
        return false;

    if ($_SESSION['UsersId'] != '') {
        if ($rout == 'MIDDLE') {
            //���� �������� �� �����
            if ($_SESSION['pointOk'] != '') {

                // ������ ���������� // ������ ������
                $person = array(
                    "ouid" => $obj->ouid,
                    "data" => date("U"),
                    "time" => date("H:s a"),
                    "mail" => PHPShopSecurity::TotalClean($_POST['mail'], 3),
                    "name_person" => PHPShopSecurity::TotalClean($_POST['name_person']),
                    "org_name" => PHPShopSecurity::TotalClean($_POST['org_name']),
                    "org_inn" => PHPShopSecurity::TotalClean($_POST['org_inn']),
                    "org_kpp" => PHPShopSecurity::TotalClean($_POST['org_kpp']),
                    "tel_code" => PHPShopSecurity::TotalClean($_POST['tel_code']),
                    "tel_name" => PHPShopSecurity::TotalClean($_POST['tel_name']),
                    "adr_name" => PHPShopSecurity::TotalClean($_POST['adr_name']),
                    "dostavka_metod" => intval($_POST['dostavka_metod']),
                    "discount" => 0,
                    "user_id" => $obj->userId,
                    "dos_ot" => PHPShopSecurity::TotalClean($_POST['dos_ot']),
                    "dos_do" => PHPShopSecurity::TotalClean($_POST['dos_do']),
                    "order_metod" => intval($_POST['order_metod']),
                    "pointOk" => $_SESSION['pointOk']
                );

                // ������ �� �������
                $cart = array(
                    "cart" => $obj->PHPShopCart->getArray(),
                    "num" => $obj->num,
                    "sum" => $_SESSION['sumitog'],
                    "weight" => $obj->weight,
                    "dostavka" => 0);

                // ������ ������
                $obj->status = array(
                    "maneger" => "",
                    "time" => "");

                // ��������������� ������ ������
                $obj->order = serialize(array("Cart" => $cart, "Person" => $person));



                //////�������� �� ���� ������///////////////////////////////////////////
                //���-�� ������
                $obj->PHPShopUserBB = new PHPShopUser($_SESSION['UsersId']);
                $balanceP = $obj->PHPShopUserBB->getParam('point');
                if ($balanceP == '')
                    $balanceP = 0;

                $pointAll = $_SESSION['pointOk'];

                //������ �� ������ �������
                $pointResult = $balanceP - $pointAll;

                //���������
                $titleMailShopuser = "��� ����� �" . $obj->ouid . " ������� ��������";
                $titleInsert = "�������� �� ����� " . $pointAll . " ���. - �� ������ �" . $obj->ouid;
                //$titleMailShopuser = "�������� �� ����� ".$pointAll." ���. - �� ������ �".$obj->ouid;
                $titleMailAdmin = "������������ " . $_POST['mail'] . " ����������� " . $pointAll . " ���. �� ������ �" . $obj->ouid;
                $obj->set('points', $pointAll);
                $obj->set('balancePoints', $pointResult);

                if ($_SESSION['sumitogBase'] != '') {
                    $discountPoint = $_SESSION['sumitogBase'] - $_SESSION['sumitog'];
                    $obj->set('discountPoint', $discountPoint);
                    $obj->set('sumitogBase', $_SESSION['sumitog']);
                    $obj->set('sumP', $_SESSION['sumitogBase']);
                }

                //������� ����� ������������ � ������� ����������
                mysql_query("UPDATE `phpshop_shopusers` SET `point` = '" . $pointResult . "' WHERE `id`=" . $_SESSION['UsersId']);
                mysql_query("INSERT INTO `phpshop_modules_rewardpoints_users_transaction` (
                `id_users` , `operation` , `date` , `number_points` , `balance_points` , `id_order` , `sum_orders` , `type` , `comment_admin`, `confirmation`)
                VALUES ('" . $_SESSION['UsersId'] . "',  '0', CURRENT_TIMESTAMP ,  '" . $pointAll . "',  '" . $pointResult . "',  '" . $obj->ouid . "',  '������� �� �����',  '0',  '" . $titleInsert . "', '1')");

                // �������� ������ ���������� � ������
                $PHPShopMail = new PHPShopMail($_POST['mail'], $obj->PHPShopSystem->getParam('adminmail2'), $titleMailShopuser, '', true, true);
                $content = ParseTemplateReturn('phpshop/modules/rewardpoints/templates/order/usermailpoints.tpl', true);
                $PHPShopMail->sendMailNow($content);

                // �������� ������ �������������� � ������
                //$PHPShopMail = new PHPShopMail($obj->PHPShopSystem->getParam('adminmail2'), $_POST['mail'], $titleMailAdmin , '', true, true);
                //$content_adm = ParseTemplateReturn('phpshop/modules/rewardpoints/templates/order/adminmailpoints.tpl', true);
                //$PHPShopMail->sendMailNow($content_adm);
                //������� ������
                unset($_SESSION['pointOk']);
                unset($_SESSION['sumitog']);
            } else {
                //���-�� ������
                $obj->PHPShopUserBB = new PHPShopUser($_SESSION['UsersId']);
                $balanceP = $obj->PHPShopUserBB->getParam('point');

                //������ ������� ������� � �������
                foreach ($obj->PHPShopCart->getArray() as $key => $value) {
                    $sql = 'SELECT * FROM phpshop_products WHERE id=' . $value['id'];
                    $ro = mysql_query($sql);
                    $row = mysql_fetch_array($ro);

                    //���� �����
                    $PHPShopOrmValutaf = new PHPShopOrm($GLOBALS['SysValue']['base']['currency']);
                    $currency = $PHPShopOrmValutaf->select(array('*'));
                    foreach ($currency as $cur) {
                        if ($cur['kurs'] == 1)
                            $price_point = $cur['price_point'];
                    }
                    //��������� ������
                    $PHPShopOrmRew = new PHPShopOrm($PHPShopModules->getParam("base.rewardpoints.rewardpoints_system"));
                    $system = $PHPShopOrmRew->select(array('*'), false, false, array('limit' => 1));
                    $percent_add = $system['percent_add'] / 100;
                    //�����������
                    $pointsAccrued = round(($row['price'] * $percent_add) / $price_point);
                    //�������� �� ������ ���� 
                    if ($pointsAccrued < $row['point'])
                        $pointsAccrued = $row['point'];

                    if ($pointsAccrued != 0) {
                        $point = $pointsAccrued * $value['num'];
                        $pointAll = $pointAll + $point;
                    }
                }


                if ($pointAll != '') {
                    //������ �� ������ �������
                    $pointResult = $balanceP;

                    //���������
                    $titleInsert = "���������� �� ���� " . $pointAll . " ���. - �� ������ �" . $obj->ouid;
                    $titleMailShopuser = "���������� �� ���� " . $pointAll . " ���. - �� ������ �" . $obj->ouid;
                    $titleMailAdmin = "������������ " . $_POST['mail'] . " ������� " . $pointAll . " ���. �� ������ �" . $obj->ouid;
                    $obj->set('points', $pointAll);

                    //������� ����� ������������ � ������� ����������
                    //mysql_query("UPDATE `phpshop_shopusers` SET `point` = '".$pointResult."' WHERE `id`=".$_SESSION['UsersId']);
                    mysql_query("INSERT INTO `phpshop_modules_rewardpoints_users_transaction` (
                    `id_users` , `operation` , `date` , `number_points` , `balance_points` , `id_order` , `sum_orders` , `type` , `comment_admin`)
                    VALUES ('" . $_SESSION['UsersId'] . "',  '1', CURRENT_TIMESTAMP ,  '" . $pointAll . "',  '" . $pointResult . "',  '" . $obj->ouid . "',  '" . $obj->sum . "',  '0',  '" . $titleInsert . "')");

                    // �������� ������ ���������� � ������
                    //$PHPShopMail = new PHPShopMail($_POST['mail'], $obj->PHPShopSystem->getParam('adminmail2'), $titleMailShopuser, '', true, true);
                    //$content = ParseTemplateReturn('phpshop/modules/rewardpoints/templates/order/usermailpoints.tpl', true);
                    //$PHPShopMail->sendMailNow($content);
                    // �������� ������ �������������� � ������
                    //$PHPShopMail = new PHPShopMail($obj->PHPShopSystem->getParam('adminmail2'), $_POST['mail'], $titleMailAdmin , '', true, true);
                    //$content_adm = ParseTemplateReturn('phpshop/modules/rewardpoints/templates/order/adminmailpoints.tpl', true);
                    //$PHPShopMail->sendMailNow($content_adm);
                }
            }
        }
    }
}

function rewardpoints_send_to_order($obj, $data, $rout) {
    if ($_SESSION['sumitog'] != '') {
        $_SESSION['sumitogBase'] = $GLOBALS['SysValue']['other']['total'];
        $GLOBALS['SysValue']['other']['total'] = $_SESSION['sumitog'];
    }
}

function rewardpoints_mail($obj, $data, $rout) {

    if ($_SESSION['pointOk'] != '') {
        $obj->set('cart', $obj->PHPShopCart->display('mailcartforma', array('currency' => $obj->currency)));
        $obj->set('sum', $obj->sum);
        $obj->set('currency', $obj->currency);
        $obj->set('discount', $obj->discount);
        $obj->set('deliveryPrice', $obj->delivery);
        $obj->set('total', $obj->total);
        $obj->set('shop_name', $obj->PHPShopSystem->getName());
        $obj->set('ouid', $obj->ouid);
        $obj->set('date', date("d-m-y"));
        $obj->set('adr_name', PHPShopSecurity::CleanStr(@$_POST['adr_name']));
        $obj->set('deliveryCity', $obj->PHPShopDelivery->getCity());
        $obj->set('mail', $_POST['mail']);

        if ($obj->PHPShopPayment)
            $obj->set('payment', $obj->PHPShopPayment->getName());

        $obj->set('company', $obj->PHPShopSystem->getParam('name'));

        // ��������� ������ ������ ����� ��������.
        $obj->set('adresList', $obj->PHPShopDelivery->getAdresListFromOrderData($_POST, "\n"));

        // ����� ������ � ������ ��� ������ ������ �������.
        $obj->set('dos_ot', @$_POST['dos_ot']);
        $obj->set('dos_do', @$_POST['dos_do']);
        $obj->set('tel', @$_POST['tel_code'] . "-" . @$_POST['tel_name']);
        //���� �����������, ��� ���� �� ������, ����� �� �����.
        if (!empty($_SESSION['UsersId']) and PHPShopSecurity::true_num($_SESSION['UsersId']))
            $obj->set('user_name', $_SESSION['UsersName']);
        elseif (!empty($_POST['name_new']))
            $obj->set('user_name', $_POST['name_new']);
        else
            $obj->set('user_name', $_POST['name_person']);

        // �������������� ���������� �� ������
        if (!empty($_POST['dop_info']))
            $obj->set('dop_info', $_POST['dop_info']);

        //$PHPShopMail->sendMailNow($content);
        $obj->set('shop_admin', "http://" . $_SERVER['SERVER_NAME'] . $obj->getValue('dir.dir') . "/phpshop/admpanel/");
        $obj->set('time', date("d-m-y H:i a"));
        $obj->set('ip', $_SERVER['REMOTE_ADDR']);
        $title_adm = $obj->lang('mail_title_adm') . $_POST['ouid'] . "/" . date("d-m-y");

        // �������� ������ ��������������
        $PHPShopMail = new PHPShopMail($obj->PHPShopSystem->getParam('adminmail2'), $_POST['mail'], $title_adm, '', true, true);
        $content_adm = ParseTemplateReturn('phpshop/lib/templates/order/adminmail.tpl', true);
        $PHPShopMail->sendMailNow($content_adm);

        return true;
    }
}

$addHandler = array
    (
    'write' => 'rewardpoints_write',
    'send_to_order' => 'rewardpoints_send_to_order',
    'mail' => 'rewardpoints_mail'
);
?>