<?php

function delivery_yandexorder_hook($obj, $data) {
    if (!empty($_SESSION['setYandexOrderVal'])) {
        $_RESULT = $data[0];
        $hook['adresList'] = $_RESULT['adresList'];
        $hook['dellist'] = $_RESULT['dellist'];
        $hook['hook'] = 'if ($.isFunction(window.setYandexOrderVal)) setYandexOrderVal();';
        $hook['success'] = 1;

        return $hook;
    }
}

$addHandler = array(
    'delivery' => 'delivery_yandexorder_hook'
);
?>
