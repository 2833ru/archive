<?
/*
+-------------------------------------+
|  PHPShop Enterprise                 |
|  Модуль Поиска                      |
+-------------------------------------+
*/

function DispCategory($c)// вывод категорий
{
    global $LoadItems,$SysValue;

    if(is_array($LoadItems['CatalogKeys']))
        foreach($LoadItems['CatalogKeys'] as $cat=>$val) {

            $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$cat);
            if(count($podcatalog_id)==0) {
                $parent=$LoadItems['Catalog'][$cat]['parent_to'];
                if ($c==$cat) $sel="selected";
                else $sel="";
                @$dis.="<option value=\"$cat\" $sel>".$LoadItems['Catalog'][$parent]['name']." / ".$LoadItems['Catalog'][$cat]['name']."</option>\n";
            }
        }

    @$disp="<select name=\"cat\"  size=1 onchange=\"proSerch(this.value)\">
<option value=\"0\">Все разделы</option>
            $dis
</select>
            ";
    return @$disp;
}


function SearchJurnalWrite($name,$num,$cat,$set) {// Запись в журнал
    global $SERVER_NAME,$SysValue,$HTTP_REFERER ;
    $sql="INSERT INTO ".$SysValue['base']['table_name18']." VALUES ('','$name','$num','".date("U")."','".$HTTP_REFERER ."','$cat','$set')";
    $result=mysql_query($sql);
    @$SysValue['sql']['num']++;
}

function DispCategoryParent($cat)// вывод категорий для каталогов
{
    global $LoadItems,$SysValue;
    $sql="select id from ".$SysValue['base']['table_name']." where parent_to=".$cat;
    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
        $id=$row['id'];
        @$dis.=" or category=$id";
    }
    @$disp="(category=0".$dis.")";
    @$SysValue['sql']['num']++;
    return @$disp;
}


// Secure Fix 6.0
function CleanSearch($search) {
    $search=strtolower($search);
    $search=str_replace("'", "", $search);
    if (preg_match("/union/i", $search)) $search = str_replace("union", "", $search);
    if (preg_match("/select/i", $search)) $search = str_replace("select", "", $search);
    if (preg_match("/insert/i", $search)) $search = str_replace("insert", "", $search);
    if (preg_match("/delete/i", $search)) $search = str_replace("delete", "", $search);
    if (preg_match("/update/i", $search)) $search = str_replace("update", "", $search);
    return $search;
}


function Page_search($words,$cat)// Создание страниц
{
    global $SysValue,$LoadItems,$p,$set,$pole,$v;
    $words=trim($words);
    $num_row=$LoadItems['System']['num_row'];
    $num_ot=0;
    $q=0;


// Сортировка по характеристикам
    if(empty($_POST['v'])) @$v=$SysValue['nav']['query']['v'];
    if(is_array($v))
        foreach($v as $key=>$value) {
            if(!empty($value)) {
                $hash=$key."-".$value;
                @$sortV.=" and vendor REGEXP 'i".$hash."i' ";
            }
        }


    $words=TotalClean($words,2);
    $words=CleanSearch($words);// Secure Fix

    if(empty($pole)) $pole=1;
    if(empty($set)) $set=1;

    if($set == 1) {
        $_WORDS=explode(" ",$words);

        switch($pole) {

            case(1):
                foreach($_WORDS as $w)
                    @$sort.="(name REGEXP '$w' or keywords REGEXP '$w') and ";
                break;

            case(2):
                foreach($_WORDS as $w)
                    @$sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or keywords REGEXP '$w' or uid REGEXP '$w' or id = '$w') and ";
                break;
        }
    }

    else {
// Разделяем слова
        $_WORDS=explode(" ",$words);


        switch($pole) {

            case(1):
                foreach($_WORDS as $w)
                    @$sort.="(name REGEXP '$w' or uid REGEXP '$w' or id = '$w' or keywords REGEXP '$w') or ";
                break;

            case(2):
                foreach($_WORDS as $w)
                    @$sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or keywords REGEXP '$w' or uid REGEXP '$w' or id = '$w') or ";
                break;
        }
    }




