<?php

/**
 * Модуль расширения для учета внешнего кода в CML
 * @author PHPShop Software
 * @version 1.0
 */
// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort'] = 19;
}

// Персонализация вставки
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    return " external_code='" . $CsvToArray[17] . "',";
}

?>