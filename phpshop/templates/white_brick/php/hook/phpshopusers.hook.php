<?php

/**
 * Изменение шаблона ссылок таблицы категорий со строки на столбик
 * @param array $obj объект
 * @param array $val массив данных
 */
function user_info_nt($obj, $val, $rout) {
    if ($rout == 'END') {
        $obj->set('formaContent', ParseTemplateReturn('users/users_page_info.tpl'));
    }
}

    $addHandler = array
        (
        'user_info' => 'user_info_nt'
    );
?>