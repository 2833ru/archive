<?php

/**
 * Раздел пожаловаться на цену
 * @package PHPShopCoreDepricated
 */

// Проверка параметра идентификатора товара
if(!true_num($SysValue['nav']['id'])) header('Location: /error/');

$sql="select * from ".$SysValue['base']['table_name2']." where (id=".$SysValue['nav']['id'].")";
$result=mysql_query($sql);
$SysValue['sql']['num']++;
@$row = mysql_fetch_array($result);

$baseinputvaluta=$row['baseinputvaluta'];

// Определяем переменные
$SysValue['other']['productNameLat']=$LoadItems['Product'][$SysValue['nav']['id']]['name'];
$SysValue['other']['productUid']=$SysValue['nav']['id'];
$SysValue['other']['productName']= $LoadItems['Product'][$SysValue['nav']['id']]['name'];
$SysValue['other']['productValutaName'] = GetValuta();
$SysValue['other']['productPrice']=GetPriceValuta($LoadItems['Product'][$SysValue['nav']['id']]['price'],"",$baseinputvaluta);
//$SysValue['other']['productPrice']=GetPriceValuta($LoadItems['Product'][$SysValue['nav']['id']]['price']);

if(empty($LoadItems['Product'][$SysValue['nav']['id']]['pic_small']))
    $LoadItems['Product'][$id]['pic_small']="images/shop/no_photo.gif";
$SysValue['other']['productImg']= $LoadItems['Product'][$SysValue['nav']['id']]['pic_small'];

if(PHPShopSecurity::true_param($_SESSION['text'],$_POST['send_price_link'],$_POST['mail'],$_POST['name_person'],$_POST['link_to_page']) and $_POST['key']==$_SESSION['text']) {

    $codepage  = "windows-1251";
    $header  = "MIME-Version: 1.0\n";
    $header .= "From:   <".$LoadItems['System']['adminmail2'].">\n";
    $header .= "Content-Type: text/plain; charset=$codepage\n";
    $header .= "X-Mailer: PHP/";
    $zag=$LoadItems['System']['name']." - Сообщение о меньшей цене";
    $content="
Доброго времени!
--------------------------------------------------------

Поступила ссылка на меньшую цену с интернет-магазина '".$LoadItems['System']['name']."'

Подробности сообщения:
---------------------------------------------------------
Контактное лицо: ".$_POST['name_person']."
Компания: ".$_POST['org_name']."
Телефон: ".$_POST['tel_code']."-".$_POST['tel_name']."
Доп. инф: ".$_POST['adr_name']."
E-mail: ".$_POST['mail']."
Ссылка на меньшую цену: ".$_POST['link_to_page']."

Данные товара:
---------------------------------------------------------
Наименование: ".$LoadItems['Product'][$SysValue['nav']['id']]['name']."
Артикул: ".$LoadItems['Product'][$SysValue['nav']['id']]['uid']."
ID: ".$SysValue['nav']['id']."
Прямая ссылка:  ".$_SERVER['SERVER_NAME']."/shop/UID_".$SysValue['nav']['id'].".html
Дата/время: ".date("d-m-y H:i a")."
IP:".$_SERVER['REMOTE_ADDR']."
---------------------------------------------------------


Powered & Developed by www.PHPShop.ru
".$SysValue['license']['product_name'];

    // Отсылаем сообщение администратору
    mail($LoadItems['System']['adminmail2'],$zag, $content, $header);

    header("Location: /shop/UID_".$SysValue['nav']['id'].".html");
}
else {
    if(isset($_SESSION['UsersId'])) {
        $GetUsersInfo=GetUsersInfo($_SESSION['UsersId']);

        // Определяем переменные
        $SysValue['other']['UserMail']= $GetUsersInfo['mail'];
        $SysValue['other']['UserName']= $GetUsersInfo['name'];
        $SysValue['other']['UserTel']= $GetUsersInfo['tel'];
        $SysValue['other']['UserTelCode']= $GetUsersInfo['tel_code'];
        $SysValue['other']['UserAdres']= $GetUsersInfo['adres'];
        $SysValue['other']['UserComp']= $GetUsersInfo['company'];
        $SysValue['other']['formaLock']="readonly=1";

    }


    $SysValue['other']['DispShop']=ParseTemplateReturn("pricemail/main_forma.tpl");
}

// Подключаем шаблон 
ParseTemplate($SysValue['templates']['shop']);
?>