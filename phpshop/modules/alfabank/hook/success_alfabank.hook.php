<?php

function success_mod_alfanank_hook($obj, $value) {

    if ($value["payment"] == "alfabank") {
        $obj->order_metod = 'modules" and id="10021';

        $mrh_ouid = explode("-", $_REQUEST['uid']);
        $obj->inv_id = $mrh_ouid[0] . $mrh_ouid[1];

        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['payment']);
        $data = $PHPShopOrm->select(array('uid'), array('uid' => '="' . $obj->inv_id . '"'), false, array('limit' => 1));
        if (is_array($data)) {

            $obj->message();

            return true;
        } else {
            $obj->error();
        }
    }
}

$addHandler = array('index' => 'success_mod_alfanank_hook');
?>