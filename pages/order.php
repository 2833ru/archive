<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Файл Оформление Заказа             |
+-------------------------------------+
*/

// Создаем корзину
function Chek($stroka)// проверка вводимых цифр
{
    if (!ereg ("([0-9])", $stroka)) $stroka="0";
    return abs($stroka);
}

function Chek2($stroka)// проверка вводимых цифр
{
    global $LoadItems;
    $formatPrice = unserialize($LoadItems['System']['admoption']);
    $format=$formatPrice['price_znak'];
    if (!ereg ("([0-9])", $stroka)) $stroka="0";
    return number_format(abs($stroka),$format,"."," ");
}


// Очистка корзины
if(@$SysValue['nav']['query']['cart']=="clean") {
    session_unregister('cart');
    unset($cart);
}


function ReturnNum($cart) {
    while (list($key, $value) = @each($cart)) @$num+=$cart[$key]['num'];
    return @$num;
}

if(isset($id_edit))// редактирование кол-ва
{
    $cart[$id_edit]['num']=abs($num_new);
    session_register('cart');
}
if(isset($id_delet))// удаление товара
{
    unset($cart[@$id_delet]);
    session_register('cart');
}
if(@$cart[@$id_edit]['num']=="0")// удаление товара с нулевым кол-ом
{
    unset($cart[$id_edit]);
    session_register('cart');
}


$option=unserialize($LoadItems['System']['admoption']);


function getExcelInfoUid($uid) {
    global $SysValue;
    $sql="select * from ".$SysValue['base']['table_name2']." where uid=\"$uid\" limit 1";
    $result=mysql_query($sql);
    return @mysql_fetch_array($result);
}


function getExcelInfoId($id) {
    global $SysValue;
    $sql="select * from ".$SysValue['base']['table_name2']." where id=$id limit 1";
    $result=mysql_query($sql);
    return @mysql_fetch_array($result);
}


switch($_REQUEST['from']) {

    // Поддержка корзины из Shop2CD
    case "html":

        if(true_num($_GET['id'])) {
            $Prod=getExcelInfoId($_GET['id']);
            $baseinputvaluta=$row['baseinputvaluta'];
            $defvaluta=$LoadItems['System']['dengi'];
            $vkurs=$LoadItems['Valuta'][$Prod['baseinputvaluta']]['kurs'];
            $price=$Prod['price']/$vkurs;
            $price=($price+(($price*$LoadItems['System']['percent'])/100));

            if(!empty($Prod['id']))
                $_SESSION['cart'][$Prod['id']]=array(
                        "id"=>$Prod['id'],
                        "name"=>$Prod['name'],
                        "price"=>$price,
                        "uid"=>$Prod['uid'],
                        "num"=>1,
                        "weight"=>$Prod['weight'],
                        "user"=>$Prod['user']
                );

            $cart=$_SESSION['cart'];
        }
        break;

    // Поддержка корзины из Excel OnLine Price
    case "onlineprice":

        $excel_cart=base64_decode($c);
        parse_str($excel_cart,$order_array);
        if(is_array($order_array['c'])) {
            foreach ($order_array['c'] as $k=>$num) {
                if(true_num($k)) {
                    $Prod=getExcelInfoId($k);

                    $baseinputvaluta=$row['baseinputvaluta'];
                    $defvaluta=$LoadItems['System']['dengi'];
                    $vkurs=$LoadItems['Valuta'][$Prod['baseinputvaluta']]['kurs'];
                    $price=$Prod['price']/$vkurs;
                    $price=($price+(($price*$LoadItems['System']['percent'])/100));

                    if(!empty($Prod['id']))
                        $_SESSION['cart'][$Prod['id']]=array(
                                "id"=>$Prod['id'],
                                "name"=>$Prod['name'],
                                "price"=>$price,
                                "uid"=>$k,
                                "num"=>$num,
                                "weight"=>$Prod['weight'],
                                "user"=>$Prod['user']
                        );
                }
            }
            $cart=$_SESSION['cart'];
        }
        break;

    // Поддержка корзины из Excel 1C Price
    case "":
        $excel_cart=base64_decode($c);
        parse_str($excel_cart,$order_array);
        if(is_array($order_array['c'])) {
            foreach ($order_array['c'] as $k=>$num) {

                $Prod=getExcelInfoUid(CleanSearch($k));

                $baseinputvaluta=$row['baseinputvaluta'];
                $defvaluta=$LoadItems['System']['dengi'];
                $vkurs=$LoadItems['Valuta'][$Prod['baseinputvaluta']]['kurs'];
                $price=$Prod['price']/$vkurs;
                $price=($price+(($price*$LoadItems['System']['percent'])/100));

                if(!empty($Prod['id']))
                    $_SESSION['cart'][$Prod['id']]=array(
                            "id"=>$Prod['id'],
                            "name"=>$Prod['name'],
                            "price"=>$price,
                            "uid"=>$k,
                            "num"=>$num,
                            "weight"=>$Prod['weight'],
                            "user"=>$Prod['user']
                    );
            }
            $cart=$_SESSION['cart'];
        }
        break;



}




