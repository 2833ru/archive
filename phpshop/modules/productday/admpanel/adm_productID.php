<?php

function addOptionProdDay($data) {
    global $PHPShopGUI;

    // Опции вывода
    $Tab10 = $PHPShopGUI->setCheckbox('productday_new', 1, 'Вывод товара дня', $data['productday']);


    $PHPShopGUI->addTab(array("Товар дня", $Tab10, true));
}

function updateOptionProdDay($data) {
    if (empty($_POST['productday_new'])) {
        $_POST['productday_new'] = 0;
    }
    
    $_POST['datas_new'] = time();
}

$addHandler = array(
    'actionStart' => 'addOptionProdDay',
    'actionDelete' => false,
    'actionUpdate' => 'updateOptionProdDay'
);
?>