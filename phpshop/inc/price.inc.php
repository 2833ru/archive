<?php
/**
 * Вывод категорий в разделе прайс
 * @package PHPShopCoreDepricated
 * @param int $c ИД категории
 * @return string
 */
function DispCategoryPrice($c){
    global $LoadItems,$SysValue;

    $dis=null;
    if(is_array($LoadItems['CatalogKeys']))
        foreach($LoadItems['CatalogKeys'] as $cat=>$val) {

            $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$cat);
            if(count($podcatalog_id)==0) {
                $parent=$LoadItems['Catalog'][$cat]['parent_to'];
                if ($c==$cat) $sel="selected";
                else $sel="";
                $dis.="<option value=\"$cat\" $sel>".$LoadItems['Catalog'][$parent]['name']." / ".$LoadItems['Catalog'][$cat]['name']."</option>\n";
            }
        }
    $disp="<select name=\"catId\" id=\"catId\" size=1><option value=\"ALL\">Показать все разделы</option>
        $dis</select>";

    return $disp;
}

/**
 * Вывод товаров для раздела прайса
 * @package PHPShopCoreDepricated
 * @param int $n ИД категории
 * @return string
 */
function Vivod_product_price($n){
    global $SysValue,$LoadItems;

    $disp=null;
    $n=TotalClean($n,1);
    $sql="select id,name,price,sklad,price2,price3,price4,price5,baseinputvaluta from ".$SysValue['base']['table_name2']." where (category='$n' or dop_cat LIKE '%#$n#%') and enabled='1' order by name";
    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $uid=$row['uid'];
        $name=$row['name'];
        $price=$row['price'];
        $sklad=$row['sklad'];
        $baseinputvaluta=$row['baseinputvaluta'];
        $price=($price+(($price*$LoadItems['System']['percent'])/100));

        // Выборка из базы нужной колонки цены
        if(session_is_registered('UsersStatus')) {
            $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
            if($GetUsersStatusPrice>1) {
                $pole="price".$GetUsersStatusPrice;
                $pricePersona=$row[$pole];
                if(!empty($pricePersona))
                    $price=($pricePersona+(($pricePersona*$LoadItems['System']['percent'])/100));
            }
        }

        // Если цены показывать только после аторизации
        $admoption=unserialize($LoadItems['System']['admoption']);
        if($admoption['user_price_activate']==1 and !$_SESSION['UsersId']) {
            $price="~";
            $valuta="";
        }else {
            $price=GetPriceValuta($price,"",$baseinputvaluta);
            $valuta=GetValuta();
        }

        $disp.="<tr bgcolor=\"#ffffff\">
	<td>
	<a href=\"/shop/UID_".$id.".html\" title=\"".$name."\">".$uid." ".$name."</a>
	</td>
	<td width=\"150\" align=\"center\">";

        if(!empty($price)) $disp.=$price." ".$valuta;

        $disp.="</td><td>";

        if($sklad==0 and !empty($price)) {// Если товар на складе
            if($admoption['user_price_activate']==1 and !$_SESSION['UsersId'])
                $disp.="";
            else
                $disp.="
	<A href=\"javascript:AddToCart($id)\"><IMG  src=\"images/shop/basket_put.gif\" align=absMiddle border=0></A>";
        }
        $disp.="</td</tr>";
    }
    $SysValue['sql']['num']++;

    return $disp;
}

/**
 * Вывод прайса
 * @package PHPShopCoreDepricated
 * @param string $dir директория
 * @return array 
 */
function Vivod_price($dir){
    global $SysValue,$LoadItems;
    
    $dis=null;

    // Если задан каталог
    if($dir!="null") {
        if(!$LoadItems['Catalog'][$dir]['name']) return 404;
        $parent=$LoadItems['Catalog'][$dir]['parent_to'];
        $dis.="
	   <tr valign=\"top\" class=bgprice>
	     <td colspan=4>
	      <b title=\"".$dir."\">".$LoadItems['Catalog'][$parent]['name']." / ".$LoadItems['Catalog'][$dir]['name']."</b>
	     </td>
	   </tr>";
        $dis.=Vivod_product_price($dir);
    }
    else {
        foreach($LoadItems['CatalogKeys'] as $cat=>$val) {
            $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$cat);
            if(count($podcatalog_id)==0) {
                $dis.="
	   <tr valign=\"top\" class=bgprice>
	     <td colspan=4>
	      <b title=\"".$cat."\">".$LoadItems['Catalog'][$val]['name']." / ".$LoadItems['Catalog'][$cat]['name']."</b>
	     </td>
	   </tr>";
                $dis.=Vivod_product_price($cat);
            }
        }
    }
    // Определяем переменые
    $SysValue['other']['productPageDis']="<table cellpadding=3 cellspacing=1 bgcolor=\"#D2D2D2\" width=\"98%\" align=\"center\">$dis</table>";

    // Подключаем шаблон
    $disp=ParseTemplateReturn($SysValue['templates']['price_page_list']);
    return $disp;
}
?>