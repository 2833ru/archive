<?php
/**
 * Навигация по разделу новой цены
 * @package PHPShopCoreDepricated
 * @return string
 */
function DispNewpriceNav() {
    global $SysValue,$LoadItems;

    $navigat=null;
    $i=1;

    $id=TotalClean($SysValue['nav']['id'],1);
    $v=$SysValue['nav']['query']['v'];
    $p=$SysValue['nav']['id'];
    if(empty($p) and empty($_POST['priceSearch'])) $p=1;

    // querystring
    if(!empty($SysValue['nav']['querystring']))
        $querystring="?".$SysValue['nav']['querystring'];
    else $querystring="";

    // Все страницы
    if($p=="ALL" or isset($_POST['priceSearch']))
        $productSortD="sortActiv";
    else $p=TotalClean($p,1);

    $num_row=$LoadItems['System']['num_row'];
    $num_page=NumFrom("table_name2","where price_n!='' and enabled='1'".$sort);

    $num=$num_page/$num_row;
    while ($i<$num+1) {
        if($i!=$p) {

            if($i==1) $pageOt=$i+$pageDo;
            else $pageOt=$i+$pageDo-$i;

            $pageDo=$i*$num_row;
            $navigat.="
	     <a href=\"./newtip_".$i.".html".$querystring."\">".$pageOt."-".$pageDo."</a> | ";
        }
        else {

            if($i==1) $pageOt=$i+@$pageDo;
            else $pageOt=$i+@$pageDo-$i;

            $pageDo=$i*$num_row;
            $navigat.="
	     <b>".$pageOt."-".$pageDo."</b> | ";
        }
        $i++;
    }
    if($num>1) {
        if($p>=$num) {
            $p_to=$i-1;
        }else {
            $p_to=$p+1;
        }
        $nava=$SysValue['lang']['page_now'].":
<a href=\"./newtip_".($p-1).".html".$querystring."\" title=\"Назад\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
                $navigat<a href=\"./newtip_".$p_to.".html".$querystring."\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
&nbsp;&nbsp;
<a href=\"./newtip_ALL.html".$querystring."\" class=\"$productSortD\">Все позиции</a>
                ";
    }
    return $nava;
}

/**
 * Создание SQL запроса
 * @package PHPShopCoreDepricated
 * @return string
 */
function PageNewpriceDisp() {
    global $SysValue,$LoadItems;

    $n=TotalClean($n,1);
    $p=$SysValue['nav']['id'];
    if(empty($p)) $p=1;
    $v=$SysValue['nav']['query']['v'];
    $s=TotalClean($SysValue['nav']['query']['s'],1);
    $f=TotalClean($SysValue['nav']['query']['f'],1);

    if($p!="ALL")
        $p=TotalClean($p,1);

    $num_row=$LoadItems['System']['num_row'];
    $num_ot=0;
    $q=0;

    // Сортировка
    switch($s) {
        case(1): $string="order by name";
            break;
        case(2): $string="order by price";
            break;
        case(3): $string="order by num";
            break;
        default: $string="order by num";
    }

    // Сортировка направление
    switch($f) {
        case(1): $string.="";
            break;
        case(2): $string.=" desc";
            break;
        default: $string.="";
    }

    // Все страницы
    if($p=="ALL") {
        $sql="select * from ".$SysValue['base']['table_name2']." where price_n!=''  and enabled='1' ".$sort." ".$string;
    }

    // Поиск по цене
    elseif(isset($_POST['priceSearch'])) {
        $priceOT=TotalClean($_POST['priceOT'],1);
        $priceDO=TotalClean($_POST['priceDO'],1);
        if(empty($priceOT)) $priceOT=0;
        $sql="select * from ".$SysValue['base']['table_name2']." where  price_n!='' and enabled='1' and price BETWEEN ".GetPriceSort($priceOT,0)." AND ".GetPriceSort($priceDO,0)." ".$string;
    }
    else while($q<$p) {
            $sql="select * from ".$SysValue['base']['table_name2']." where price_n!='' and enabled='1' $sort ".$string." LIMIT $num_ot, $num_row";
            $q++;
            $num_ot=$num_ot+$num_row;
        }

    return $sql;
}

/**
 * Вывод краткого описания товаров
 * @package PHPShopCoreDepricated
 * @return string
 */
