<?php

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI,$select_name;
    
    $PHPShopGUI->setActionPanel(__("Настройка модуля") . ' <span id="module-name">' . ucfirst($_GET['id']).'</span>', $select_name, null);

    $Info = '<p>Модуль позволяет выводить составные товары в виде единой карточки и управлять составом корзины при их добавлении. Подходит для продажи мебели, компьютеров в сборе и т.д.</p>
        <h4>Настройка товара</h4>
        <p>При редактирование товара во вкладке <kbd>Группы</kbd> есть возможность настроить список составных товаров в группе.</p>
<h4>Настройка шаблона</h4>
    <p><kbd>@productsgroup_list@</kbd> - переменная отвечает за вывод блока в шаблоне подробного описания товара <code>/phpshop/templates/имя_шаблона/product/main_product_forma_full.tpl</code></p>
    <p><kbd>@productsgroup_button_buy@</kbd> - кнопка покупки для списков товаров, например файл шаблона: <code>/phpshop/templates/имя_шаблона/product/main_product_forma_2.tpl</code></p>
    <p>Для изменения динамически цены при выборе кол-ва товара в карточке товара, нужно внести изменения в шаблоне страницы товара <code>/phpshop/templates/имя_шаблона/product/main_product_forma_full.tpl</code>. Добавить класс "<b>priceGroupeR</b>" в тэг, содержащий цену. Пример: <pre>
&lt;div class="tovarDivPrice12"&gt;Цена: &lt;span class="priceGroupeR"&gt;@productPrice@&lt;/span&gt; &lt;span&gt;@productValutaName@&lt;/span>&lt;/div&gt;</pre>

    ';

    // Содержание закладки 1
    $Tab2 = $PHPShopGUI->setInfo($Info);

    // Содержание закладки 2
    $Tab3 = $PHPShopGUI->setPay('О модуле', false);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Инструкция", $Tab2), array("О Модуле", $Tab3));

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