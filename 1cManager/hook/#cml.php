<?php

/**
 * Модуль расширения для учета внешнего кода в CML
 * @author PHPShop Software
 * @version 1.0
 */
// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort'] = 18;
}

// Персонализация обновления
function mod_update(&$CsvToArray, $class_name, $func_name) {
    return " external_code='" . $CsvToArray[17] . "',";
}

// Персонализация вставки
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    return " external_code='" . $CsvToArray[17] . "',";
}

// Персонализация конечного действия
function mod_end_load($ReadCsv){
    
}

?>