<?php

/**
 * Вывод категорий для поиска
 * @package PHPShopCoreDepricated
 * @param int $c ИД категории
 * @return string
 */
function DispCategory($c) {
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
    
    $disp="<select name=\"cat\"  size=1 onchange=\"proSerch(this.value)\"><option value=\"0\">Все разделы</option>$dis</select>";
    return $disp;
}

/**
 * Запись поиска в журнал
 * @package PHPShopDepricated
 * @param string $name
 * @param int $num
 * @param int $cat
 * @param int $set
 */
function SearchJurnalWrite($name,$num,$cat,$set) {
    global $SysValue;
    
    $sql="INSERT INTO ".$SysValue['base']['table_name18']." VALUES ('','$name','$num','".date("U")."','".$_SERVER['HTTP_REFERER'] ."','$cat','$set')";
    $result=mysql_query($sql);
    $SysValue['sql']['num']++;
}

/**
 * SQL запрос для поиска
 * @package PHPShopCoreDepricated
 * @param string $words строка поиска
 * @param int $cat ИД категории для поиска
 * @return string
 */
function Page_search($words,$cat) {
    global $SysValue,$LoadItems;
    
    $v=$_REQUEST['v'];
    $set=$_REQUEST['set'];
    $pole=$_REQUEST['pole'];
    $p=$_REQUEST['p'];
    if(empty($p)) $p=1;
    
    $words=trim($words);
    $num_row=$LoadItems['System']['num_row'];
    $num_ot=0;
    $q=0;
    $sortV=null;
    $sort=null;
    
    // Сортировка по характеристикам
    if(empty($_POST['v'])) @$v=$SysValue['nav']['query']['v'];
    if(is_array($v))
        foreach($v as $key=>$value) {
            if(!empty($value)) {
                $hash=$key."-".$value;
                $sortV.=" and vendor REGEXP 'i".$hash."i' ";
            }
        }
    
    // Чистка запроса
    $words=TotalClean($words,2);
    $words=CleanSearch($words);// Secure Fix
    
    if(empty($pole)) $pole=1;
    if(empty($set)) $set=1;
    
    if($set == 1) {
        
        $_WORDS=explode(" ",$words);
        switch($pole) {
            
            case(1):
                foreach($_WORDS as $w)
                    $sort.="(name REGEXP '$w' or keywords REGEXP '$w') and ";
                break;
            
            case(2):
                foreach($_WORDS as $w)
                    $sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or keywords REGEXP '$w' or uid REGEXP '$w' or id = '$w') and ";
                break;
        }
    }
    else {
        
        // Разделяем слова
        $_WORDS=explode(" ",$words);
        switch($pole) {
            case(1):
                foreach($_WORDS as $w)
                    $sort.="(name REGEXP '$w' or uid REGEXP '$w' or id = '$w' or keywords REGEXP '$w') or ";
                break;
            
            case(2):
                foreach($_WORDS as $w)
                    $sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or keywords REGEXP '$w' or uid REGEXP '$w' or id = '$w') or ";
                break;
        }
    }
    
    // По категориям
    if($cat!=0) $string=" category=$cat and";
    
    // Перенаправление поиска
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
    $SysValue['sql']['num']++;
    return $sql;
}

/**
 * Навигация для раздела поиска
 * @package PHPShopCoreDepricated
 * @param <type> $words
 * @param <type> $cat
 * @return <type>
 */