// По категориям
    if($cat!=0)
        $string=" category=$cat and";

    $prewords=PreSearchBase($words);
// Все страницы
    if($p=="all") {
        $sql="select * from ".$SysValue['base']['table_name2']." where $sort id !=0 $prewords and enabled='1' GROUP BY name";
    }
    else while($q<$p) {
            if($set==1)
                $sql="select * from ".$SysValue['base']['table_name2']." where enabled='1' and $string  $sort id!=0 $prewords $sortV GROUP BY name LIMIT $num_ot, $num_row";
            else $sql="select * from ".$SysValue['base']['table_name2']." where enabled='1' and $string $sort id=0 $prewords $sortV GROUP BY name LIMIT $num_ot, $num_row";
            $q++;
            $num_ot=$num_ot+$num_row;
        }
    @$SysValue['sql']['num']++;
    return $sql;
}

function SearchNav($words,$cat)// Навигация 
{
    global $SysValue,$LoadItems,$set,$p,$pole,$v;

    if(empty($pole)) $pole=1;
    if(empty($set)) $set=1;


// Все страницы
    if($p=="all")
        $productSortD="sortActiv";
    else $p=TotalClean($p,1);


// По категориям
    if($cat!=0)
        $string=" category=$cat and";


// Сортировка по характеристикам
    if(empty($_POST['v'])) @$v=$SysValue['nav']['query']['v'];
    if(is_array($v))
        foreach($v as $key=>$value) {
            if(!empty($value)) {
                $hash=$key."-".$value;
                @$sortV.=" and vendor REGEXP 'i".$hash."i' ";
                @$sortNav.="&v[$key]=$value";
            }

        }


    $words=TotalClean($words,2);
    $words=CleanSearch($words);

// Переадресация
    $prewords=PreSearchBase($words);



    if($set == 1) {
        $_WORDS=explode(" ",$words);

        switch($pole) {

            case(1):
                foreach($_WORDS as $w)
                    @$sort.="(name REGEXP '$w' or keywords REGEXP '$w') and ";
                break;

            case(2):
                foreach($_WORDS as $w)
                    @$sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or keywords REGEXP '$w' or uid REGEXP '$w' or id = '$w') and ";
                break;
        }
    }

    else {
// Разделяем слова
        $_WORDS=explode(" ",$words);


        switch($pole) {

            case(1):
                foreach($_WORDS as $w)
                    @$sort.="(name REGEXP '$w' or uid = '$w' or id = '$w' or keywords REGEXP '$w') or ";
                break;

            case(2):
                foreach($_WORDS as $w)
                    $sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or keywords REGEXP '$w' or uid REGEXP '$w' or id = '$w') or ";
                break;
        }
    }



    $num_row=$LoadItems['System']['num_row'];
    $i=1;
    if($set == 1)
        $num_page=NumFrom("table_name2","where $string $sort id!=0 $prewords $sortV and enabled='1'");
    else $num_page=NumFrom("table_name2","where $string $sort id=0 $prewords $sortV and enabled='1'");

// пишем в журнал
    $SearchJurnalWrite=SearchJurnalWrite($words,$num_page,$cat,$set);

    $num=$num_page/$num_row;
    while ($i<$num+1) {
        if($i!=$p) {
            if($i==1) $pageOt=$i+@$pageDo;
            else $pageOt=$i+@$pageDo-$i;

            $pageDo=$i*$num_row;
            @$navigat.="
	     <a href=\"./?words=".$words."&pole=".$pole."&set=".$set."&p=".$i."&cat=".$cat."$sortNav\">".$pageOt."-".$pageDo."</a> | ";
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
<a href=\"./?words=".$words."&set=".$set."&p=".($p-1)."&cat=".$cat."$sortNav\" title=\"Назад\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
                $navigat<a href=\"./?words=".$words."&set=".$set."&p=".$p_to."&cat=".$cat."$sortNav\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
&nbsp;&nbsp;
<a href=\"./?words=".$words."&set=".$set."&p=all&cat=".$cat."&pole=".$pole."$sortNav\" class=\"$productSortD\">Все позиции</a>
                ";
    }
    return @$nava;
}

