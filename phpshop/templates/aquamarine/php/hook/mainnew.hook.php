<?php

function specMainIcon_hook($obj) {
    
    $obj->cell=2;
    $obj->limitspec=4;

}

$addHandler = array
    (
    'specMainIcon' => 'specMainIcon_hook'
);


?>