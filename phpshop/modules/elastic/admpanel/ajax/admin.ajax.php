<?php

session_start();

$_classPath = "../../../../";
include_once($_classPath . "class/obj.class.php");
include_once($_classPath . "modules/elastic/class/include.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass('modules');
PHPShopObj::loadClass('orm');
PHPShopObj::loadClass('system');
PHPShopObj::loadClass('security');
PHPShopObj::loadClass('order');

if(isset($_REQUEST['token'])) {
    if($_REQUEST['token'] !== Elastic::getOption('api')) {
        echo 'Access Denied!'; exit;
    }
} else {
    $PHPShopBase->chekAdmin();
}

$Elastic = new Elastic();
$result = ['success' => true];
$limit = 1000;

try {
    $result['total_imported']   = (int) $_REQUEST['total_imported'];
    $result['total_documents']  = (int) $_REQUEST['total_documents'];
    $result['total_categories'] = (int) $_REQUEST['total_categories'];
    $result['total_products']   = (int) $_REQUEST['total_products'];
    $result['documents']        = (int) $_REQUEST['documents'];

    if((int) $_REQUEST['initial'] === 1) {
        $Elastic->client->removeProductsIndex();
        $Elastic->client->removeCategoriesIndex();

        $Elastic->client->createProductsIndex();
        $Elastic->client->createCategoriesIndex();

        $result['total_documents'] = $Elastic->getDocumentsCount();
        $result['total_categories'] = (int) (new PHPShopOrm('phpshop_categories'))
            ->select(['COUNT(id) as count'])['count'];
        $result['total_products'] = (int) (new PHPShopOrm('phpshop_products'))
            ->select(['COUNT(id) as count'], ['parent_enabled' => '="0"'])['count'];
    }

    switch ((int) $_REQUEST['documents']) {
        case 0:
            $imported = $Elastic->importCategories((int) $_REQUEST['from'], $limit);
            break;
        case 1;
            $imported = $Elastic->importProducts((int) $_REQUEST['from'], $limit);
            break;
    }

    $result['from'] = (int) $_REQUEST['from'] + $imported;
    $result['total_imported'] += $imported;
    $result['percent'] = round($result['total_imported'] * 100 / $result['total_documents'], 2);

    // Переходим с категорий на товары
    if($result['from'] >= $result['total_categories'] && (int) $_REQUEST['documents'] === 0) {
        $result['documents'] = 1;
        $result['from'] = 0;
        $result['message'] = PHPShopString::win_utf8('Категории успешно экспортированы.');
    }

    // Завершаем выполнение
    if($result['from'] >= $result['total_products'] && (int) $_REQUEST['documents'] === 1) {
        $result['finished'] = true;
        $result['percent'] = 100;
        $result['message'] = PHPShopString::win_utf8('Товары успешно экспортированы.');
    }
} catch (\Exception $exception) {
    $result['finished'] = true; // завершаем выполнение при ошибке.
    $result['success'] = false;
    $result['message'] = PHPShopString::win_utf8($exception->getMessage());
}

echo (json_encode($result)); exit;