<?php

function filial_mod_page_hook($obj, $row, $rout) {

    if ($rout == 'END') {


        if (!empty($row['target_url_page']) and !strstr($_SERVER['SERVER_NAME'], $row['target_url_page'])) {

            // Определяем переменные
            $obj->setError404();
            return true;
        }
    }
}

$addHandler = array
    (
    '#index' => 'filial_mod_page_hook'
);
?>