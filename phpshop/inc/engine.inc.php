<?php
/**
 * Secure Fix 6.2
 * @package PHPShopDepricated
 * @param string $search строка
 * @return string
 */
function CleanSearch($search) {
    $count=strlen($search);
    $search=strtolower($search);
    $i=0;
    while($i<($count/7)) {
        $search = str_replace("'", "", $search);
        $search = str_replace("union", "", $search);
        $search = str_replace("select", "", $search);
        $search = str_replace("insert", "", $search);
        $search = str_replace("delete", "", $search);
        $search = str_replace("update", "", $search);
        $i++;
    }
    return $search;
}

/**
 * Редактор строки, вырезание до точки
 * @package PHPShopDepricated
 * @param string $str строка
 * @param int $a кол-во символом
 * @return string 
 */
function mySubstr($str,$a) {
    if(empty($str)) return $str;
    $str = htmlspecialchars(strip_tags($str));
    for ($i = 1; $i <= $a; $i++) {
        if($str{$i} == ".") $T=$i;
    }
    if($T<1) return substr($str, 0, $a)."...";
    else return substr($str, 0, $T+1);
}


/**
 * Выборка колонки цен для статуса пользователя
 * @package PHPShopDepricated
 * @param int $n ИД пользователя
 * @return int 
 */
function GetUsersStatusPrice($n) {
    global $SysValue;
    $sql="select price from ".$SysValue['base']['table_name28']." where id=$n";
    $result=mysql_query($sql);
    $row = mysql_fetch_array(@$result);
    
    return $row['price'];
}


/**
 * Выборка из базы нужной колонки цены
 * @package PHPShopDepricated
 * @param int $n номер ценовой колонки
 * @param float $price начальная цена
 * @return float 
 */
function ReturnTruePriceUser($n,$price) {
    global $SysValue,$LoadItems;
    
    if(session_is_registered('UsersStatus')) {
        $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
        $pole="price".$GetUsersStatusPrice;
        
        $sql="select $pole from ".$SysValue['base']['table_name2']." where id='$n'";
        $result=mysql_query($sql);
        @$row = mysql_fetch_array(@$result);
        if(!empty($row[$pole])) return $row[$pole];
        else return $price;
    } else return $price;
}


/**
 * Создание массива характеристик
 * @package PHPShopDepricated
 * @param string $sql дополнительный запрос к БД
 * @return array 
 */
function Sorts($sql=false) {
    global $SysValue;
    
    $Sorts=array();
    
    $sql="select * from ".$SysValue['base']['table_name21'].$sql;
    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $name=$row['name'];
        $page=$row['page'];
        
        $array=array(
                "id"=>$id,
                "page"=>$page,
                "name"=>$name
        );
        $Sorts[$id]=$array;
    }
    $SysValue['sql']['num']++;
    
    return $Sorts;
}


/**
 * Создание массива каталогов характеристик
 * @package PHPShopDepricated
 * @return array 
 */
function CatalogSorts() {
    global $SysValue;
    
    $Sorts=array();
    
    $sql="select * from ".$SysValue['base']['table_name20'];
    @$result=mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $name=$row['name'];
        $category=$row['category'];
        $filtr=$row['filtr'];
        $flag=$row['flag'];
        $goodoption=$row['goodoption'];
        $page=$row['page'];
        $array=array(
                "id"=>$id,
                "name"=>$name,
                "category"=>$category,
                "filtr"=>$filtr,
                "page"=>$page,
                "flag"=>$flag,
                "goodoption"=>$goodoption
        );
        $Sorts[$id]=$array;
    }
    $SysValue['sql']['num']++;
    
    return $Sorts;
}


/**
 * Создание массива каталогов страниц
 * @package PHPShopDepricated
 * @return array 
 */
function CatalogPage() {
    global $SysValue;
    
    $sql="select id,name,parent_to from ".$SysValue['base']['table_name29'];
    $result=mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $name=$row['name'];
        $parent_to=$row['parent_to'];
        $array=array(
                "id"=>$id,
                "name"=>$name,
                "parent_to"=>$parent_to
        );
        $Catalog[$id]=$array;
    }
    $SysValue['sql']['num']++;
    
    return $Catalog;
}