function SearchNav($words,$cat) {
    global $SysValue,$LoadItems;
    
    $v=$_REQUEST['v'];
    $set=$_REQUEST['set'];
    $pole=$_REQUEST['pole'];
    $p=$_REQUEST['p'];
    if(empty($p)) $p=1;
    if(empty($pole)) $pole=1;
    if(empty($set)) $set=1;
    $sortV=null;
    $sortNav=null;
    $sort=null;
    $navigat=null;
    
    // Все страницы
    if($p=="all") $productSortD="sortActiv";
    else $p=TotalClean($p,1);
    
    // По категориям
    if($cat!=0) $string=" category=$cat and";
    
    // Сортировка по характеристикам
    if(empty($_POST['v'])) $v=$SysValue['nav']['query']['v'];
    if(is_array($v))
        foreach($v as $key=>$value) {
            if(!empty($value)) {
                $hash=$key."-".$value;
                $sortV.=" and vendor REGEXP 'i".$hash."i' ";
                $sortNav.="&v[$key]=$value";
            }
        }
    
    // Чистка запроса
    $words=TotalClean($words,2);
    $words=CleanSearch($words);
    
    // Переадресация
    $prewords=PreSearchBase($words);
    
    if($set == 1) {
        $_WORDS=explode(" ",$words);
        switch($pole) {
            case(1):
                foreach($_WORDS as $w)
                    $sort.="(name REGEXP '$w' or keywords REGEXP '$w') and ";
                break;
            case(2):
                foreach($_WORDS as $w)
                    $sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or keywords REGEXP '$w' or uid REGEXP '$w' or id = '$w') and ";
                break;
        }
    }
    else {
        // Разделяем слова
        $_WORDS=explode(" ",$words);
        switch($pole) {
            case(1):
                foreach($_WORDS as $w)
                    $sort.="(name REGEXP '$w' or uid = '$w' or id = '$w' or keywords REGEXP '$w') or ";
                break;
            case(2):
                foreach($_WORDS as $w)
                    $sort.="(name REGEXP '$w' or content REGEXP '$w' or description REGEXP '$w' or keywords REGEXP '$w' or uid REGEXP '$w' or id = '$w') or ";
                break;
        }
    }
    
    $num_row=$LoadItems['System']['num_row'];
    $i=1;
    if($set == 1) $num_page=NumFrom("table_name2","where $string $sort id!=0 $prewords $sortV and enabled='1'");
    else $num_page=NumFrom("table_name2","where $string $sort id=0 $prewords $sortV and enabled='1'");
    
    // Запись в журнал поиска
    $SearchJurnalWrite=SearchJurnalWrite($words,$num_page,$cat,$set);
    
    $num=$num_page/$num_row;
    while ($i<$num+1) {
        if($i!=$p) {
            if($i==1) $pageOt=$i+$pageDo;
            else $pageOt=$i+$pageDo-$i;
            
            $pageDo=$i*$num_row;
            $navigat.="
	     <a href=\"./?words=".$words."&pole=".$pole."&set=".$set."&p=".$i."&cat=".$cat."$sortNav\">".$pageOt."-".$pageDo."</a> | ";
        }
        else {
            
            if($i==1) $pageOt=$i+$pageDo;
            else $pageOt=$i+$pageDo-$i;
            
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
<a href=\"./?words=".$words."&set=".$set."&p=".($p-1)."&cat=".$cat."$sortNav\" title=\"Назад\"><img src=\"images/shop/3.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\"></a>
                $navigat<a href=\"./?words=".$words."&set=".$set."&p=".$p_to."&cat=".$cat."$sortNav\"><img src=\"images/shop/4.gif\" width=\"16\" height=\"15\" border=\"0\" align=\"absmiddle\" title=\"Вперед\"></a>
&nbsp;&nbsp;
<a href=\"./?words=".$words."&set=".$set."&p=all&cat=".$cat."&pole=".$pole."$sortNav\" class=\"$productSortD\">Все позиции</a>
                ";
    }
    return $nava;
}

/**
 * Выдача переадресации поиска из БД
 * @package PHPShopDepricated
 * @param string $words строка поиска
 * @return string 
 */
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

/**
 * Вывод резултат поиска
 * @package PHPShopCoreDepricated
 * @param string $words строка поиска
 * @param int $cat ИД категории поиска
 * @return string 
 */
