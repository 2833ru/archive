<?php

PHPShopObj::loadClass('sort');

$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['sort']);

// Функция удаления
function actionDelete() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->delete(array('id' => '=' . $_POST['rowID']));

    return array('success' => $action);
}

/**
 * Экшен редакирования из модального окна 
 */
function actionValueEdit() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['id'])));


    $PHPShopGUI->field_col = 2;
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Название', $PHPShopGUI->setInputArg(array('name' => 'name_value', 'type' => 'text.required', 'value' => $data['name'])));
    $PHPShopGUI->_CODE.= $PHPShopGUI->setField('Приоритет', $PHPShopGUI->setInputArg(array('name' => 'num_value', 'type' => 'text', 'value' => $data['num'], 'size' => 100)));

    $PHPShopGUI->_CODE.=$PHPShopGUI->setField(__("Иконка"), $PHPShopGUI->setIcon($data['icon'], "icon_value", true, array('load' => false, 'server' => true, 'url' => false)));

    // Страницы с описанием
    $page_value[] = array('- Нет описания - ', null, $data['page']);
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
    $data_page = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1000));
    if (is_array($data_page))
        foreach ($data_page as $v)
            $page_value[] = array($v['name'], $v['link'], $data['page']);

    $PHPShopGUI->_CODE.=$PHPShopGUI->setField("Описание:", $PHPShopGUI->setSelect('page_value', $page_value, '100%', false, false, false, false, false, false, false, 'form-control'));


    // Категории
    $PHPShopSort = new PHPShopSortCategoryArray(array('category' => '!=0'));
    $PHPShopSortArray = $PHPShopSort->getArray();

    if (is_array($PHPShopSortArray))
        foreach ($PHPShopSortArray as $v)
            $sort_value[] = array($v['name'], $v['id'], $data['category']);

    $PHPShopGUI->_CODE.=$PHPShopGUI->setField("Категория:", $PHPShopGUI->setSelect('category_value', $sort_value, '100%', false, false, false, false, false, false, false, 'form-control'));

    $PHPShopGUI->_CODE.=$PHPShopGUI->setInputArg(array('name' => 'rowID', 'type' => 'hidden', 'value' => $_REQUEST['id']));
    $PHPShopGUI->_CODE.=$PHPShopGUI->setInputArg(array('name' => 'parentID', 'type' => 'hidden', 'value' => $_REQUEST['parentID']));


    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    exit($PHPShopGUI->_CODE . '<p class="clearfix"> </p>');
}

/**
 * Экшен сохранения
 */
function actionSave() {

    // Сохранение данных
    actionUpdate();

    header('Location: ?path=' . $_GET['path']);
}

// Функция обновления
function actionUpdate() {
    global $PHPShopOrm, $PHPShopModules;

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);
    $PHPShopOrm->debug = false;
    $action = $PHPShopOrm->update($_POST, array('id' => '=' . $_POST['rowID']), '_value');
    return array('success' => $action);
}

// Обработка событий
$PHPShopGUI->getAction();

// Вывод формы при старте
$PHPShopGUI->setAction($_GET['id'], 'actionStart', 'none');
?>