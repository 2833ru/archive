<?
/*
+-------------------------------------+
|  PHPShop 2.1 Enterprise             |
|  Модуль вывода статей               |
+-------------------------------------+
*/

function GetPageNumFromCategory($category){
global $SysValue,$LoadItems;
$category=TotalClean($category,1);
$sql="select link from ".$SysValue['base']['table_name11']." where category=$category";
$result=mysql_query($sql);
$num=mysql_numrows($result);
$row = mysql_fetch_array($result);
if($num==1) return $row['link'];
  else return "FALSE";
}


function DispPageCatContent($category){ // Вывод описания каталога
global $SysValue,$LoadItems;
$category=TotalClean($category,1);
$sql="select content from ".$SysValue['base']['table_name29']." where id=$category";
$result=mysql_query($sql);
@$SysValue['sql']['num']++;
@$row = mysql_fetch_array($result);
$content = $row['content'];

// Режим Multibase
$admoption=unserialize($LoadItems['System']['admoption']);
if($admoption['base_enabled'] == 1 and !empty($admoption['base_host']))
$content=eregi_replace("/UserFiles/","http://".$admoption['base_host']."/UserFiles/",$row['content']);

return $content;
}


function DispContentPage($name,$flag=0){
global $SysValue,$LoadItems;
$name=TotalClean($name,2);



// Страницы только для аторизованных
if(isset($_SESSION['UsersId'])) {$sort=" and ((secure !='1') OR (secure ='1' AND secure_groups='') OR (secure ='1' AND secure_groups REGEXP 'i".$_SESSION['UsersStatus']."-1i')) ";} else {$sort=" and (secure !='1') ";}
$sql="select * from ".$SysValue['base']['table_name11']." where link='$name' and enabled='1'  $sort order by num";



$result=mysql_query($sql);
@$row=mysql_fetch_array($result);
@$SysValue['sql']['num']++;
$id=$row['id'];
$names=$row['name'];
$content=stripslashes($row['content']);
$link=$row['link'];
$category=$row['category'];
$odnotip=$row['odnotip'];



// Страницы
$Content=explode("<HR>",$content);
if(is_array($Content)){
$_Content=array("");
foreach($Content as $val)
$_Content[]=$val;

if($flag==0) $p=$SysValue['nav']['id'];
if(empty($p)) $p=1;
$content=$_Content[$p];
$num=count($Content);




if($p=="ALL"){
$content=stripslashes($row['content']);
$productSortD="sortActiv";
}

$i=1;
while ($i<$num+1)
    {
  if($i!=$p){
    @$navigat.="
       <a href=\"/page/".$link."_".$i.".html\">".$i."</a> | ";
  }
  else{
  
   @$navigat.="
       <b>".$i."</b> | ";
  }
  $i++;
  }
}



// Навигация
if(count($Content)>1){
if($p>=$num){$p_to=$i-1;}else{$p_to=$p+1;}
@$nava=$SysValue['lang']['page_now'].":
<a href=\"/page/".$link."_".($p-1).".html\" title=\"Назад\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
$navigat<a href=\"/page/".$link."_".$p_to.".html\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
&nbsp;&nbsp;
<a href=\"/page/".$link."_ALL.html\" class=\"$productSortD\">Все содержимое</a>
    ";
}

// Определяем переменые
$SysValue['other']['pageTitle']= $names;
$SysValue['other']['pageContent']= $content;
$SysValue['other']['pageNav']=@$nava;
$SysValue['other']['catalogId']=@$LoadItems['CatalogPage'][$category]['id'];
$SysValue['other']['catalogCategory']=@$LoadItems['CatalogPage'][$category]['name'];
$parent_to=@$LoadItems['CatalogPage'][$category]['parent_to'];
$SysValue['other']['catalogCat']=@$LoadItems['CatalogPage'][$parent_to]['name'];
$SysValue['other']['thisCat'] = $parent_to;
@$SysValue['other']['parentId']= $LoadItems['CatalogPage'][$parent_to]['parent_to'];


// Разделитель
if($parent_to=="") {
$SysValue['other']['catalogCat']= $SysValue['lang']['catalog'];
$SysValue['other']['parentId']= "00";
}


// Однотипы
if($row['odnotip']!=""){
// Определяем переменые
$SysValue['other']['specMainTitle'] =$SysValue['lang']['page_product'];
$SysValue['other']['specMainIcon']= DispOdnotipForPage($odnotip);
$SysValue['other']['productOdnotip']= $SysValue['lang']['page_product'];


// Подключаем шаблон
$SysValue['other']['productOdnotipList']=$SysValue['other']['specMainIcon'];
$odnotipDisp=ParseTemplateReturn($SysValue['templates']['main_product_odnotip_list']);
}



// Подключаем шаблон
$SysValue['other']['odnotipDisp']= @$odnotipDisp;
if($link!="")
$dis=ParseTemplateReturn($SysValue['templates']['page_page_list']);
else $dis=404;
return $dis;
}



