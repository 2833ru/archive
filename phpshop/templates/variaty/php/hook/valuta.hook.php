<?php

function valutaDisp_hook($obj, $arr) {

    if (isset($_SESSION['valuta']))
        $valuta = $_SESSION['valuta'];
    else
        $valuta = $obj->PHPShopSystem->getParam('dengi');


    if (is_array($obj->PHPShopValuta))
        foreach ($obj->PHPShopValuta as $v) {
            if ($valuta == $v['id'])
                $sel = $v['name'];

            $value .= '<li><a href="#" class="valCh" data-for="' . $v['id'] . '">' . $v['name'] . '</a></li> <li class="divider"></li>';
        }

    // Определяем переменные
    $obj->set('leftMenuName', $sel);
    $obj->set('leftMenuContent', $value);
}

$addHandler = array
    (
    'valutaDisp' => 'valutaDisp_hook',
);
?>