/**
 * Создание массива каталогов страниц
 * @package PHPShopDepricated
 * @return array
 */
function  CatalogPageKeys() {
    global $SysValue;

    $sql="select id,parent_to  from ".$SysValue['base']['table_name29']." order by num";
    $result=mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $parent_to=$row['parent_to'];
        $Catalog[$id]=$parent_to;
    }
    $SysValue['sql']['num']++;

    return $Catalog;
}

/**
 * Создание массива ключей каталогов
 * @package PHPShopDepricated
 * @return array
 */
function  CatalogKeys() {
    global $SysValue;

    $sql="select id,parent_to,num  from ".$SysValue['base']['table_name']." order by num";
    $result=mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $parent_to=$row['parent_to'];
        $Catalog[$id]=$parent_to;
    }
    $SysValue['sql']['num']++;

    return $Catalog;
}

/**
 * Создание массива каталогов
 * @package PHPShopDepricated
 * @return array
 */
function  Catalog() {
    global $SysValue;

    $sql="select * from ".$SysValue['base']['table_name'];
    $result=mysql_query($sql);
    while (@$row = mysql_fetch_array(@$result)) {
        $id=$row['id'];
        $name=$row['name'];
        $parent_to=$row['parent_to'];
        $num_row=$row['num_row'];
        $num_cow=$row['num_cow'];
        $sort=$row['sort'];
        $servers=$row['servers'];
        $title_enabled=$row['title_enabled'];
        $descrip_enabled=$row['descrip_enabled'];
        $keywords_enabled=$row['keywords_enabled'];
        $skin_enabled=$row['skin_enabled'];
        $skin=$row['skin'];
        $array=array(
                "id"=>$id,
                "name"=>$name,
                "parent_to"=>$parent_to,
                "num_row"=>$num_row,
                "num_cow"=>$num_cow,
                "sort"=>$sort,
                "title_enabled"=>$title_enabled,
                "descrip_enabled"=>$descrip_enabled,
                "keywords_enabled"=>$keywords_enabled,
                "skin_enabled"=>$skin_enabled,
                "skin"=>$skin,
                "order_by"=>$row['order_by'],
                "order_to"=>$row['order_to'],
                "parent_len"=>$row['parent_len'],
                "vid"=>$row['vid'],
                "servers"=>$servers
        );
        $Catalog[$id]=$array;
    }
    $SysValue['sql']['num']++;

    return $Catalog;
}

/**
 * Создание массива товаров
 * @package PHPShopDepricated
 * @param string $str дополнитлеьный параметр выборки
 * @return array
 */
function  Product($str="") {
    global $SysValue,$LoadItems;
    
    if($str != "none") {
        
        $System=DispSystems();
        $sql="select id,uid,name,category,price,price_n,sklad,odnotip,vendor,title_enabled,datas,page,user,descrip_enabled,keywords_enabled,pic_small,pic_big,parent,baseinputvaluta  from ".$SysValue['base']['table_name2'].$str;
        $result=mysql_query($sql);
        while (@$row = mysql_fetch_array($result)) {
            $id=$row['id'];
            $uid=$row['uid'];
            $name=$row['name'];
            $category=$row['category'];
            $price=$row['price'];
            $sklad=$row['sklad'];
            $priceNew=$row['price_n'];
            $price=($price+(($price*$System['percent'])/100));
            $title_enabled=$row['title_enabled'];
            $descrip_enabled=$row['descrip_enabled'];
            $keywords_enabled=$row['keywords_enabled'];
            $datas=$row['datas'];
            $page=explode(",",$row['page']);
            $user=$row['user'];
            $pic_small=$row['pic_small'];
            $pic_big=$row['pic_big'];
            
            // Выборка из базы нужной колонки цены
            if(session_is_registered('UsersStatus')) {
                $GetUsersStatusPrice=GetUsersStatusPrice($_SESSION['UsersStatus']);
                if($GetUsersStatusPrice>1) {
                    $pole="price".$GetUsersStatusPrice;
                    $pricePersona=$row[$pole];
                    if(!empty($pricePersona)) 
                        $price=($pricePersona+(($pricePersona*$System['percent'])/100));
                }
            }
            
            // Если есть новая цена
            if($priceNew>0) {
                $priceNew=($priceNew+(($priceNew*$System['percent'])/100));
                $priceNew=number_format($priceNew,"2",".","");
                //$priceNew=$priceNew." ".$System['dengi'];
            }
            
            // Проверка на нулевую цену
            if(!is_numeric($row['price']))
                $sklad = 1;
            
            $uid=$row['uid'];
            $odnotip=explode(",",$row['odnotip']);
            $parent=explode(",",$row['parent']);
            $vendor=$row['vendor'];
            $baseinputvaluta=$row['baseinputvaluta'];	
            $array=array(
                    "category"=>$category,
                    "id"=>$id,
                    "name"=>$name,
                    "price"=>$price,
                    "priceNew"=>$priceNew,
                    "priceSklad"=>$sklad,
                    "odnotip"=>$odnotip,
                    "parent"=>$parent,
                    "vendor"=>$vendor,
                    "uid"=>$uid,
                    "pic_small"=>$pic_small,
                    "pic_big"=>$pic_big,
                    "title_enabled"=>$title_enabled,
                    "descrip_enabled"=>$descrip_enabled,
                    "keywords_enabled"=>$keywords_enabled,
                    "datas"=>$datas,
                    "page"=>$page,
                    "baseinputvaluta"=>$baseinputvaluta,
                    "user"=>$user
            );
            $Products[$id]=$array;
        }
        $SysValue['sql']['num']++;

        return $Products;
    }
}

