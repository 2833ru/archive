<?php

/**
 * Составление SQL запроса для поиска товара
 * @author PHPShop Software
 * @version 1.0
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @return mixed
 */
function query_filter($obj) {
    global $SysValue;
    
    $v=$_REQUEST['v'];
    $set=$_REQUEST['set'];
    $pole=$_REQUEST['pole'];
    $p=$_REQUEST['p'];
    $cat=PHPShopSecurity::TotalClean($_REQUEST['cat'],1);
    if(empty($p)) $p=1;
    
    $words=trim(PHPShopSecurity::true_search($_REQUEST['words']));
    $num_row=$obj->PHPShopSystem->getValue('num_row');
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
    
    // Чистка запроса Secure Fix
    $words=PHPShopSecurity::true_search(PHPShopSecurity::TotalClean($words,2));
    
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

    $sort=substr($sort,0,strlen($sort)-4);
    
    // По категориям
    if($cat!=0) $string=" category=$cat and";
    
    // Перенаправление поиска
    $prewords=search_base($obj,$words);
    
    // Все страницы
    if($p=="all") {
        $sql="select * from ".$SysValue['base']['table_name2']." where $sort $prewords and enabled='1' and parent_enabled='0'";
    }
    else while($q<$p) {

            $sql="select * from ".$SysValue['base']['table_name2']." where enabled='1' and parent_enabled='0' and $string $sort $prewords $sortV LIMIT $num_ot, $num_row";
            $q++;
            $num_ot=$num_ot+$num_row;
        }
    
    $obj->search_order=array(
            'words'=>$words,
            'pole'=>$pole,
            'set'=>$set,
            'cat'=>$cat,
            'string'=>$string,
            'sort'=>$sort,
            'prewords'=>$prewords,
            'sortV'=>$sortV
    );
    
    $obj->set('searchString',$words);
    
    if($set==1) $obj->set('searchSetA','checked');
    elseif($set==2) $obj->set('searchSetB','checked');
    else $obj->set('searchSetA','checked');
    
    if($pole==1) $obj->set('searchSetC','checked');
    elseif($pole==2) $obj->set('searchSetD','checked');
    else $obj->set('searchSetC','checked');
    
    // Возвращаем SQL запрос
    return $sql;
}

/**
 * Выдача переадресации поиска из БД
 * @package PHPShopCoreFunction
 * @param obj $obj объект класса
 * @param string $words строка поиска
 * @return string 
 */
function search_base($obj,$words) {
    $string=null;

    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->debug=$obj->debug;
    $result=$PHPShopOrm->query("select uid from ".$GLOBALS['SysValue']['base']['table_name26']." where name REGEXP 'i".$words."i'");
    while(@$row = mysql_fetch_array(@$result)) {
        $uid=$row['uid'];
        $uids=explode(",",$uid);
        foreach($uids as $v) $string.="id=$v or ";
    }
    
    if(!empty($string)){
    $string=substr($string,0,strlen($string)-3);
    $string =" OR (($string) AND enabled='1')";
    }

    return $string;
}
?>