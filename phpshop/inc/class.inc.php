<?

/* Класс вывода товаров на главную страницу

$Disp = new DispSpec(); // объявляем класс
$Disp->sql=""; // sql запрос
$Disp->setka_num=""; // тип сетки (1-3)
$Disp->setka_style=""; // стиль сетки (setka)
$Disp->template=""; // имя шаблона вывода
$Disp->Engen(); // генерация контента
$Return=$Disp->disp; // переменная результата для вывода

*/

class DispSpec {
      var $sql;
	  var $setka_num;
      var $disp;
      var $template;
	  var $setka_style;


      function Engen(){
      global $SysValue,$LoadItems;

$Options=unserialize($LoadItems['System']['admoption']);

$sql=$this->sql;
$result=mysql_query($sql);
@$num=mysql_numrows(@$result);
if($num>0){
$SysValue['my']['setka_speс_num']=$this->setka_num;
if($SysValue['my']['setka_speс_num'] == 2) $j=0;
if($SysValue['my']['setka_speс_num'] == 3) $j=5;

while(@$row = mysql_fetch_array(@$result))
    {
    $id=$row['id'];
	$uid=$row['uid'];
	$name=stripslashes($row['name']);
	$category=$row['category'];
	$price=$row['price'];
	$priceNew=$row['price_n'];
	$price=($price+(($price*$LoadItems['System']['percent'])/100));
	$pic_small=$row['pic_small'];
	$pic_big=$row['pic_big'];
	$description=stripslashes($row['description']);
	$sklad=$row['sklad'];
	$items=$row['items'];
	$baseinputvaluta=$row['baseinputvaluta'];		

// Подтипы
if($row['parent']!=""){
$SysValue['other']['ComStartCart']="<!--";
$SysValue['other']['ComEndVart']="-->";

}else{
$SysValue['other']['ComStart']="";
$SysValue['other']['ComEnd']="";
}
	
	// Выборка из базы нужной колонки цены
	if(session_is_registered('UsersStatus')){
    $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
	  if($GetUsersStatusPrice>1){
	   $pole="price".$GetUsersStatusPrice;
	   $pricePersona=$row[$pole];
	   if(!empty($pricePersona)) 
	     $price=($pricePersona+(($pricePersona*$LoadItems['System']['percent'])/100));
	   }
	}
	
	
	// Если есть новая цена
	if($priceNew>0){
	$priceNew=($priceNew+(($priceNew*$LoadItems['System']['percent'])/100));
	$priceNew=number_format($priceNew,"2",".","");
	}
	
	// Проверка на нулевую цену
	if(!is_numeric($row['price']))
	$sklad = 1;
	
	$uid=$row['uid'];
	$odnotip=explode(",",$row['odnotip']);
	$parent=explode(",",$row['parent']);
	$vendor=$row['vendor'];
	$vendor_array=$row['vendor_array'];

// Режим Multibase
$admoption=unserialize($LoadItems['System']['admoption']);
if($admoption['base_enabled'] == 1 and !empty($admoption['base_host']))
$pic_small=eregi_replace("/UserFiles/","http://".$admoption['base_host']."/UserFiles/",$pic_small);


// Пустая картинка
if(empty($pic_small))
$pic_small="images/shop/no_photo.gif";

// Определяем переменые
$SysValue['other']['productPriceMoney']= $LoadItems['System']['dengi'];
$SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
$SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
@$SysValue['other']['productName']= $name;
$SysValue['other']['productDes']= $description;
$SysValue['other']['productValutaName']= GetValuta();
@$SysValue['other']['productArt']= $uid;
$SysValue['other']['productImg']= $pic_small;
$SysValue['other']['productImgBigFoto']= $pic_big;
@$SysValue['other']['productId']= $category;
@$SysValue['other']['productUid']= $id;


// Показывать состояние склада
if($admoption['sklad_enabled'] == 1 and $items>0)
$SysValue['other']['productSklad']= $SysValue['lang']['product_on_sklad']." ".$items." ".$SysValue['lang']['product_on_sklad_i'];
 else $SysValue['other']['productSklad']="";


// and $items>0
if($sklad==0 ){// Если товар на складе

// Коменты
$SysValue['other']['Notice']="";
$SysValue['other']['ComStartCart']="";
$SysValue['other']['ComEndCart']="";
$SysValue['other']['ComStartNotice']="<!--";
$SysValue['other']['ComEndNotice']="-->";

// Если нет новой цены
if(empty($priceNew)){
$SysValue['other']['productPrice']=GetPriceValuta($price,"",$baseinputvaluta);
$SysValue['other']['productPriceRub']= "";
}else{// Если есть новая цена
$SysValue['other']['productPrice']=GetPriceValuta($price,"",$baseinputvaluta);
$SysValue['other']['productPriceRub']= "<strike>".GetPriceValuta($priceNew,"",$baseinputvaluta)." ".GetValuta()."</strike>";
}}else{ // Товар под заказ
$SysValue['other']['productPrice']=GetPriceValuta($price,"",$baseinputvaluta);
$SysValue['other']['productPriceRub']=$SysValue['lang']['sklad_mesage'];
$SysValue['other']['ComStartNotice']="";
$SysValue['other']['ComEndNotice']="";
$SysValue['other']['ComStartCart']="<!--";
$SysValue['other']['ComEndCart']="-->";
$SysValue['other']['productNotice']=$SysValue['lang']['product_notice'];
}

// Если цены показывать только после аторизации
if($admoption['user_price_activate']==1 and !$_SESSION['UsersId']){
    $SysValue['other']['ComStartCart']="<!--";
    $SysValue['other']['ComEndCart']="-->";
    $SysValue['other']['productPrice']="";
	$SysValue['other']['productValutaName']="";
}


// Вывод опций для корзины
$DispCatOptionsTest=DispCatOptionsTest($category);
if($DispCatOptionsTest == 1){
  $SysValue['other']['ComStartCart']="<!--";
  $SysValue['other']['ComEndCart']="-->";
  }

// Подключаем шаблон взависисмости от сетки
@$dis=ParseTemplateReturn($this->template);


// Сетка 1*1
if($SysValue['my']['setka_speс_num'] == 1){

 $td="<tr><TD class=".$this->setka_style." colspan=3 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
 $td.="<tr><td valign=\"top\">"; @$j++; $td2="</td>";

 @$disp.=$td.$dis;

}


// Сетка 2*2
if($SysValue['my']['setka_speс_num'] == 2){

 if($j==1){ $td="<td valign=\"top\"  class=\"panel_r\">"; $j=0; $td2="</td><tr>";}
 else {
 $td="<TD class=".$this->setka_style." colspan=3 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
 $td.="<tr><td valign=\"top\"  class=\"panel_l\">"; $j++; $td2="</td>";
 $td2.="<TD width=1 class=".$this->setka_style."><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
 }
 
 @$disp.=$td.$dis.$td2;

}

// Сетка 3*3
if($SysValue['my']['setka_speс_num'] == 3){

 if($j==3){
$td="<td valign=\"top\" class=\"panel_t\">"; $j++; $td2="</td></tr>";
@$disp.=$td.$dis.$td2;
}

if($j==2){
$td="<td  valign=\"top\" class=\"panel_t\">"; $j++; $td2="</td>";
$td2.="<TD width=1 class=".$this->setka_style."><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
@$disp.=$td.$dis.$td2;
}

if($j==1){

$td="<tr><TD class=".$this->setka_style." colspan=5 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
$td.="<tr><td   valign=\"top\" class=\"panel_t\">"; $j++; $td2="</td>";
$td2.="
<TD width=1 class=".$this->setka_style."><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
@$disp.=$td.$dis.$td2;
}

if($j==4){
$j=1;
}

if($j==5){

$td="<tr><td   valign=\"top\">"; $j=2; $td2="</td>";
$td2.="
<TD width=1 class=".$this->setka_style."><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
@$disp.=$td.$dis.$td2;
}

}}


$disp="<table cellpadding=0 cellspacing=0 width=\"100%\">".@$disp."</table>";
@$SysValue['sql']['num']++;

$this->disp = @$disp;
       }
  }
}

?>
