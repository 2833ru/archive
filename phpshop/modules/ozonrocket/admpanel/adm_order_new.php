<?php

function OzonRocketOrderCopy($data)
{
    (new PHPShopOrm($GLOBALS['SysValue']['base']['orders']))->update(
        ['ozonrocket_order_data_new' => ''],
        ['id' => sprintf('="%s"', $data)]
    );
}

$addHandler = ['actionStart' => 'OzonRocketOrderCopy'];
