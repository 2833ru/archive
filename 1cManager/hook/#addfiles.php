<?php
/**
 * ������ ���������� ��� ����� ������������� ������ � �������
 * ��� ��������� ������������� ���� � addfiles.php
 * @author PHPShop Software
 * @version 1.0
 */
// �������������� ��������
function mod_option($option) {
    $GLOBALS['option']['sort'] = 18;
}

// ���������� ������� ���� � ������
function mod_addfiles_path($files){
    foreach($files as $k=>$f){
        $path_parts = pathinfo($f);
        $files_with_path[]=array('name'=>$path_parts['basename'],'path'=>$GLOBALS['SysValue']['dir']['dir'] . "/UserFiles/Files/".$f);
    }
    
    return $files_with_path;
}

// �������������� ����������
function mod_update($CsvToArray, $class_name, $func_name) {
    if (!empty($CsvToArray[17])) {

        if (strstr($CsvToArray[17], ','))
            $files = explode(',', $CsvToArray[17]);
        else
            $files[] = $CsvToArray[17];

        return "files='". serialize(mod_addfiles_path($files)) . "', ";
    }
}

// �������������� �������
function mod_insert($CsvToArray, $class_name, $func_name) {
    if (!empty($CsvToArray[17])) {

        if (strstr($CsvToArray[17], ','))
            $files = explode(',', $CsvToArray[17]);
        else
            $files[] = $CsvToArray[17];

        return "files='". serialize(mod_addfiles_path($files)) . "', ";
    }
}

?>