// выбор товаров однотипов
// кеш отключен
function DispOdnotipForPage($odnotip)
{
global $SysValue,$LoadItems;


$odnotip=explode(",",$odnotip);

// Собираем массив товаров
foreach($odnotip as $value){
$ReturnProductData=ReturnProductData($value);
if($ReturnProductData!="false") $Product[$value]=ReturnProductData($value);
}




if(is_array($Product))
foreach($Product as $val=>$p){
		   // Определяем переменые
		   $SysValue['other']['productName']= $p['name'];
		   $SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
$SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
$SysValue['other']['productValutaName']= GetValuta();

		   if($Product[$val]['priceSklad']==0){// Если товар на складе

// Коменты
$SysValue['other']['Notice']="";
$SysValue['other']['ComStartCart']="";
$SysValue['other']['ComEndCart']="";
$SysValue['other']['ComStartNotice']="<!--";
$SysValue['other']['ComEndNotice']="-->";

// Если нет новой цены
if(empty($Product[$val]['priceNew'])){
$SysValue['other']['productPrice']=GetPriceValuta($Product[$val]['price'],"",$Product[$val]['baseinputvaluta']);
$SysValue['other']['productPriceRub']= "";
}else{// Если есть новая цена
$SysValue['other']['productPrice']=GetPriceValuta($Product[$val]['price'],"",$Product[$val]['baseinputvaluta']);
$SysValue['other']['productPriceRub']= "<strike>".GetPriceValuta($Product[$val]['priceNew'],"",$Product[$val]['baseinputvaluta'])." ".GetValuta()."</strike>";
}}else{ // Товар под заказ
$SysValue['other']['productPrice']=$SysValue['lang']['sklad_no'];
$SysValue['other']['productPriceRub']=$SysValue['lang']['sklad_mesage'];
$SysValue['other']['productValutaName']="";
$SysValue['other']['ComStartNotice']="";
$SysValue['other']['ComEndNotice']="";
$SysValue['other']['ComStartCart']="<!--";
$SysValue['other']['ComEndCart']="-->";
$SysValue['other']['productNotice']=$SysValue['lang']['product_notice'];
}

// Поддержка Pro
if($SysValue['pro']['enabled'] == "true"){
$SysValue['other']['productPrice']=GetPriceValuta(ReturnTruePriceUser($val['uid'],$Product[$val]['price']),"",$Product[$val]['baseinputvaluta']);
}


$productImgBigFoto = str_replace("s.",".",$Product[$val]['pic_small']);
$SysValue['other']['productImgBigFoto']= $productImgBigFoto;
$SysValue['other']['productImg']= $Product[$val]['pic_small'];
$SysValue['other']['productDesOdnotip']= $Product[$val]['description'];
$SysValue['other']['productUid']= $val;
$SysValue['other']['productImgOdnotip']= $Product[$val]['pic_small'];
	       @$disp.=ParseTemplateReturn($SysValue['templates']['main_spec_forma_icon']);
		   
		 }


return @$disp;
}



function DispListPage($n){
global $SysValue,$LoadItems;
$n=TotalClean($n,1);

// Страницы только для аторизованных                                                                                                                     
if(isset($_SESSION['UsersId'])) {$sort=" and ((secure !='1') OR (secure ='1' AND secure_groups='') OR (secure ='1' AND secure_groups REGEXP 'i".$_SESSION['UsersStatus']."-1i')) ";} else {$sort=" and (secure !='1') ";}
	
$sql="select name, link  from ".$SysValue['base']['table_name11']." where category=$n and enabled='1'  $sort order by num";
$result=mysql_query($sql);
while(@$row = mysql_fetch_array(@$result)){

// Определяем переменые
$SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
$parent_to=$LoadItems['CatalogPage'][$n]['parent_to'];
@$SysValue['other']['parentName']= $LoadItems['CatalogPage'][$parent_to]['name'];


if($parent_to == 0) {
  $SysValue['other']['parentName']= $SysValue['lang']['catalog'];
  $SysValue['other']['catalogId']= "00";
  $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
  }
  else {
  $SysValue['other']['parentName']= $LoadItems['CatalogPage'][$parent_to]['name'];
  $SysValue['other']['catalogId']= $parent_to;
  $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
  }



 @$dis.="<li><a href=\"/page/".$row['link'].".html\" title=\"".$row['name']."\">".$row['name']."</a></li>";

}

$disp="<h1>".$LoadItems['CatalogPage'][$n]['name']."</h1>".DispPageCatContent($n)."<ul>$dis</ul>";

// Определяем переменые
$SysValue['other']['catalogList']=@$disp;

// Подключаем шаблон
@$dis=ParseTemplateReturn('catalog/catalog_page_info_forma.tpl');
@$SysValue['sql']['num']++;
return @$dis;
}


?>
