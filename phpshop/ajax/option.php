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

// Массив валют
$PHPShopValutaArray = new PHPShopValutaArray();

// Системные настройки
$PHPShopSystem = new PHPShopSystem();

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

    $where = array('id' => ' IN ("' . @implode('","', $parent_array_true) . '")', 'parent' => '="' . PHPShopSecurity::true_search(urldecode($_REQUEST['size'])) . '"', 'parent2' => '="' . PHPShopSecurity::true_search(urldecode($_REQUEST['color']) . '"'));

    // Подтипы из 1С
    if ($PHPShopSystem->ifSerilizeParam('1c_option.update_option')) {
        unset($where['id']);
        $where['uid'] = ' IN ("' . @implode('","', $parent_array_true) . '")';
    }

    $PHPShopParentProductArray = new PHPShopProductArray($where);
}
$data = array_keys($PHPShopParentProductArray->getArray());

// Формируем результат
$_RESULT = array(
    "id" => $data[0],
    "success" => 1
);

// Перехват модуля в начале функции
$hook = $PHPShopModules->setHookHandler('option', 'option', false, array($_RESULT, $_REQUEST));
if (is_array($hook))
    $_RESULT = $hook;

// JSON 
echo json_encode($_RESULT);
?>