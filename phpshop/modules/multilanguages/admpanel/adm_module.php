<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.multilanguages.multilanguages"));

// Функция обновления
function actionUpdate() {
    $action = true;
    header('Location: ?path=modules&install=check');
    return $action;
}


// Начальная функция загрузки
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();

    $Info = 'Модуль дополнительных языков позволяет добавлять любое количество языков перевода контента сайта, работающих в кодировке <kbd>windows-1251</kbd> (английский, украинский, белорусский, болгарский и т.д.). Модуль добавляет в карточки управления данными новые поля для заполнения информации по каждому новому языку.
     
<h4>Инструкция</h4>
<ol>
<li>Создать новый язык в интерфейсе управления дополнительными языками, указать код обозначения языка, например для английского, <kbd>en</kbd>
<li>Создать новый файл конфигурации перевода если он отсутствует в <code>/phpshop/modules/multilanguages/inc/lang_en.ini</code>, (где префикс <code>en</code> должен быть уникальным и соответствует коду обозначения, указанному на предыдущем шаге). Для создания нового персонального файла конфигурации перевода служит  <code>/phpshop/modules/multilanguages/inc/lang.ini</code>
<li>В карточке редактирования языка заполнить массив перевода служебных обозначений  платформы.
<li>Добавить переменную вывода меню выбора языков <code>@lang_panel_menu_top@</code> в текущий шаблон магазина в файлах <code>/main/index.tpl</code> и <code>/main/shop.tpl</code>. Все последующие правки будут производиться только в этих файлах (index.tpl и shop.tpl).
<li>Заменить переменную вывода меню каталогов <code>@leftCatal@</code> на <code>@leftCatalMulti@</code>.
<li>Заменить переменную вывода горизонтального меню страниц <code>@topMenu@</code> на <code>@topMenuMulti@</code> .
<li>Заменить переменную вывода меню каталога навигации страниц <code>@pageCatal@</code> на <code>@pageCatalMult@</code>.
<li>Заменить переменную вывода послдених новостей на главной странице <code>@miniNews@</code> на <code>@miniNewsMulti@</code> в файле <code>/main/index.tpl</code>.
<li>Добавить перевод информации на нужный язык в карточках редактирования товаров, каталогов, характеристик, текстовых блоков, статей и типов оплат через закладку <kbd>Языки</kbd>. 
</ol>';
    
    $Tab1 = $PHPShopGUI->setInfo($Info);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1),array("О модуле", $PHPShopGUI->setPay()), array("Языки", null,'?path=modules.dir.multilanguages'));

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