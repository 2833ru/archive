<?php

function footer_copy_hook() {

    echo '</div>';
}

$addHandler = array
    (
    'footer' => 'footer_copy_hook'
);
?>