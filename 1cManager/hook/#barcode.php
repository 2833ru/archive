<?php

/**
 * Модуль расширения для учета штрихкода в Ozon.Seller и Яндекс.Маркет
 * Для включения переименовать файл в barcode.php
 * @author PHPShop Software
 * @version 1.0
 */
// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort'] = 18;
}

// Персонализация обновления
function mod_update(&$CsvToArray, $class_name, $func_name) {
    return " barcode_ozon='" . $CsvToArray[17] . "', barcode='" . $CsvToArray[17] . "',";
}

// Персонализация вставки
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    return " barcode_ozon='" . $CsvToArray[17] . "', barcode='" . $CsvToArray[17] . "',";
}

?>