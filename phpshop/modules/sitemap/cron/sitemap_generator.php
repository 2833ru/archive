<?php

$_classPath="../../../";
include($_classPath."class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");

$PHPShopBase = new PHPShopBase($_classPath."inc/config.ini");

// Настройки модуля
PHPShopObj::loadClass("modules");
$PHPShopModules = new PHPShopModules($_classPath."modules/");

function sitemaptime($nowtime){
    return date("Y",$nowtime)."-".date("m",$nowtime)."-".date("d",$nowtime);
}

// Библиотека
$title = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$title.= '<urlset xmlns="http://www.google.com/schemas/sitemap/0.84">' . "\n";

// Страницы
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name11']);
$data = $PHPShopOrm->select(array('id,datas,link'),array('enabled'=>"!='0'"),array('order'=>'datas DESC'));

if(is_array($data))
    foreach($data as $row) {
        $stat_pages.= '<url>' . "\n";
        $stat_pages.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/page/'.$row['link'].'.html</loc>' . "\n";
        $stat_pages.= '<lastmod>'.sitemaptime($row['datas'],false).'</lastmod>' . "\n";
        $stat_pages.= '<changefreq>weekly</changefreq>' . "\n";
        $stat_pages.= '<priority>1.0</priority>' . "\n";
        $stat_pages.= '</url>' . "\n";
    }

// Страницы каталоги
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page_categories']);
$data = $PHPShopOrm->select(array('id'));

if(is_array($data))
    foreach($data as $row) {
        $stat_pages.= '<url>' . "\n";
        $stat_pages.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/page/CID_'.$row['id'].'.html</loc>' . "\n";
        $stat_pages.= '<changefreq>weekly</changefreq>' . "\n";
        $stat_pages.= '<priority>0.5</priority>' . "\n";
        $stat_pages.= '</url>' . "\n";
    }

// Новости
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['table_name8']);
$data = $PHPShopOrm->select(array('id,datas'),false,array('order'=>'datas DESC'));

if(is_array($data))
    foreach($data as $row) {
        $stat_news.= '<url>' . "\n";
        $stat_news.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/news/ID_'.$row['id'].'.html</loc>' . "\n";
        $stat_news.= '<lastmod>'.$row['datas'].'</lastmod>' . "\n";
        $stat_news.= '<changefreq>daily</changefreq>' . "\n";
        $stat_news.= '<priority>0.5</priority>' . "\n";
        $stat_news.= '</url>' . "\n";
    }

// Товары
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
$data = $PHPShopOrm->select(array('id,datas'),array('enabled'=>"='1'",'parent_enabled'=>"='0'"),array('order'=>'datas DESC'));

if(is_array($data))
    foreach($data as $row) {
        $stat_products.= '<url>' . "\n";
        $stat_products.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/shop/UID_'.$row['id'].'.html</loc>' . "\n";
        $stat_products.= '<lastmod>'.sitemaptime($row['datas']).'</lastmod>' . "\n";
        $stat_products.= '<changefreq>daily</changefreq>' . "\n";
        $stat_products.= '<priority>1.0</priority>' . "\n";
        $stat_products.= '</url>' . "\n";
    }

// Каталоги
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
$data = $PHPShopOrm->select(array('id'));

if(is_array($data))
    foreach($data as $row) {
        $stat_products.= '<url>' . "\n";
        $stat_products.= '<loc>http://'.$_SERVER['SERVER_NAME'].'/shop/CID_'.$row['id'].'.html</loc>' . "\n";
        $stat_products.= '<changefreq>weekly</changefreq>' . "\n";
        $stat_products.= '<priority>0.5</priority>' . "\n";
        $stat_products.= '</url>' . "\n";
    }


$sitemap=$title.$stat_pages.$stat_news.$stat_products.'</urlset>';


// Запись в файл
@fwrite(@fopen('../../../../UserFiles/Files/sitemap.xml',"w+"), $sitemap);

echo "Sitemap.xml done!";
?>