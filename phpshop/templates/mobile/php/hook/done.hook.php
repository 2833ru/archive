<?php

function send_to_order_mob_hook($obj, $row, $rout) {

    if ($rout == 'START') {

        // Активная закладка
        $obj->set('cart_active', ' ');
        $obj->set('cart_active_num', ' ');

        if (!PHPShopSecurity::true_param($_POST['d'], $_POST['order_metod'])) {
            unset($_POST['name_person']);
        }

        if (!PHPShopSecurity::true_param($_POST['mail'], $_POST['name_new'], $_POST['tel_new'], $_POST['adr_name'])) {
            $obj->set('orderMesage', message_mob_hook_content($obj->lang('bad_order_mesage_1')));
            $obj->parseTemplate($obj->getValue('templates.order_forma_mesage_main'));
            return true;
        }
    }

    /*
    if ($rout == 'END') {
        $obj->set('orderMesage', '<button class="btn btn-positive btn-block btn-outlined"><span class="icon icon-info"></span> ' . $obj->get('orderMesage') . '</button>');
    }*/
}

function message_mob_hook_content($title) {
    return '<button onclick="history.back()" class="btn btn-negative btn-block btn-outlined"><span class="icon icon-info"></span> ' . $title . '</button>';
    ;
}

function message_mob_hook($obj, $row) {
    return message_mob_hook_content($row[0]);
}

$addHandler = array
    (
    'send_to_order' => 'send_to_order_mob_hook',
    'message' => 'message_mob_hook',
);
?>
