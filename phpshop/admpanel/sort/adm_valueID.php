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
 * Экшен редактирования из модального окна 
 */
function actionValueEdit() {
    global $PHPShopGUI, $PHPShopModules, $PHPShopOrm,$PHPShopSystem;

    // Выборка
    $data = $PHPShopOrm->select(array('*'), array('id' => '=' . intval($_REQUEST['id'])));

    $PHPShopGUI->field_col = 2;
    $Tab1 = $PHPShopGUI->setField('Название', $PHPShopGUI->setInputArg(array('name' => 'name_value', 'type' => 'text.required', 'value' => $data['name'])));
    $Tab1.= $PHPShopGUI->setField(
            array('Иконка','Приоритет'),
            array(
                $PHPShopGUI->setIcon($data['icon'], "icon_value", true, array('load' => false, 'server' => true, 'url' => false)),
                $PHPShopGUI->setInputArg(array('name' => 'num_value', 'type' => 'text', 'value' => $data['num']))
            ),
            array(
                array(2, 6),
                array(2, 2)
            ));

    // Страницы с описанием
    $page_value[] = array('- '.__('Нет описания').' - ', null, $data['page']);
    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['page']);
    $data_page = $PHPShopOrm->select(array('*'), false, false, array('limit' => 1000));
    if (is_array($data_page))
        foreach ($data_page as $v)
            $page_value[] = array($v['name'], $v['link'], $data['page']);

    $Tab1.=$PHPShopGUI->setField("Страница описания", $PHPShopGUI->setSelect('page_value', $page_value, '100%', false, false, false, false, false, false, false, 'form-control'));

    // Категории
    $PHPShopSort = new PHPShopSortCategoryArray(array('category' => '!=0'));
    $PHPShopSortArray = $PHPShopSort->getArray();

    if (is_array($PHPShopSortArray))
        foreach ($PHPShopSortArray as $v)
            $sort_value[] = array($v['name'], $v['id'], $data['category']);

    $Tab1.=$PHPShopGUI->setField("Категория", $PHPShopGUI->setSelect('category_value', $sort_value, '100%', false, false, false, false, false, false, false, 'form-control'));
    
    // Редактор 
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('description_value');
    $oFCKeditor->Height = '100';
    $oFCKeditor->Value = $data['description'];
    
    $Tab1.=$PHPShopGUI->setField("Описание", $oFCKeditor->AddGUI());
    $Tab1.=$PHPShopGUI->setInputArg(array('name' => 'rowID', 'type' => 'hidden', 'value' => $_REQUEST['id']));
    $Tab1.=$PHPShopGUI->setInputArg(array('name' => 'parentID', 'type' => 'hidden', 'value' => $_REQUEST['parentID']));

    $Tab2=$PHPShopGUI->setField("Meta заголовок:", '
        <textarea class="form-control" style="height:100px;" name="title_value">' . $data['title'] . '</textarea>
            <div class="btn-group" role="group" aria-label="...">
                <input type="button" value="'.__('Каталог').'" data-seo="@Catalog@" data-target="title_value" class="seo-button btn btn-default btn-sm">
                <input type="button" value="'.__('Подкаталог').'" data-seo="@Podcatalog@" data-target="title_value" class="seo-button btn btn-default btn-sm">
                <input type="button" value="'.__('Общий').'" data-seo="@System@" data-target="title_value" class="seo-button btn btn-default btn-sm">
                <input type="button" value="'.__('Характеристика').'" data-seo="@sortTitle@" data-target="title_value" class="seo-button btn btn-default btn-sm">
                <input type="button" value="'.__('Значение').'" data-seo="@valueTitle@" data-target="title_value" class="seo-button btn btn-default btn-sm">
            </div>');

    $Tab2.=$PHPShopGUI->setField("Meta описание:", '
        <textarea class="form-control" style="height:100px" name="meta_description_value">' . $data['meta_description'] . '</textarea>
            <div class="btn-group" role="group" aria-label="...">
                <input type="button" value="'.__('Каталог').'" data-seo="@Catalog@" data-target="meta_description_value" class="seo-button btn btn-default btn-sm">
                <input type="button" value="'.__('Подкаталог').'" data-seo="@Podcatalog@" data-target="meta_description_value" class="seo-button btn btn-default btn-sm">
                <input type="button" value="'.__('Общий').'" data-seo="@System@" data-target="meta_description_value" class="seo-button btn btn-default btn-sm">
                <input type="button" value="'.__('Характеристика').'" data-seo="@sortTitle@" data-target="meta_description_value" class="seo-button btn btn-default btn-sm">
                <input type="button" value="'.__('Значение').'" data-seo="@valueTitle@" data-target="meta_description_value" class="seo-button btn btn-default btn-sm">
            </div>');

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);

    $PHPShopGUI->tab_key = 100;
    $PHPShopGUI->setTab(["Основное", $Tab1,true], ["Дополнительно", $Tab2,true]);

    $PHPShopGUI->_CODE .= '<script>$(document).ready(function () {
        $(".seo-button").on("click", function() {
            var seo = $(this).attr("data-seo");
            var area = $("[name=" + $(this).attr("data-target") + "]").val();
            $("[name=" + $(this).attr("data-target") + "]").val(area + seo);
        });
    });</script>';

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
    
    $_POST['name_value'] = html_entity_decode($_POST['name_value']);
    
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