<?php

/*
 * Создание сделки и счета в Битрикс24.
 */
function bitrix24_write_hook($obj, $data, $rout)
{
    if($rout === 'END') {

        require "./phpshop/modules/bitrix24/class/Bitrix24.php";
        $Bitrix24 = new Bitrix24($data);
        $Bitrix24->init();
    }
}

$addHandler = array('write' => 'bitrix24_write_hook');