<?php

/**
 * Реверсивный вывод каталогов страниц
 * @package PHPShopElementsDepricated
 * @param int $n ИД каталога
 * @return string
 */
function DispCatalogPageTreeRevers($n) {
    global $LoadItems,$SysValue;
    $dis=null;
    $disp=null;
    $LoadItems['Catalog'][0]['name']="Каталог";

    $podcatalog_id = array_keys($LoadItems['CatalogPageKeys'],$n);
    foreach($podcatalog_id as $key) {
        $dis.="<li><a href=\"/page/CID_$key.html\" title=\"".$LoadItems['CatalogPage'][$key]['name']."\">".$LoadItems['CatalogPage'][$key]['name']."</a>";
    }
    if(count($podcatalog_id)) $disp="<h1>".$LoadItems['CatalogPage'][$n]['name']."</h1><ul>$dis</ul>";
    return $disp;
}


/**
 * Вывод каталогов страниц
 * @package PHPShopElementsDepricated
 * @param int $n ИД каталога
 * @return string
 */
function DispCatalogPageTree($n) {
    global $LoadItems,$SysValue;
    $n=TotalClean($n,1);
    $dis=null;

    // Определяем переменные
    $parent_to=$LoadItems['CatalogPage'][$n]['parent_to'];

    if($parent_to == 0) {
        $SysValue['other']['parentName']= $SysValue['lang']['catalog'];
        $SysValue['other']['catalogId']= "00";
        $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
    }
    else {
        $SysValue['other']['parentName']= $LoadItems['CatalogPage'][$parent_to]['name'];
        $SysValue['other']['catalogId']= $parent_to;
        $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$n]['name'];
        //$SysValue['other']['thisCat']= $parent_to;
    }

    $SysValue['other']['catalogList']=DispCatalogPageTreeRevers($n);

    // Подключаем шаблон
    $dis.=ParseTemplateReturn('catalog/catalog_page_info_forma.tpl');

    return $dis;
}


/**
 * Вывод фильтров в списке каталогов
 * @package PHPShopElementsDepricated
 * @param int $n ИД категории к
 * @param int $category ИД категории
 * @return string 
 */
function DispFiltr($n,$category) {
    global $SysValue,$LoadItems;
    $dis=null;
    $sql="select * from ".$SysValue['base']['table_name21']." where category=$n order by num";
    $result=mysql_query($sql);
    while($row = mysql_fetch_array($result)) {
        $id=$row['id'];
        $name=$row['name'];
        $SysValue['other']['SortCategoryName']=$LoadItems['CatalogSort'][$n]['name'];
        $SysValue['other']['SortName']=$name;
        $SysValue['other']['SortId']=$id;
        $SysValue['other']['SortCategoryId']=$n;
        $SysValue['other']['CatalogId']=$category;
        $dis.=ParseTemplateReturn('catalog/catalog_tree_forma.tpl');
    }
    return $dis;
}

/**
 * Рекурсивный вывод каталогов товаров
 * @package PHPShopElementsDepricated
 * @param int $n ИД каталога
 * @param string $content описание каталога
 * @return string 
 */
function DispCatalogTreeRevers($n,&$content) {
    global $LoadItems,$SysValue;
    $dis=null;

    $LoadItems['Catalog'][0]['name']="Каталог";

    // Описание каталога
    $sql="select content from ".$SysValue['base']['table_name']." where id=$n";
    $result=mysql_query($sql);
    $row = mysql_fetch_array(@$result);
    $content = $row["content"];

    $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$n);
    foreach($podcatalog_id as $key) {
        $dis.="<li><a href=\"/shop/CID_$key.html\" title=\"".$LoadItems['Catalog'][$key]['name']."\">".$LoadItems['Catalog'][$key]['name']."</a>";

        // Фильтры
        $sort=unserialize($LoadItems['Catalog'][$key]['sort']);
        if(is_array($sort))
            foreach($sort as $v) {
                if($LoadItems['CatalogSort'][$v]['flag']==1)
                    $dis.=DispFiltr($v,$key);
            }
    }
    if(count($podcatalog_id))
        return $dis;
}



