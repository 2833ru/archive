<?php

function addOptionProdDay($data) {
    global $PHPShopGUI;

    // Опции вывода
    $Tab10 = $PHPShopGUI->setCheckbox('productday_new', 1, 'Вывод товара дня на сутки', @$data['productday']);

    if (empty($data['sklad']))
        $PHPShopGUI->addTab(array("Товар дня", $Tab10, true));
}

function updateOptionProdDay($data) {
    
    if (empty($_POST['ajax'])) {
        if (empty($_POST['productday_new'])) {
            $_POST['productday_new'] = 0;
            $_POST['productday_time_new'] = 0;
        }

        $_POST['datas_new'] = time();
    }
}

$addHandler = array(
    'actionStart' => 'addOptionProdDay',
    'actionDelete' => false,
    'actionUpdate' => 'updateOptionProdDay'
);
?>