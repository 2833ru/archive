<?php

/**
 * Проверка подтипов товаров
 * @package PHPShopAjaxElements
 */
session_start();

$_classPath = "../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("product");
PHPShopObj::loadClass("system");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("string");
PHPShopObj::loadClass("cart");
PHPShopObj::loadClass("security");
PHPShopObj::loadClass("user");
PHPShopObj::loadClass("lang");
PHPShopObj::loadClass("order");

// Массив валют
$PHPShopValutaArray = new PHPShopValutaArray();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

$PHPShopLang = new PHPShopLang(array('locale' => $_SESSION['lang'], 'path' => 'shop'));

// Модули
$PHPShopModules = new PHPShopModules($_classPath . "modules/");

$PHPShopProductArray = new PHPShopProductArray(array('id' => '=' . intval($_REQUEST['parent'])));
$parent = $PHPShopProductArray->getParam(intval($_REQUEST['parent']) . '.parent');


// Проверяем опции
if (!empty($parent)) {

    $parent_array = @explode(",", $parent);
    if (is_array($parent_array))
        foreach ($parent_array as $v)
            if (!empty($v))
                $parent_array_true[] = $v;

    $where = array('id' => ' IN ("' . @implode('","', $parent_array_true) . '")', 'parent' => '="' . PHPShopSecurity::true_search(urldecode($_REQUEST['size']),true) . '"', 'parent2' => '="' . PHPShopSecurity::true_search(urldecode($_REQUEST['color']),true) . '"');

    // Подтипы из 1С
    if ($PHPShopSystem->ifSerilizeParam('1c_option.update_option')) {
        unset($where['id']);
        $where['uid'] = ' IN ("' . @implode('","', $parent_array_true) . '")';
    }

    $PHPShopParentProductArray = new PHPShopProductArray($where);
}

$ParentProductArray = $PHPShopParentProductArray->getArray();
$data = array_keys($ParentProductArray);
$id = $data[0];
$format = intval($PHPShopSystem->getSerilizeParam("admoption.price_znak"));

// Промоакции
$PHPShopPromotions = new PHPShopPromotions();
$promotions = $PHPShopPromotions->getPrice($ParentProductArray[$id]);
if (is_array($promotions)) {
    $price = $promotions['price'];
    $price_n = $promotions['price_n'];
} else {
    $price = $ParentProductArray[$id]['price'];
    $price_n = $ParentProductArray[$id]['price_n'];
}

$result_price = number_format(PHPShopProductFunction::GetPriceValuta($id, array($price, $ParentProductArray[$id]['price2'], $ParentProductArray[$id]['price3'], $ParentProductArray[$id]['price4'], $ParentProductArray[$id]['price5']), $ParentProductArray[$id]['baseinputvaluta']), $format, '.', ' ');

$result_price_n = number_format(PHPShopProductFunction::GetPriceValuta($id, array($price_n, $ParentProductArray[$id]['price2'], $ParentProductArray[$id]['price3'], $ParentProductArray[$id]['price4'], $ParentProductArray[$id]['price5']), $ParentProductArray[$id]['baseinputvaluta']), $format, '.', ' ');

if (empty($result_price_n))
    $result_price_n = '';

 // Если цены показывать только после авторизации
if($PHPShopSystem->getSerilizeParam('admoption.user_price_activate') == 1 and  empty($_SESSION['UsersId'])){
    $result_price=$result_price_n=null;
}


// Единица измерения
if (empty($ParentProductArray[$id]['ed_izm']))
    $ParentProductArray[$id]['ed_izm'] = $PHPShopBase->SysValue['lang']['product_on_sklad_i'];

// Формируем результат
$_RESULT = array(
    "id" => $id,
    "image" => $ParentProductArray[$id]['pic_small'],
    "image_big" => $ParentProductArray[$id]['pic_big'],
    "price" => $result_price,
    "price_n" => $result_price_n,
    "items" => PHPShopString::win_utf8($PHPShopBase->SysValue['lang']['product_on_sklad'] . " " . $ParentProductArray[$id]['items'] . " " . $ParentProductArray[$id]['ed_izm']),
    "success" => 1
);


// Перехват модуля в начале функции
$hook = $PHPShopModules->setHookHandler('option', 'option', false, array($_RESULT, $_REQUEST));
if (is_array($hook))
    $_RESULT = $hook;

// JSON 
echo json_encode($_RESULT);
?>