/**
 * Вывод дерева каталога товаров
 * @package PHPShopElementsDepricated
 * @param int $n ИД каталога
 * @return string 
 */
function DispCatalogTree($n) {
    global $LoadItems,$SysValue;
    $n=TotalClean($n,1);
    $dis=null;

    // Определяем переменные
    $parent_to=$LoadItems['Catalog'][$n]['parent_to'];

    if($parent_to == 0) {
        $SysValue['other']['parentName']= $SysValue['lang']['catalog'];
        $SysValue['other']['catalogId']= "00";
        $SysValue['other']['catalogName']= $LoadItems['Catalog'][$n]['name'];
        //$SysValue['other']['thisCat']= $n;
    }
    else {
        $SysValue['other']['parentName']= $LoadItems['Catalog'][$parent_to]['name'];
        $SysValue['other']['catalogId']= $parent_to;
        $SysValue['other']['catalogName']= $LoadItems['Catalog'][$n]['name'];
        //$SysValue['other']['thisCat']= $parent_to;
    }

    $SysValue['other']['catalogList']=DispCatalogTreeRevers($n,&$content);
    $SysValue['other']['catalogContent']=$content;

    // Подключаем шаблон
    $dis.=ParseTemplateReturn('catalog/catalog_info_forma.tpl');

    return $dis;
}


function CatalogFilter($id) {
    global $LoadItems;
    if($LoadItems['Podcatalog'][$id]['parent_to'] == 0)
        return $id;
}function
Vivod_page_cat() {
    exit();
}

/**
 * Вывод подкаталогов с 3 уровним
 * @package PHPShopElementsDepricated
 * @param int $n ИД каталога
 * @return string
 */
function Vivod_pot_variant_2($n)
{
    global $LoadItems,$SysValue;
    $dis=null;

    $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$n);

    foreach($podcatalog_id as $key) {
        $SysValue['other']['catalogPodcatalogNew']="";
        $SysValue['other']['catalogTitle']= $LoadItems['Catalog'][$n]['name'];
        $SysValue['other']['catalogNameParent']= $LoadItems['Catalog'][$key]['name'];

        $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$key);

        if(count($podcatalog_id)>0) {

            foreach($podcatalog_id as $k) {
                $SysValue['other']['catalogId']= $k;
                $SysValue['other']['catalogUid']= $k;
                $SysValue['other']['catalogName']= $LoadItems['Catalog'][$k]['name'];
                $SysValue['other']['catalogPodcatalogNew'].=ParseTemplateReturn("catalog/podcatalog_forma_2.tpl");
            }

            $SysValue['other']['catalogId']= $key;
            //$SysValue['other']['catalogUid']= $key;

            $dis.=ParseTemplateReturn("catalog/podcatalog_forma_3.tpl");
        }
        else {
            $SysValue['other']['catalogId']= $n;
            $SysValue['other']['catalogUid']= $key;
            $dis.=ParseTemplateReturn($SysValue['templates']['podcatalog_forma']);
        }
    }
    return $dis;
}


/**
 * Вывод подкаталогов
 * @package PHPShopElementsDepricated
 * @param int $n ИД каталога
 * @return string
 */
function Vivod_pot($n)
{
    global $LoadItems,$SysValue;
    $dis=null;
    $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$n);
    foreach($podcatalog_id as $key) {
        
        // Определяем переменные
        $SysValue['other']['catalogId']= $n;
        $SysValue['other']['catalogUid']= $key;
        $SysValue['other']['catalogTitle']= $LoadItems['Catalog'][$n]['name'];
        $SysValue['other']['catalogName']= $LoadItems['Catalog'][$key]['name'];

        // Подключаем шаблон
        $dis.=ParseTemplateReturn($SysValue['templates']['podcatalog_forma']);
    }
    return $dis;
}

/**
 * Вывод каталогов
 * @package PHPShopElementsDepricated
 * @return string
 */
