<?php

/**
 * Модуль расширения для учета дополнительных полей товаров в формате JSON
 * Для включения переименовать файл в json.php
 * @author PHPShop Software
 * @version 1.0
 */
// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort'] = 18;
}

// Персонализация обновления
function mod_update(&$CsvToArray, $class_name, $func_name) {
    $json = @json_decode($CsvToArray[17]);
    if (is_array($json)) {
        return " spec='" . intval($json['spec']) . "', newtip='" . intval($json['newtip']) . "', ";
    }
}

// Персонализация вставки
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    $json = @json_decode($CsvToArray[17]);
    if (is_array($json)) {
        return " spec='" . intval($json['spec']) . "', newtip='" . intval($json['newtip']) . "', ";
    }
}

?>