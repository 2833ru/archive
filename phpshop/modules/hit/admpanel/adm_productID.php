<?php

function addOptionHit($data) {
    global $PHPShopGUI;

    // Опции вывода
    $Tab10 = $PHPShopGUI->setCheckbox('hit_new', 1, 'Хит', $data['hit']);

    $PHPShopGUI->addTab(array("Хиты", $Tab10, true));
}

function updateOptionHit($data) {
    if (empty($_POST['hit_new'])) {
        $_POST['hit_new'] = 0;
    }
}

$addHandler = array(
    'actionStart' => 'addOptionHit',
    'actionDelete' => false,
    'actionUpdate' => 'updateOptionHit'
);
?>