function Vivod_cats()
{
    global $SysValue,$LoadItems;
    $admoption=unserialize($LoadItems['System']['admoption']);
    $dis=null;

    if($admoption['base_enabled'] == 1)
        $sort="and servers REGEXP 'i".$admoption['base_id']."i'";

    $sql="select id from ".$SysValue['base']['table_name']." where parent_to=0 ".@$sort." order by num";
    $result=mysql_query($sql);

    while($row = mysql_fetch_array($result)) {
        $id=$row['id'];

        // Определяем переменные
        $SysValue['other']['catalogId']= $id;
        $SysValue['other']['catalogI']= $i;
        $SysValue['other']['catalogTemplates']=$SysValue['dir']['templates'].chr(47).$LoadItems['System']['skin'].chr(47);
        $SysValue['other']['catalogPodcatalog']= Vivod_pot($id);
        $SysValue['other']['catalogTitle']= $LoadItems['Catalog'][$id]['name'];
        $SysValue['other']['catalogName']= $LoadItems['Catalog'][$id]['name'];

        $podcatalog_id = array_keys($LoadItems['CatalogKeys'],$id);

        if(!count($podcatalog_id))
            $dis.=ParseTemplateReturn("catalog/catalog_forma_3.tpl");
        else {

            if($LoadItems['Catalog'][$id]['vid'] == 1)
                $dis.=ParseTemplateReturn("catalog/catalog_forma_2.tpl");
            else $dis.=ParseTemplateReturn($SysValue['templates']['catalog_forma']);
        }
    }

    $SysValue['sql']['num']++;
    return $dis;
}

/**
 * Вывод подкаталогов страниц
 * @package PHPShopElementsDepricated
 * @param int $n ИД каталога
 * @return string 
 */
function Vivod_page_pot($n)
{
    global $LoadItems,$SysValue;
    $dis=null;
    $podcatalog_id = array_keys($LoadItems['CatalogPageKeys'],$n);
    foreach($podcatalog_id as $key) {
        
        // Определяем переменные
        $SysValue['other']['catalogId']= $n;
        $SysValue['other']['catalogUid']= $key;
        $SysValue['other']['catalogTitle']= $LoadItems['CatalogPage'][$n]['name'];
        $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$key]['name'];
        
        // Подключаем шаблон
        $dis.=ParseTemplateReturn($SysValue['templates']['podcatalog_page_forma']);
    }
    return $dis;
}

/**
 * Вывод каталогов страниц
 * @package PHPShopElementsDepricated
 * @return string
 */
function Vivod_page_cats()
{
    global $SysValue,$LoadItems;
    $dis=null;
    $sql="select * from ".$SysValue['base']['table_name29']." where parent_to=0 order by num";
    $result=mysql_query($sql);


    while($row = mysql_fetch_array($result)) {
        $id=$row['id'];

        // Определяем переменые
        $SysValue['other']['catalogId']= $id;
        $SysValue['other']['catalogTemplates']=$SysValue['dir']['templates'].chr(47).$LoadItems['System']['skin'].chr(47);
        $SysValue['other']['catalogPodcatalog']= Vivod_page_pot($id);
        $SysValue['other']['catalogTitle']= $LoadItems['CatalogPage'][$id]['name'];
        $SysValue['other']['catalogName']= $LoadItems['CatalogPage'][$id]['name'];

        $podcatalog_id = array_keys($LoadItems['CatalogPageKeys'],$id);

        if(!count($podcatalog_id))
            $dis.=ParseTemplateReturn("catalog/catalog_page_forma_2.tpl");
        else $dis.=ParseTemplateReturn($SysValue['templates']['catalog_page_forma']);
    }

    $SysValue['sql']['num']++;
    return $dis;
}


function Vivod_cat() {
    global $SysValue;
    $sql="select id from ".$SysValue['base']['table_name']." where parent_to=0";
    $result=mysql_query($sql);
    $num=mysql_num_rows($result);
    exit($num);
}
?>