function DispNewpriceKratko() {
    global $SysValue,$LoadItems;

    $p=$SysValue['nav']['page'];
    $v=TotalClean($SysValue['nav']['query']['v'],1);
    $s=TotalClean($SysValue['nav']['query']['s'],1);
    $f=TotalClean($SysValue['nav']['query']['f'],1);
    if(empty($p)) $p=1;
    $n=TotalClean($n,1);

    if($p!="ALL") $p=TotalClean($p,1);

    // SQL запрос
    $sql=PageNewpriceDisp();
    $result=mysql_query($sql);
    @$num_rows=mysql_num_rows($result);

    $i=0;
    $disp=null;
    $SysValue['my']['setka_num']=$LoadItems['System']['num_vitrina'];
    if($SysValue['my']['setka_num'] == 2) $j=0;
    if($SysValue['my']['setka_num'] == 3) $j=1;

    while($row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $uid=$row['uid'];
        $name=$row['name'];
        $category=$row['category'];
        $price=$row['price'];
        $sklad=$row['sklad'];
        $items=$row['items'];
        $priceNew=$row['price_n'];
        $price=($price+(($price*$LoadItems['System']['percent'])/100));
        $pic_small=$row['pic_small'];
        $pic_big=$row['pic_big'];
        $baseinputvaluta=$row['baseinputvaluta'];

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


        // Если есть новая цена
        if($priceNew>0) {
            $priceNew=($priceNew+(($priceNew*$LoadItems['System']['percent'])/100));
            $priceNew=number_format($priceNew,"2",".","");
        }

        // Проверка на нулевую цену
        if(!is_numeric($row['price']))
            $sklad = 1;

        $uid=$row['uid'];
        $parent=explode(",",$row['parent']);
        $vendor=$row['vendor'];
        $vendor_array=$row['vendor_array'];
        $description=$row['description'];

        // Режим Multibase
        $admoption=unserialize($LoadItems['System']['admoption']);
        if($admoption['base_enabled'] == 1 and !empty($admoption['base_host']))
            $LoadItems['Product'][$id]['pic_small']=eregi_replace("/UserFiles/","http://".$admoption['base_host']."/UserFiles/",$LoadItems['Product'][$id]['pic_small']);

        // Пустая картинка
        if(empty($pic_small))
            $pic_small="images/shop/no_photo.gif";

        // Определяем переменные
        $SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
        $SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
        $SysValue['other']['productName']= $name;
        //$SysValue['other']['productNameLat']=NameToLatin($name);
        $SysValue['other']['productArt']= $uid;
        $SysValue['other']['productDes']= DispCatSortTable($category,$vendor_array).$description;
        $SysValue['other']['productImg']= $pic_small;
        $SysValue['other']['productImgBigFoto']= $pic_big;
        $SysValue['other']['productValutaName']= GetValuta();

        // Показывать состояние склада
        if($admoption['sklad_enabled'] == 1 and $items>0)
            $SysValue['other']['productSklad']= $SysValue['lang']['product_on_sklad']." ".$items." ".$ed_izm;
        else $SysValue['other']['productSklad']="";


        // Если товар на складе
        if($priceSklad==0) {
            // Если нет новой цены
            if(empty($priceNew)) {
                $SysValue['other']['productPrice']=GetPriceValuta($price,"",$baseinputvaluta);
                $SysValue['other']['productPriceRub']= "";
            }else {// Если есть новая цена
                $SysValue['other']['productPrice']=GetPriceValuta($price,"",$baseinputvaluta);
                $SysValue['other']['productPriceRub']= "<strike>".GetPriceValuta($priceNew,"",$baseinputvaluta)." ".GetValuta()."</strike>";
            }
        }
        else { // Товар по заказ
            $SysValue['other']['productPrice']=$SysValue['lang']['sklad_no'];
            $SysValue['other']['productPriceRub']=$SysValue['lang']['sklad_mesage'];
        }

        $SysValue['other']['productId']= $category;
        $SysValue['other']['productCat']= $cat;
        $SysValue['other']['productCatnav']= $cat;
        $SysValue['other']['productPageThis']=$p;
        $SysValue['other']['productUid']= $id;

        // Если цены показывать только после аторизации
        if($admoption['user_price_activate']==1 and !$_SESSION['UsersId']) {
            $SysValue['other']['ComStartCart']="<!--";
            $SysValue['other']['ComEndCart']="-->";
            $SysValue['other']['productPrice']="";
            $SysValue['other']['productValutaName']="";
        }

        // Вывод опций для корзины
        $DispCatOptionsTest=DispCatOptionsTest($category);
        if($DispCatOptionsTest == 1) {
            $SysValue['other']['ComStartCart']="<!--";
            $SysValue['other']['ComEndCart']="-->";
        }else {
            $SysValue['other']['ComStartCart']="";
            $SysValue['other']['ComEndCart']="";
        }

        // Подключаем шаблон
        $dis=ParseTemplateReturn($SysValue['templates']['main_product_forma_'.$SysValue['my']['setka_num']]);

        // Сетка 1*1
        if($SysValue['my']['setka_num'] == 1) {
            $td="<tr><TD class=setka colspan=3 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
            $td.="<tr><td valign=\"top\">";
            $j++;
            $td2="</td>";
            $disp.=$td.$dis;
        }

        // Сетка 2*2
        if($SysValue['my']['setka_num'] == 2) {
            if($j==1) {
                $td="<td valign=\"top\" class=\"panel_r\">";
                $j=0;
                $td2="</td>";
            }
            else {
                $td="<tr><TD class=setka colspan=3 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
                $td.="<tr><td valign=\"top\" class=\"panel_l\">";
                $j++;
                $td2="</td>";
                $td2.="<TD width=1 class=setka><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
            }
            $disp.=$td.$dis.$td2;
        }

        // Сетка 3*3
        if($SysValue['my']['setka_num'] == 3) {
            if($j==3) {
                $td="<td  valign=\"top\">";
                $j++;
                $td2="</td></tr>";
                $disp.=$td.$dis.$td2;
            }
            if($j==2) {
                $td="<td  valign=\"top\">";
                $j++;
                $td2="</td>";
                $td2.="<TD width=1  class=setka><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
                $disp.=$td.$dis.$td2;
            }
            if($j==1) {
                $td="<tr><TD width=100%  class=setka colspan=5 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
                $td.="<tr><td  valign=\"top\">";
                $j++;
                $td2="</td>";
                $td2.="
<TD width=1  class=setka><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
                $disp.=$td.$dis.$td2;
            }

            if($j==4) {
                $j=1;
            }
        }
    }

    // Определяем переменные
    $SysValue['other']['catalog']= $SysValue['lang']['catalog'];
    $cat=$LoadItems['Podcatalog'][$category]['parent_to'];
    $SysValue['other']['catalogCat']= "";
    $SysValue['other']['catalogCategory']="Распродажа";
    $SysValue['other']['producFound']= $SysValue['lang']['found_of_products'];
    $SysValue['other']['productPodcat']=$category;
    $SysValue['other']['productPodcatId']=$LoadItems['Catalog'][$category]['parent_to'];

    // Направление сортировки
    switch($f) {
        case(1):
            $SysValue['other']['productSortNext']=2;
            $SysValue['other']['productSortImg']=1;
            $SysValue['other']['productSortTo']=1;
            break;
        case(2):
            $SysValue['other']['productSortNext']=1;
            $SysValue['other']['productSortImg']=2;
            $SysValue['other']['productSortTo']=2;
            break;
        default:
            $SysValue['other']['productSortNext']=2;
            $SysValue['other']['productSortImg']=1;
            $SysValue['other']['productSortTo']=1;
    }

    switch($s) {
        case(1):
            $SysValue['other']['productSortA']="sortActiv";
            $SysValue['other']['productSort']=1;
            break;
        case(2):
            $SysValue['other']['productSortB']="sortActiv";
            $SysValue['other']['productSort']=2;
            break;
        case(3):
            $SysValue['other']['productSortC']="sortActiv";
            $SysValue['other']['productSort']=3;
            break;
        case(4):
            $SysValue['other']['productSortD']="sortActiv";
            $SysValue['other']['productSort']=4;
            break;
        default:
            $SysValue['other']['productSort']=3;
            $SysValue['other']['productSortC']="sortActiv";
    }

    $SysValue['other']['productNumOnPage']=$SysValue['lang']['row_on_page'];

    // Сортировка по цене
    $SysValue['other']['productRriceOT']=TotalClean($_POST['priceOT'],1);
    $SysValue['other']['productRriceDO']=TotalClean($_POST['priceDO'],1);

    if($LoadItems['Podcatalog'][$n]['num_cow']>0)
        $SysValue['other']['productNumRow']=$LoadItems['Catalog'][$n]['num_cow'];
    else $SysValue['other']['productNumRow']=$LoadItems['System']['num_row'];

    $SysValue['other']['productPage']=$SysValue['lang']['page_now'];
    $SysValue['other']['productPageNav']=DispNewpriceNav();
    $SysValue['other']['productDir']=$SysValue['nav']['path'];

    if($num_rows>0) @$SysValue['other']['productPageDis']=@$disp;
    else @$SysValue['other']['productPageDis']=
                "<DIV style=\"padding:10px\"><h2>Товаров выбранного типа сегодня нет в продаже</h2></DIV>";

    // Подключаем шаблон
    $disp=ParseTemplateReturn($SysValue['templates']['product_page_spec_list']);
    return $disp;
}
?>