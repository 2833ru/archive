<?php

PHPShopObj::loadClass("array");
PHPShopObj::loadClass("category");

$TitlePage = __('Создание баннера');
$PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['banner']);

// Построение дерева категорий
function treegenerator($array, $i, $curent, $dop_cat_array) {
    global $tree_array;
    $del = '¦&nbsp;&nbsp;&nbsp;&nbsp;';
    $tree_select = $tree_select_dop = $check = false;

    $del = str_repeat($del, $i);
    if (is_array($array['sub'])) {
        foreach ($array['sub'] as $k => $v) {

            $check = treegenerator($tree_array[$k], $i + 1, $curent, $dop_cat_array);

            if ($k == $curent)
                $selected = 'selected';
            else
                $selected = null;

            // Допкаталоги
            $selected_dop = null;
            if (is_array($dop_cat_array))
                foreach ($dop_cat_array as $vs) {
                    if ($k == $vs)
                        $selected_dop = "selected";
                }

            if (empty($check['select'])) {
                $tree_select.='<option value="' . $k . '" ' . $selected . '>' . $del . $v . '</option>';

                if ($k < 1000000)
                    $tree_select_dop.='<option value="' . $k . '" ' . $selected_dop . '>' . $del . $v . '</option>';

                $i = 1;
            } else {
                $tree_select.='<option value="' . $k . '" ' . $selected . ' disabled>' . $del . $v . '</option>';
                if ($k < 1000000)
                    $tree_select_dop.='<option value="' . $k . '" ' . $selected_dop . ' disabled >' . $del . $v . '</option>';
            }

            $tree_select.=$check['select'];
            $tree_select_dop.=$check['select_dop'];
        }
    }
    return array('select' => $tree_select, 'select_dop' => $tree_select_dop);
}

// Стартовый вид
function actionStart() {
    global $PHPShopGUI, $PHPShopSystem, $PHPShopOrm, $PHPShopModules;

    // Выборка
    $data['flag'] = 1;
    $data['name'] = __('Реклама');
    $PHPShopGUI->field_col = 1;
    $PHPShopGUI->setActionPanel(__("Создание Баннера"), false, array('Сохранить и закрыть'));

    // Содержание закладки 1
    $Tab1 = $PHPShopGUI->setField("Имя", $PHPShopGUI->setInput("text", "name_new", $data['name'], false, 500)) .
            $PHPShopGUI->setField("Статус", $PHPShopGUI->setRadio("flag_new", 1, "Включить", $data['flag']) . $PHPShopGUI->setRadio("flag_new", 0, "Выключить", $data['flag']));

    // Редактор 
    $PHPShopGUI->setEditor($PHPShopSystem->getSerilizeParam("admoption.editor"));
    $oFCKeditor = new Editor('content_new');
    $oFCKeditor->Height = '300';
    $oFCKeditor->Value = $data['content'];

    // Содержание закладки 1
    $Tab1.= $PHPShopGUI->setField("Содержание", $oFCKeditor->AddGUI());

    $Tab2 = $PHPShopGUI->setField("Таргетинг:", $PHPShopGUI->setInput("text", "dir_new", $data['dir']) . $PHPShopGUI->setHelp('* Пример: /,page,news. Можно указать несколько адресов через запятую.'));

    $PHPShopCategoryArray = new PHPShopCategoryArray();
    $CategoryArray = $PHPShopCategoryArray->getArray();

    $CategoryArray[0]['name'] = '- ' . __('Выбрать каталог') . ' -';
    $tree_array = array();

    foreach ($PHPShopCategoryArray->getKey('parent_to.id', true) as $k => $v) {
        foreach ($v as $cat) {
            $tree_array[$k]['sub'][$cat] = $CategoryArray[$cat]['name'];
        }
        $tree_array[$k]['name'] = $CategoryArray[$k]['name'];
        $tree_array[$k]['id'] = $k;
    }


    $GLOBALS['tree_array'] = &$tree_array;

    // Допкаталоги
    $dop_cat_array = preg_split('/#/', $data['dop_cat'], -1, PREG_SPLIT_NO_EMPTY);

    if (is_array($tree_array[0]['sub']))
        foreach ($tree_array[0]['sub'] as $k => $v) {
            $check = treegenerator($tree_array[$k], 1, $data['category'], $dop_cat_array);

            if ($k == $data['category'])
                $selected = 'selected';
            else
                $selected = null;

            // Допкаталоги
            $selected_dop = null;
            if (is_array($dop_cat_array))
                foreach ($dop_cat_array as $vs) {
                    if ($k == $vs)
                        $selected_dop = "selected";
                }

            if (empty($tree_array[$k]))
                $disabled = null;
            else
                $disabled = ' disabled';


            $tree_select_dop.='<option value="' . $k . '" ' . $selected_dop . $disabled . '>' . $v . '</option>';

            $tree_select_dop.=$check['select_dop'];
        }

    $tree_select_dop = '<select class="selectpicker show-menu-arrow hidden-edit" data-live-search="true" data-container=""  data-style="btn btn-default btn-sm" name="dop_cat[]" data-width="100%" multiple><option value="0">' . $CategoryArray[0]['name'] . '</option>' . $tree_select_dop . '</select>';

    // Дополнительные каталоги
    $Tab2.=$PHPShopGUI->setField('Каталоги', $tree_select_dop . $PHPShopGUI->setHelp('Баннер выводится только в заданных каталогах.'));

    // Витрина
    $Tab2.=$PHPShopGUI->setField("Витрины", $PHPShopGUI->loadLib('tab_multibase', $data, 'catalog/'));

    // Вывод формы закладки
    $PHPShopGUI->setTab(array("Основное", $Tab1, true), array("Дополнительно", $Tab2, true));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $data);


    // Вывод кнопок сохранить и выход в футер
    $ContentFooter = $PHPShopGUI->setInput("submit", "saveID", "ОК", "right", 70, "", "but", "actionInsert.banner.create");

    // Футер
    $PHPShopGUI->setFooter($ContentFooter);
    return true;
}

// Функция записи
function actionInsert() {
    global $PHPShopOrm, $PHPShopModules;

    // Мультибаза
    if (is_array($_POST['servers'])) {
        $_POST['servers_new'] = "";
        foreach ($_POST['servers'] as $v)
            if ($v != 'null' and !strstr($v, ','))
                $_POST['servers_new'].="i" . $v . "i";
    }

    // Доп каталоги
    $_POST['dop_cat_new'] = "";
    if (is_array($_POST['dop_cat'])) {
        $_POST['dop_cat_new'] = "#";
        foreach ($_POST['dop_cat'] as $v)
            if ($v != 'null' and !strstr($v, ','))
                $_POST['dop_cat_new'].=$v . "#";
    }

    // Перехват модуля
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, $_POST);

    $action = $PHPShopOrm->insert($_POST);
    header('Location: ?path=' . $_GET['path']);
    return $action;
}

// Вывод формы при старте
$PHPShopGUI->setLoader($_POST['editID'], 'actionStart');

// Обработка событий
$PHPShopGUI->getAction();
?>



