<?php

/**
 * ������ ���������� ��� ����� �������������� ��� �������
 * ��� ��������� ������������� ���� � price.php
 * @author PHPShop Software
 * @version 1.0
 */
// �������������� ��������
function mod_option($option) {
    $GLOBALS['option']['sort'] = 17;
}

// �������������� ����������
function mod_update(&$CsvToArray, $class_name, $func_name) {

    // ���� ����1 �� ������
    if (empty($CsvToArray[7])) {

        // ���� ����2 ������ �� ����1 = ����2
        if (!empty($CsvToArray[8]))
            $CsvToArray[7] = $CsvToArray[8];

        // ���� ����3 ������ �� ����1 = ����3
        if (!empty($CsvToArray[9]))
            $CsvToArray[7] = $CsvToArray[9];

        // ���� ����1, ����2, ����3 �� ������ �� ����1 = ����4
        if (empty($CsvToArray[7]) and empty($CsvToArray[8]) and empty($CsvToArray[9]))
            $CsvToArray[7] = $CsvToArray[10];
    }

    // ����2 = ����4
    $CsvToArray[8] = $CsvToArray[10];
}

// �������������� �������
function mod_insert(&$CsvToArray, $class_name, $func_name) {
    
     // ���� ����1 �� ������
    if (empty($CsvToArray[7])) {

        // ���� ����2 ������ �� ����1 = ����2
        if (!empty($CsvToArray[8]))
            $CsvToArray[7] = $CsvToArray[8];

        // ���� ����3 ������ �� ����1 = ����3
        if (!empty($CsvToArray[9]))
            $CsvToArray[7] = $CsvToArray[9];

        // ���� ����1, ����2, ����3 �� ������ �� ����1 = ����4
        if (empty($CsvToArray[7]) and empty($CsvToArray[8]) and empty($CsvToArray[9]))
            $CsvToArray[7] = $CsvToArray[10];
    }

    // ����2 = ����4
    $CsvToArray[8] = $CsvToArray[10];
}
?>