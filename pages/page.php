<?php
/**
 * Обработчик страниц
 * @author PHPShop Software
 * @package PHPShopCoreDepricated
 */

// Библиотека
include_once($SysValue['file']['page']);

if($SysValue['nav']['nav']=="CID") {

    if($SysValue['nav']['id'] == "00")
        header("Location: ../map/");


    $SysValue['other']['thisCat'] = $LoadItems['CatalogPage'][$SysValue['nav']['id']]['parent_to'];

    // Проверка вложенности
    $podcatalog_id = @array_keys($LoadItems['CatalogPageKeys'],$SysValue['nav']['id']);

    // Если только 1 страница, редирект на нее
    $GetPageNum = GetPageNumFromCategory($SysValue['nav']['id']);

    if(count($podcatalog_id)>0)
        $SysValue['other']['DispShop']=DispCatalogPageTree($SysValue['nav']['id']);
    elseif(empty($GetPageNum))
        $SysValue['other']['DispShop']=DispListPage($SysValue['nav']['id']);
    elseif(!empty($GetPageNum)) $SysValue['other']['DispShop']=DispContentPage($GetPageNum,$flag=1);

}
else {
    $SysValue['other']['DispShop']=DispContentPage($SysValue['nav']['name']);
}


if($SysValue['other']['DispShop'] == 404) {
    header("HTTP/1.0 404 Not Found");
    header("Status: 404 Not Found");
    include("pages/error.php");
}else {
    header("HTTP/1.0 200 OK");
    header("Status: 200 OK");
}

// Подключаем шаблон
$SysValue['other']['NavActive']=$SysValue['nav']['name'];
ParseTemplate($SysValue['templates']['shop']);
?>