if(count(@$cart)>0)// вывод корзины
{
    if(is_array($cart))
        foreach($cart as $j=>$v) {
            $price_now=ReturnSummaNal($cart[$j]['price']*$cart[$j]['num'],0);
            $priceOrder=$cart[$j]['price']*$cart[$j]['num'];

            //$CatId=$LoadItems['Product'][$cart[$j]['id']]['category'];
            //$Catname=$LoadItems['Podcatalog'][$CatId]['name'];

            @$display_cart.='
<form name="forma_cart" method="post" action="'.$SysValue['dir']['dir'].'/order/">
<tr>
	<td>
	<a href="/shop/UID_'.$cart[$j]['id'].'.html" title="'.$cart[$j]['name'].'"><img src="images/shop/action_forward.gif" border=\"0\" hspace="5" align="absmiddle">'.$cart[$j]['name'].'</a></td>
	<td><input type=text value='.$cart[$j]['num'].' size=3 maxlength=3 name="num_new"></td>
	<td>
<table cellpadding="0" cellspacing="0">
<tr>
	<td><input type="image" name="edit_num" src="images/shop/cart_add.gif" value="edit" alt="Пересчитать" hspace="5" >
<input type=hidden name="id_edit" value="'.$j.'"></td>
	<td>
	<table cellpadding="0" cellspacing="0">
</form>
<form name="forma_cart" method="post" action="'.$SysValue['dir']['dir'].'/order/">
<tr>
	<td>
	
	<input type="image" name="edit_del" src="images/shop/cart_delete.gif" value="delet" alt="Удалить" hspace="5">
	<input type=hidden name="id_delet" value="'.$j.'">
	</td>
</tr>
</form>
</table>
	</td>
</tr>
</table>
	</td>
	<td align=right class=red>'.$price_now.' '.GetValutaOrder().'</td>
	</td>
</tr>

 ';

            //Определение и суммирование веса
            $goodid=$cart[$j]['id'];
            $goodnum=$cart[$j]['num'];
            $wsql='select weight from '.$SysValue['base']['table_name2'].' where id=\''.$goodid.'\'';
            $wresult=mysql_query($wsql);
            $wrow=mysql_fetch_array($wresult);
            $cweight=$wrow['weight']*$goodnum;
            if (!$cweight) {
                $zeroweight=1;
            } //Один из товаров имеет нулевой вес!
            @$weight+=$cweight;

            @$sum+=$price_now;
            @$sumOrder+=$priceOrder;
            @$sum=number_format($sum,"2",".","");
            @$num+=$cart[$j]['num'];
        }

    //Обнуляем вес товаров, если хотя бы один товар был без веса
    if ($zeroweight) {
        $weight=0;
        $we=' &ndash; Не указан';
    } else {
        $we='&nbsp;гр.';
    }

    if(count(@$cart)>0) {
        $ChekDiscount=ChekDiscount($sumOrder);
//$GetDeliveryPrice=$weight;
        $GetDeliveryPrice=GetDeliveryPrice("",$sum,$weight);
        @$display='
<table border=0 width=99% cellpadding=0 cellspacing=3 class=style1>
<tr>
	<td ><strong>Наименование</strong></td>
	<td width=50><strong>Кол-во</strong></td>
	<td width=50><strong>Операции</strong></td>
	<td width=70 align="right" colspan=""><strong>Цена</strong></td>
</tr>
<tr>
	<td colspan="4">
	<img src="images/shop/break.gif" alt="" width="100%" height="1" border="0">
	</td>
</tr>
'.@$display_cart.'

<tr>
	<td colspan="4">
	<img src="images/shop/break.gif" alt="" width="100%" height="1" border="0">
	</td>
</tr>
<tr style="padding-top:10">
   <td ><b>Итого:</b></td>
   <td class=style2>
<strong>'.ReturnNum($cart).'</strong> (шт.)
	</td>
	<td></td>
   <td class=red align="right">
   '.Chek2($sum).' '.GetValutaOrder().'<br>
</td>
</tr>
<tr>
	<td colspan="4">
	<img src="../images/shop/break.gif" alt="" width="100%" height="1" border="0">
	</td>
</tr>
</table>
<table border=0 width=99% cellpadding=0 cellspacing=3 class=style1 align="center">
<!-- <tr style="padding-top:0">
   <td colspan="3" valign="top">Курс:</td>
   <td class=red align="right"><span>'.GetKursOrder().' </span></td>
</tr> -->
<tr style="padding-top:0" style="visibility:hidden;display:none;">
   <td colspan="3" valign="top">Вес товаров:</td>
   <td class=red align="right"><span id="WeightSumma">'.$weight.'</span>'.$we.'</td>
</tr>


<tr style="padding-top:0">
   <td colspan="3" valign="top">Скидка:</td>
   <td class=red align="right"><span id="SkiSumma">'.$ChekDiscount[0].'</span>&nbsp;%</td>
</tr>
<tr style="padding-top:0">
   <td colspan="3" valign="top">Доставка:</td>
   <td class=red align="right"><span id="DosSumma">0'.$GetDeliveryPrice.'</span>&nbsp; '.GetValutaOrder().'</td>
</tr>
<tr>
    <td>
  К оплате с учетом скидки:
	</td>
	<td class=style2>
	</td>
	<td colspan=2 align="right" class=red>
	<b><span id="TotalSumma">'.(ReturnSummaOrder($sum,$ChekDiscount[0])+$GetDeliveryPrice).'</span></b> '.GetValutaOrder().'</td>
</tr>
</table>
<input type="hidden" id="OrderSumma" name="OrderSumma"  value="'.ReturnSummaOrder($sum,$ChekDiscount[0]).'">
';
        @$display.="
<script>
if(window.document.getElementById('num')){
window.document.getElementById('num').innerHTML='".ReturnNum(@$cart)."';
window.document.getElementById('sum').innerHTML='".Chek2(@$sum)."';
}
</script>
";
    }


    // Убераем приглашение регистрации
    if(isset($_SESSION['UsersId'])) {
        $SysValue['other']['ComStartReg']="<!--";
        $SysValue['other']['ComEndReg']="-->";
    }



    // Номер заказа
    $sql="select uid from ".$SysValue['base']['table_name1']." order by id desc LIMIT 0, 1";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    $last=$row['uid'];
    $all_num=explode("-",$last);
    $ferst_num=$all_num[0];
    if($ferst_num<100) $ferst_num=100;
    $order_num = $ferst_num + 1;
    $order_num=$order_num."-".substr(abs(crc32(uniqid($sid))),0,2);

    if(isset($_SESSION['UsersId'])) {
        $GetUsersInfo=GetUsersInfo($_SESSION['UsersId']);
        
        // Определяем переменные
        $SysValue['other']['UserMail']= $GetUsersInfo['mail'];
        $SysValue['other']['UserName']= $GetUsersInfo['name'];
        $SysValue['other']['UserTel']= $GetUsersInfo['tel'];
        $SysValue['other']['UserTelCode']= $GetUsersInfo['tel_code'];
        $SysValue['other']['UserAdres']= $GetUsersInfo['adres'];
        $SysValue['other']['UserComp']= $GetUsersInfo['company'];
        $SysValue['other']['UserInn']= $GetUsersInfo['inn'];
        $SysValue['other']['UserKpp']= $GetUsersInfo['kpp'];
        $SysValue['other']['formaLock']="readonly=1";
    }
    else {
        /*
// Определяем переменые
$SysValue['other']['UserMail']= $_COOKIE['UserMail'];
$SysValue['other']['UserName']= $_COOKIE['UserName'];
$SysValue['other']['UserTel']= $_COOKIE['UserTel'];
$SysValue['other']['UserAdres']= $_COOKIE['UserAdres'];
$SysValue['other']['UserComp']= $_COOKIE['UserComp'];
$SysValue['other']['UserInn']= $_COOKIE['UserInn'];
        */
    }

    $SysValue['other']['orderNum']= $order_num;
    $SysValue['other']['orderWeight']= ReturnNum($cart);
    $SysValue['other']['catalogCat']= "Оформление заказа";
    $SysValue['other']['catalogCategory']= "Данные";
    $SysValue['other']['orderContentCart']=@$display;
    $SysValue['other']['orderDate']=date("d-m-y");
    $SysValue['other']['orderDelivery']=GetDelivery(@$_GET['d']);
    $SysValue['other']['orderOplata']=GetOplataMetod();
    $SysValue['other']['deliveryId']= @$_GET['d'];


// Если корзина больше суммы мимального заказа
    if($option['cart_minimum'] < $sum) {

// Подключаем шаблон
        $SysValue['other']['orderContent']=ParseTemplateReturn($SysValue['templates']['main_order_forma']);

    }else {

        // Определяем переменные
        $SysValue['other']['orderContent']="<FONT style=\"font-size:14px;color:red\">
<B>".$SysValue['lang']['cart_minimum']." ".$option['cart_minimum']." </B></FONT><BR>".$SysValue['lang']['bad_order_mesage_2']."
";


    }
    $SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['main_order_list']);


}

else {
    // Определяем переменые
    $SysValue['other']['mesageText']= "<FONT style=\"font-size:14px;color:red\">
<B>".$SysValue['lang']['bad_cart_1']."</B></FONT><BR>".$SysValue['lang']['bad_order_mesage_2']."
<script language=\"JavaScript\">
document.getElementById('num').innerHTML = '--';
document.getElementById('sum').innerHTML = '';
document.getElementById('order').style.display = 'none';
</script>
";

    // Подключаем шаблон
    $SysValue['other']['orderMesage']=ParseTemplateReturn($SysValue['templates']['order_forma_mesage']);
// Определяем переменые
    $SysValue['other']['DispShop']=ParseTemplateReturn($SysValue['templates']['order_forma_mesage_main']);

}

// Подключаем шаблон 
@ParseTemplate($SysValue['templates']['shop']);
?>
