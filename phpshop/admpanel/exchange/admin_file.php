<?php

$TitlePage = __("Проверка изображений");


// Стартовый вид
function actionStart() {
    global $PHPShopInterface, $PHPShopGUI, $TitlePage, $PHPShopModules, $PHPShopSystem;

    // Исходное изображение
    $image_source = $PHPShopSystem->ifSerilizeParam('admoption.image_save_source');

    $PHPShopInterface->addJSFiles('./exchange/gui/exchange.gui.js');
    $PHPShopInterface->setActionPanel($TitlePage, false, false);
    $PHPShopInterface->checkbox_action = false;

    $PHPShopInterface->setCaption(array("Иконка", "5%", array('sort' => 'none')), array("Название товара", "50%"), array("Вывод", "10%", array('align' => 'center')), array("Отсутствующие файлы", "35%", array('align' => 'right', array('sort' => 'none'))));

    if (empty($_GET['limit']))
        $_GET['limit'] = '0,10000';
    else
        $clean = true;

    $PHPShopOrm = new PHPShopOrm($GLOBALS['SysValue']['base']['products']);
    $PHPShopOrm->debug = false;
    $PHPShopOrm->mysql_error = false;

    $PHPShopOrm->sql = 'SELECT a.id, a.uid, a.name, a.enabled, a.yml, b.name as img FROM ' . $GLOBALS['SysValue']['base']['products'] . ' AS a 
        RIGHT JOIN ' . $GLOBALS['SysValue']['base']['foto'] . ' AS b ON a.id = b.parent order by a.id desc 
            limit ' . $_GET['limit'];

    $data = $PHPShopOrm->select();
    if (is_array($data))
        foreach ($data as $row) {

            if (empty($row['name']))
                continue;

            $row['pic_big'] = $row['img'];
            $row['pic_small'] = str_replace(array('.jpg', '.png'), array('s.jpg', 's.png'), $row['img']);
            $row['pic_source'] = str_replace(array('.jpg', '.png'), array('_big.jpg', '_big.png'), $row['img']);

            if (!file_exists('../..' . $row['pic_small']) and !strstr($row['pic_small'],'http'))
                $error[] = $row['pic_small'];

            if (!file_exists('../..' . $row['pic_big']) and !strstr($row['pic_big'],'http'))
                $error[] = $row['pic_big'];

            if (!empty($image_source) and !file_exists('../..' . $row['pic_source']) and !strstr($row['pic_source'],'http'))
                $error[] = $row['pic_source'];

            if (!empty($error) and is_array($error)) {
                $file = null;
                foreach ($error as $img) {
                    $file .= '<a href="//' . $_SERVER['SERVER_NAME'] . $img . '" target="_blank" >' . $img . '</a><br>';
                }
            } else
                continue;

            $icon = '<img src="./images/no_photo.gif" class="media-object">';

            // Артикул
            if (!empty($row['uid']))
                $uid = '<div class="text-muted">' . __('Арт') . ' ' . $row['uid'] . '</div>';
            else
                $uid = null;

            // Вывод
            if (empty($row['enabled'])) {
                $enabled = '<span class="text-muted glyphicon glyphicon-eye-close" data-toggle="tooltip" data-placement="top" title="Скрыто"></span>';
                $enabled_css = 'text-muted';
            } else {
                $enabled =  $enabled_css = null;
            }


            // YML
            if (!empty($row['yml']))
                $uid .= '<span class="label label-success" title="Вывод в Яндекс.Маркете">Я</span>';

            $PHPShopInterface->setRow(array('name' => $icon, 'link' => '?path=product&return=' . $_GET['path'] . '&id=' . $row['id']), array('name' => $row['name'], 'link' => '?path=product&return=' . $_GET['path'] . '&id=' . $row['id'], 'addon' => $uid,'class'=>$enabled_css), array('name' => $enabled, 'align' => 'center'), array('name' => $file, 'align' => 'right'));

            unset($error);
        }


    $option = $PHPShopGUI->setInputText($PHPShopGUI->setHelpIcon('Товары с 1 по 3000'), 'limit', $_GET['limit'], '100%');
    $option .= $PHPShopGUI->setButton('Показать', 'search', 'btn-file-search pull-right');
    if (!empty($clean))
        $option .= $PHPShopGUI->setButton('Сброс', 'remove', 'btn-file-cancel pull-left');
    $option .= $PHPShopGUI->setInputArg(array('type' => 'hidden', 'name' => 'path', 'value' => $_GET['path']));

    // Запрос модуля на закладку
    $PHPShopModules->setAdmHandler(__FILE__, __FUNCTION__, false);

    $sidebarleft[] = array('title' => 'Категории', 'content' => $PHPShopInterface->loadLib('tab_menu_service', false, './exchange/'));
    $sidebarleft[] = array('title' => 'Лимит строк', 'content' => $PHPShopInterface->setForm($option, false, "file_search", false, false, 'form-sidebar'));
    $PHPShopInterface->setSidebarLeft($sidebarleft, 2);

    // Футер
    $PHPShopInterface->Compile(2);
    return true;
}

// Обработка событий
$PHPShopGUI->getAction();
?>