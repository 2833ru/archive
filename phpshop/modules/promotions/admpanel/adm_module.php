<?php

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$select_name;
    
    $PHPShopGUI->setActionPanel(__("Настройка модуля") . ' <span id="module-name">' . ucfirst($_GET['id']).'</span>', $select_name, null);

    $Info = '
        <h4>Дополнительные переменные в товарах</h4>
    <ol>
        <li><kbd>@promotionInfo@</kbd> - Описание акции. Доступно только в шаблон <code>phpshop/templates/имя шаблона/product/main_product_forma_full.tpl</code></li>
        <li><kbd>@promotionsIcon@</kbd> - Иконка акции. Доступно в шаблонах <code>phpshop/templates/имя шаблона/product/*</code></li></li>
    </ol>';

    // Содержание закладки 1
    $Tab2 = $PHPShopGUI->setInfo($Info);

    // Содержание закладки 2
    $Tab3 = $PHPShopGUI->setPay('О модуле', false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Инструкция", $Tab2), array("О Модуле", $Tab3),array("Промоакции", null,'?path=modules.dir.promotions'));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>