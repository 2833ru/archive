<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.stockgallery.stockgallery_system"));

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;
    if (empty($_POST['enabled_new']))
        $_POST['enabled_new'] = 0;
    $action = $PHPShopOrm->update($_POST);
     header('Location: ?path=modules&install=check');
    return $action;
}

// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

           // bootstrap-colorpicker
    $PHPShopGUI->addCSSFiles('./css/bootstrap-colorpicker.min.css');
    $PHPShopGUI->addJSFiles('./js/bootstrap-colorpicker.min.js');

    $Tab1 = $PHPShopGUI->setField('Статус', $PHPShopGUI->setCheckbox('enabled_new', 1, 'Выводить в блоке спецпредложений на главной странице', $data['enabled']));
    $Tab1.=$PHPShopGUI->setField('Ширина карусели', $PHPShopGUI->setInputText(false, 'width_new', $data['width'], 100, 'px'));
    $Tab1.=$PHPShopGUI->setField('Ширина изображения товара', $PHPShopGUI->setInputText(false, 'img_width_new', $data['img_width'], 100, 'px'));
    $Tab1.=$PHPShopGUI->setField('Высота изображения товара', $PHPShopGUI->setInputText(false, 'img_height_new', $data['img_height'], 100, 'px'));
    $Tab1.=$PHPShopGUI->setField('Рамка', $PHPShopGUI->setInputText(false, 'border_new', $data['border'], 100, 'px'));
    $Tab1.=$PHPShopGUI->setField('Цвет рамки', $PHPShopGUI->setInputColor('border_color_new', $data['border_color']));
    $Tab1.=$PHPShopGUI->setField('Количество товаров', $PHPShopGUI->setInputText(false, 'limit_new', $data['limit'], 100));

    $info = 'Для произвольной вставки элемента следует выбрать парамет вывода "Не выводить" и в ручном режиме вставить переменную
        <kbd>@stockgallery@</kbd> в свой шаблон. Или через панель управления создайте текстовый блок, переключитесь в режим исходного кода (Система - Настройка - Режимы - Визуальный редактор),
        внесите метку <kbd>@stockgallery@</kbd> - теперь блок будет выводиться в нужном вам месте.
        <p>Для персонализации формы вывода отредактируйте шаблон <code>phpshop/modules/stockgallery/templates/stockgallery_forma.tpl</code></p>';

    $Tab2 = $PHPShopGUI->setInfo($info);
    
    // Содержание закладки 3
    $Tab3 = $PHPShopGUI->setPay();
    

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1),array("Инструкция",$Tab2), array("О Модуле", $Tab3));

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