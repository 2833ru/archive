<?php  
/**
 * Дата модификации документа
 * @package PHPShopDepricated 
 * @return array 
 */
function GetLastModifiedTime() {
    global $LoadItems;
    
    $updateU = $LoadItems['System']['updateU'];
    $updateDate=gmdate("D, d M Y H:i:s",$updateU);
    $array=array($updateU,$updateDate." GMT");
    
    return $array;
}

/**
 * Дата модификации документа подкаталога
 * @package PHPShopDepricated
 * @param int $productID ИД товара
 * @return array
 */
function GetCatalogDate($productID) {
    global $SysValue;

    $sql="select MAX(datas) as datas from ".$SysValue['base']['table_name2']." where category='$productID'";
    $result=mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    $updateDate=gmdate("D, d M Y H:i:s",$row['datas']);
    $array=array($row['datas'],$updateDate." GMT");

    return $array;
}


/**
 * Дата модификации документа подробного описания товара
 * @package PHPShopDepricated
 * @param int $productID ИД товара
 * @return array 
 */
function GetProductDate($productID) {
    global $SysValue;
    
    $sql="select datas from ".$SysValue['base']['table_name2']." where id=$productID";
    $result=mysql_query($sql);
    @$row = mysql_fetch_array(@$result);
    $updateDate=gmdate("D, d M Y H:i:s",$row['datas']);
    $array=array($row['datas'],$updateDate." GMT");
    
    return $array;
}
 
/**
 * Дата модификации документа информационной страницы
 * @package PHPShopDepricated
 * @param int $pageID ссылка страницы
 * @return string 
 */
function GetPageDate($pageID) {
    global $SysValue;
    
    $sql="select datas from ".$SysValue['base']['table_name11']." where link='$pageID'";
    $result=mysql_query($sql);
    $row = mysql_fetch_array($result);
    $updateDate=gmdate("D, d M Y H:i:s",$row['datas']);
    $array=array($row['datas'],$updateDate." GMT");
    
    return $array;
}

/**
 * Дата модификации документа каталога страницы
 * @package PHPShopDepricated
 * @param string $catalogID ИД каталога
 * @return string
 */
function GetCatalogPageDate($catalogID) {
    global $SysValue;

    $catalogID=TotalClean($catalogID,1);
    $sql="select datas from ".$SysValue['base']['table_name11']." where category=$catalogID limit 0,1";
    @$result=mysql_query($sql);
    $num=mysql_numrows($result);

    if($num>0) $row = mysql_fetch_array($result);
    else $row['datas']=date("U");

    $updateDate=gmdate("D, d M Y H:i:s",$row['datas']);
    $array=array($row['datas'],$updateDate." GMT");

    return $array;
}


if($SysValue['nav']['nav']=="UID" or $SysValue['nav']['nav']=="id")
    $lastmodified=GetProductDate($SysValue['nav']['id']);
elseif($SysValue['nav']['path']=="shop" and $SysValue['nav']['nav']=="CID")
    $lastmodified=GetCatalogDate($SysValue['nav']['id']);
elseif($SysValue['nav']['path']=="page" and $SysValue['nav']['nav']=="CID")
    $lastmodified=GetCatalogPageDate($SysValue['nav']['id']);
elseif($SysValue['nav']['path']=="page")
    $lastmodified=GetPageDate($SysValue['nav']['name']);
else $lastmodified=GetLastModifiedTime();


// Посылаем заголовки
if($SysValue['cache']['last_modified'] == "true") {

    // Некторые сервера требуют обзательных заголовков 200
    //header("HTTP/1.1 200");
    //header("Status: 200");
    Header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    Header("Pragma: no-cache"); // HTTP/1.1

    // Дата модификации, кроме корзины
    if($SysValue['nav']["path"]!="order") Header("Last-Modified: ".$lastmodified[1]);
}
?>