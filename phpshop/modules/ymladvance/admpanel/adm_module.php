<?php

// SQL
$PHPShopOrm = new PHPShopOrm($PHPShopModules->getParam("base.ymladvance.ymladvance_system"));

// Обновление версии модуля
function actionBaseUpdate() {
    global $PHPShopModules, $PHPShopOrm;
    $PHPShopOrm->clean();
    $option = $PHPShopOrm->select();
    $new_version = $PHPShopModules->getUpdate($option['version']);
    $PHPShopOrm->clean();
    $action = $PHPShopOrm->update(array('version_new' => $new_version));
    return $action;
}

function actionStart() {
    global $PHPShopGUI,  $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select();


    $vendor = unserialize($data['vendor']);


    $Tab1 = $PHPShopGUI->setField('Характеристика A', $PHPShopGUI->setInputText('Тег', 'sort1_tag', $vendor['sort1_tag'], 300, false, 'left') .
            $PHPShopGUI->set_().
            $PHPShopGUI->setInputText('Имя', 'sort1_name', $vendor['sort1_name'], 300, false, 'left')
    );

    $Tab1.=$PHPShopGUI->setField('Характеристика B', $PHPShopGUI->setInputText('Тег', 'sort2_tag', $vendor['sort2_tag'], 300, false, 'left') .
            $PHPShopGUI->set_().
            $PHPShopGUI->setInputText('Имя', 'sort2_name', $vendor['sort2_name'], 300, false, 'left')
    );

    $Tab1.=$PHPShopGUI->setField('Характеристика C', $PHPShopGUI->setInputText('Тег', 'sort3_tag', $vendor['sort3_tag'], 300, false, 'left') .
            $PHPShopGUI->set_().
            $PHPShopGUI->setInputText('Имя', 'sort3_name', $vendor['sort3_name'], 300, false, 'left')
    );

    $Tab1.=$PHPShopGUI->setField('Характеристика D', $PHPShopGUI->setInputText('Тег', 'sort4_tag', $vendor['sort4_tag'], 300, false, 'left') .
            $PHPShopGUI->set_().
            $PHPShopGUI->setInputText('Имя', 'sort4_name', $vendor['sort4_name'], 300, false, 'left')
    );

    $Tab1.=$PHPShopGUI->setField('Гарантия от производителя', $PHPShopGUI->setCheckbox('warranty_enabled_new', 1, __('Добавить тег гарантии manufacturer_warranty'), $data['warranty_enabled']));
    
    $Tab1.=$PHPShopGUI->setField('Подробное описание товара', $PHPShopGUI->setCheckbox('content_enabled_new', 1, __('Добавить тег подробного описания content'), $data['content_enabled']). $PHPShopGUI->setHelp('Требуется для использования PriceLoader'));
    
    $Tab1.=$PHPShopGUI->setField('Пароль', $PHPShopGUI->setInputText(null, 'password_new', $data['password'], 300).$PHPShopGUI->setHelp('Требуется для использования <a href="http://faq.phpshop.ru/page/batch-loading.html" target="_blamck">PriceLoader</a>'));
    

    $Info = 'Модуль позволяет добавить специфические теги для отображения товарных позиций согласно рекомендациям по классификациям размещения товаров в Яндексе.
Например, для категории "Одежда и обувь" существует спецификация <a href="http://help.yandex.ru/partnermarket/?id=1124379#4" target="_blank">Яндекс Гардероб</a>.
<p>
Для включения тегов производителя(Бренда) следует указать в поле Имя тега <kbd>vendor</kbd>, а в поле Имя характеристики указать 
имя существующей характеристики в системе (Производитель или свое название). Характеристика должна быть активной и заполненной у всех товаров.
</p>
<p>
Для включения тегов размера следует указать в поле Имя тега <kbd>param name="Цвет"</kbd>, а в поле Имя характеристики указать 
имя существующей характеристики в системе (Цвет или свое название). Характеристика должна быть активной и заполненной у всех товаров.
</p>
<p>
Модуль позволяет вывести дополнительные настройки в файл выгрузки для Яндекс.Маркета. Добавляет в файл 
http://' . $_SERVER['SERVER_NAME'] . '/yml/yandex.php тег наличия гарантии от производителя <kbd>manufacturer_warranty</kbd>.
</p>
<p>
Поле пароль <b>защищает</b> от несанкционированной кражи контента. При использовании пароля ссылка на файл YML примет вид <code>http://' . $_SERVER['SERVER_NAME'] . '/yml/yandex.php?pas=*******</code>. При использовании пароля требуется так же заменить ссылку в Яндекс.Маркете.
</p>
';
    $Tab2 = $PHPShopGUI->setInfo($Info);

    $Tab3 = $PHPShopGUI->setPay($data['serial'], false, $data['version'], true);
    
    // История изменений
    $Tab3.= $PHPShopGUI->setHistory();

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Инструкция", $Tab2), array("О Модуле", $Tab3));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter =
            $PHPShopGUI->setInput("hidden", "rowID", $data['id']) .
            $PHPShopGUI->setInput("submit", "saveID", "Применить", "right", 80, "", "but", "actionUpdate.modules.edit");

    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm;

    $vendor = array(
        'sort1_tag' => $_POST['sort1_tag'],
        'sort1_name' => $_POST['sort1_name'],
        'sort2_tag' => $_POST['sort2_tag'],
        'sort2_name' => $_POST['sort2_name'],
        'sort3_tag' => $_POST['sort3_tag'],
        'sort3_name' => $_POST['sort3_name'],
        'sort4_tag' => $_POST['sort4_tag'],
        'sort4_name' => $_POST['sort4_name'],
    );

    $_POST['vendor_new'] = serialize($vendor);

    if (empty($_POST['warranty_enabled_new']))
        $_POST['warranty_enabled_new'] = 0;
    
       if (empty($_POST['content_enabled_new']))
        $_POST['content_enabled_new'] = 0;


    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST);
    header('Location: ?path=modules&install=check');
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['saveID'], 'actionStart');
?>