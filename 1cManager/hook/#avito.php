<?php

/**
 * Модуль расширения для учета в Авито
 * Для включения переименовать файл в avito.php
 * @author PHPShop Software
 * @version 1.0
 */


// Персонализация вставки
function mod_insert(&$CsvToArray, $class_name, $func_name) {
        $PHPShopOrm = new PHPShopOrm();
        $result = $PHPShopOrm->query('select export_avito,condition_avito from ' . $GLOBALS['SysValue']['base']['categories'] . ' where id="' . $CsvToArray[15] . '"  limit 1');
        $row = mysqli_fetch_array($result);
        return " export_avito='" . $row['export_avito'] . "', condition_avito='" . $row['condition_avito'] . "',";
}

?>