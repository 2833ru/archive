<?php

/**
 * ������ ���������� ��� ����� �������� ���� � CML
 * @author PHPShop Software
 * @version 1.0
 */
// �������������� ��������
function mod_option($option) {
    $GLOBALS['option']['sort'] = 18;
}

// �������������� ����������
function mod_update(&$CsvToArray, $class_name, $func_name) {
    return " external_code='" . $CsvToArray[17] . "',";
}

// �������������� �������
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    return " external_code='" . $CsvToArray[17] . "',";
}

// �������������� ��������� ��������
function mod_end_load($ReadCsv){
    
}

?>