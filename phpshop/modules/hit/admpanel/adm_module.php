<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.hit.hit_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $PHPShopOrm->update(array('version_new' => $new_version));
}

// Функция обновления
function actionUpdate() {
    global $PHPShopModules;

    // Настройки витрины
    $PHPShopModules->updateOption($_GET['id'], $_POST['servers']);

    $PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.hit.hit_system"));
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);

    header('Location: ?path=modules&id=' . $_GET['id']);

    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    $Tab1 = $PHPShopGUI->setField('Количество хитов на главной странице:', '<input class="form-control input-sm" type="number" step="1" min="0" value="' . $data['hit_main'] . '" name="hit_main_new" style="width:300px; ">');
    $Tab1 .= $PHPShopGUI->setField('Количество хитов в ряд на странице хитов:', '<input class="form-control input-sm" type="number" step="1" min="0" value="' . $data['hit_page'] . '" name="hit_page_new" style="width:300px; ">');

    $info = '<h4>Настройка модуля</h4>
       <ol>
        <li>Включить модуль, в карточке товара отметить "Хит", на соответствующей вкладке.</li>
        <li>Для вывода на главной странице, добавить в ваш <code>phpshop/имя_шаблона/main/index.tpl</code> переменную <kbd>@hitMain@</kbd>, для скрытия блока, при отсутсвии товаров, используется переменная <kbd>@hitMainHidden@</kbd>.</li>
        <li>Для вывода Хитов в каталоге, добавить в файл <code>phpshop/имя_шаблона/product/product_catalog_content.tpl</code> переменную <kbd>@hit@</kbd>.</li>
        </ol>';

    $Tab2 = $PHPShopGUI->setInfo($info);

    // Форма регистрации
    $Tab3 = $PHPShopGUI->setPay($serial = false, false, $data['version'], true);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>