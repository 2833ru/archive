<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль подбора по параметрам       |
+-------------------------------------+
*/

// Вывод описания сортировки
function DispSortDescription($v) {
    global $SysValue,$LoadItems;


    if(is_array($v))
        foreach($v as $key=>$value)
            $name=$LoadItems['Sort'][$value]['page'];


    $sql="select content from ".$SysValue['base']['table_name11']." where link='$name' and enabled='1' ";
    $result=mysql_query($sql);
    @$row=mysql_fetch_array($result);
    @$SysValue['sql']['num']++;
    $content=stripslashes($row['content']);
    return $content;
}


// Вывод каталогов
function DispSelectionCat() {
    global $SysValue,$LoadItems;
    @$v=$SysValue['nav']['query']['v'];
    

// Сортировка по характеристикам
    if(is_array($v)) {
        foreach($v as $key=>$value) {
            if(true_num($value))
                if($value>0) {
                    $hash=$key."-".$value;
                    @$sort.=" and vendor REGEXP 'i".$hash."i' ";
                    $v_str="v[".$key."]=".$value;
                }
        }

    }

    $sql="select DISTINCT(category) from ".$SysValue['base']['table_name2']." where  enabled='1' $sort";
    $result=mysql_query($sql);


    while(@$row = mysql_fetch_array(@$result)) {
        $category=$row['category'];
        $parent = $LoadItems['Catalog'][$category]['parent_to'];
        $catArray[$parent][]=$category;
    }

    if(is_array($catArray))
    foreach($catArray as $key=>$val) {
        $dis="";
        foreach($val as $value)
            $dis.="<li><a href=\"/shop/CID_$value.html?$v_str\" title=\"".$LoadItems['Catalog'][$value]['name']."\">".$LoadItems['Catalog'][$value]['name']."</a>  ";
        @$disp.="<h2>".$LoadItems['Catalog'][$key]['name']."</h2>$dis";
    }


    $SysValue['other']['DispCatNav'] = $disp;
    $SysValue['other']['sortDes']= DispSortDescription($v);
    @$disp=ParseTemplateReturn("selection/page_selection_list.tpl");
    return $disp;
}


