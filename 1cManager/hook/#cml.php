<?php

/**
 * ������ ���������� ��� ����� �������� ���� � CML
 * @author PHPShop Software
 * @version 1.0
 */
// �������������� ��������
function mod_option($option) {
    $GLOBALS['option']['sort'] = 19;
}

// �������������� �������
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    return " external_code='" . $CsvToArray[17] . "',";
}

?>