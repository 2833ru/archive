<?
// Проверка параметра
if(!true_num($SysValue['nav']['id'])) header('Location: /error/');

$sql="select * from ".$SysValue['base']['table_name2']." where (id=".$SysValue['nav']['id'].")";
echo $sql;
$result=mysql_query($sql);
@$SysValue['sql']['num']++;
@$row = mysql_fetch_array($result);

$baseinputvaluta=$row['baseinputvaluta'];

// Определяем переменные
$SysValue['other']['productNameLat']=$LoadItems['Product'][$SysValue['nav']['id']]['name'];
$SysValue['other']['productUid']=$SysValue['nav']['id'];
$SysValue['other']['productName']= $LoadItems['Product'][$SysValue['nav']['id']]['name'];

$SysValue['other']['productPrice']=GetPriceValuta($LoadItems['Product'][$SysValue['nav']['id']]['price'],"",$baseinputvaluta);
//$SysValue['other']['productPrice']=GetPriceValuta($LoadItems['Product'][$SysValue['nav']['id']]['price']);

if(empty($LoadItems['Product'][$SysValue['nav']['id']]['pic_small']))
    $LoadItems['Product'][$id]['pic_small']="images/shop/no_photo.gif";
$SysValue['other']['productImg']= $LoadItems['Product'][$SysValue['nav']['id']]['pic_small'];


if(!empty($_SESSION['text']) and @$send_price_link and $mail and $name_person and $link_to_page and $_POST['key']==$_SESSION['text']) {

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
Контактное лицо: ".@$name_person."
Компания: ".@$org_name."
Телефон: ".@$tel_code."-".@$tel_name."
Доп. инф: ".@$adr_name."
E-mail: ".@$mail."
Ссылка на меньшую цену: ".@$link_to_page."

Данные товара:
---------------------------------------------------------
Наименование: ".$LoadItems['Product'][$SysValue['nav']['id']]['name']."
Артикул: ".$LoadItems['Product'][$SysValue['nav']['id']]['uid']."
ID: ".$SysValue['nav']['id']."
Прямая ссылка:  ".$SERVER_NAME."/shop/UID_".$SysValue['nav']['id'].".html
Дата/время: ".date("d-m-y H:i a")."
IP:".$REMOTE_ADDR."
---------------------------------------------------------


Powered & Developed by www.PHPShop.ru
".$SysValue['license']['product_name'];
    mail($LoadItems['System']['adminmail2'],$zag, $content, $header);
    header("Location: /shop/UID_".$SysValue['nav']['id'].".html");
}
else {


    if(isset($_SESSION['UsersId'])) {
        $GetUsersInfo=GetUsersInfo($_SESSION['UsersId']);
// Определяем переменые
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
@ParseTemplate($SysValue['templates']['shop']);
?>

