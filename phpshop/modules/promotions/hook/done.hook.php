<?php
 
 
/**
 * Записываем заказ для модуля промо-акции
 * @param array $obj объект
 * @param array $data массив данных
 * @param string $rout роутер места вызовы модуля [START|MIDDLE|END]
 */
function promotions_write($obj, $data, $rout) {


    if($_SESSION['totalsumma']>0) {
        $sumn = $_SESSION['totalsumma'];
    }
    else {
        $sumn = $obj->sum;
    }

    if($_SESSION['freedelivery']==0) {
        $deliveryn = 0;
    }
    else {
        $deliveryn = $obj->delivery;
    }

    if($_SESSION['discpromo']!='') {
        $discn = $_SESSION['discpromo'];
        $discnreal = 0;
        $tip_disc = $_SESSION['tip_disc'];
    }
    else {
        $discnreal = $obj->discount;
    }

    if($_SESSION['promocode']!='') {
        $promocode = $_SESSION['promocode'];
    }
    else {
        $promocode = '';
    }

    //Если промокод одноразовый, то отключаем его
    if($_SESSION['codetip']==1) {
        $sqlpro = 'UPDATE `phpshop_modules_promotions_forms` SET `enabled` = "0" WHERE `code` ="'.$_SESSION['promocode'].'"';
        mysql_query($sqlpro);
    }

    // Данные покупателя // Старая логика
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
            "discount" => $discnreal,
            "user_id" => $obj->userId,
            "dos_ot" => PHPShopSecurity::TotalClean($_POST['dos_ot']),
            "dos_do" => PHPShopSecurity::TotalClean($_POST['dos_do']),
            "order_metod" => intval($_POST['order_metod']),
            "promocode" => $promocode,
            "discount_promo" => $discn,
            "tip_disc" => $tip_disc,
        );


        // Данные по корзине
        $cart = array(
            "cart" => $obj->PHPShopCart->getArray(),
            "num" => $obj->num,
            "sum" => $sumn,
            "weight" => $obj->weight,
            "dostavka" => $deliveryn);

        // Статус заказа
        $obj->status = array(
            "maneger" => "",
            "time" => "");

        // Серелиазованный массив заказа
        $obj->order = serialize(array("Cart" => $cart, "Person" => $person));

        //обнуляем сессию доставки
        //$_SESSION['freedelivery'] = 1;



}
 