function PreSearchBase($words) {
    global $SysValue,$LoadItems;
    $sql="select uid from ".$SysValue['base']['table_name26']." where name REGEXP 'i".$words."i'";
    @$result=mysql_query($sql);
    while(@$row = mysql_fetch_array(@$result)) {
        $uid=$row['uid'];
        $uids=explode(",",$uid);
        foreach($uids as $v) @$string.=" or id=$v";
    }
    return $string;
}


function DisSearch($words,$cat)// поиск по базе
{
    global $SysValue,$LoadItems,$set,$p,$pole,$_SESSION;
    $words=TotalClean($words,2);
    $words=CleanSearch($words);
    $cat=TotalClean($cat,1);
    $i=0;
    $j=1;
    $n=0;
    if($words!="") {
        $sql=Page_search($words,$cat);
        $result=mysql_query($sql);

        while($row = mysql_fetch_array($result)) {
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
            $items=$row['items'];

            $baseinputvaluta=$row['baseinputvaluta'];
            $SysValue['other']['productImgBigFoto'] = $pic_big;
            // Выборка из базы нужной колонки цены
            if(session_is_registered('UsersStatus')) {
                $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
                if($GetUsersStatusPrice>1) {
                    $polePrice="price".$GetUsersStatusPrice;
                    $pricePersona=$row[$polePrice];
                    if(!empty($pricePersona))
                        $price=($pricePersona+(($pricePersona*$LoadItems['System']['percent'])/100));
                }
            }





// Показывать состояние склада
            if($admoption['sklad_enabled'] == 1 and $items>0)
                $SysValue['other']['productSklad']= $SysValue['lang']['product_on_sklad']." ".$items." ".$SysValue['lang']['product_on_sklad_i'];
            else $SysValue['other']['productSklad']="";


// Вывод опций для корзины
            $DispCatOptionsTest=DispCatOptionsTest($category);
            if($DispCatOptionsTest == 1) {
                $SysValue['other']['ComStartCart']="<!--";
                $SysValue['other']['ComEndCart']="-->";
            }else {
                $SysValue['other']['ComStartCart']="";
                $SysValue['other']['ComEndCart']="";
            }


            if($sklad==0) {// Если товар на складе


                // Коменты
                $SysValue['other']['Notice']="";
                $SysValue['other']['ComStartCart']="";
                $SysValue['other']['ComEndCart']="";
                $SysValue['other']['ComStartNotice']="<!--";
                $SysValue['other']['ComEndNotice']="-->";

// Если нет новой цены
                if(empty($priceNew)) {
                    $SysValue['other']['productPrice']=GetPriceValuta(ReturnTruePriceUser($id,$price),"",$baseinputvaluta);
                    $SysValue['other']['productPriceRub']= "";
                }else {// Если есть новая цена
                    $SysValue['other']['productPrice']=GetPriceValuta(ReturnTruePriceUser($id,$price),"",$baseinputvaluta);
                    $SysValue['other']['productPriceRub']= "<strike>".GetPriceValuta($priceNew,"",$baseinputvaluta)." ".GetValuta()."</strike>";
                }
            }
            else { // Товар по заказ
                $SysValue['other']['productPrice']=GetPriceValuta(ReturnTruePriceUser($id,$price),"",$baseinputvaluta);
                $SysValue['other']['productPriceRub']=$SysValue['lang']['sklad_mesage'];
                $SysValue['other']['ComStartNotice']="";
                $SysValue['other']['ComEndNotice']="";
                $SysValue['other']['ComStartCart']="<!--";
                $SysValue['other']['ComEndCart']="-->";
                $SysValue['other']['productNotice']=$SysValue['lang']['product_notice'];
            }

            // Проверка на нулевую цену
            if(!is_numeric($row['price']))
                $sklad = 1;

            $uid=$row['uid'];
            $description=stripslashes($row['description']);


// Режим Multibase
            $admoption=unserialize($LoadItems['System']['admoption']);
            if($admoption['base_enabled'] == 1 and !empty($admoption['base_host']))
                $pic_small=eregi_replace("/UserFiles/","http://".$admoption['base_host']."/UserFiles/",$pic_smal);

// Пустая картинка
            if(empty($pic_small))
                $pic_small="images/shop/no_photo.gif";

// Определяем переменые
            $SysValue['other']['productPriceMoney']= $LoadItems['System']['dengi'];
            $SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
            $SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
            $SysValue['other']['productName']= $name;
            $SysValue['other']['productArt']= $uid;
            $SysValue['other']['productDes']= mySubstr($description,250);
            $SysValue['other']['productId']= $id;
            $SysValue['other']['productUid']= $id;
//$SysValue['other']['productWords']= $words;
            $SysValue['other']['productImgWidth']= $LoadItems['System']['width_icon'];
            $SysValue['other']['productValutaName']= GetValuta();
            $SysValue['other']['productImg']= $pic_small;
            $SysValue['other']['productImgWidth']= $Options['width_kratko'];

            if($set==1) $SysValue['other']['searchSetA']="checked";
            elseif($set==2) $SysValue['other']['searchSetB']="checked";
            else $SysValue['other']['searchSetA']="checked";

            if($pole==1) $SysValue['other']['searchSetC']="checked";
            elseif($pole==2) $SysValue['other']['searchSetD']="checked";
            else $SysValue['other']['searchSetC']="checked";



            // Если цены показывать только после аторизации
            if($admoption['user_price_activate']==1 and !$_SESSION['UsersId']) {
                $SysValue['other']['ComStartCart']="<!--";
                $SysValue['other']['ComEndCart']="-->";
                $SysValue['other']['productPrice']="";
                $SysValue['other']['productValutaName']="";
            }


            @$dis=ParseTemplateReturn($SysValue['templates']['main_search_forma_2']);
            $td="<tr><TD colspan=5 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
            $td.="<tr ><td width=\"100%\" valign=\"top\">";
            $j++;
            $td2="</td>";
            $td2.="</TD></tr>";
            @$disp.=$td.$dis.$td2;


            $i++;
        }

//if($i==0)$i="ничего не найдено";

// Определяем переменые
        $SysValue['other']['catalog']= $SysValue['lang']['catalog'];
//@$SysValue['other']['catalogCat']= $LoadItems['Product'][$id]['category'];;
        $SysValue['other']['searchString']=$words;
        $SysValue['other']['producFound']= $SysValue['lang']['found_of_products'];
        $SysValue['other']['productNumOnPage']=$SysValue['lang']['row_on_page'];
        $SysValue['other']['productNumRow']=$LoadItems['System']['num_row'];
        $SysValue['other']['productPage']=$SysValue['lang']['page_now'];
        $SysValue['other']['productPageThis']=$p;
        $SysValue['other']['productPageDis']=$disp;
        $SysValue['other']['searchPageNav']=SearchNav($words,$cat);
        $SysValue['other']['searchPageCategory']=DispCategory($cat);
        $SysValue['other']['searchPageSort']=DispCatSort($cat);

        if(empty($disp)) $SysValue['other']['productPageDis']="<p style=\"padding:10px\"><h3>Ничего не найдено</h3></p>";


// Подключаем шаблон
        $disp=ParseTemplateReturn($SysValue['templates']['search_page_list']);
    }
    else {

        if($set==1) $SysValue['other']['searchSetA']="checked";
        elseif($set==2) $SysValue['other']['searchSetB']="checked";
        else $SysValue['other']['searchSetA']="checked";

        if($pole==1) $SysValue['other']['searchSetC']="checked";
        elseif($pole==2) $SysValue['other']['searchSetD']="checked";
        else $SysValue['other']['searchSetC']="checked";

        $SysValue['other']['searchPageCategory']=DispCategory($cat);
        // Подключаем шаблон
        $disp=ParseTemplateReturn($SysValue['templates']['search_page_list']);
    }
    return $disp;
}
?>
