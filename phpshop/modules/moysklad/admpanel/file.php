<?php

$_classPath = "../../../";
include($_classPath . "class/obj.class.php");
PHPShopObj::loadClass("base");
PHPShopObj::loadClass("orm");
PHPShopObj::loadClass("date");
PHPShopObj::loadClass("file");
PHPShopObj::loadClass("array");
PHPShopObj::loadClass("valuta");
PHPShopObj::loadClass("category");
PHPShopObj::loadClass("delivery");

$PHPShopBase = new PHPShopBase($_classPath . "inc/config.ini");
include($_classPath . "admpanel/enter_to_admin.php");

// Валюты
$PHPShopValuta = new PHPShopValutaArray();
$PHPShopValutaArray = $PHPShopValuta->getArray();

// Категории
$PHPShopCategory = new PHPShopCategoryArray();
$PHPShopCategoryArray = $PHPShopCategory->getArray();

function getCat($id) {
    global $PHPShopCategoryArray;

    $name = $PHPShopCategoryArray[$id]['name'];
    $parent_id = $PHPShopCategoryArray[$id]['parent_to'];
    
    if (!empty($PHPShopCategoryArray[$parent_id]['parent_to']))
        return $PHPShopCategoryArray[$parent_id]['name'] . '/' . $name;
    else return $PHPShopCategoryArray[$parent_id]['name'] . '/' . $name;
    
}

// Номенклатура
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
$data = $PHPShopOrm->select();

$csv = null;
$_ = ';';
$empty = '';
$next = '
';

$csv.='Группы;Код;Наименование;Внешний код;Артикул;Единица измерения;Цена продажи;Валюта (Цена продажи);Закупочная цена;Валюта (Закупочная цена);Неснижаемый остаток;Штрихкод EAN13;Штрихкод EAN8;Штрихкод Code128;Описание;Минимальная цена;Страна;НДС;Поставщик;Остаток
';
if (is_array($data)) {
    foreach ($data as $row) {
        $csv.=getCat($row['category']) . $_ . $row['id'] . $_ . $row['name'] . $_ . $empty . $_ . $row['uid'] . $_ . $row['ed_izm'] . $_ . $row['price'] . $_ . $PHPShopValutaArray[$row['baseinputvaluta']]['iso'] . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty .$_ . $empty .$_ .$empty.$_ . $row['items'] . $next;
    }
}

// Доставка
$PHPShopDelivery = new PHPShopDeliveryArray();
$PHPShopDeliveryArray = $PHPShopDelivery->getArray();
if (is_array($PHPShopDeliveryArray))
    foreach ($PHPShopDeliveryArray as $row) {
        if (empty($row['is_folder']) and !empty($row['enabled'])) {
            $csv.='Услуги/Доставка' . $_ . 'delivery_'.$row['id'] . $_ . $row['city'] . $_ . $empty . $_ . 'delivery_' . $row['id'] . $_ . $empty . $_ . $row['price'] . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ . $empty . $_ .$empty . $next;
        }
    }

  
$dir = "../../../admpanel/csv/";
$file = 'moysklad-' . PHPShopDate::dataV(false, false) . '-' . substr(md5(time()), 0, 5) . '.csv';
PHPShopFile::write($dir . $file, $csv);
header("Location: " . $dir . $file);
?>