function promotions_mail($obj,$data,$rout) {

    if($rout == 'MIDDLE') {
        if($_SESSION['promocode']=='*') {
            foreach ($_SESSION['cart'] as $value) {
                $sum = $value['price'] * $value['num'];
                if($value['discount']!='') {
                    if($value['discount_tip']!='%') {
                        $price_discount = $value['price'] - ($value['discount']);
                        $ted = ($value['discount']*$value['num']).' '.$value['discount_tip'];
                        $sumitog = $price_discount*$value['num'];
                    }
                    else {
                        $di = $value['discount']/100;
                        $price_discount = $value['price'] - ($sum*$di);
                        $ted = $value['discount'].$value['discount_tip'];
                        $sumitog = $price_discount*$value['num'];
                    }
                    $text_di = 'Со скидкой '.$ted.' ('.$value['num'].' шт. * '.$price_discount.'руб.<strike>'.$value['price'].'руб.</strike>)';
                    
                }
                else {
                    $text_di = 'Без скидки('.$value['num'].' шт. * '.$value['price'].'руб.)';
                    $sumitog = $value['num'] * $value['price'];
                }
                $cartlist .= $value['name'].' '.$text_di.' -- Всего '.$sumitog.' руб. <br>';
            }
            $obj->set('cart', $cartlist);
        }
        else {
            $obj->set('cart', $obj->PHPShopCart->display('mailcartforma', array('currency' => $obj->currency)));
        }
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
        $obj->set('payment', $obj->PHPShopPayment->getName());
        $obj->set('company', $obj->PHPShopSystem->getParam('name'));

        //тип скидки
        if($_SESSION['tip_disc']==1)
            $tip_disc = '%';
        else
            $tip_disc = 'руб.';

        //бесплатная доставка
        if($_SESSION['freedelivery']==1)
            $deliveryp = '0 руб.';
        else
            $deliveryp = $obj->delivery.' '.$obj->currency;

        $obj->set('promocode', $_SESSION['promocode']);
        $obj->set('totalsumma', $_SESSION['totalsumma']);
        $obj->set('tipdisc', $tip_disc);
        $obj->set('discpromo', $_SESSION['discpromo']);
        $obj->set('deliveryp', $deliveryp);

        // формируем список данных полей доставки.
        $obj->set('adresList', $obj->PHPShopDelivery->getAdresListFromOrderData($_POST, "\n"));

        // метки письма о заказе для старых версий системы.
        $obj->set('dos_ot', @$_POST['dos_ot']);
        $obj->set('dos_do', @$_POST['dos_do']);
        $obj->set('tel', @$_POST['tel_code'] . "-" . @$_POST['tel_name']);
        //если авторизован, имя берём из сессии, иначе из формы.
        if (!empty($_SESSION['UsersId']) and PHPShopSecurity::true_num($_SESSION['UsersId']))
            $obj->set('user_name', $_SESSION['UsersName']);
        elseif (!empty($_POST['name_new']))
            $obj->set('user_name', $_POST['name_new']);
        else
            $obj->set('user_name', $_POST['name_person']);

        // Дополнительная информация по заказу
        if (!empty($_POST['dop_info']))
            $obj->set('dop_info', $_POST['dop_info']);

        // Заголовок письма покупателю
        //$title = $obj->PHPShopSystem->getName() . $obj->lang('mail_title_user_start') . $_POST['ouid'] . $obj->lang('mail_title_user_end');
        $title = $obj->lang('mail_title_user_start') . $_POST['ouid'] . $obj->lang('mail_title_user_end');


        // Отсылаем письмо покупателю
        $PHPShopMail = new PHPShopMail($_POST['mail'], $obj->PHPShopSystem->getParam('adminmail2'), $title, '', true, true);
        if($_SESSION['promocode']=='*'):
            //если промо-код есть
            $content = ParseTemplateReturn('phpshop/modules/promotions/templates/order/np_usermail.tpl', true);
        elseif($_SESSION['promocode']!=''):
            //если промо-код есть
            $content = ParseTemplateReturn('phpshop/modules/promotions/templates/order/usermail.tpl', true);
        else:
            //базовый шаблон
            $content = ParseTemplateReturn('phpshop/lib/templates/order/usermail.tpl', true);
        endif;

        $PHPShopMail->sendMailNow($content);

        $obj->set('shop_admin', "http://" . $_SERVER['SERVER_NAME'] . $obj->getValue('dir.dir') . "/phpshop/admpanel/");
        $obj->set('time', date("d-m-y H:i a"));
        $obj->set('ip', $_SERVER['REMOTE_ADDR']);

        $title_adm = $obj->PHPShopSystem->getName() . ' - ' . $obj->lang('mail_title_adm') . $_POST['ouid'] . "/" . date("d-m-y");
        $title_adm = $obj->lang('mail_title_adm') . $_POST['ouid'] . "/" . date("d-m-y");

        // Отсылаем письмо администратору
        $PHPShopMail = new PHPShopMail($obj->PHPShopSystem->getParam('adminmail2'), $_POST['mail'], $title_adm, '', true, true);
        if($_SESSION['promocode']=='*'):
            //если промо-код есть
            $content_adm = ParseTemplateReturn('phpshop/modules/promotions/templates/order/np_adminmail.tpl', true);
        elseif($_SESSION['promocode']!=''):
            //если промо-код есть
            $content_adm = ParseTemplateReturn('phpshop/modules/promotions/templates/order/adminmail.tpl', true);
        else:
            //базовый шаблон
            $content_adm = ParseTemplateReturn('phpshop/lib/templates/order/adminmail.tpl', true);
        endif;

        $PHPShopMail->sendMailNow($content_adm);

        return true;
        
    }

    
}

$addHandler=array
        (
            'write' => 'promotions_write',
            'mail' => 'promotions_mail'
 
);
?>