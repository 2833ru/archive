<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.countcat.countcat_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST);

    if (!empty($_POST['clean'])) {
        $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['categories']);
        $PHPShopOrm->update(array('count' => '0'), false, false);
    }
    
    header('Location: ?path=modules&install=check');
    return $action;
}

function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();
    $PHPShopGUI->field_col = 1;


    $info = 'Для вывода количества товаров в подкаталоге добавьте переменную <kbd>@catalogCount@</kbd> в шаблон вывода подкаталога 
        phpshop/templates/имя шаблона/catalog/pogcatalog_forma.tpl
        <p>При первом включении модуля будет произведен автоматический расчет товаров в подкаталогах с занесением в базу модуля. 
        Для дальнейшей корректировки этого параметра используйте одноименное поле в карточке редактировния подкаталога в закладке
        <kbd>Count</kbd>.</p>
';

    $Tab1 = $PHPShopGUI->setInfo($info, 200, '96%');
    $Tab1.=$PHPShopGUI->setField('Вывод', $PHPShopGUI->setCheckbox("enabled_new", 1, 'Добавить количество товара к имени каталога', $data['enabled']).'<br>'.
    $PHPShopGUI->setCheckbox("clean", 1, 'Пересчитать ранее установленные значения кол-ва товара в категориях', 0));

    $Tab2 = $PHPShopGUI->setPay();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Инструкция", $Tab1), array("О Модуле", $Tab2));

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
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>