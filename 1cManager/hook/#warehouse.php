<?php

/**
 * Модуль расширения для учета данных по дополнительным складам
 * @author PHPShop Software
 * @version 1.0
 */
// Персонализация настроек
function mod_option($option) {
    $GLOBALS['option']['sort'] = 18;
}

// Персонализация обновления
function mod_update(&$CsvToArray, $class_name, $func_name) {
    return " option1='" . $CsvToArray[17] . "',  ";
}

// Персонализация вставки
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    return " option1='" . $CsvToArray[17] . "', ";
}

/* 
1. Добавить следующий код в файл phpshopshop.hook.php в функцию template_UID

// Склады
$slad_name = array('МАКСИ-СЕВСК', 'Набережная 32', 'НИКТОВА 9', 'Новодвинск', 'Севск-ЛОМ75', 'Ягры');
$sklad_disp = null;

if (strstr($dataArray['option1'], "#")) {
    $sklad_array = explode("#", $dataArray['option1']);
    if (is_array($sklad_array)) {

        foreach ($sklad_array as $k => $v) {

            if (!empty($v))
                $sklad_disp.=$slad_name[$k] . ': есть';
            else
                $sklad_disp.=$slad_name[$k] . ': нет';
            $sklad_disp.='<br>';
        }
    }
    $obj->set('skladOption', $sklad_disp);
}
 
2. Добавить переменную шаблонизатора @skladOption@ в product/main_product_forma_full.tpl

 */
?>