<?php

/**
 * Модуль расширения для учета дополнительных цен товаров
 * Для включения переименовать файл в price.php
 * @author PHPShop Software
 * @version 1.0
 */
// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort'] = 17;
}

// Персонализация обновления
function mod_update(&$CsvToArray, $class_name, $func_name) {

    // Если цена1 не задана
    if (empty($CsvToArray[7])) {

        // Если цена2 задана то цена1 = цена2
        if (!empty($CsvToArray[8]))
            $CsvToArray[7] = $CsvToArray[8];

        // Если цена3 задана то цена1 = цена3
        if (!empty($CsvToArray[9]))
            $CsvToArray[7] = $CsvToArray[9];

        // Если цена1, цена2, цена3 не задана то цена1 = цена4
        if (empty($CsvToArray[7]) and empty($CsvToArray[8]) and empty($CsvToArray[9]))
            $CsvToArray[7] = $CsvToArray[10];
    }

    // Цена2 = цена4
    $CsvToArray[8] = $CsvToArray[10];
}

// Персонализация вставки
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    
     // Если цена1 не задана
    if (empty($CsvToArray[7])) {

        // Если цена2 задана то цена1 = цена2
        if (!empty($CsvToArray[8]))
            $CsvToArray[7] = $CsvToArray[8];

        // Если цена3 задана то цена1 = цена3
        if (!empty($CsvToArray[9]))
            $CsvToArray[7] = $CsvToArray[9];

        // Если цена1, цена2, цена3 не задана то цена1 = цена4
        if (empty($CsvToArray[7]) and empty($CsvToArray[8]) and empty($CsvToArray[9]))
            $CsvToArray[7] = $CsvToArray[10];
    }

    // Цена2 = цена4
    $CsvToArray[8] = $CsvToArray[10];
}
?>