function DisSearch($words,$cat){
    global $SysValue,$LoadItems;
    
    $v=$_REQUEST['v'];
    $set=$_REQUEST['set'];
    $pole=$_REQUEST['pole'];
    $p=$_REQUEST['p'];
    $words=TotalClean($words,2);
    $words=CleanSearch($words);
    $cat=TotalClean($cat,1);
    $i=0;
    $j=1;
    $n=0;
    $disp=null;
    
    if(!empty($words)) {
        
        // Создание запроса к БД
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

            // Вывод опций для корзины
            $DispCatOptionsTest=DispCatOptionsTest($category);
            if($DispCatOptionsTest == 1) {
                $SysValue['other']['ComStartCart']="<!--";
                $SysValue['other']['ComEndCart']="-->";
            }else {
                $SysValue['other']['ComStartCart']="";
                $SysValue['other']['ComEndCart']="";
            }
            
            // Если товар на складе
            if($sklad==0) {
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
            if(!is_numeric($row['price'])) $sklad = 1;
            
            $uid=$row['uid'];
            $description=stripslashes($row['description']);

            // Режим Multibase
            $admoption=$GLOBALS['admoption'];
            if($admoption['base_enabled'] == 1 and !empty($admoption['base_host']))
                $pic_small=eregi_replace("/UserFiles/","http://".$admoption['base_host']."/UserFiles/",$pic_smal);
            
            // Показывать состояние склада
            if($admoption['sklad_enabled'] == 1 and $items>0)
                $SysValue['other']['productSklad']= $SysValue['lang']['product_on_sklad']." ".$items." ".$SysValue['lang']['product_on_sklad_i'];
            else $SysValue['other']['productSklad']="";
            
            // Пустая картинка
            if(empty($pic_small))
                $pic_small="images/shop/no_photo.gif";
            
            // Определяем переменные
            $SysValue['other']['productPriceMoney']= $LoadItems['System']['dengi'];
            $SysValue['other']['productSale']= $SysValue['lang']['product_sale'];
            $SysValue['other']['productInfo']= $SysValue['lang']['product_info'];
            $SysValue['other']['productName']= $name;
            $SysValue['other']['productArt']= $uid;
            $SysValue['other']['productDes']= mySubstr($description,250);
            $SysValue['other']['productId']= $id;
            $SysValue['other']['productUid']= $id;
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

            $dis=ParseTemplateReturn($SysValue['templates']['main_search_forma_2']);
            
            $td="<tr><TD colspan=5 height=1><IMG height=1 src=\"images/spacer.gif\" width=1></TD></tr>";
            $td.="<tr ><td width=\"100%\" valign=\"top\">";
            $j++;
            $td2="</td>";
            $td2.="</TD></tr>";
            $disp.=$td.$dis.$td2;
            $i++;
        }   
        
        // Определяем переменные
        $SysValue['other']['catalog']= $SysValue['lang']['catalog'];
        //@$SysValue['other']['catalogCat']= $LoadItems['Product'][$id]['category'];
        $SysValue['other']['searchString']=$words;
        $SysValue['other']['producFound']= $SysValue['lang']['found_of_products'];
        $SysValue['other']['productNumOnPage']=$SysValue['lang']['row_on_page'];
        $SysValue['other']['productNumRow']=$LoadItems['System']['num_row'];
        $SysValue['other']['productPage']=$SysValue['lang']['page_now'];
        $SysValue['other']['productPageThis']=$p;
        
        // Навигация
        $SysValue['other']['searchPageNav']=SearchNav($words,$cat);
        
        // Категории
        $SysValue['other']['searchPageCategory']=DispCategory($cat);
        
        // Сортировка по характеристикам
        $SysValue['other']['searchPageSort']=DispCatSort($cat);
        
        // Если не найдено
        if(empty($disp)) $SysValue['other']['productPageDis']="<p style=\"padding:10px\"><h3>Ничего не найдено</h3></p>";
        else $SysValue['other']['productPageDis']=$disp;
 
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

        // Категории
        $SysValue['other']['searchPageCategory']=DispCategory($cat);

        // Подключаем шаблон
        $disp=ParseTemplateReturn($SysValue['templates']['search_page_list']);
    }
    return $disp;
}
?>