// Навигация 
function DispSelectionNav($v) {
    global $SysValue,$LoadItems,$_POST;
    $v=$SysValue['nav']['query']['v'];
    $p=$SysValue['nav']['id'];
    if(@!$p and !@$_POST['priceSearch']) $p=1;


// Сортировка по характеристикам
    if(is_array($v)) {
        foreach($v as $key=>$value) {
            if($value>0) {
                $hash=$key."-".$value;
                @$sort.=" and vendor REGEXP 'i".$hash."i' ";
                @$querystring.="v[$key]=$value&";
            }
        }

    }

    /*
// Хвост
if(!empty($SysValue['nav']['querystring']))
$querystring="?".$SysValue['nav']['querystring'];
  else $querystring="";
    */


// Все страницы
    if($p=="ALL")
        $productSortD="sortActiv";
    else $p=TotalClean($p,1);

    $num_row=$LoadItems['System']['num_row'];
    $num_page=NumFrom("table_name2","where enabled='1' ".$sort);

    $i=1;
    $num=$num_page/$num_row;
    while ($i<$num+1) {
        if($i!=$p) {

            if($i==1) $pageOt=$i+@$pageDo;
            else $pageOt=$i+@$pageDo-$i;

            $pageDo=$i*$num_row;
            @$navigat.="
	     <a href=\"./page_".$i.".html?".$querystring."\" style='font-weight:normal;'>".$pageOt."-".$pageDo."</a> | ";
        }
        else {

            if($i==1) $pageOt=$i+@$pageDo;
            else $pageOt=$i+@$pageDo-$i;

            $pageDo=$i*$num_row;
            @$navigat.="
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
<a href=\"./page_".($p-1).".html?".$querystring."\" title=\"Назад\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
                $navigat<a href=\"./page_".$p_to.".html?".$querystring."\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>

		";
    }
    return @$nava;
}


function PageSelectionDisp()// Создание страниц
{
    global $SysValue,$LoadItems,$_POST;
    $sort="";
    $n=TotalClean($n,1);
    $p=$SysValue['nav']['id'];
    if(@!$p) $p=1;
    @$v=$SysValue['nav']['query']['v'];

    if($p!="ALL")
        $p=TotalClean($p,1);

    $num_row=$LoadItems['System']['num_row'];
    $num_ot=0;
    $q=0;


// Сортировка по характеристикам
    if(is_array($v)) {
        foreach($v as $key=>$value) {
            if(true_num($value))
                if($value>0) {
                    $hash=$key."-".$value;
                    @$sort.=" and vendor REGEXP 'i".$hash."i' ";
                }
        }

    }


    while($q<$p) {
        $sql="select * from ".$SysValue['base']['table_name2']." where  enabled='1' $sort  LIMIT $num_ot, $num_row";
        $q++;
        $num_ot=$num_ot+$num_row;
    }
    return $sql;
}


function DispSelection()// выбор товаров из данного подкатолога
{
    global $SysValue,$LoadItems,$_POST,$_SESSION;
    $p=$SysValue['nav']['page'];
    $v=TotalClean($SysValue['nav']['query']['v'],1);
    $s=TotalClean($SysValue['nav']['query']['s'],1);
    $f=TotalClean($SysValue['nav']['query']['f'],1);
    if(@!$p) $p=1;
    $n=TotalClean($n,1);

    if($p!="ALL")
        $p=TotalClean($p,1);
    $sql=PageSelectionDisp();
    $result=mysql_query($sql);


    $i=0;
    $SysValue['my']['setka_num']=$LoadItems['System']['num_vitrina'];
    if($SysValue['my']['setka_num'] == 2) $j=0;
    if($SysValue['my']['setka_num'] == 3) $j=1;

    while(@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $uid=$row['uid'];
        $name=$row['name'];
        $category=$row['category'];
        $price=$row['price'];
        $sklad=$row['sklad'];
        $priceNew=$row['price_n'];
        $price=($price+(($price*$LoadItems['System']['percent'])/100));
        $title_enabled=$row['title_enabled'];
        $descrip_enabled=$row['descrip_enabled'];
        $keywords_enabled=$row['keywords_enabled'];
        $datas=$row['datas'];
        $page=explode(",",$row['page']);
        $user=$row['user'];
        $pic_small=$row['pic_small'];
        $pic_big=$row['pic_big'];
        $description=$row['description'];
        $baseinputvaluta=$row['baseinputvaluta'];

        // Если есть новая цена
        if($priceNew>0) {
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

// Пустая картинка
        if(empty($pic_small))
            $pic_small="images/shop/no_photo.gif";

// Определяем переменые
        $SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
        $SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
        $SysValue['other']['productName']= $name;
//$SysValue['other']['productNameLat']=NameToLatin($name);
        $SysValue['other']['productArt']= $uid;

// Описание
        $SysValue['other']['productDes']= $description;

        $SysValue['other']['productImg']= $pic_small;
        $SysValue['other']['productValutaName']= GetValuta();


        if($priceSklad==0) {// Если товар на складе
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

// Подключаем шаблон
        @$dis=ParseTemplateReturn($SysValue['templates']['main_product_forma_'.$SysValue['my']['setka_num']]);



// Сетка 1*1
        if($SysValue['my']['setka_num'] == 1) {

            $td="<tr><TD class=setka colspan=3 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
            $td.="<tr><td valign=\"top\">";
            $j++;
            $td2="</td>";

            @$disp.=$td.$dis;

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

            @$disp.=$td.$dis.$td2;

        }

// Сетка 3*3
        if($SysValue['my']['setka_num'] == 3) {

            if($j==3) {
                $td="<td  valign=\"top\">";
                $j++;
                $td2="</td></tr>";
                @$disp.=$td.$dis.$td2;
            }

            if($j==2) {
                $td="<td  valign=\"top\">";
                $j++;
                $td2="</td>";
                $td2.="<TD width=1  class=setka><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
                @$disp.=$td.$dis.$td2;
            }

            if($j==1) {

                $td="<tr><TD width=100%  class=setka colspan=5 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
                $td.="<tr><td  valign=\"top\">";
                $j++;
                $td2="</td>";
                $td2.="
<TD width=1  class=setka><IMG height=1 src=\"images/spacer.gif\" width=1></TD>";
                @$disp.=$td.$dis.$td2;
            }

            if($j==4) {
                $j=1;
            }

        }

    }


    @$SysValue['other']['productPage']=$SysValue['lang']['page_now'];
    @$SysValue['other']['productPageNav']=DispSelectionNav();
    @$SysValue['other']['productPageDis']=@$disp;
    @$SysValue['other']['productDir']=$SysValue['nav']['path'];

// Подключаем шаблон
    if(empty($dis)) @$SysValue['other']['productPageDis']= "<h4>Ничего не найдено...</h4>";
    @$disp=ParseTemplateReturn("selection/page_selection_list.tpl");

    return @$disp;
}


?>
