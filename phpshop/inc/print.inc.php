<?
function DispPrint($n)// Вывод подробной инфы о товаре 
{
global $SysValue,$LoadItems,$SERVER_NAME;
$n=TotalClean($n,1);
$cat=TotalClean($cat,1);
$i=0;
$sql="select * from ".$SysValue['base']['table_name2']." where id=$n and enabled='1'";
$result=mysql_query($sql);
@$SysValue['sql']['num']++;
$row = mysql_fetch_array($result);
@$SysValue['sql']['num']++;
    $id=$row['id'];
	$uid=$LoadItems['Product'][$id]['uid'];
    $name=$LoadItems['Product'][$id]['name'];
	$category=$LoadItems['Product'][$id]['category'];
	$content=stripslashes($row['content']);
	$odnotip=$LoadItems['Product'][$id]['odnotip'];
	$vendor=$LoadItems['Product'][$id]['vendor'];
	$vendor_array=$row['vendor_array'];
	$baseinputvaluta=$row['baseinputvaluta'];	

// Режим Multibase
$admoption=unserialize($LoadItems['System']['admoption']);
if($admoption['base_enabled'] == 1 and !empty($admoption['base_host']))
$LoadItems['Product'][$id]['pic_big']=eregi_replace("/UserFiles/","http://".$admoption['base_host']."/UserFiles/",$LoadItems['Product'][$id]['pic_big']);

// Пустая картинка
if(empty($LoadItems['Product'][$id]['pic_big']))
$LoadItems['Product'][$id]['pic_big']="images/shop/no_photo.gif";


	// Определяем переменые
$SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
$SysValue['other']['productBack']= $SysValue['lang']['product_back'];
$SysValue['other']['productName']= $name;
$SysValue['other']['productArt']= $uid;
$SysValue['other']['productDes']= $content;
$SysValue['other']['productValutaName']= GetValuta();
$SysValue['other']['productImg']= $LoadItems['Product'][$id]['pic_big'];
@$SysValue['other']['vendorDisp']=DispCatSortTable($category,$vendor_array);



if($LoadItems['Product'][$id]['priceSklad']==0){// Если товар на складе
// Если нет новой цены
if(empty($LoadItems['Product'][$id]['priceNew'])){
$SysValue['other']['productPrice']=GetPriceValuta($LoadItems['Product'][$id]['price'],"",$baseinputvaluta);
}else{// Если есть новая цена
$SysValue['other']['productPrice']=GetPriceValuta($LoadItems['Product'][$id]['price'],"",$baseinputvaluta);
$SysValue['other']['productPriceRub']= "<strike>".GetPriceValuta($LoadItems['Product'][$id]['priceNew'])." ".GetValuta()."</strike>";
}}else{ // Товар по заказ
$SysValue['other']['productPrice']=$SysValue['lang']['sklad_no'];
$SysValue['other']['productPriceRub']=$SysValue['lang']['sklad_mesage'];
$SysValue['other']['productValutaName']="";
}



@$SysValue['other']['productId']= $id;
@$SysValue['other']['productUid']= $id;
@$SysValue['other']['productCat']= $LoadItems['Podcatalog'][$category]['parent_to'];

// Если цены показывать только после аторизации
if($admoption['user_price_activate']==1 and !$_SESSION['UsersId']){
  $SysValue['other']['productPrice']="";
  $SysValue['other']['productValutaName']="";
}

// Подключаем шаблон
@$dis=ParseTemplateReturn($SysValue['templates']['main_product_forma_full']);


// Определяем переменые
$SysValue['other']['catalog']= $SysValue['lang']['catalog'];
$SysValue['other']['odnotipDisp']= $odnotipDisp;
$SysValue['other']['pagetemaDisp']=$pagetemaDisp;
@$cat=$LoadItems['Podcatalog'][$category]['parent_to'];
@$SysValue['other']['catalogCat']= $LoadItems['Catalog'][$cat]['name'];
@$SysValue['other']['catalogId']= $LoadItems['Catalog'][$cat]['name'];
@$SysValue['other']['catalogUId']= $cat;
@$SysValue['other']['pcatalogId']= $LoadItems['Podcatalog'][$category]['id'];
@$SysValue['other']['catalogCategory']=$LoadItems['Podcatalog'][$category]['name'];
$SysValue['other']['productPageDis']=$dis;
$SysValue['other']['productPageNum']=$p;
$SysValue['other']['productPageVendor']='0'.$vendor;
$SysValue['other']['productPodcat']=$category;
$SysValue['other']['productName']= $name;
if($LoadItems['System']['logo']=="") 
$SysValue['other']['logoShop']= "images/shop/phpshop_logo.gif";
 else $SysValue['other']['logoShop']=$LoadItems['System']['logo'];
$SysValue['other']['descripShop']=$LoadItems['System']['descrip'];
$SysValue['other']['nameShop']= $LoadItems['System']['name'];
$SysValue['other']['serverShop']=$SERVER_NAME;
$SysValue['other']['pathTemplate']=$pathTemplate;
// Подключаем шаблон
if(@$name)
@$disp=ParseTemplate("print/print_page_forma.tpl");
else
@$disp=ParseTemplate($SysValue['templates']['error_page_forma']);
return @$disp;
}
?>