/**
 * Подсчет кол-ва товара в каталоге
 * @package PHPShopDepricated
 * @param string $from_base имя таблица
 * @param string $query дополнительное условие
 * @return int 
 */
function NumFrom($from_base,$query) {
    global $SysValue;
    
    $num=0;
    $sql="select COUNT('id') as count from ".$SysValue['base'][$from_base]." ".$query;
    $result=mysql_query($sql);
    @$row = mysql_fetch_array($result);
    $num=$row['count'];
    
    return $num;
}

/**
 * Создание массива ситемных настроек
 * @package PHPShopDepricated
 * @return atrray 
 */
function DispSystems(){
    global $SysValue;
    
    $sql="select * from ".$SysValue['base']['table_name3'];
    $result=mysql_query($sql);
    @$row = mysql_fetch_array($result);
    if(is_array($row))
        foreach($row as $k=>$v)
            $array[$k]=$v;
    
    // Валюта по умолчанию
    //$array['dengi']=4;
    $SysValue['sql']['num']++;
    
    return $array;
}

/**
 * Создание массива валют
 * @package PHPShopDepricated
 * @return array 
 */
function DispValuta(){
    
    global $SysValue;
    
    $sql="select * from ".$SysValue['base']['table_name24']." where enabled='1' order by num";
    $result=mysql_query($sql);
    while (@$row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $name=$row['name'];
        $code=$row['code'];
        $iso=$row['iso'];
        $kurs=$row['kurs'];
        $array=array(
                "id"=>$id,
                "name"=>"$name",
                "code"=>"$code",
                "iso"=>"$iso",
                "kurs"=>"$kurs"
            );
        $Valuta[$id]=$array;
    }
    $SysValue['sql']['num']++;
    
    return $Valuta;
}

/**
 * Проверка существования файла обработчика
 * @package PHPShopDepricated
 * @param string $page имя файла
 * @return string 
 */
function Open($page){
    global $SysValue;
    
    $page=$page.".php";
    $handle=@opendir($SysValue['my']['default_page_dir']);
    while ($file = readdir($handle)) {
        if($file==$page) {
            return $page;
            exit;
        }
    }
    return "404";
}


/**
 * Вывод содержания страницы
 * @package PHPShopDepricated
 * @param string $name имя страницы
 * @param string $str условие выборки
 * @return array
 */
function Open_from_base($name,$str="none") {
    global $SysValue;

    $name=TotalClean($name,2);
    if($str=="none") $sql="select * from ".$SysValue['base']['table_name11']." where link='$name'";
    else $sql="select * from ".$SysValue['base']['table_name11']." $str";
    $result=mysql_query($sql);
    $row=mysql_fetch_array($result);
    @$SysValue['sql']['num']++;
    $names=$row['name'];
    $content=stripslashes($row['content']);
    $link=$row['link'];
    $ar=array($names,$content,$link);

    return $ar;
}
?>