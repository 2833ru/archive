<?php

session_start();
$_classPath = "../../../";
include_once($_classPath . "class/obj.class.php");
include_once($_classPath . "modules/elastic/class/include.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("modules");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("security");

$PHPShopSystem = new PHPShopSystem();

$isSuccess = true;
try {
    $filter = [];
    foreach ($_POST['filter'] as $value) {
        $valueArr = explode('-', $value);
        $filter[(int) $valueArr[0]][] = (int) $valueArr[1];
    }

    $categories = array_map(function ($category) {
        return (int) $category;
    }, $_POST['categories']);

    $counts = ElasticSort::calculateProducts($categories, $filter);
} catch (\Exception $exception) {
    $isSuccess = false;
}

$result = ['success' => $isSuccess, 'counts' => $counts];

echo(json_encode($result));
exit;