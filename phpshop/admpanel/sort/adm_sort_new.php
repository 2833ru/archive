<?php

PHPShopObj::loadClass('sort');

if (!empty($_GET['type']))
    $TitlePage = __('Создание группы характеристики');
else
    $TitlePage = __('Создание характеристики');

$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort_categories']);

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopOrm, $PHPShopModules, $TitlePage;

    // Выборка
    $data['id'] = getLastID();

    // Размер названия поля
    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->addJSFiles('./sort/gui/sort.gui.js');
    $PHPShopGUI->setActionPanel($TitlePage, false, array('Создать и редактировать', 'Сохранить и закрыть'));

    // Страницы
    $page_value[] = array('- Нет описания - ', null, $data['page']);
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
    $data_page = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1000));
    if (is_array($data_page))
        foreach ($data_page as $v)
            $page_value[] = array($v['name'], $v['link'], $data['page']);

    // Категории
    $PHPShopSort = new PHPShopSortCategoryArray(array('category' => '=0'));
    $PHPShopSortArray = $PHPShopSort->getArray();
    if (is_array($PHPShopSortArray))
        foreach ($PHPShopSortArray as $v)
            $category_value[] = array($v['name'], $v['id'], $_GET['cat']);

    // Группа категорий
    if (empty($_GET['type'])) {
        $Tab3 = $PHPShopGUI->setField("Группа:", $PHPShopGUI->setSelect('category_new', $category_value, '100%', false, false, true), 1, 'Группа характеристик служит для исключения дубликатов характеристик в различных категориях.') . $PHPShopGUI->setField("Бренд:", $PHPShopGUI->setCheckbox('brand_new', 1, 'Вкл.', $data['brand']), 1, 'Характеристика становится брендом и отображается в списке брендов') .                $PHPShopGUI->setField("Товары:", $PHPShopGUI->setCheckbox('product_new', 1, 'Вкл.', $data['product']), 1, 'Совместить значение с полем рекомендуемых товаров для совместной продажи') .  $PHPShopGUI->setField("Опции:", $PHPShopGUI->setCheckbox('filtr_new', 1, 'Фильтр', $data['filtr']) . $PHPShopGUI->setCheckbox('goodoption_new', 1, 'Товарная опция', $data['goodoption']). $PHPShopGUI->setCheckbox('optionname_new', 1, 'Не обязательна для добавления в корзину', $data['optionname'])) . $PHPShopGUI->setField("Описание:", $PHPShopGUI->setSelect('page_new', $page_value, '100%', false, false, true), 1, 'Имя характеристики (в таблице характеристик в подробном описании товара) становится ссылкой на указанную страницу с описанием.');
    }

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setCollapse(__('Информация'), $PHPShopGUI->setField("Наименование:", $PHPShopGUI->setInputArg(array('type' => 'text.requared', 'name' => 'name_new', 'value' => $data['name']))) .
            $PHPShopGUI->setField("Приоритет:", $PHPShopGUI->setInputArg(array('type' => 'text', 'name' => 'num_new', 'value' => $data['num'], 'size' => 100))) .
            $Tab3 .
            $PHPShopGUI->setField("Подсказка:", $PHPShopGUI->setTextarea('description_new', $data['description']))
    );

    // Варианты
    if (empty($_GET['type']))
        $Tab1.=$PHPShopGUI->setCollapse(__('Значения'), $PHPShopGUI->setField("Варианты:", $PHPShopGUI->loadLib('tab_value', $data)));


    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1));

    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.sort.create") . $PHPShopGUI->setInput("hidden", "rowID", $data['id']);

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

/**
 * ID новой записи в таблице
 * @return integer 
 */
function getLastID() {
    $PHPShopOrm = new PHPShopOrm();
    $PHPShopOrm->sql = 'SHOW TABLE STATUS LIKE "' . $GLOBALS['SysValue']['base']['sort_categories'] . '"';
    $data = $PHPShopOrm->select();
    if (is_array($data)) {
        return $data[0]['Auto_increment'];
    }
}

/**
 * Экшен записи
 */
function actionInsert() {
    global $PHPShopModules, $PHPShopOrm;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $_POST['category_new'] = intval($_POST['category_new']);

    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->insert($_POST, '_new');

    if ($_POST['saveID'] == 'Создать и редактировать')
        header('Location: ?path=' . $_GET['path'] . '&id=' . $_POST['rowID']);
    else
        header('Location: ?path=' . $_GET['path'] . '&cat=' . $_POST['category_new']);
    return $action;
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');
?>