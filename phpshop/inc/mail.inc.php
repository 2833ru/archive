<?php
/**
 * Вывод типа оплаты
 * @package PHPShopDepricated
 * @param int $tip ИД оплаты
 * @return string
 */
function OplataMetod($tip) {
    $GetPathOrdermetod=GetPathOrdermetod($tip);
    return $GetPathOrdermetod['name'];
}

// Проверка заполнения данных
if(PHPShopSecurity::true_param($_POST['send_to_order'],$_POST['mail'],$_POST['name_person'],$_POST['tel_name'],$_POST['adr_name'])) {

    $cart=$_SESSION['cart'];
    $disCart=null;
    $sum=null;
    $num=null;

    // Список товоров в корзине
    if(is_array($cart))
        foreach($cart as $j=>$i) {
            $disCart.=$cart[$j]['uid']."  ".$cart[$j]['name']." (".$cart[$j]['num']." шт. * ".ReturnSummaNal($cart[$j]['price'],0).") -- ".ReturnSummaNal($cart[$j]['price']*$cart[$j]['num'],0)." ".GetValutaOrder()."
";
            $sum+=$cart[$j]['price']*$cart[$j]['num'];
            $num+=$cart[$j]['num'];

            // Определение и суммирование веса
            $goodid=$cart[$j]['id'];
            $goodnum=$cart[$j]['num'];
            $wsql='select weight from '.$SysValue['base']['table_name2'].' where id=\''.$goodid.'\'';
            $wresult=mysql_query($wsql);
            $wrow=mysql_fetch_array($wresult);
            $cweight=$wrow['weight']*$goodnum;
            if (!$cweight) {
                $zeroweight=1;
            } //Один из товаров имеет нулевой вес!
            $weight+=$cweight;
        }

    // Обнуляем вес товаров, если хотя бы один товар был без веса
    if ($zeroweight) {
        $weight=0;
    }


    $sum=number_format($sum,"2",".","");
    $ChekDiscount=ChekDiscount($sum);
    $GetDeliveryPrice=GetDeliveryPrice($_POST['d'],$sum,$weight);
    $disCart.="
-------------------------------------------------------
Итого -- ".ReturnSummaNal($sum,0)." ".GetValutaOrder()."
Скидка -- ".$ChekDiscount[0]."%
Доставка -- ".$GetDeliveryPrice." ".GetValutaOrder()."
-------------------------------------------------------
К оплате с учетом скидки: ".(GetPriceOrder($ChekDiscount[1])+$GetDeliveryPrice)." ".GetValutaOrder();

    $content="Доброго времени!
--------------------------------------------------------
Спасибо за покупку в нашем интернет-магазине '".$PHPShopSystem->getName()."'
Наши менеджеры свяжутся с вами по координатам, 
оставленным в форме заказа.


Подробности заказа № ".$_POST['ouid']." от ".date("d-m-y")."
--------------------------------------------------------
Контактное лицо: ".$_POST['name_person'];

    if(!empty($_POST['org_name'])) {
        $content.="
Компания: ".$_POST['org_name']."
ИНН: ".$_POST['org_inn']."
КПП: ".$_POST['org_kpp'];
    }

    $content.="
Телефон: ".$_POST['tel_code']."-".$_POST['tel_name']."
Адрес и доп. инф: ".$_POST['adr_name']."
Желаемое время доставки: ".$_POST['dos_ot']." - ".$_POST['dos_do']."
Грузополучатель: ".GetDeliveryBase($_POST['dostavka_metod'],'city')."
E-mail: ".$_POST['mail']."
Тип оплаты: ".OplataMetod($_POST['order_metod'])."

Заказанные товары
--------------------------------------------------------
".$disCart;
    if(empty($_SESSION['UsersId']))
        $content.="

Вы всегда можете проверить статус заказа, загрузить файлы, распечатать платежные 
документы он-лайн по ссылке http://".$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']."/clients/?mail=".$_POST['mail']."&order=".$_POST['ouid']."
E-mail: ".$_POST['mail']."
№ Заказа: ".$_POST['ouid'];
    else
        $content.="

Вы всегда можете проверить статус заказа, загрузить файлы, распечатать платежные 
документы он-лайн через 'Личный кабинет' или по ссылке http://".$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']."/users/";

    $content.="

---------------------------------------------------------
С уважением,
Компания ".$LoadItems['System']['company']."
".$LoadItems['System']['adminmail2']."
http://".$_SERVER['SERVER_NAME'].$SysValue['dir']['dir'];

    $codepage  = "windows-1251";
    $header  = "MIME-Version: 1.0\n";
    $header .= "From:   <".$LoadItems['System']['adminmail2'].">\n";
    $header .= "Content-Type: text/plain; charset=$codepage\n";
    $header .= "X-Mailer: PHP/";
    $zag=$LoadItems['System']['name']." - Ваш заказ ".$_POST['ouid']."/".date("d-m-y")." успешно оформлен";

    $content_adm="
Доброго времени!
--------------------------------------------------------

Поступил заказ с интернет-магазина '".$LoadItems['System']['name']."'
Для редактирования состояния заказа перейдите в панель
администрирования магазина http://".$_SERVER['SERVER_NAME'].$SysValue['dir']['dir']."/phpshop/admpanel/

Заказанные товары
---------------------------------------------------------
".$disCart."

Подробности заказа № ".$_POST['ouid']."/".date("d-m-y")."
---------------------------------------------------------
Контактное лицо: ".$_POST['name_person'];

    if(!empty($_POST['org_name'])) {
        $content_adm.="
Компания: ".$_POST['org_name']."
ИНН: ".$_POST['org_inn']."
КПП: ".$_POST['org_kpp'];
    }

    $content_adm.="
Телефон: ".$_POST['tel_code']."-".$_POST['tel_name']."
Адрес и доп. инф: ".$_POST['adr_name']."
Желаемое время доставки: ".$_POST['dos_ot']." - ".$_POST['dos_do']."
Грузополучатель: ".GetDeliveryBase($_POST['dostavka_metod'],'city')."
E-mail: ".$_POST['mail']."
Тип оплаты: ".OplataMetod($_POST['order_metod'])."
Дата/время: ".date("d-m-y H:i a")."
IP:".$_SERVER['REMOTE_ADDR']."
REF: ".base64_decode($_COOKIE['ps_partner'])." 
---------------------------------------------------------


Powered & Developed by www.PHPShop.ru
".$SysValue['license']['product_name'];

    $codepage  = "windows-1251";
    $header_adm  = "MIME-Version: 1.0\n";
    $header_adm .= "From:   <".$LoadItems['System']['adminmail2'].">\n";
    $header_adm .= "Content-Type: text/plain; charset=$codepage\n";
    $header_adm .= "X-Mailer: PHP/";
    $zag_adm=$LoadItems['System']['name']." - Поступил заказ №".$_POST['ouid']."/".date("d-m-y");
    $datas=date("U");

    // Создаем массив заказа
    $OrderWrite = new OrderWrite($ChekDiscount[0],$GetDeliveryPrice);
    $Content = $OrderWrite->MAS;

    // Кол-во товаров в корзине
    $NumInCart = $OrderWrite->NUM; 

    if($NumInCart>0) {

        // Отсылаем почту
        $Option=unserialize($LoadItems['System']['admoption']);
        mail($LoadItems['System']['adminmail2'],$zag_adm, $content_adm, $header_adm);
        mail($mail,$zag,$content,$header);

        // Отсылаем SMS
        if($Option['sms_enabled'] == 1) {
            $sum = GetPriceOrder($ChekDiscount[1])+$GetDeliveryPrice;
            $msg="Поступил заказ N$id на сумму $sum ".GetValutaOrder();
            $phone=$SysValue['sms']['phone'];

            include_once($SysValue['file']['sms']);
            SendSMS($msg,$phone);
        }

        // Статус заказа
        $Status=array(
                "maneger"=>"",
                "time"=>""
        );

        // Запись заказа в БД
        $sql="INSERT INTO ".$SysValue['base']['table_name1']."
   VALUES ('','$datas','".$_POST['ouid']."','$Content','".serialize($Status)."','".$_SESSION['UsersId']."','','0')";
        $result=mysql_query($sql);
    }
}
?>