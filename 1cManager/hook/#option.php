<?php

/**
 * Модуль расширения для учета дополнительных полей товаров
 * Для включения переименовать файл в option.php
 * @author PHPShop Software
 * @version 1.1
 */
// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort'] = 22;
}

// Персонализация обновления
function mod_update(&$CsvToArray, $class_name, $func_name) {
    $CsvToArray[3]=$CsvToArray[21];
    return " option1='" . $CsvToArray[17] . "', option2='" . $CsvToArray[18] . "', option3='" . $CsvToArray[19] . "', option4='" . $CsvToArray[20] . "', ";
}

// Персонализация вставки
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    $CsvToArray[3]=$CsvToArray[21];
    return " option1='" . $CsvToArray[17] . "', option2='" . $CsvToArray[18] . "', option3='" . $CsvToArray[19] . "', option4='" . $CsvToArray[20] . "', ";
}

?>